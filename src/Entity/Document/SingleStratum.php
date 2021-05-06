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
 * @ORM\Table(name="bb__material_element__find__relation_to_strata__single")
 * @ORM\Entity
 */
class SingleStratum extends AbstractRelationToStrata
{
    /**
     * @var Stratum
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Stratum", cascade={"persist"})
     * @ORM\JoinColumn(name="stratum_id", referencedColumnName="id", nullable=false)
     */
    private $stratum;

    public function setStratum(Stratum $stratum): self
    {
        $this->stratum = $stratum;

        return $this;
    }

    public function getStratum(): Stratum
    {
        return $this->stratum;
    }
}
