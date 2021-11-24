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

use App\Entity\Document\Document;
use App\Services\Document\Sorter\DocumentsSorterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DocumentRepository extends ServiceEntityRepository
{
    private TownRepository $townRepository;
    private DocumentsSorterInterface $documentsSorter;

    public function __construct(
        ManagerRegistry $registry,
        TownRepository $townRepository,
        DocumentsSorterInterface $documentsSorter
    ) {
        parent::__construct($registry, Document::class);
        $this->townRepository = $townRepository;
        $this->documentsSorter = $documentsSorter;
    }

    public function findOneByTownAliasAndNumber(
        string $townAlias,
        string $number,
        bool $onlyShownOnSite = false
    ): ?Document {
        $criteria = [
            'town' => $this->townRepository->findOneByAlias($townAlias),
            'number' => $number,
        ];

        if ($onlyShownOnSite) {
            $criteria['isShownOnSite'] = true;
        }

        return $this->findOneBy($criteria);
    }

    /**
     * @return Document[]
     */
    public function findAllInConventionalOrder(bool $onlyShownOnSite = false): array
    {
        $criteria = [];

        if ($onlyShownOnSite) {
            $criteria['isShownOnSite'] = true;
        }

        $documents = $this->findBy($criteria);

        return $this->documentsSorter->sort($documents);
    }
}
