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

namespace App\Entity\Bibliography;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bibliography__record")
 * @ORM\Entity(repositoryClass="App\Repository\Bibliography\RecordRepository")
 */
class Record
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
    private $shortName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $authors;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private $details;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @var Collection|Authorship[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Bibliography\Authorship",
     *     cascade={"persist"},
     *     mappedBy="bibliographicRecord",
     *     orphanRemoval=true
     * )
     * @ORM\OrderBy({"position": "ASC"})
     */
    private $authorships;

    public function __construct()
    {
        $this->authorships = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setAuthors(string $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

    public function getAuthors(): string
    {
        return $this->authors;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param Collection|Authorship[] $authorships
     *
     * @return Record
     */
    public function setAuthorships(Collection $authorships): self
    {
        $this->authorships = new ArrayCollection();

        foreach ($authorships as $authorship) {
            $this->addAuthorship($authorship);
        }

        return $this;
    }

    /**
     * @return Collection|Authorship[]
     */
    public function getAuthorships(): Collection
    {
        return $this->authorships;
    }

    public function addAuthorship(Authorship $authorship): void
    {
        $authorship->setBibliographicRecord($this);

        $this->authorships[] = $authorship;
    }
}
