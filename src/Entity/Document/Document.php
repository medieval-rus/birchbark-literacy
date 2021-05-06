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

use App\Entity\MediaBundle\Media;
use App\Repository\Document\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vyfony\Bundle\BibliographyBundle\Persistence\Entity\BibliographicRecord;

/**
 * @ORM\Table(
 *     name="bb__document",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="town__number", columns={"town_id", "number"})}
 * )
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @var Town
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Town", inversedBy="documents")
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id", nullable=false)
     */
    private $town;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isShownOnSite = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isPreliminaryPublication = true;

    /**
     * @var Scribe|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Scribe")
     * @ORM\JoinColumn(name="scribe_id", referencedColumnName="id")
     */
    private $scribe;

    /**
     * @var StateOfPreservation|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\StateOfPreservation")
     * @ORM\JoinColumn(name="state_of_preservation_id", referencedColumnName="id")
     */
    private $stateOfPreservation;

    /**
     * @var ConventionalDateCell|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\ConventionalDateCell")
     * @ORM\JoinColumn(name="conventional_date_cell_id", referencedColumnName="id")
     */
    private $conventionalDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isConventionalDateBiasedBackward = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isConventionalDateBiasedForward = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stratigraphicalDate;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nonStratigraphicalDate;

    /**
     * @var WayOfWriting
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\WayOfWriting")
     * @ORM\JoinColumn(name="way_of_writing_id", referencedColumnName="id", nullable=false)
     */
    private $wayOfWriting;

    /**
     * @var Collection|MaterialElement[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Document\MaterialElement",
     *     cascade={"persist"},
     *     mappedBy="document",
     *     orphanRemoval=true
     * )
     */
    private $materialElements;

    /**
     * @var Collection|ContentElement[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Document\ContentElement",
     *     cascade={"persist"},
     *     mappedBy="document",
     *     orphanRemoval=true
     * )
     */
    private $contentElements;

    /**
     * @var Collection|BibliographicRecord[]
     *
     * @ORM\ManyToMany(targetEntity="Vyfony\Bundle\BibliographyBundle\Persistence\Entity\BibliographicRecord")
     * @ORM\JoinTable(
     *     name="bb__document_record",
     *     joinColumns={@ORM\JoinColumn(name="birch_bark_document_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id")}
     * )
     */
    private $literature;

    /**
     * @var DndSection|null
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Document\DndSection",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="dnd_section_id", referencedColumnName="id")
     */
    private $dndSection;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ngbVolume;

    /**
     * @var Collection|Amendment[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Document\Amendment",
     *     cascade={"persist"},
     *     mappedBy="birchBarkDocument",
     *     orphanRemoval=true
     * )
     */
    private $amendments;

    /**
     * @var Collection|Media[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\MediaBundle\Media", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *     name="bb__document_photo",
     *     joinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
     * )
     */
    private $photos;

    /**
     * @var Collection|Media[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\MediaBundle\Media", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *     name="bb__document_sketch",
     *     joinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
     * )
     */
    private $drawings;

    public function __construct()
    {
        $this->materialElements = new ArrayCollection();
        $this->contentElements = new ArrayCollection();
        $this->literature = new ArrayCollection();
        $this->amendments = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->drawings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->town->getName(), $this->number);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setTown(Town $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function getIsShownOnSite(): ?bool
    {
        return $this->isShownOnSite;
    }

    public function setIsShownOnSite(bool $isShownOnSite): self
    {
        $this->isShownOnSite = $isShownOnSite;

        return $this;
    }

    public function getIsPreliminaryPublication(): ?bool
    {
        return $this->isPreliminaryPublication;
    }

    public function setIsPreliminaryPublication(bool $isPreliminaryPublication): self
    {
        $this->isPreliminaryPublication = $isPreliminaryPublication;

        return $this;
    }

    public function getScribe(): ?Scribe
    {
        return $this->scribe;
    }

    public function setScribe(?Scribe $scribe): self
    {
        $this->scribe = $scribe;

        return $this;
    }

    public function setStateOfPreservation(?StateOfPreservation $stateOfPreservation): self
    {
        $this->stateOfPreservation = $stateOfPreservation;

        return $this;
    }

    public function getStateOfPreservation(): ?StateOfPreservation
    {
        return $this->stateOfPreservation;
    }

    public function setConventionalDate(?ConventionalDateCell $conventionalDate): self
    {
        $this->conventionalDate = $conventionalDate;

        return $this;
    }

    public function getConventionalDate(): ?ConventionalDateCell
    {
        return $this->conventionalDate;
    }

    public function getIsConventionalDateBiasedBackward(): ?bool
    {
        return $this->isConventionalDateBiasedBackward;
    }

    public function setIsConventionalDateBiasedBackward(bool $isConventionalDateBiasedBackward): self
    {
        $this->isConventionalDateBiasedBackward = $isConventionalDateBiasedBackward;

        return $this;
    }

    public function getIsConventionalDateBiasedForward(): ?bool
    {
        return $this->isConventionalDateBiasedForward;
    }

    public function setIsConventionalDateBiasedForward(bool $isConventionalDateBiasedForward): self
    {
        $this->isConventionalDateBiasedForward = $isConventionalDateBiasedForward;

        return $this;
    }

    public function setStratigraphicalDate(?string $stratigraphicalDate): self
    {
        $this->stratigraphicalDate = $stratigraphicalDate;

        return $this;
    }

    public function getStratigraphicalDate(): ?string
    {
        return $this->stratigraphicalDate;
    }

    public function setNonStratigraphicalDate(?string $nonStratigraphicalDate): self
    {
        $this->nonStratigraphicalDate = $nonStratigraphicalDate;

        return $this;
    }

    public function getNonStratigraphicalDate(): ?string
    {
        return $this->nonStratigraphicalDate;
    }

    public function setWayOfWriting(WayOfWriting $wayOfWriting): self
    {
        $this->wayOfWriting = $wayOfWriting;

        return $this;
    }

    public function getWayOfWriting(): ?WayOfWriting
    {
        return $this->wayOfWriting;
    }

    /**
     * @param Collection|MaterialElement[] $materialElements
     *
     * @return Document
     */
    public function setMaterialElements(Collection $materialElements): self
    {
        $this->materialElements = new ArrayCollection();

        foreach ($materialElements as $materialElement) {
            $this->addMaterialElement($materialElement);
        }

        return $this;
    }

    /**
     * @return Collection|MaterialElement[]
     */
    public function getMaterialElements(): Collection
    {
        return $this->materialElements;
    }

    public function addMaterialElement(MaterialElement $materialElement): void
    {
        $materialElement->setDocument($this);

        $this->materialElements[] = $materialElement;
    }

    /**
     * @param Collection|ContentElement[] $contentElements
     *
     * @return Document
     */
    public function setContentElements(Collection $contentElements): self
    {
        $this->contentElements = new ArrayCollection();

        foreach ($contentElements as $contentElement) {
            $this->addContentElement($contentElement);
        }

        return $this;
    }

    /**
     * @return Collection|ContentElement[]
     */
    public function getContentElements(): Collection
    {
        return $this->contentElements;
    }

    public function addContentElement(ContentElement $contentElement): void
    {
        $contentElement->setDocument($this);

        $this->contentElements[] = $contentElement;
    }

    /**
     * @param Collection|BibliographicRecord[] $literature
     *
     * @return Document
     */
    public function setLiterature(Collection $literature): self
    {
        $this->literature = new ArrayCollection();

        foreach ($literature as $literatureItem) {
            $this->addLiteratureItem($literatureItem);
        }

        return $this;
    }

    /**
     * @return Collection|BibliographicRecord[]
     */
    public function getLiterature(): Collection
    {
        return $this->literature;
    }

    public function addLiteratureItem(BibliographicRecord $literatureItem): void
    {
        $this->literature[] = $literatureItem;
    }

    public function setDndSection(?DndSection $dndSection): self
    {
        $this->dndSection = $dndSection;

        return $this;
    }

    public function getDndSection(): ?DndSection
    {
        return $this->dndSection;
    }

    public function setNgbVolume(?string $ngbVolume): self
    {
        $this->ngbVolume = $ngbVolume;

        return $this;
    }

    public function getNgbVolume(): ?string
    {
        return $this->ngbVolume;
    }

    /**
     * @param Collection|Amendment[] $amendments
     *
     * @return Document
     */
    public function setAmendments(Collection $amendments): self
    {
        $this->amendments = new ArrayCollection();

        foreach ($amendments as $amendment) {
            $this->addAmendment($amendment);
        }

        return $this;
    }

    /**
     * @return Collection|Amendment[]
     */
    public function getAmendments(): Collection
    {
        return $this->amendments;
    }

    public function addAmendment(Amendment $amendment): void
    {
        $amendment->setBirchBarkDocument($this);

        $this->amendments[] = $amendment;
    }

    /**
     * @param iterable|Media[] $photos
     *
     * @return Document
     */
    public function setPhotos(iterable $photos): self
    {
        $this->photos = new ArrayCollection();

        foreach ($photos as $photo) {
            $this->addPhoto($photo);
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Media $photo): void
    {
        $this->photos[] = $photo;
    }

    /**
     * @param iterable|Media[] $drawings
     *
     * @return Document
     */
    public function setDrawings(iterable $drawings): self
    {
        $this->drawings = new ArrayCollection();

        foreach ($drawings as $drawing) {
            $this->addDrawing($drawing);
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getDrawings(): Collection
    {
        return $this->drawings;
    }

    public function addDrawing(Media $drawing): void
    {
        $this->drawings[] = $drawing;
    }
}
