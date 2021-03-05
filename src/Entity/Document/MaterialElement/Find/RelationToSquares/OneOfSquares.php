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

namespace App\Entity\Document\MaterialElement\Find\RelationToSquares;

use App\Entity\Document\MaterialElement\Find\Square;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__material_element__find__relation_to_squares__one_of")
 * @ORM\Entity
 */
class OneOfSquares extends AbstractRelationToSquares
{
    /**
     * @var Collection|Square[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\MaterialElement\Find\Square", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="bb__material_element__find__relation_to_squares__one_of_square",
     *     joinColumns={@ORM\JoinColumn(name="relation_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="square_id", referencedColumnName="id")}
     * )
     */
    private $squares;

    public function __construct()
    {
        $this->squares = new ArrayCollection();
    }

    /**
     * @param Collection|Square[] $squares
     *
     * @return OneOfSquares
     */
    public function setSquares(Collection $squares): self
    {
        $this->squares = $squares;

        return $this;
    }

    /**
     * @return Collection|Square[]
     */
    public function getSquares(): Collection
    {
        return $this->squares;
    }
}
