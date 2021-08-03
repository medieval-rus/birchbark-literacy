<?php

declare(strict_types=1);

/*
 * This file is part of «Birchbark Literacy from Medieval Rus» database.
 *
 * Copyright (c) Department of Linguistic and Literary Studies of the University of Padova
 *
 * «Birchbark Literacy from Medieval Rus» database is free software:
 * you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation, version 3.
 *
 * «Birchbark Literacy from Medieval Rus» database is distributed
 * in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

namespace App\Admin\Media;

use App\Admin\AbstractEntityAdmin;
use App\DataStorage\DataStorageManagerInterface;
use App\Entity\Media\File;
use App\Helper\StringHelper;
use App\Repository\Media\FileRepository;
use RuntimeException;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContext;

final class FileAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'media_file';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'media/file';

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var DataStorageManagerInterface
     */
    private $dataStorageManager;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        FileRepository $fileRepository,
        DataStorageManagerInterface $dataStorageManager
    ) {
        parent::__construct($code, $class, $baseControllerName);

        $this->fileRepository = $fileRepository;
        $this->dataStorageManager = $dataStorageManager;
    }

    /**
     * @param File $object
     */
    public function prePersist($object): void
    {
        $object->setBinaryContent(null);

        $uploadedFile = $this->getForm()['binaryContent']->getData();

        if (!$uploadedFile instanceof UploadedFile) {
            throw new RuntimeException('Uploaded file is invalid.');
        }

        $this->dataStorageManager->upload(
            $object,
            $uploadedFile->getClientOriginalName(),
            $uploadedFile->getRealPath(),
            $uploadedFile->getMimeType()
        );
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id', null, $this->createLabeledListOptions('id'))
            ->add('fileName', null, $this->createLabeledListOptions('fileName'))
            ->add('mediaType', null, $this->createLabeledListOptions('mediaType'))
            ->add('description', null, $this->createLabeledListOptions('description'))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->isEditForm()) {
            $this->buildEditForm($formMapper);
        } else {
            $this->buildCreateForm($formMapper);
        }
    }

    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection->remove('delete');
    }

    private function buildEditForm(FormMapper $formMapper): void
    {
        $formMapper->add('fileName', null, $this->createLabeledFormOptions('fileName', ['disabled' => true]));
        $formMapper->add('mediaType', null, $this->createLabeledFormOptions('mediaType', ['disabled' => true]));
        $formMapper->add('url', null, $this->createLabeledFormOptions('url', ['disabled' => true]));
        $formMapper->add('description', null, $this->createLabeledFormOptions('description'));
        $formMapper->add(
            'binaryContent',
            FileType::class,
            $this->createLabeledFormOptions(
                'binaryContent',
                [
                    'required' => false,
                    'disabled' => true,
                ]
            )
        );
    }

    private function buildCreateForm(FormMapper $formMapper): void
    {
        $formMapper
            ->add(
                'binaryContent',
                FileType::class,
                $this->createLabeledFormOptions(
                    'binaryContent',
                    [
                        'required' => true,
                        'constraints' => [
                            new NotBlank(),
                            new NotNull(),
                            new Callback(
                                function (UploadedFile $uploadedFile, ExecutionContext $context): void {
                                    $fileName = $uploadedFile->getClientOriginalName();

                                    if (!self::isFileNameValid($fileName)) {
                                        $context
                                            ->buildViolation(sprintf('Uploaded file name "%s" is invalid.', $fileName))
                                            ->addViolation();
                                    }

                                    $pathToUploadedFile = $uploadedFile->getRealPath();

                                    if (is_dir($pathToUploadedFile)) {
                                        $context
                                            ->buildViolation(sprintf('Uploaded file "%s" has wrong format.', $fileName))
                                            ->addViolation();
                                    }

                                    $hash = md5(file_get_contents($pathToUploadedFile));

                                    $result = $this
                                        ->fileRepository
                                        ->createQueryBuilder('file')
                                        ->where('file.fileName = ?1')
                                        ->orWhere('file.hash = ?2')
                                        ->setParameter(1, $fileName)
                                        ->setParameter(2, $hash)
                                        ->getQuery()
                                        ->execute();

                                    /**
                                     * @var $filesWithTheSameName File[]
                                     */
                                    $filesWithTheSameName = array_filter(
                                        $result,
                                        static function (File $file) use ($fileName): bool {
                                            return $file->getFileName() === $fileName;
                                        }
                                    );

                                    /**
                                     * @var $filesWithTheSameContent File[]
                                     */
                                    $filesWithTheSameContent = array_filter(
                                        $result,
                                        static function (File $file) use ($hash): bool {
                                            return $file->getHash() === $hash;
                                        }
                                    );

                                    if (\count($filesWithTheSameName) > 0) {
                                        $context
                                            ->buildViolation(sprintf('File with name "%s" already exists.', $fileName))
                                            ->addViolation();
                                    }

                                    if (\count($filesWithTheSameContent) > 0) {
                                        $existingFileName = $filesWithTheSameContent[0]->getFileName();

                                        $context
                                            ->buildViolation(
                                                sprintf(
                                                    'File with the same content already exists ("%s").',
                                                    $existingFileName
                                                )
                                            )
                                            ->addViolation();
                                    }
                                }
                            ),
                        ],
                    ]
                )
            )
            ->add('description', null, $this->createLabeledFormOptions('description'));
    }

    private function isEditForm(): bool
    {
        $file = $this->hasSubject() ? $this->getSubject() : $this->getNewInstance();

        if (!$file instanceof File) {
            throw new RuntimeException('%s error: subject is not instance of %s', __CLASS__, File::class);
        }

        return null !== $file->getId();
    }

    private static function isFileNameValid(string $fileName): bool
    {
        if (!StringHelper::isLowercased($fileName)) {
            return false;
        }

        preg_match(
            '/^(photo|drawing)_([a-z-]+)_([0-9]{4}[a-z]*(?:-[0-9]{4}[a-z]*)*|[a-z]+\d*)_(\d+)( .+)?\.([a-z0-9]+)$/u',
            $fileName,
            $matches
        );

        $expectedMatchesCount = 7;

        return $expectedMatchesCount === \count($matches);
    }
}
