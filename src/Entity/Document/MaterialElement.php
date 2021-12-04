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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__material_element")
 * @ORM\Entity()
 */
class MaterialElement
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parts_count", type="integer", nullable=true)
     */
    private $partsCount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="length", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $length;

    /**
     * @var string|null
     *
     * @ORM\Column(name="inner_length", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $innerLength;

    /**
     * @var string|null
     *
     * @ORM\Column(name="width", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $width;

    /**
     * @var string|null
     *
     * @ORM\Column(name="inner_width", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $innerWidth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="diameter", type="decimal", precision=65, scale=2, nullable=true)
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
     * @var int|null
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var Excavation|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Excavation", cascade={"persist"})
     * @ORM\JoinColumn(name="excavation_id", referencedColumnName="id", nullable=true)
     */
    private $excavation;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_archaeological_find", type="boolean", options={"default": true})
     */
    private $isArchaeologicalFind = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_palisade", type="boolean", options={"default": false})
     */
    private $isPalisade = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_roadway", type="boolean", options={"default": false})
     */
    private $isRoadway = false;

    /**
     * @var Collection|Estate[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Estate", cascade={"persist"})
     * @ORM\JoinTable(name="bb__material_element__estate")
     */
    private $estates;

    /**
     * @var Collection|Square[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Square", cascade={"persist"})
     * @ORM\JoinTable(name="bb__material_element__square")
     */
    private $squares;

    /**
     * @var Collection|Stratum[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Stratum", cascade={"persist"})
     * @ORM\JoinTable(name="bb__material_element__stratum")
     */
    private $strata;

    /**
     * @var Collection|Street[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Street", cascade={"persist"})
     * @ORM\JoinTable(name="bb__material_element__street")
     */
    private $streets;

    /**
     * @var int|null
     *
     * @ORM\Column(name="initial_tier", type="integer", nullable=true)
     */
    private $initialTier;

    /**
     * @var int|null
     *
     * @ORM\Column(name="final_tier", type="integer", nullable=true)
     */
    private $finalTier;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment_on_tiers", type="string", length=255, nullable=true)
     */
    private $commentOnTiers;

    /**
     * @var string|null
     *
     * @ORM\Column(name="initial_depth", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $initialDepth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="final_depth", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $finalDepth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment_on_depths", type="string", length=255, nullable=true)
     */
    private $commentOnDepths;

    /**
     * @var AbstractFind
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Document\AbstractFind",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="find_id", referencedColumnName="id", nullable=true)
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

    public function __construct()
    {
        $this->estates = new ArrayCollection();
        $this->squares = new ArrayCollection();
        $this->strata = new ArrayCollection();
        $this->streets = new ArrayCollection();
    }

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

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setExcavation(?Excavation $excavation): self
    {
        $this->excavation = $excavation;

        return $this;
    }

    public function getExcavation(): ?Excavation
    {
        return $this->excavation;
    }

    public function getIsArchaeologicalFind(): bool
    {
        return $this->isArchaeologicalFind;
    }

    public function setIsArchaeologicalFind(bool $isArchaeologicalFind): self
    {
        $this->isArchaeologicalFind = $isArchaeologicalFind;

        return $this;
    }

    public function getIsPalisade(): bool
    {
        return $this->isPalisade;
    }

    public function setIsPalisade(bool $isPalisade): self
    {
        $this->isPalisade = $isPalisade;

        return $this;
    }

    public function getIsRoadway(): bool
    {
        return $this->isRoadway;
    }

    public function setIsRoadway(bool $isRoadway): self
    {
        $this->isRoadway = $isRoadway;

        return $this;
    }

    /**
     * @return Collection|Estate[]
     */
    public function getEstates(): Collection
    {
        return $this->estates;
    }

    /**
     * @param Collection|Estate[] $estates
     */
    public function setEstates(Collection $estates): self
    {
        $this->estates = $estates;

        return $this;
    }

    /**
     * @return Collection|Square[]
     */
    public function getSquares(): Collection
    {
        return $this->squares;
    }

    /**
     * @param Collection|Square[] $squares
     */
    public function setSquares(Collection $squares): self
    {
        $this->squares = $squares;

        return $this;
    }

    /**
     * @return Collection|Stratum[]
     */
    public function getStrata(): Collection
    {
        return $this->strata;
    }

    /**
     * @param Collection|Stratum[] $strata
     */
    public function setStrata(Collection $strata): self
    {
        $this->strata = $strata;

        return $this;
    }

    /**
     * @return Collection|Street[]
     */
    public function getStreets(): Collection
    {
        return $this->streets;
    }

    /**
     * @param Collection|Street[] $streets
     */
    public function setStreets(Collection $streets): self
    {
        $this->streets = $streets;

        return $this;
    }

    public function setInitialTier(?int $initialTier): self
    {
        $this->initialTier = $initialTier;

        return $this;
    }

    public function getInitialTier(): ?int
    {
        return $this->initialTier;
    }

    public function setFinalTier(?int $finalTier): self
    {
        $this->finalTier = $finalTier;

        return $this;
    }

    public function getFinalTier(): ?int
    {
        return $this->finalTier;
    }

    public function setCommentOnTiers(?string $commentOnTiers): self
    {
        $this->commentOnTiers = $commentOnTiers;

        return $this;
    }

    public function getCommentOnTiers(): ?string
    {
        return $this->commentOnTiers;
    }

    public function setInitialDepth(?string $initialDepth): self
    {
        $this->initialDepth = $initialDepth;

        return $this;
    }

    public function getInitialDepth(): ?string
    {
        return $this->initialDepth;
    }

    public function setFinalDepth(?string $finalDepth): self
    {
        $this->finalDepth = $finalDepth;

        return $this;
    }

    public function getFinalDepth(): ?string
    {
        return $this->finalDepth;
    }

    public function setCommentOnDepths(?string $commentOnDepths): self
    {
        $this->commentOnDepths = $commentOnDepths;

        return $this;
    }

    public function getCommentOnDepths(): ?string
    {
        return $this->commentOnDepths;
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
