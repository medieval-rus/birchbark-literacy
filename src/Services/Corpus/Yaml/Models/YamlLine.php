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
     * @var YamlPiece[]
     */
    private array $pieces;
    private YamlPage $page;

    /**
     * @param YamlPiece[] $pieces
     */
    public function __construct(?string $name, array $pieces)
    {
        $this->name = $name;
        $this->pieces = $pieces;
    }

    public function addPiece(YamlPiece $piece): void
    {
        $this->pieces[] = $piece;
        $piece->setLine($this);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return YamlPiece[]
     */
    public function getPieces(): array
    {
        return $this->pieces;
    }

    public function getLastPiece(string $parsingEntityName, int $parsingLineIndex): YamlPiece
    {
        if (0 === \count($this->pieces)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot parse line %d: %s does not belong to any line piece.',
                    $parsingLineIndex,
                    $parsingEntityName
                )
            );
        }

        return end($this->pieces);
    }

    public function getPage(): YamlPage
    {
        return $this->page;
    }

    public function setPage(YamlPage $page): void
    {
        $this->page = $page;
    }
}
