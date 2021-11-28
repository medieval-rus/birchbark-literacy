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

namespace App\FilterableTable;

use App\Entity\Document\Document;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Routing\RouteConfiguration;
use Vyfony\Bundle\FilterableTableBundle\Table\Checkbox\CheckboxHandlerInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Configurator\AbstractTableConfigurator;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadata;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;

final class DocumentsTableConfigurator extends AbstractTableConfigurator
{
    private DocumentFormatterInterface $formatter;
    private TranslatorInterface $translator;

    public function __construct(
        RouterInterface $router,
        FilterConfiguratorInterface $filterConfigurator,
        DocumentFormatterInterface $formatter,
        TranslatorInterface $translator
    ) {
        parent::__construct($router, $filterConfigurator);

        $this->formatter = $formatter;
        $this->translator = $translator;
    }

    protected function getListRoute(): RouteConfiguration
    {
        return new RouteConfiguration('document__list', []);
    }

    /**
     * @param Document $entity
     */
    protected function getShowRoute($entity): RouteConfiguration
    {
        return new RouteConfiguration(
            'document__show',
            [
                'town' => $entity->getTown()->getAlias(),
                'number' => $entity->getNumber(),
            ]
        );
    }

    protected function getSortBy(): string
    {
        return 'id';
    }

    protected function getIsSortAsc(): bool
    {
        return true;
    }

    protected function getPaginatorTailLength(): int
    {
        return 2;
    }

    protected function getResultsCountText(): string
    {
        return 'controller.document.list.table.resultsCount';
    }

    /**
     * @return ColumnMetadataInterface[]
     */
    protected function createColumnMetadataCollection(): array
    {
        return [
            (new ColumnMetadata())
                ->setIsIdentifier(true)
                ->setName('number')
                ->setLabel('controller.document.list.table.column.number')
                ->setIsRaw(true)
                ->setValueExtractor(function (Document $document): string {
                    $preliminaryPublication = $document->getIsPreliminaryPublication()
                        ? '<br /><span class="mr-preliminary-publication">'.
                        $this->translator->trans('global.document.preliminaryPublication').
                        '</span>'
                        : '';

                    return $this->formatter->getLabel($document).$preliminaryPublication;
                }),
            (new ColumnMetadata())
                ->setName('conventionalDate')
                ->setLabel('controller.document.list.table.column.conventionalDate')
                ->setIsRaw(true)
                ->setValueExtractor(
                    fn (Document $document): string => $this->formatter->getConventionalDateWithBiases($document)
                ),
            (new ColumnMetadata())
                ->setName('town')
                ->setLabel('controller.document.list.table.column.town')
                ->setValueExtractor(fn (Document $document): string => $this->formatter->getTown($document)),
            (new ColumnMetadata())
                ->setName('town')
                ->setLabel('controller.document.list.table.column.description')
                ->setValueExtractor(fn (Document $document): string => $this->formatter->getDescription($document)),
        ];
    }

    /**
     * @return CheckboxHandlerInterface[]
     */
    protected function createCheckboxHandlers(): array
    {
        return [];
    }
}
