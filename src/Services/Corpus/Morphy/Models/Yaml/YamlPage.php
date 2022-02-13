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

namespace App\Services\Corpus\Morphy\Models\Yaml;

use RuntimeException;

final class YamlPage implements YamlPropertyContainerInterface
{
    use PropertyContainer;

    private string $name;
    /**
     * @var YamlLine[]
     */
    private array $lines;
    private YamlDocument $document;

    /**
     * @param YamlLine[] $lines
     */
    public function __construct(string $name, array $lines)
    {
        $this->name = $name;
        $this->lines = $lines;
    }

    public function addLine(YamlLine $line): void
    {
        $this->lines[] = $line;
        $line->setPage($this);
    }

    public function getLastLine(string $parsingEntityName, int $parsingLineIndex): YamlLine
    {
        if (0 === \count($this->lines)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot parse line %d: %s doesn\'t belong to any line.',
                    $parsingLineIndex,
                    $parsingEntityName
                )
            );
        }

        return end($this->lines);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return YamlLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    public function getDocument(): YamlDocument
    {
        return $this->document;
    }

    public function setDocument(YamlDocument $document): void
    {
        $this->document = $document;
    }
}
