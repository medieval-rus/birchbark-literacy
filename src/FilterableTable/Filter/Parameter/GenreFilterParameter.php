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

namespace App\FilterableTable\Filter\Parameter;

use App\Entity\Document\Genre;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Persistence\QueryBuilder\Alias\AliasFactoryInterface;

final class GenreFilterParameter implements FilterParameterInterface, ExpressionBuilderInterface
{
    private AliasFactoryInterface $aliasFactory;

    public function __construct(AliasFactoryInterface $aliasFactory)
    {
        $this->aliasFactory = $aliasFactory;
    }

    public function getQueryParameterName(): string
    {
        return 'genre';
    }

    public function getType(): string
    {
        return EntityType::class;
    }

    public function getOptions(EntityManager $entityManager): array
    {
        return [
            'label' => 'controller.document.list.filter.genre',
            'attr' => [
                'class' => '',
                'data-vyfony-filterable-table-filter-parameter' => true,
            ],
            'class' => Genre::class,
            'choice_label' => 'name',
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
        if (0 === \count($formData)) {
            return null;
        }

        $ids = $formData->map(fn (Genre $entity): int => $entity->getId())->toArray();

        $queryBuilder
            ->innerJoin(
                $entityAlias.'.contentElements',
                $contentElementAlias = $this->aliasFactory->createAlias(static::class, 'contentElements')
            )
            ->leftJoin(
                $contentElementAlias.'.genres',
                $genreAlias = $this->createAlias()
            )
        ;

        return (string) $queryBuilder->expr()->in($genreAlias.'.id', $ids);
    }

    private function createQueryBuilder(): callable
    {
        return fn (EntityRepository $repository): QueryBuilder => $repository
                ->createQueryBuilder($entityAlias = $this->createAlias())
                ->orderBy($entityAlias.'.name', 'ASC');
    }

    private function createAlias(): string
    {
        return $this->aliasFactory->createAlias(static::class, 'genre');
    }
}
