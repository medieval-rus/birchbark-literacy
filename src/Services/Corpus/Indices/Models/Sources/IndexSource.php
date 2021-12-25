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

final class IndexSource
{
    /**
     * @var IndexItemSource[]
     */
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return IndexItemSource[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getOrCreateItem(string $lemma, string $partOfSpeech): IndexItemSource
    {
        $key = sprintf('%s_%s', $lemma, $partOfSpeech);

        if (!\array_key_exists($key, $this->items)) {
            $this->items[$key] = new IndexItemSource($lemma, $partOfSpeech, []);
        }

        return $this->items[$key];
    }
}
