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

namespace App\Entity\Document\MaterialElement\Find;

use App\Entity\Document\Town;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bb__material_element__find__excavation",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "town_id"})}
 * )
 * @ORM\Entity
 */
class Excavation
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
     * @var Town
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Town", cascade={"persist"})
     * @ORM\JoinColumn(name="town_id", referencedColumnName="id", nullable=false)
     */
    private $town;

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

    public function setTown(Town $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getTown(): Town
    {
        return $this->town;
    }

    public function getAdminNameWithTown(): string
    {
        return $this->getName().' ('.$this->town->getName().')';
    }
}
