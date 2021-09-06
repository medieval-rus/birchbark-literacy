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

namespace App\Repository\Bibliography;

use App\Entity\Bibliography\BibliographicRecord;
use App\Entity\Bibliography\ReferencesList;
use App\Entity\Bibliography\ReferencesListItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class BibliographicRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BibliographicRecord::class);
    }

    public function findBook(int $id): ?BibliographicRecord
    {
        $result = $this->getEntityManager()->getRepository(ReferencesList::class)
            ->findBooks()
            ->getItems()
            ->filter(fn (ReferencesListItem $item): bool => $item->getBibliographicRecord()->getId() === $id)
            ->first();

        if (!$result instanceof ReferencesListItem) {
            return null;
        }

        return $result->getBibliographicRecord();
    }
}
