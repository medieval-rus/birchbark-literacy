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

namespace App\Services\Corpus\Indices\Models\Sources;

final class IndexItemSource
{
    private string $lemma;
    private string $partOfSpeech;
    /**
     * @var IndexItemEntrySource[]
     */
    private array $entries;

    /**
     * @param IndexItemEntrySource[] $entries
     */
    public function __construct(string $lemma, string $partOfSpeech, array $entries)
    {
        $this->lemma = $lemma;
        $this->partOfSpeech = $partOfSpeech;
        $this->entries = $entries;
    }

    public function getLemma(): string
    {
        return $this->lemma;
    }

    public function getPartOfSpeech(): string
    {
        return $this->partOfSpeech;
    }

    /**
     * @return IndexItemEntrySource[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    public function addEntry(IndexItemEntrySource $entry): void
    {
        $this->entries[] = $entry;
    }
}
