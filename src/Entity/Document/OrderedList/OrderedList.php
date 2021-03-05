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

use App\Repository\Document\OrderedList\OrderedListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__ordered_list__ordered_list")
 * @ORM\Entity(repositoryClass=OrderedListRepository::class)
 */
class OrderedList
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @var Collection|Item[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Document\OrderedList\Item",
     *     cascade={"persist"},
     *     mappedBy="orderedList",
     *     orphanRemoval=true
     * )
     * @ORM\OrderBy({"position": "ASC"})
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Collection|Item[] $items
     *
     * @return OrderedList
     */
    public function setItems(Collection $items): self
    {
        $this->items = new ArrayCollection();

        foreach ($items as $birchBarkDocumentsListHasBirchBarkDocument) {
            $this->addItem($birchBarkDocumentsListHasBirchBarkDocument);
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): void
    {
        $item->setOrderedList($this);

        $this->items[] = $item;
    }
}
