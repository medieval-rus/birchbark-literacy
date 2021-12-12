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
use App\FilterableTable\Filter\Parameter\ContentCategoryFilterParameter;
use App\FilterableTable\Filter\Parameter\ConventionalDateFinalYearFilterParameter;
use App\FilterableTable\Filter\Parameter\ConventionalDateInitialYearFilterParameter;
use App\FilterableTable\Filter\Parameter\ExcavationFilterParameter;
use App\FilterableTable\Filter\Parameter\GenreFilterParameter;
use App\FilterableTable\Filter\Parameter\LanguageFilterParameter;
use App\FilterableTable\Filter\Parameter\NumberFilterParameter;
use App\FilterableTable\Filter\Parameter\OriginalTextFilterParameter;
use App\FilterableTable\Filter\Parameter\StateOfPreservationFilterParameter;
use App\FilterableTable\Filter\Parameter\StoragePlaceFilterParameter;
use App\FilterableTable\Filter\Parameter\TownFilterParameter;
use App\FilterableTable\Filter\Parameter\TranslatedTextFilterParameter;
use App\FilterableTable\Filter\Parameter\WayOfWritingFilterParameter;
use App\Services\Document\Sorting\DocumentComparerInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\AbstractFilterConfigurator;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\Table\TableParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Restriction\BooleanPropertyFilterRestriction;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Restriction\FilterRestrictionInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting\CustomSortConfiguration;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting\CustomSortConfigurationInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting\DbSortConfigurationInterface;

final class DocumentsFilterConfigurator extends AbstractFilterConfigurator
{
    private DocumentComparerInterface $documentComparer;
    private TownFilterParameter $townFilterParameter;
    private ExcavationFilterParameter $excavationFilterParameter;
    private StateOfPreservationFilterParameter $stateOfPreservationFilterParameter;
    private ContentCategoryFilterParameter $contentCategoryFilterParameter;
    private ConventionalDateInitialYearFilterParameter $conventionalDateInitialYearFilterParameter;
    private ConventionalDateFinalYearFilterParameter$conventionalDateFinalYearFilterParameter;
    private NumberFilterParameter $numberFilterParameter;
    private GenreFilterParameter $genreFilterParameter;
    private LanguageFilterParameter $languageFilterParameter;
    private OriginalTextFilterParameter $originalTextFilterParameter;
    private TranslatedTextFilterParameter $translatedTextFilterParameter;
    private StoragePlaceFilterParameter $storagePlaceFilterParameter;
    private WayOfWritingFilterParameter $wayOfWritingFilterParameter;

    public function __construct(
        DocumentComparerInterface $documentComparer,
        TownFilterParameter $townFilterParameter,
        ExcavationFilterParameter $excavationFilterParameter,
        StateOfPreservationFilterParameter $stateOfPreservationFilterParameter,
        ContentCategoryFilterParameter $contentCategoryFilterParameter,
        ConventionalDateInitialYearFilterParameter $conventionalDateInitialYearFilterParameter,
        ConventionalDateFinalYearFilterParameter $conventionalDateFinalYearFilterParameter,
        NumberFilterParameter $numberFilterParameter,
        GenreFilterParameter $genreFilterParameter,
        LanguageFilterParameter $languageFilterParameter,
        OriginalTextFilterParameter $originalTextFilterParameter,
        TranslatedTextFilterParameter $translatedTextFilterParameter,
        StoragePlaceFilterParameter $storagePlaceFilterParameter,
        WayOfWritingFilterParameter $wayOfWritingFilterParameter
    ) {
        $this->documentComparer = $documentComparer;
        $this->townFilterParameter = $townFilterParameter;
        $this->excavationFilterParameter = $excavationFilterParameter;
        $this->stateOfPreservationFilterParameter = $stateOfPreservationFilterParameter;
        $this->contentCategoryFilterParameter = $contentCategoryFilterParameter;
        $this->conventionalDateInitialYearFilterParameter = $conventionalDateInitialYearFilterParameter;
        $this->conventionalDateFinalYearFilterParameter = $conventionalDateFinalYearFilterParameter;
        $this->numberFilterParameter = $numberFilterParameter;
        $this->genreFilterParameter = $genreFilterParameter;
        $this->languageFilterParameter = $languageFilterParameter;
        $this->originalTextFilterParameter = $originalTextFilterParameter;
        $this->translatedTextFilterParameter = $translatedTextFilterParameter;
        $this->storagePlaceFilterParameter = $storagePlaceFilterParameter;
        $this->wayOfWritingFilterParameter = $wayOfWritingFilterParameter;
    }

    public function createSubmitButtonOptions(): array
    {
        return [
            'attr' => ['class' => 'btn mr-btn mr-btn-dark'],
            'label' => 'controller.document.list.filter.controls.submitButton',
        ];
    }

    public function createResetButtonOptions(): array
    {
        return [
            'attr' => ['class' => 'btn mr-btn mr-btn-light'],
            'label' => 'controller.document.list.filter.controls.resetButton',
        ];
    }

    public function createSearchInFoundButtonOptions(): array
    {
        return [
            'attr' => ['class' => 'btn mr-btn mr-btn-very-light'],
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

    /**
     * @param Document $entity
     */
    public function getEntityId($entity): int
    {
        return (int) $entity->getId();
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
            $this->numberFilterParameter,
            $this->conventionalDateInitialYearFilterParameter,
            $this->conventionalDateFinalYearFilterParameter,
            $this->townFilterParameter,
            $this->excavationFilterParameter,
            $this->stateOfPreservationFilterParameter,
            $this->contentCategoryFilterParameter,
            $this->genreFilterParameter,
            $this->languageFilterParameter,
            $this->storagePlaceFilterParameter,
            $this->wayOfWritingFilterParameter,
            $this->originalTextFilterParameter,
            $this->translatedTextFilterParameter,
        ];
    }

    /**
     * @return TableParameterInterface[]
     */
    protected function createTableParameters(): array
    {
        return [];
    }

    protected function createDbSortConfiguration(): ?DbSortConfigurationInterface
    {
        return null;
    }

    protected function createCustomSortConfiguration(): ?CustomSortConfigurationInterface
    {
        return new CustomSortConfiguration(
            function (array $documents): array {
                usort($documents, fn (Document $a, Document $b): int => $this->documentComparer->compare($a, $b));

                return $documents;
            }
        );
    }
}
