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

use App\Entity\Document\ArchaeologicalFind;
use App\Entity\Document\Excavation;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Persistence\QueryBuilder\Alias\AliasFactoryInterface;

final class ExcavationFilterParameter implements FilterParameterInterface, ExpressionBuilderInterface
{
    /**
     * @var AliasFactoryInterface
     */
    private $aliasFactory;

    /**
     * @var DocumentFormatterInterface
     */
    private $formatter;

    public function __construct(AliasFactoryInterface $aliasFactory, DocumentFormatterInterface $formatter)
    {
        $this->aliasFactory = $aliasFactory;
        $this->formatter = $formatter;
    }

    public function getQueryParameterName(): string
    {
        return 'excavation';
    }

    public function getType(): string
    {
        return EntityType::class;
    }

    public function getOptions(EntityManager $entityManager): array
    {
        return [
            'label' => 'controller.document.list.filter.excavation',
            'attr' => [
                'class' => '',
                'data-vyfony-filterable-table-filter-parameter' => true,
            ],
            'class' => Excavation::class,
            'choice_label' => fn (Excavation $excavation): string => $this
                ->formatter
                ->getExcavationWithTown($excavation),
            'expanded' => false,
            'multiple' => true,
            'query_builder' => $this->createQueryBuilder(),
        ];
    }

    /**
     * @param mixed $formData
     */
    public function buildWhereExpression(QueryBuilder $queryBuilder, $formData, string $entityAlias): ?string
    {
        $excavations = $formData;

        if (0 === \count($excavations)) {
            return null;
        }

        $ids = [];

        foreach ($excavations as $excavation) {
            $ids[] = $excavation->getId();
        }

        $queryBuilder
            ->innerJoin(
                $entityAlias.'.materialElements',
                $materialElementAlias = $this->aliasFactory->createAlias(static::class, 'materialElements')
            )
            ->innerJoin(
                $materialElementAlias.'.find',
                $findAlias = $this->aliasFactory->createAlias(static::class, 'find')
            )
            ->innerJoin(
                ArchaeologicalFind::class,
                $archaeologicalFindAlias = $this->aliasFactory->createAlias(static::class, 'archaeologicalFind'),
                Join::WITH,
                sprintf('%s.id = %s.id', $archaeologicalFindAlias, $findAlias)
            )
            ->innerJoin(
                $archaeologicalFindAlias.'.excavation',
                $excavationAlias = $this->aliasFactory->createAlias(static::class, 'excavation')
            )
        ;

        return (string) $queryBuilder->expr()->in($excavationAlias.'.id', $ids);
    }

    private function createQueryBuilder(): callable
    {
        return fn (EntityRepository $repository): QueryBuilder => $repository
            ->createQueryBuilder($excavationAlias = $this->createAlias())
            ->leftJoin(
                $excavationAlias.'.town',
                $townAlias = $this->aliasFactory->createAlias(static::class, 'town')
            )
            ->addOrderBy($townAlias.'.name', 'ASC')
            ->addOrderBy($excavationAlias.'.name', 'ASC');
    }

    private function createAlias(): string
    {
        return $this->aliasFactory->createAlias(static::class, 'excavation');
    }
}
