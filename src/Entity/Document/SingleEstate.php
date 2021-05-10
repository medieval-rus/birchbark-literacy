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
 * @ORM\Table(name="bb__material_element__find__relation_to_estates__single")
 * @ORM\Entity
 */
class SingleEstate extends AbstractRelationToEstates
{
    /**
     * @var Estate
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Estate", cascade={"persist"})
     * @ORM\JoinColumn(name="estate_id", referencedColumnName="id", nullable=false)
     */
    private $estate;

    public function setEstate(Estate $estate): self
    {
        $this->estate = $estate;

        return $this;
    }

    public function getEstate(): Estate
    {
        return $this->estate;
    }
}
