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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bb__book_part",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="name__book", columns={"name", "book_id"})}
 * )
 * @ORM\Entity
 */
class BookPart
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
    private $name;

    /**
     * @var Media|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\MediaBundle\Media", cascade={"persist"}, orphanRemoval=true)
     *
     * @ORM\JoinColumn(name="pdf_document_id", referencedColumnName="id")
     */
    private $pdfDocument;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Book\Book",
     *     cascade={"persist"},
     *     inversedBy="parts"
     * )
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false)
     */
    private $book;

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

    public function setPdfDocument(?Media $pdfDocument): self
    {
        $this->pdfDocument = $pdfDocument;

        return $this;
    }

    public function getPdfDocument(): ?Media
    {
        return $this->pdfDocument;
    }

    public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
}
