<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Media\File;
use App\Services\Media\Thumbnails\ThumbnailsGenerator;
use Imagick;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

final class ThumbnailGenerationTest extends TestCase
{
    public function testJpegThumbnailIsGeneratedFromLocalImage(): void
    {
        $directory = sys_get_temp_dir().'/gramoty-thumbnail-'.bin2hex(random_bytes(6));
        mkdir($directory);
        $image = new Imagick();
        $image->newImage(800, 400, 'white');
        $image->setImageFormat('jpeg');
        $source = $directory.'/source.jpg';
        $image->writeImage($source);
        $image->clear();
        $file = (new File())->setFileName('fixture.jpg')->setMediaType('image/jpeg')->setUrl($source);
        $generator = new ThumbnailsGenerator(new MockHttpClient(), $directory, [[
            'media-types' => ['image/jpeg'], 'presets' => ['default' => [
                'type' => 'jpeg', 'extension' => 'jpeg', 'quality' => 70, 'max-dimension' => 200,
            ]],
        ]], new NullLogger());
        try {
            $generator->generateAll($file);
            $thumbnail = $directory.'/fixture.jpg_thumb-default.jpeg';
            self::assertFileExists($thumbnail);
            $generated = new Imagick($thumbnail);
            self::assertSame(200, $generated->getImageWidth());
            self::assertSame(100, $generated->getImageHeight());
            $generated->clear();
            self::assertSame('/thumbs/fixture.jpg_thumb-default.jpeg', $generator->getThumbnail($file));
        } finally {
            foreach (glob($directory.'/*') as $path) { unlink($path); }
            rmdir($directory);
        }
    }
}
