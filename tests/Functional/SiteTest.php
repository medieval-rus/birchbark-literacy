<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Content\Post;
use App\Entity\Security\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SiteTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private array $userIds = [];
    private array $postIds = [];

    protected function setUp(): void
    {
        $this->client = static::createClient(['debug' => false]);
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        // Make accidental execution with production/local browsing DB impossible.
        self::assertStringEndsWith('_test', $this->manager->getConnection()->getDatabase());
    }

    protected function tearDown(): void
    {
        $connection = $this->manager->getConnection();
        foreach ($this->postIds as $id) { $connection->delete('content__post', ['id' => $id]); }
        foreach ($this->userIds as $id) { $connection->delete('security__user', ['id' => $id]); }
        parent::tearDown();
    }

    #[DataProvider('publicPages')]
    public function testPublicPageRenders(string $path, string $title): void
    {
        $this->client->request('GET', $path);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('title', $title);
    }

    public static function publicPages(): array
    {
        return [
            ['/birchbark/', 'Древнерусские берестяные грамоты'],
            ['/birchbark/document/list/', 'База данных'],
            ['/birchbark/document/show/novgorod/2/', 'Грамота №2'],
            ['/birchbark/bibliography/', 'Избранная библиография'],
            ['/birchbark/bibliography/full', 'Полная библиография'],
            ['/birchbark/login', 'Авторизация'],
        ];
    }

    public function testNumberFilterRestrictsResults(): void
    {
        $all = $this->client->request('GET', '/birchbark/document/list/')->filter('tbody tr')->count();
        $filtered = $this->client->request('GET', '/birchbark/document/list/', ['number' => '2']);
        self::assertResponseIsSuccessful();
        self::assertGreaterThan(0, $filtered->filter('tbody tr')->count());
        self::assertLessThan($all, $filtered->filter('tbody tr')->count());
        self::assertStringContainsString('/show/novgorod/2/', $this->client->getResponse()->getContent());
    }

    #[DataProvider('invalidFilters')]
    public function testInvalidFilterInputReturnsBadRequest(array $query): void
    {
        $this->client->request('GET', '/birchbark/document/list/', $query);
        self::assertResponseStatusCodeSame(400);
    }

    public static function invalidFilters(): array
    {
        return [
            'SQL sort expression' => [['sortBy' => 'id DESC, (SELECT 1)']],
            'array value' => [['number' => ['2']]],
            'expired search' => [['requestId' => str_repeat('a', 32)]],
        ];
    }

    public function testAnonymousAdminAccessRequiresLogin(): void
    {
        $this->client->request('GET', '/admin/dashboard');
        self::assertResponseRedirects('/birchbark/login');
    }

    public function testNonAdminCannotAccessDashboard(): void
    {
        $this->client->loginUser($this->createUser(['ROLE_USER']));
        $this->client->request('GET', '/admin/dashboard');
        self::assertResponseStatusCodeSame(403);
    }

    #[DataProvider('invalidLogins')]
    public function testInvalidLoginDoesNotAuthenticate(bool $badCsrf): void
    {
        $user = $this->createUser(['ROLE_SUPER_ADMIN']);
        $crawler = $this->client->request('GET', '/birchbark/login');
        $form = $crawler->filter('form[action$="/birchbark/login"]')->form();
        $form['admin_login_form[username]'] = $user->getUserIdentifier();
        $form['admin_login_form[password]'] = $badCsrf ? 'local-regression-only' : 'incorrect-local-password';
        if ($badCsrf) { $form['_csrf_token'] = 'invalid-local-token'; }
        $this->client->submit($form);
        self::assertResponseRedirects('/birchbark/login');
        $this->client->request('GET', '/admin/dashboard');
        self::assertResponseRedirects('/birchbark/login');
    }

    public static function invalidLogins(): array
    {
        return ['wrong password' => [false], 'wrong CSRF token' => [true]];
    }

    public function testRememberMeRestoresAuthentication(): void
    {
        $user = $this->createUser(['ROLE_SUPER_ADMIN']);
        $crawler = $this->client->request('GET', '/birchbark/login');
        $form = $crawler->filter('form[action$="/birchbark/login"]')->form();
        $form['admin_login_form[username]'] = $user->getUserIdentifier();
        $form['admin_login_form[password]'] = 'local-regression-only';
        $form['_remember_me'] = 'on';
        $this->client->submit($form);
        self::assertResponseRedirects('/admin/dashboard');
        self::assertNotNull($this->client->getCookieJar()->get('REMEMBERME'));
        $name = static::getContainer()->get('session.factory')->createSession()->getName();
        $this->client->getCookieJar()->expire($name);
        $this->client->request('GET', '/admin/dashboard');
        self::assertResponseIsSuccessful();
    }

    public function testRealLoginAdminFormsSavingAndLogout(): void
    {
        $user = $this->createUser(['ROLE_SUPER_ADMIN']);
        $crawler = $this->client->request('GET', '/birchbark/login');
        $form = $crawler->filter('form[action$="/birchbark/login"]')->form();
        $form['admin_login_form[username]'] = $user->getUserIdentifier();
        $form['admin_login_form[password]'] = 'local-regression-only';
        $this->client->submit($form);
        self::assertResponseRedirects('/admin/dashboard');
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        // Both edit forms must build after the Sonata/Doctrine update.
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $document = $this->manager->getRepository(\App\Entity\Document\Document::class)->findOneBy(['number' => '2']);
        $admin = static::getContainer()->get('sonata.admin.pool')->getAdminByAdminCode('admin.document');
        $this->client->request('GET', $admin->generateObjectUrl('edit', $document));
        self::assertResponseIsSuccessful();

        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $post = (new Post())->setTitle('PHP84 regression fixture')->setBody('Before update');
        $this->manager->persist($post);
        $this->manager->flush();
        $this->postIds[] = $post->getId();
        $postAdmin = static::getContainer()->get('sonata.admin.pool')->getAdminByAdminCode('admin.content.post');
        $crawler = $this->client->request('GET', $postAdmin->generateObjectUrl('edit', $post));
        self::assertResponseIsSuccessful();
        $form = $crawler->filter('form')->reduce(static fn ($node) => $node->filter('[name$="[title]"]')->count() > 0)->form();
        foreach ($form->all() as $name => $field) {
            if (str_ends_with($name, '[title]')) { $form[$name] = 'PHP84 regression saved'; }
        }
        $this->client->submit($form);
        self::assertTrue($this->client->getResponse()->isRedirection());
        self::assertSame('PHP84 regression saved', $this->manager->getConnection()->fetchOne('SELECT title FROM content__post WHERE id = ?', [$post->getId()]));

        $this->client->request('GET', '/birchbark/logout');
        self::assertTrue($this->client->getResponse()->isRedirection());
        $this->client->request('GET', '/admin/dashboard');
        self::assertResponseRedirects('/birchbark/login');
    }

    public function testCsvExport(): void
    {
        $this->client->request('GET', '/api/v1/corpus/metadata/', ['csv' => 'true']);
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('.csv', $this->client->getResponse()->headers->get('Content-Disposition'));
        self::assertStringContainsString(';', $this->client->getResponse()->getContent());
    }

    public function testZipIndexExportFromUploadedCorpus(): void
    {
        $source = tempnam(sys_get_temp_dir(), 'gramoty-yaml-');
        file_put_contents($source, implode("\r\n", [
            '-document: 2', '-page: 1', '-line: 1', '-word: азъ',
            ' -ana: 1', '  lemma: азъ', '  grdic: PRO', '',
        ]));
        $archivePath = tempnam(sys_get_temp_dir(), 'gramoty-zip-');
        try {
            $crawler = $this->client->request('GET', '/birchbark/corpus/morphy/index/');
            $form = $crawler->selectButton('Send')->form();
            $form['corpus_yaml_form[yaml]']->upload($source);
            $this->client->submit($form);
            self::assertResponseIsSuccessful();
            self::assertResponseHeaderSame('Content-Type', 'application/zip');
            file_put_contents($archivePath, $this->client->getResponse()->getContent());
            $zip = new \ZipArchive();
            self::assertTrue($zip->open($archivePath));
            self::assertSame(2, $zip->numFiles);
            self::assertStringContainsString('азъ', $zip->getFromIndex(0));
            $zip->close();
        } finally {
            unlink($source);
            unlink($archivePath);
        }
    }

    public function testParallelXhtmlExport(): void
    {
        $source = tempnam(sys_get_temp_dir(), 'gramoty-xhtml-');
        file_put_contents($source, '<html><body><document id="2"><page>азъ</page></document></body></html>');
        try {
            $crawler = $this->client->request('GET', '/birchbark/corpus/morphy/parallelize/');
            $form = $crawler->selectButton('Send')->form();
            $form['corpus_xhtml_form[xhtml]']->upload($source);
            $this->client->submit($form);
            self::assertResponseIsSuccessful();
            self::assertStringContainsString('.xhtml', $this->client->getResponse()->headers->get('Content-Disposition'));
            $xml = new \DOMDocument();
            self::assertTrue($xml->loadXML($this->client->getResponse()->getContent(), LIBXML_NONET));
            self::assertStringContainsString('азъ', $this->client->getResponse()->getContent());
        } finally {
            unlink($source);
        }
    }

    private function createUser(array $roles): User
    {
        $user = (new User())->setUsername('php84_'.bin2hex(random_bytes(6)))->setFullName('Local regression fixture')->setRoles($roles);
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'local-regression-only'));
        $this->manager->persist($user);
        $this->manager->flush();
        $this->userIds[] = $user->getId();
        return $user;
    }
}
