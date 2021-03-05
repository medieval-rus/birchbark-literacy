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

namespace App\Entity\Document\OrderedList;

use App\Entity\Document\Document;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bb__ordered_list__item",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"ordered_list_id", "document_id"}),
 *         @ORM\UniqueConstraint(columns={"ordered_list_id", "position"})
 *     }
 * )
 * @ORM\Entity
 */
class Item
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
     * @var OrderedList
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Document\OrderedList\OrderedList",
     *     cascade={"persist"},
     *     inversedBy="items"
     * )
     * @ORM\JoinColumn(name="ordered_list_id", referencedColumnName="id", nullable=false)
     */
    private $orderedList;

    /**
     * @var Document
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Document", cascade={"persist"})
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", nullable=false)
     */
    private $birchBarkDocument;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $position;

    public function getId(): int
    {
        return $this->id;
    }

    public function setOrderedList(OrderedList $orderedList): self
    {
        $this->orderedList = $orderedList;

        return $this;
    }

    public function getOrderedList(): OrderedList
    {
        return $this->orderedList;
    }

    public function setBirchBarkDocument(Document $birchBarkDocument): self
    {
        $this->birchBarkDocument = $birchBarkDocument;

        return $this;
    }

    public function getBirchBarkDocument(): ?Document
    {
        return $this->birchBarkDocument;
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
}
