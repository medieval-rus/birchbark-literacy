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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Persistence\QueryBuilder\Alias\AliasFactoryInterface;

final class NumberFilterParameter implements FilterParameterInterface, ExpressionBuilderInterface
{
    /**
     * @var AliasFactoryInterface
     */
    private $aliasFactory;

    public function __construct(AliasFactoryInterface $aliasFactory)
    {
        $this->aliasFactory = $aliasFactory;
    }

    public function getQueryParameterName(): string
    {
        return 'number';
    }

    public function getType(): string
    {
        return HiddenType::class;
    }

    public function getOptions(EntityManager $entityManager): array
    {
        return [
            'attr' => [
                'data-mr-number-filter' => true,
            ],
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

        $number = (string) $formData;

        $queryBuilder
            ->setParameter(
                $equalityParameter = $this->createParameter('number'),
                $number,
                Types::STRING
            )
            ->setParameter(
                $composedLeftParameter = $this->createParameter('number'),
                $number.'/%',
                Types::STRING
            )
            ->setParameter(
                $composedRightParameter = $this->createParameter('number'),
                '%/'.$number,
                Types::STRING
            )
            ->setParameter(
                $composedMiddleParameter = $this->createParameter('number'),
                '%/'.$number.'/%',
                Types::STRING
            );

        return (string) $queryBuilder->expr()->orX(
            $queryBuilder->expr()->eq($entityAlias.'.number', $equalityParameter),
            $queryBuilder->expr()->like($entityAlias.'.number', $composedLeftParameter),
            $queryBuilder->expr()->like($entityAlias.'.number', $composedRightParameter),
            $queryBuilder->expr()->like($entityAlias.'.number', $composedMiddleParameter),
        );
    }

    // todo move to AliasFactory
    private function createParameter(string $parameterName): string
    {
        return strtolower(':'.$parameterName.'_'.uniqid());
    }
}
