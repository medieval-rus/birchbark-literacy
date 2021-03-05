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

namespace App\Entity\Document\MaterialElement\Find\RelationToEstates;

use App\Entity\Document\MaterialElement\Find\Estate;
use App\Entity\Document\MaterialElement\Find\Street;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__material_element__find__relation_to_estates__roadway")
 * @ORM\Entity
 */
class RoadwayBetweenEstates extends AbstractRelationToEstates
{
    /**
     * @var Collection|Estate[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\MaterialElement\Find\Estate", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="bb__material_element__find__relation_to_estates__roadway_estate",
     *     joinColumns={@ORM\JoinColumn(name="relation_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="estate_id", referencedColumnName="id")}
     * )
     */
    private $estates;

    /**
     * @var Collection|Street[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\MaterialElement\Find\Street", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="bb__material_element__find__relation_to_estates__roadway_street",
     *     joinColumns={@ORM\JoinColumn(name="relation_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="street_id", referencedColumnName="id")}
     * )
     */
    private $streets;

    public function __construct()
    {
        $this->estates = new ArrayCollection();
        $this->streets = new ArrayCollection();
    }

    /**
     * @param Collection|Estate[] $estates
     *
     * @return RoadwayBetweenEstates
     */
    public function setEstates(Collection $estates): self
    {
        $this->estates = $estates;

        return $this;
    }

    /**
     * @return Collection|Estate[]
     */
    public function getEstates(): Collection
    {
        return $this->estates;
    }

    /**
     * @param Collection|Street[] $streets
     *
     * @return RoadwayBetweenEstates
     */
    public function setStreets(Collection $streets): self
    {
        $this->streets = $streets;

        return $this;
    }

    /**
     * @return Collection|Street[]
     */
    public function getStreets(): Collection
    {
        return $this->streets;
    }
}
