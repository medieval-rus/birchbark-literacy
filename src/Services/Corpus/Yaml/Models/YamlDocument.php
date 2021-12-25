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

namespace App\Services\Corpus\Yaml\Models;

use RuntimeException;

final class YamlDocument implements YamlPropertyContainerInterface
{
    use PropertyContainer;

    private string $number;
    /**
     * @var YamlPage[]
     */
    private array $pages;
    /**
     * @var YamlLineElement[]
     */
    private array $wordParts;

    /**
     * @param YamlPage[] $pages
     */
    public function __construct(string $number, array $pages, array $wordParts)
    {
        $this->number = $number;
        $this->pages = $pages;
        $this->wordParts = $wordParts;
    }

    public function addPage(YamlPage $page): void
    {
        $this->pages[] = $page;
        $page->setDocument($this);
    }

    public function registerWordPart(YamlLineElement $wordPart): void
    {
        $this->wordParts[] = $wordPart;
    }

    public function getLastPage(string $parsingEntityName, int $parsingLineIndex): YamlPage
    {
        if (0 === \count($this->pages)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot parse line %d: %s doesn\'t belong to any page.',
                    $parsingLineIndex,
                    $parsingEntityName
                )
            );
        }

        return end($this->pages);
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return YamlPage[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }
}
