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

namespace App\Entity\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__material_element")
 * @ORM\Entity
 */
class MaterialElement
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partsCount;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $length;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $innerLength;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $width;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $innerWidth;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $diameter;

    /**
     * @var StoragePlace|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\StoragePlace")
     * @ORM\JoinColumn(name="storage_place_id", referencedColumnName="id")
     */
    private $storagePlace;

    /**
     * @var Material
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Material")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id", nullable=false)
     */
    private $material;

    /**
     * @var AbstractFind
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Document\AbstractFind",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="find_id", referencedColumnName="id", nullable=false)
     */
    private $find;

    /**
     * @var Document
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Document\Document",
     *     cascade={"persist"},
     *     inversedBy="materialElements"
     * )
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", nullable=false)
     */
    private $document;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setPartsCount(?int $partsCount): self
    {
        $this->partsCount = $partsCount;

        return $this;
    }

    public function getPartsCount(): ?int
    {
        return $this->partsCount;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setLength(?string $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setInnerLength(?string $innerLength): self
    {
        $this->innerLength = $innerLength;

        return $this;
    }

    public function getInnerLength(): ?string
    {
        return $this->innerLength;
    }

    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setInnerWidth(?string $innerWidth): self
    {
        $this->innerWidth = $innerWidth;

        return $this;
    }

    public function getInnerWidth(): ?string
    {
        return $this->innerWidth;
    }

    public function setDiameter(?string $diameter): self
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getDiameter(): ?string
    {
        return $this->diameter;
    }

    public function setStoragePlace(?StoragePlace $storagePlace): self
    {
        $this->storagePlace = $storagePlace;

        return $this;
    }

    public function getStoragePlace(): ?StoragePlace
    {
        return $this->storagePlace;
    }

    public function setMaterial(Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setFind(AbstractFind $find): self
    {
        $this->find = $find;

        return $this;
    }

    public function getFind(): ?AbstractFind
    {
        return $this->find;
    }

    public function setDocument(Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }
}
