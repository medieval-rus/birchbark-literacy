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

namespace App\Entity\Media;

use App\Repository\Media\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

/**
 * @ORM\Table(name="media__file")
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
{
    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="file_name", type="string", length=255, unique=true)
     */
    private $fileName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="media_type", type="string", length=255)
     */
    private $mediaType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=2048, nullable=true)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="binary_content", type="binary", length=16777215, nullable=true)
     */
    private $binaryContent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="hash", type="string", length=32, options={"fixed" = true}, unique=true)
     */
    private $hash;

    /**
     * @var array|null
     *
     * @ORM\Column(name="metadata", type="json", nullable=true)
     */
    private $metadata;

    public function __toString(): string
    {
        return (string) $this->fileName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(?string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBinaryContent(): ?string
    {
        return $this->binaryContent;
    }

    public function setBinaryContent(?string $binaryContent): self
    {
        $this->binaryContent = $binaryContent;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getOsfFileId(): string
    {
        $metadata = $this->getMetadata() ?? [];

        if (!\array_key_exists('osf', $metadata)) {
            throw new RuntimeException(
                sprintf('File with id "%s" does not contain OSF metadata', $this->id)
            );
        }

        $osfMetadata = $metadata['osf'];

        if (!\array_key_exists('id', $osfMetadata)) {
            throw new RuntimeException(
                sprintf('OSF metadata of file with id "%s" does not contain OSF file id', $this->id)
            );
        }

        $id = $osfMetadata['id'];
        $idParts = explode('/', $id);

        if (2 !== \count($idParts)) {
            throw new RuntimeException(
                sprintf('OSF file id of file with id "%s" has unknown format: "%s" ', $this->id, $id)
            );
        }

        return $idParts[1];
    }

    public function setOsfFileId(string $osfFileId): self
    {
        $metadata = $this->getMetadata() ?? [];

        if (!\array_key_exists('osf', $metadata)) {
            $metadata['osf'] = [];
        }

        $metadata['osf']['id'] = $osfFileId;

        $this->setMetadata($metadata);

        return $this;
    }
}
