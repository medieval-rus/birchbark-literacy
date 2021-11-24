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

namespace App\Entity\Bibliography;

use App\Entity\Media\File;
use App\Repository\Bibliography\BibliographicRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bibliography__bibliographic_record")
 * @ORM\Entity(repositoryClass=BibliographicRecordRepository::class)
 */
class BibliographicRecord
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=255, unique=true)
     */
    private $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", length=65535)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="formal_notation", type="text", length=65535)
     */
    private $formalNotation;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text", length=65535, nullable=true)
     */
    private $label;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var Collection|Author[]
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Bibliography\Author",
     *     cascade={"persist"},
     *     inversedBy="bibliographicRecords"
     * )
     * @ORM\JoinTable(name="bibliography__bibliographic_record_author")
     */
    private $authors;

    /**
     * @var File|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media\File", cascade={"persist"})
     * @ORM\JoinColumn(name="main_file_id", referencedColumnName="id")
     */
    private $mainFile;

    /**
     * @var File|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media\File", cascade={"persist"})
     * @ORM\JoinColumn(name="main_image_id", referencedColumnName="id")
     */
    private $mainImage;

    /**
     * @var Collection|FileSupplement[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Bibliography\FileSupplement",
     *     cascade={"persist"},
     *     mappedBy="bibliographicRecord"
     * )
     */
    private $fileSupplements;

    /**
     * @var Collection|StructuralComponent[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Bibliography\StructuralComponent",
     *     cascade={"persist"},
     *     mappedBy="bibliographicRecord"
     * )
     */
    private $structuralComponents;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->fileSupplements = new ArrayCollection();
        $this->structuralComponents = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->shortName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFormalNotation(): ?string
    {
        return $this->formalNotation;
    }

    public function setFormalNotation(string $formalNotation): self
    {
        $this->formalNotation = $formalNotation;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    /**
     * @param Collection|Author[] $authors
     */
    public function setAuthors(Collection $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function getMainFile(): ?File
    {
        return $this->mainFile;
    }

    public function setMainFile(?File $mainFile): self
    {
        $this->mainFile = $mainFile;

        return $this;
    }

    public function getMainImage(): ?File
    {
        return $this->mainImage;
    }

    public function setMainImage(?File $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return Collection|FileSupplement[]
     */
    public function getFileSupplements(): Collection
    {
        return $this->fileSupplements;
    }

    public function addFileSupplement(FileSupplement $fileSupplement): self
    {
        if (!$this->fileSupplements->contains($fileSupplement)) {
            $this->fileSupplements[] = $fileSupplement;

            $fileSupplement->setBibliographicRecord($this);
        }

        return $this;
    }

    public function removeFileSupplement(FileSupplement $fileSupplement): self
    {
        if ($this->fileSupplements->contains($fileSupplement)) {
            $this->fileSupplements->removeElement($fileSupplement);

            if ($fileSupplement->getBibliographicRecord() === $this) {
                $fileSupplement->setBibliographicRecord(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StructuralComponent[]
     */
    public function getStructuralComponents(): Collection
    {
        return $this->structuralComponents;
    }

    public function addStructuralComponent(StructuralComponent $structuralComponent): self
    {
        if (!$this->structuralComponents->contains($structuralComponent)) {
            $this->structuralComponents[] = $structuralComponent;

            $structuralComponent->setBibliographicRecord($this);
        }

        return $this;
    }

    public function removeStructuralComponent(StructuralComponent $structuralComponent): self
    {
        if ($this->structuralComponents->contains($structuralComponent)) {
            $this->structuralComponents->removeElement($structuralComponent);

            if ($structuralComponent->getBibliographicRecord() === $this) {
                $structuralComponent->setBibliographicRecord(null);
            }
        }

        return $this;
    }
}
