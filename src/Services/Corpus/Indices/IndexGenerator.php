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
                    foreach ($line->getPieces() as $piece) {
                        foreach ($piece->getAnalyses() as $analysis) {
                            $indexSource
                                ->getOrCreateWord($analysis->getLemmaWithoutModifiers(), $analysis->getPartOfSpeech())
                                ->addEntry(
                                    new InflectedFormSource(
                                        $this
                                            ->yamlParsingHelper
                                            ->getNumber(
                                                $analysis->getPiece()->getLine()->getPage()->getDocument(),
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
        $inflectedForms = [];

        $groupedByWordModifiers = ArrayHelper::groupBy(
            $inflectedFormSources,
            fn (InflectedFormSource $itemEntrySource): string => $itemEntrySource
                ->getAnalysis()
                ->getPiece()
                ->getModifiers()
                ->__toString()
        );

        foreach ($groupedByWordModifiers as $groupingByWordModifiers) {
            /**
             * @var $wordModifiersSource InflectedFormSource
             */
            $wordModifiersSource = $groupingByWordModifiers->getItems()[0];

            $groupedByLemmaModifiers = ArrayHelper::groupBy(
                $groupingByWordModifiers->getItems(),
                fn (InflectedFormSource $itemEntrySource): string => $itemEntrySource
                    ->getAnalysis()
                    ->getModifiers()
                    ->__toString()
            );

            foreach ($groupedByLemmaModifiers as $groupingByLemmaModifiers) {
                /**
                 * @var $lemmaModifiersSource InflectedFormSource
                 */
                $lemmaModifiersSource = $groupingByLemmaModifiers->getItems()[0];

                $groupedByInflectedForm = ArrayHelper::groupBy(
                    $groupingByLemmaModifiers->getItems(),
                    fn (InflectedFormSource $itemEntrySource): string => $itemEntrySource
                        ->getAnalysis()
                        ->getPiece()
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
                                fn (InflectedFormSource $inflectedFormSource): string => $inflectedFormSource
                                    ->getDocumentNumber()
                            )
                        ),
                        $lemmaModifiersSource->getAnalysis()->getModifiers()->getIsUnsure(),
                        $lemmaModifiersSource->getAnalysis()->getModifiers()->getIsPhonemicUnsure(),
                        $wordModifiersSource->getAnalysis()->getPiece()->getModifiers()->getIsReconstruction(),
                        $wordModifiersSource->getAnalysis()->getPiece()->getModifiers()->getIsMisspelled()
                    );
                }
            }
        }

        return $inflectedForms;
    }
}
