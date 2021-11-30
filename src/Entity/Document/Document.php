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

use App\Entity\Bibliography\BibliographicRecord;
use App\Entity\Media\File;
use App\Repository\Document\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=255)
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
     * @ORM\Column(name="is_shown_on_site", type="boolean", options={"default": false})
     */
    private $isShownOnSite = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_part_of_rnc", type="boolean", options={"default": true})
     */
    private $isPartOfRnc = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_preliminary_publication", type="boolean", options={"default": true})
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
     * @ORM\Column(name="is_conventional_date_biased_backward", type="boolean", options={"default": false})
     */
    private $isConventionalDateBiasedBackward = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_conventional_date_biased_forward", type="boolean", options={"default": false})
     */
    private $isConventionalDateBiasedForward = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="stratigraphical_date", type="string", length=255, nullable=true)
     */
    private $stratigraphicalDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="non_stratigraphical_date", type="string", length=255, nullable=true)
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Bibliography\BibliographicRecord")
     * @ORM\JoinTable(
     *     name="bb__document_bibliographic_record_dnd",
     *     joinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id")}
     * )
     */
    private $dndVolumes;

    /**
     * @var Collection|BibliographicRecord[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Bibliography\BibliographicRecord")
     * @ORM\JoinTable(
     *     name="bb__document_bibliographic_record_ngb",
     *     joinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id")}
     * )
     */
    private $ngbVolumes;

    /**
     * @var Collection|BibliographicRecord[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Bibliography\BibliographicRecord")
     * @ORM\JoinTable(
     *     name="bb__document_bibliographic_record",
     *     joinColumns={@ORM\JoinColumn(name="document_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id")}
     * )
     */
    private $literature;

    /**
     * @var Collection|File[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Media\File", cascade={"persist"})
     * @ORM\JoinTable(name="bb__document_photos")
     */
    private $photos;

    /**
     * @var Collection|File[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Media\File", cascade={"persist"})
     * @ORM\JoinTable(name="bb__document_drawings")
     */
    private $drawings;

    public function __construct()
    {
        $this->materialElements = new ArrayCollection();
        $this->contentElements = new ArrayCollection();
        $this->dndVolumes = new ArrayCollection();
        $this->ngbVolumes = new ArrayCollection();
        $this->literature = new ArrayCollection();
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

    public function getIsPartOfRnc(): ?bool
    {
        return $this->isPartOfRnc;
    }

    public function setIsPartOfRnc(bool $isPartOfRnc): self
    {
        $this->isPartOfRnc = $isPartOfRnc;

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
     * @return Collection|BibliographicRecord[]
     */
    public function getDndVolumes(): Collection
    {
        return $this->dndVolumes;
    }

    /**
     * @param Collection|BibliographicRecord[] $dndVolumes
     *
     * @return Document
     */
    public function setDndVolumes(Collection $dndVolumes): self
    {
        $this->dndVolumes = new ArrayCollection();

        foreach ($dndVolumes as $dndVolume) {
            $this->dndVolumes[] = $dndVolume;
        }

        return $this;
    }

    /**
     * @return Collection|BibliographicRecord[]
     */
    public function getNgbVolumes(): Collection
    {
        return $this->ngbVolumes;
    }

    /**
     * @param Collection|BibliographicRecord[] $ngbVolumes
     *
     * @return Document
     */
    public function setNgbVolumes(Collection $ngbVolumes): self
    {
        $this->ngbVolumes = new ArrayCollection();

        foreach ($ngbVolumes as $ngbVolume) {
            $this->ngbVolumes[] = $ngbVolume;
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

    /**
     * @param Collection|BibliographicRecord[] $literature
     *
     * @return Document
     */
    public function setLiterature(Collection $literature): self
    {
        $this->literature = new ArrayCollection();

        foreach ($literature as $literatureItem) {
            $this->literature[] = $literatureItem;
        }

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Collection|File[] $photos
     */
    public function setPhotos(Collection $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getDrawings(): Collection
    {
        return $this->drawings;
    }

    /**
     * @param Collection|File[] $drawings
     */
    public function setDrawings(Collection $drawings): self
    {
        $this->drawings = $drawings;

        return $this;
    }
}
