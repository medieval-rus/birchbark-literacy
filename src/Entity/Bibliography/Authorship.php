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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bibliography__authorship",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"bibliographic_record_id", "author_id"}),
 *         @ORM\UniqueConstraint(columns={"bibliographic_record_id", "position"})
 *     }
 * )
 * @ORM\Entity
 */
class Authorship
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var Record
     *             // todo nullable false
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Bibliography\Record",
     *     cascade={"persist"},
     *     inversedBy="authorships"
     * )
     * @ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id")
     */
    private $bibliographicRecord;

    /**
     * @var Author
     *             // todo nullable false
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Bibliography\Author",
     *     cascade={"persist"},
     *     inversedBy="authorships"
     * )
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    public function getId(): int
    {
        return $this->id;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getBibliographicRecord(): Record
    {
        return $this->bibliographicRecord;
    }

    public function setBibliographicRecord(Record $bibliographicRecord): self
    {
        $this->bibliographicRecord = $bibliographicRecord;

        return $this;
    }

    public function setAuthor(Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
