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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bibliography__references_list_item",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *             name="record_is_unique_within_list",
 *             columns={"references_list_id", "bibliographic_record_id"}
 *         )
 *     }
 * )
 * @ORM\Entity
 */
class ReferencesListItem
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
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var ReferencesList
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Bibliography\ReferencesList",
     *     cascade={"persist"},
     *     inversedBy="items"
     * )
     * @ORM\JoinColumn(name="references_list_id", referencedColumnName="id", nullable=false)
     */
    private $referencesList;

    /**
     * @var BibliographicRecord
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Bibliography\BibliographicRecord", cascade={"persist"})
     * @ORM\JoinColumn(name="bibliographic_record_id", referencedColumnName="id", nullable=false)
     */
    private $bibliographicRecord;

    public function __toString(): string
    {
        return (string) $this->position;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getReferencesList(): ReferencesList
    {
        return $this->referencesList;
    }

    public function setReferencesList(ReferencesList $referencesList): self
    {
        $this->referencesList = $referencesList;

        return $this;
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
}
