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

namespace App\Repository\Document;

use App\Entity\Document\ConditionalDateCell;
use App\Entity\Document\Document;
use App\Entity\Document\MaterialElement\Find\ExcavationDependentFindInterface;
use App\Entity\Document\Town;
use App\Repository\AbstractRepository;

/**
 * @method Document|null find(int $id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
final class DocumentRepository extends AbstractRepository
{
    public function findOneByTownAliasAndNumber(
        string $townAlias,
        string $number,
        bool $onlyShownOnSite = false
    ): ?Document {
        $criteria = [
            'town' => $this->getEntityManager()->getRepository(Town::class)->findOneByAlias($townAlias),
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
    public function findByFilters(
        int $conditionalDateInitialYear,
        int $conditionalDateFinalYear,
        array $townIds,
        array $stateOfPreservationIds,
        array $excavationIds,
        array $categoryIds
    ): array {
        $queryBuilder = $this->createQueryBuilder('d');

        $whereArguments = [];

        $totalCellsCount = $this->getEntityManager()->getRepository(ConditionalDateCell::class)->count([]);

        $conditionalDateCells = $this->getEntityManager()->getRepository(ConditionalDateCell::class)->findByFilters(
            $conditionalDateInitialYear,
            $conditionalDateFinalYear
        );

        $conditionalDateCellIds = [];

        if ($totalCellsCount !== \count($conditionalDateCells)) {
            foreach ($conditionalDateCells as $conditionalDateCell) {
                $conditionalDateCellIds[] = $conditionalDateCell->getId();
            }
        }

        if (\count($conditionalDateCellIds) > 0) {
            $whereArguments[] = $queryBuilder->expr()->in('d.conditionalDate', $conditionalDateCellIds);
        }

        if (\count($townIds) > 0) {
            $whereArguments[] = $queryBuilder->expr()->in('d.town', $townIds);
        }

        if (\count($stateOfPreservationIds) > 0) {
            $whereArguments[] = $queryBuilder->expr()->in('d.stateOfPreservation', $stateOfPreservationIds);
        }

        $whereArguments[] = $queryBuilder->expr()->eq('d.isShownOnSite', true);

        $documentsOfQuery = $queryBuilder
            ->where(\call_user_func_array([$queryBuilder->expr(), 'andX'], $whereArguments))
            ->getQuery()
            ->getResult();

        $documentsWithExcavations = [];

        if (\count($excavationIds) > 0) {
            foreach ($documentsOfQuery as $document) {
                foreach ($document->getMaterialElements() as $materialElement) {
                    $find = $materialElement->getFind();

                    if ($find instanceof ExcavationDependentFindInterface) {
                        if (null !== $find->getExcavation()
                            && \in_array((string) $find->getExcavation()->getId(), $excavationIds, true)
                        ) {
                            $documentsWithExcavations[] = $document;
                            break;
                        }
                    }
                }
            }
        } else {
            $documentsWithExcavations = $documentsOfQuery;
        }

        $documentsWithCategories = [];

        if (\count($categoryIds) > 0) {
            foreach ($documentsWithExcavations as $document) {
                foreach ($document->getContentElements() as $contentElement) {
                    if (null !== $contentElement->getCategory()
                        && \in_array((string) $contentElement->getCategory()->getId(), $categoryIds, true)
                    ) {
                        $documentsWithCategories[] = $document;
                        break;
                    }
                }
            }
        } else {
            $documentsWithCategories = $documentsWithExcavations;
        }

        return $documentsWithCategories;
    }

    /**
     * @return Document[]
     */
    public function findShown(): array
    {
        return $this->findBy([
            'isShownOnSite' => true,
        ]);
    }
}
