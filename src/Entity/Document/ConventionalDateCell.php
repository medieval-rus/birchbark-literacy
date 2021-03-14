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

namespace App\Entity\Document;

use App\Repository\Document\ConventionalDateCellRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__conditional_date_cell")
 * @ORM\Entity(repositoryClass=ConventionalDateCellRepository::class)
 */
class ConventionalDateCell
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
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    private $initialYear;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    private $finalYear;

    public function __toString(): string
    {
        return sprintf('%d–%d', $this->getInitialYear(), $this->getFinalYear());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitialYear(): ?int
    {
        return $this->initialYear;
    }

    public function setInitialYear(int $initialYear): self
    {
        $this->initialYear = $initialYear;

        return $this;
    }

    public function getFinalYear(): ?int
    {
        return $this->finalYear;
    }

    public function setFinalYear(int $finalYear): self
    {
        $this->finalYear = $finalYear;

        return $this;
    }
}
