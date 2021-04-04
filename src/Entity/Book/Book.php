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
 * in the hope  that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

namespace App\Entity\Book;

use App\Entity\MediaBundle\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__book")
 * @ORM\Entity
 */
class Book
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
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Media|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\MediaBundle\Media", cascade={"persist"}, orphanRemoval=true)
     *
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @var Media|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\MediaBundle\Media", cascade={"persist"}, orphanRemoval=true)
     *
     * @ORM\JoinColumn(name="pdf_document_id", referencedColumnName="id")
     */
    private $pdfDocument;

    /**
     * @var Collection|BookPart[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Book\BookPart",
     *     cascade={"persist"},
     *     mappedBy="book",
     *     orphanRemoval=true
     * )
     */
    private $parts;

    public function __construct()
    {
        $this->parts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setPdfDocument(?Media $pdfDocument): self
    {
        $this->pdfDocument = $pdfDocument;

        return $this;
    }

    public function getPdfDocument(): ?Media
    {
        return $this->pdfDocument;
    }

    /**
     * @param Collection|BookPart[] $parts
     *
     * @return Book
     */
    public function setParts(Collection $parts): self
    {
        $this->parts = new ArrayCollection();

        foreach ($parts as $part) {
            $this->addPart($part);
        }

        return $this;
    }

    /**
     * @return Collection|BookPart[]
     */
    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(BookPart $part): void
    {
        $part->setBook($this);

        $this->parts[] = $part;
    }
}
