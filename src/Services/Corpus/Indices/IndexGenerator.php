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
use App\Services\Corpus\Indices\Models\Sources\IndexItemEntrySource;
use App\Services\Corpus\Indices\Models\Sources\IndexItemSource;
use App\Services\Corpus\Indices\Models\Sources\IndexSource;
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
        $numbersByConventionalName = $this->yamlParsingHelper->getNumbersByConventionalName();

        $index = new IndexSource([]);

        foreach ($documents as $document) {
            foreach ($document->getPages() as $page) {
                foreach ($page->getLines() as $line) {
                    foreach ($line->getElements() as $element) {
                        foreach ($element->getAnalyses() as $analysis) {
                            $index
                                ->getOrCreateItem($analysis->getLemma(), $analysis->getPartOfSpeech())
                                ->addEntry(
                                    new IndexItemEntrySource(
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

        return new WordIndex(
            array_map(
                fn (IndexItemSource $itemSource): Word => new Word(
                    $itemSource->getLemma(),
                    $itemSource->getPartOfSpeech(),
                    array_map(
                        fn (ArrayGrouping $groupingByWord): InflectedForm => new InflectedForm(
                            $groupingByWord->getKey(),
                            array_map(
                                fn (ArrayGrouping $groupingByDocument): InflectedFromEntry => new InflectedFromEntry(
                                    $groupingByDocument->getKey(),
                                    \count($groupingByDocument->getItems())
                                ),
                                ArrayHelper::groupBy(
                                    $groupingByWord->getItems(),
                                    fn (IndexItemEntrySource $itemEntrySource): string => $itemEntrySource
                                        ->getDocumentNumber()
                                )
                            )
                        ),
                        ArrayHelper::groupBy(
                            $itemSource->getEntries(),
                            fn (IndexItemEntrySource $itemEntrySource): string => $itemEntrySource
                                ->getAnalysis()
                                ->getElement()
                                ->getValue()
                        )
                    )
                ),
                array_values($index->getItems())
            )
        );
    }
}
