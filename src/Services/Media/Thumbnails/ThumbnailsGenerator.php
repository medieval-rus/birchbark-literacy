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

namespace App\Services\Media\Thumbnails;

use App\Entity\Media\File;
use Imagick;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class ThumbnailsGenerator implements ThumbnailsGeneratorInterface
{
    private HttpClientInterface $httpClient;

    private string $thumbnailsDirectory;

    private array $thumbnailPresets;

    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        string $thumbnailsDirectory,
        array $thumbnailPresets,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->thumbnailsDirectory = $thumbnailsDirectory;
        $this->thumbnailPresets = $thumbnailPresets;
        $this->logger = $logger;
    }

    public function getThumbnailUrlWithFallback(File $file, string $presetKey = 'default'): string
    {
        $this->validatePresetKey($presetKey);

        $thumbnailUrl = $this->getThumbnailUrl($file, $presetKey);

        if (null !== $thumbnailUrl) {
            return $thumbnailUrl;
        }

        $this->logger->error(sprintf('Unable to generate thumbnail for file %s', $file->getFileName()));

        return $file->getUrl();
    }

    public function generateAll(File $file): void
    {
        $this
            ->logger
            ->info(sprintf('[ThumbnailsGenerator] <generateAll> $file->getFileName() = "%s"', $file->getFileName()));

        foreach ($this->thumbnailPresets as $presetKey => $preset) {
            $this->getThumbnailUrl($file, $presetKey);
        }
    }

    private function getThumbnailUrl(File $file, string $presetKey = 'default'): ?string
    {
        $this->validatePresetKey($presetKey);

        $policy = $this->getPolicy($file, $presetKey);

        if (null === $policy) {
            return null;
        }

        switch ($policy['type']) {
            case 'jpeg':
                $thumbnailFileName = $file->getFileName().'_thumb-'.$presetKey.'.'.$policy['extension'];
                break;

            case 'raw':
                $thumbnailFileName = $file->getFileName();
                break;

            default:
                throw $this->unknownPolicyException($policy['type']);
        }

        $pathToThumbnail = $this->thumbnailsDirectory.\DIRECTORY_SEPARATOR.$thumbnailFileName;
        $thumbnailUrl = '/thumbs/'.$thumbnailFileName;

        if (!file_exists($pathToThumbnail)) {
            try {
                switch ($policy['type']) {
                    case 'jpeg':
                        $this->handleJpeg($file, $pathToThumbnail, $policy);
                        break;

                    case 'raw':
                        $this->handleRaw($file, $pathToThumbnail);
                        break;

                    default:
                        throw $this->unknownPolicyException($policy['type']);
                }
            } catch (Throwable $throwable) {
                $this->logger->error(
                    sprintf(
                        'Error when generating thumbnail for file "%s"',
                        $file->getFileName()
                    ),
                    ['exception' => $throwable]
                );
            }

            if (!file_exists($pathToThumbnail)) {
                $this->logger->error(sprintf('Thumbnail for file "%s" has not been generated', $file->getFileName()));

                return null;
            }

            return $thumbnailUrl;
        }

        return $thumbnailUrl;
    }

    private function validatePresetKey(string $presetKey): void
    {
        if (!\array_key_exists($presetKey, $this->thumbnailPresets)) {
            throw new RuntimeException(
                sprintf(
                    'Unknown thumbnail preset key "%s". Known presets are: %s',
                    $presetKey,
                    implode(', ', array_map(fn ($key) => sprintf('"%s"', $key), array_keys($this->thumbnailPresets)))
                )
            );
        }
    }

    private function ensureDirectoryExists(): void
    {
        if (!file_exists($this->thumbnailsDirectory)) {
            mkdir($this->thumbnailsDirectory, 0755, true);
        }
    }

    private function getPolicy(File $file, string $presetKey): ?array
    {
        $preset = $this->thumbnailPresets[$presetKey];

        foreach ($preset as $policy) {
            if (\in_array($file->getMediaType(), $policy['media-types'], true)) {
                return $policy;
            }
        }

        return null;
    }

    private function handleJpeg(File $file, string $pathToThumbnail, array $policy): void
    {
        $this->ensureDirectoryExists();

        $imagick = new Imagick($file->getUrl());

        $thumbnailWidth = $policy['width'];
        $thumbnailHeight = (int) ($policy['width'] / $imagick->getImageWidth() * $imagick->getImageHeight());

        $imagick->setImageFormat($policy['extension']);
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality($policy['quality']);
        $imagick->thumbnailImage($thumbnailWidth, $thumbnailHeight);

        $imagick->writeImage($pathToThumbnail);

        $imagick->clear();
        $imagick->destroy();
    }

    private function handleRaw(File $file, string $pathToThumbnail): void
    {
        $response = $this->httpClient->request('GET', $file->getUrl());

        file_put_contents($pathToThumbnail, $response->toStream());
    }

    private function unknownPolicyException(string $policyType): RuntimeException
    {
        return new RuntimeException(sprintf('Unknown policy type "%s".', $policyType));
    }
}
