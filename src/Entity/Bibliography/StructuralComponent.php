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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bibliography__structural_component",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="file_is_unique_within_bibliographic_record",
 *             columns={"bibliographic_record_id", "file_id"}
 *         )
 *     }
 * )
 * @ORM\Entity
 */
class StructuralComponent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var BibliographicRecord
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Bibliography\BibliographicRecord",
     *     cascade={"persist"},
     *     inversedBy="structuralComponents"
     * )
     * @ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id", nullable=false)
     */
    private $bibliographicRecord;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer", options={"default" : 1})
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var File|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Media\File", cascade={"persist"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id")
     */
    private $file;

    public function __construct()
    {
        $this->level = 1;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBibliographicRecord(): ?BibliographicRecord
    {
        return $this->bibliographicRecord;
    }

    public function setBibliographicRecord(BibliographicRecord $bibliographicRecord): self
    {
        $this->bibliographicRecord = $bibliographicRecord;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
