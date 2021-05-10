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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__material_element__find__relation_to_strata__one_of")
 * @ORM\Entity
 */
class OneOfStrata extends AbstractRelationToStrata
{
    /**
     * @var Collection|Stratum[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Stratum", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="bb__material_element__find__relation_to_strata__one_of_strata",
     *     joinColumns={@ORM\JoinColumn(name="relation_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="stratum_id", referencedColumnName="id")}
     * )
     */
    private $strata;

    public function __construct()
    {
        $this->strata = new ArrayCollection();
    }

    /**
     * @param Collection|Stratum[] $strata
     *
     * @return OneOfStrata
     */
    public function setStrata(Collection $strata): self
    {
        $this->strata = $strata;

        return $this;
    }

    /**
     * @return Collection|Stratum[]
     */
    public function getStrata(): Collection
    {
        return $this->strata;
    }
}
