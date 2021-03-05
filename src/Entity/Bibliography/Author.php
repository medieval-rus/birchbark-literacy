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
 * @ORM\Table(name="bibliography__author")
 * @ORM\Entity
 */
class Author
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
    private $fullName;

    /**
     * @var Collection|Authorship[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Bibliography\Authorship",
     *     cascade={"persist"},
     *     mappedBy="author",
     *     orphanRemoval=false
     * )
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

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param Collection|Authorship[] $authorships
     *
     * @return Author
     */
    public function setAuthorships(Collection $authorships): self
    {
        $this->authorships = $authorships;

        return $this;
    }

    /**
     * @return Collection|Authorship[]
     */
    public function getAuthorships(): Collection
    {
        return $this->authorships;
    }
}
