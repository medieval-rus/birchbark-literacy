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

namespace App\Services\Corpus\Indices;

use App\Helper\ArrayGrouping;
use App\Helper\ArrayHelper;
use App\Services\Corpus\Indices\Models\InflectedForm;
use App\Services\Corpus\Indices\Models\InflectedFromEntry;
use App\Services\Corpus\Indices\Models\Sources\IndexSource;
use App\Services\Corpus\Indices\Models\Sources\InflectedFormSource;
use App\Services\Corpus\Indices\Models\Sources\WordSource;
use App\Services\Corpus\Indices\Models\Word;
use App\Services\Corpus\Indices\Models\WordIndex;
use App\Services\Corpus\Yaml\Models\YamlDocument;
use App\Services\Corpus\Yaml\YamlParsingHelperInterface;

final class IndexGenerator implements IndexGeneratorInterface
{
    private YamlParsingHelperInterface $yamlParsingHelper;

    public function __construct(YamlParsingHelperInterface $yamlParsingHelper)
    {
        $this->yamlParsingHelper = $yamlParsingHelper;
    }

    /**
     * @param YamlDocument[] $documents
     */
    public function generate(array $documents): WordIndex
    {
        $indexSource = $this->getIndexSource($documents);

        return $this->createWordIndex($indexSource);
    }

    /**
     * @param YamlDocument[] $documents
     */
    private function getIndexSource(array $documents): IndexSource
    {
        $numbersByConventionalName = $this->yamlParsingHelper->getNumbersByConventionalName();

        $indexSource = new IndexSource([]);

        foreach ($documents as $document) {
            foreach ($document->getPages() as $page) {
                foreach ($page->getLines() as $line) {
                    foreach ($line->getElements() as $element) {
                        foreach ($element->getAnalyses() as $analysis) {
                            $indexSource
                                ->йgetOrCreateWord($analysis->getLemmaWithoutModifiers(), $analysis->getPartOfSpeech())
                                ->addEntry(
                                    new InflectedFormSource(
                                        $this
                                            ->yamlParsingHelper
                                            ->getNumber(
                                                $analysis->getElement()->getLine()->getPage()->getDocument(),
                                                $numbersByConventionalName
                                            ),
                                        $analysis
                                    )
                            );
                        }
                    }
                }
            }
        }

        return $indexSource;
    }

    private function createWordIndex(IndexSource $indexSource)
    {
        return new WordIndex(
            array_map(
                fn (WordSource $wordSource): Word => new Word(
                    $wordSource->getLemma(),
                    $wordSource->getPartOfSpeech(),
                    $this->createInflectedForms($wordSource->getEntries())
                ),
                $indexSource->getWords()
            )
        );
    }

    /**
     * @param InflectedFormSource[] $inflectedFormSources
     *
     * @return InflectedForm[]
     */
    private function createInflectedForms(array $inflectedFormSources): array
    {
        $groupedByLemmaModifiers = ArrayHelper::groupBy(
            $inflectedFormSources,
            fn (InflectedFormSource $itemEntrySource): string => $itemEntrySource->getAnalysis()->getLemmaModifiers()
        );

        $inflectedForms = [];

        foreach ($groupedByLemmaModifiers as $modifiers => $grouping) {
            $groupedByInflectedForm = ArrayHelper::groupBy(
                $inflectedFormSources,
                fn (InflectedFormSource $itemEntrySource): string => $itemEntrySource
                    ->getAnalysis()
                    ->getElement()
                    ->getValue()
            );

            foreach ($groupedByInflectedForm as $groupingByInflectedForm) {
                $inflectedForms[] = new InflectedForm(
                    $groupingByInflectedForm->getKey(),
                    array_map(
                        fn (ArrayGrouping $groupingByDocument): InflectedFromEntry => new InflectedFromEntry(
                            $groupingByDocument->getKey(),
                            \count($groupingByDocument->getItems())
                        ),
                        // group by document
                        ArrayHelper::groupBy(
                            $groupingByInflectedForm->getItems(),
                            fn (InflectedFormSource $itemEntrySource): string => $itemEntrySource
                                ->getDocumentNumber()
                        )
                    ),
                    str_contains($modifiers, '?'),
                    str_contains($modifiers, '*')
                );
            }
        }

        return $inflectedForms;
    }
}
