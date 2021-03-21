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

namespace App\FilterableTable\Filter\Parameter;

use App\Repository\Document\ConventionalDateCellRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Persistence\QueryBuilder\Alias\AliasFactoryInterface;

final class ConventionalDateInitialYearFilterParameter implements FilterParameterInterface, ExpressionBuilderInterface
{
    /**
     * @var AliasFactoryInterface
     */
    private $aliasFactory;

    /**
     * @var ConventionalDateCellRepository
     */
    private $conventionalDateCellRepository;

    public function __construct(
        AliasFactoryInterface $aliasFactory,
        ConventionalDateCellRepository $conventionalDateCellRepository
    ) {
        $this->aliasFactory = $aliasFactory;
        $this->conventionalDateCellRepository = $conventionalDateCellRepository;
    }

    public function getQueryParameterName(): string
    {
        return 'conventionalDateInitialYear';
    }

    public function getType(): string
    {
        return HiddenType::class;
    }

    public function getOptions(EntityManager $entityManager): array
    {
        return [
            'attr' => [
                'class' => '',
                'data-vyfony-filterable-table-filter-parameter' => true,
            ],
            'data' => $this->conventionalDateCellRepository->getMinimalConventionalDate(),
        ];
    }

    /**
     * @param mixed $formData
     */
    public function buildWhereExpression(QueryBuilder $queryBuilder, $formData, string $entityAlias): ?string
    {
        if (null === $formData) {
            return null;
        }

        $conventionalDateInitialYear = (int) $formData;

        $queryBuilder
            ->innerJoin(
                $entityAlias.'.conventionalDate',
                $conventionalDateAlias = $this->aliasFactory->createAlias(static::class, 'conventionalDate')
            )
        ;

        return (string) $queryBuilder->expr()->gte($conventionalDateAlias.'.initialYear', $conventionalDateInitialYear);
    }
}
