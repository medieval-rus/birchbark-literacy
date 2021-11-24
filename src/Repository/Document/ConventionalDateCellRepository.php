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

namespace App\Repository\Document;

use App\Entity\Document\ConventionalDateCell as DateCell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ConventionalDateCellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateCell::class);
    }

    public function getMinimalConventionalDate(): int
    {
        $minValues = $this->createQueryBuilder('c')
            ->select(
                'MIN(c.initialYear) AS min_initial_year, '.
                'MIN(c.finalYear) AS min_final_year'
            )
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $minValues) {
            $minValues = [
                'min_initial_year' => 0,
                'min_final_year' => 0,
            ];
        }

        return (int) min($minValues);
    }

    public function getMaximalConventionalDate(): int
    {
        $maxValues = $this->createQueryBuilder('c')
            ->select(
                'MAX(c.initialYear) AS max_initial_year, '.
                'MAX(c.finalYear) AS max_final_year'
            )
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $maxValues) {
            $maxValues = [
                'max_initial_year' => 0,
                'max_final_year' => 0,
            ];
        }

        return (int) max($maxValues);
    }
}
