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

final class YamlLine implements YamlPropertyContainerInterface
{
    use PropertyContainer;

    private ?string $name;

    /**
     * @var YamlLineElement[]
     */
    private array $elements;

    /**
     * @param YamlLineElement[] $items
     */
    public function __construct(?string $name, array $elements)
    {
        $this->name = $name;
        $this->elements = $elements;
    }

    public function addElement(YamlLineElement $element): void
    {
        $this->elements[] = $element;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return YamlLineElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    public function getLastElement(string $parsingEntityName, int $parsingLineIndex): YamlLineElement
    {
        if (0 === \count($this->elements)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot parse line %d: %s doesn\'t belong to any line element.',
                    $parsingLineIndex,
                    $parsingEntityName
                )
            );
        }

        return end($this->elements);
    }
}
