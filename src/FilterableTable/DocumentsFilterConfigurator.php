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

namespace App\FilterableTable;

use App\Entity\Document\Document;
use App\FilterableTable\Filter\Parameter\ContentCategoryFilterParameter;
use App\FilterableTable\Filter\Parameter\ConventionalDateFinalYearFilterParameter;
use App\FilterableTable\Filter\Parameter\ConventionalDateInitialYearFilterParameter;
use App\FilterableTable\Filter\Parameter\ExcavationFilterParameter;
use App\FilterableTable\Filter\Parameter\StateOfPreservationFilterParameter;
use App\FilterableTable\Filter\Parameter\TownFilterParameter;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\AbstractFilterConfigurator;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\Table\TableParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Restriction\BooleanPropertyFilterRestriction;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Restriction\FilterRestrictionInterface;

final class DocumentsFilterConfigurator extends AbstractFilterConfigurator
{
    /**
     * @var TownFilterParameter
     */
    private $townFilterParameter;

    /**
     * @var ExcavationFilterParameter
     */
    private $excavationFilterParameter;

    /**
     * @var StateOfPreservationFilterParameter
     */
    private $stateOfPreservationFilterParameter;

    /**
     * @var ContentCategoryFilterParameter
     */
    private $contentCategoryFilterParameter;

    /**
     * @var ConventionalDateInitialYearFilterParameter
     */
    private $conventionalDateInitialYearFilterParameter;

    /**
     * @var ConventionalDateFinalYearFilterParameter
     */
    private $conventionalDateFinalYearFilterParameter;

    public function __construct(
        TownFilterParameter $townFilterParameter,
        ExcavationFilterParameter $excavationFilterParameter,
        StateOfPreservationFilterParameter $stateOfPreservationFilterParameter,
        ContentCategoryFilterParameter $contentCategoryFilterParameter,
        ConventionalDateInitialYearFilterParameter $conventionalDateInitialYearFilterParameter,
        ConventionalDateFinalYearFilterParameter $conventionalDateFinalYearFilterParameter
    ) {
        $this->townFilterParameter = $townFilterParameter;
        $this->excavationFilterParameter = $excavationFilterParameter;
        $this->stateOfPreservationFilterParameter = $stateOfPreservationFilterParameter;
        $this->contentCategoryFilterParameter = $contentCategoryFilterParameter;
        $this->conventionalDateInitialYearFilterParameter = $conventionalDateInitialYearFilterParameter;
        $this->conventionalDateFinalYearFilterParameter = $conventionalDateFinalYearFilterParameter;
    }

    public function createSubmitButtonOptions(): array
    {
        return [
            'attr' => ['class' => 'btn mr-btn-dark'],
            'label' => 'controller.document.list.filter.controls.submitButton',
        ];
    }

    public function createResetButtonOptions(): array
    {
        return [
            'attr' => ['class' => 'btn mr-btn-light'],
            'label' => 'controller.document.list.filter.controls.resetButton',
        ];
    }

    public function createSearchInFoundButtonOptions(): array
    {
        return [
            'attr' => ['class' => 'btn mr-btn-very-light'],
            'label' => 'controller.document.list.filter.controls.searchInFoundButton',
        ];
    }

    public function createDefaults(): array
    {
        return [
            'label_attr' => ['class' => ''],
            'translation_domain' => 'messages',
            'attr' => ['class' => 'row'],
            'method' => 'GET',
            'csrf_protection' => false,
            'required' => false,
        ];
    }

    public function getDisablePaginationLabel(): string
    {
        return 'controller.document.list.filter.controls.disablePaginator';
    }

    /**
     * @param Document $entity
     */
    public function getEntityId($entity): int
    {
        return (int) $entity->getId();
    }

    public function getPageSize(): int
    {
        return 20;
    }

    /**
     * @return FilterRestrictionInterface[]
     */
    protected function createFilterRestrictions(): array
    {
        return [
            (new BooleanPropertyFilterRestriction())
                ->setName('isShownOnSite')
                ->setValue(true),
        ];
    }

    /**
     * @return FilterParameterInterface[]
     */
    protected function createFilterParameters(): array
    {
        return [
            $this->conventionalDateInitialYearFilterParameter,
            $this->conventionalDateFinalYearFilterParameter,
            $this->townFilterParameter,
            $this->excavationFilterParameter,
            $this->stateOfPreservationFilterParameter,
            $this->contentCategoryFilterParameter,
        ];
    }

    /**
     * @return TableParameterInterface[]
     */
    protected function createTableParameters(): array
    {
        return [];
    }
}
