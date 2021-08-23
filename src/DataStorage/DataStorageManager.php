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

namespace App\DataStorage;

use App\DataStorage\Connectors\Osf\OsfConnectorInterface;
use App\Entity\Media\File;
use App\Helper\StringHelper;
use RuntimeException;
use Throwable;

final class DataStorageManager implements DataStorageManagerInterface
{
    private OsfConnectorInterface $osfConnector;

    private array $osfFolders;

    public function __construct(
        OsfConnectorInterface $osfConnector,
        array $osfFolders
    ) {
        $this->osfConnector = $osfConnector;
        $this->osfFolders = $osfFolders;
    }

    public function upload(File $file, string $fileName, string $pathToSource, string $mimeType): void
    {
        krsort($this->osfFolders);

        $remoteFolderId = null;
        foreach ($this->osfFolders as $folderKey => $folderId) {
            if (StringHelper::startsWith($fileName, $folderKey.'_')) {
                $remoteFolderId = $folderId;
                break;
            }
        }

        if (null === $remoteFolderId) {
            throw new RuntimeException(
                sprintf(
                    'Unexpected file name "%s". Allowed prefixes are: %s',
                    $fileName,
                    implode(', ', array_map(fn ($key) => sprintf('"%s"', $key), array_keys($this->osfFolders)))
                )
            );
        }

        $uploadUrl = $this->osfConnector->getUploadUrl($remoteFolderId);

        try {
            [$id, $hash, $url] = $this->osfConnector->uploadFile($uploadUrl, $fileName, $pathToSource);
        } catch (Throwable $exception) {
            // todo roll back the changes (try to remove uploaded file)
            throw $exception;
        }

        $file->setFileName($fileName);
        $file->setMediaType($mimeType);
        $file->setUrl($url);
        $file->setHash($hash);
        $file->setOsfFileId((string) $id);
    }
}
