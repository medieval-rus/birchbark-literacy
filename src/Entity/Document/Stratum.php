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

namespace App\Entity\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bb__material_element__find__stratum",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "excavation_id"})}
 * )
 * @ORM\Entity
 */
class Stratum
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
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var Excavation
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Document\Excavation",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="excavation_id", referencedColumnName="id", nullable=false)
     */
    private $excavation;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $initialDepth;

    /**
     * @var string|null
     *
     * @ORM\Column(type="decimal", precision=65, scale=2, nullable=true)
     */
    private $finalDepth;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setExcavation(Excavation $excavation): self
    {
        $this->excavation = $excavation;

        return $this;
    }

    public function getExcavation(): Excavation
    {
        return $this->excavation;
    }

    public function setInitialDepth(?string $initialDepth): self
    {
        $this->initialDepth = $initialDepth;

        return $this;
    }

    public function getInitialDepth(): ?string
    {
        return $this->initialDepth;
    }

    public function setFinalDepth(?string $finalDepth): self
    {
        $this->finalDepth = $finalDepth;

        return $this;
    }

    public function getFinalDepth(): ?string
    {
        return $this->finalDepth;
    }
}
