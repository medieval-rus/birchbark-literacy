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
 * in the hope  that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

namespace App\Services\Document\OriginalText\MarkupParser\RuleParser;

use App\Services\Document\OriginalText\MarkupParser\TextPiece\TextPieceInterface;
use App\Services\Document\OriginalText\MarkupParser\UnhandledTextArea\UnhandledTextArea;

abstract class AbstractRuleParser implements RuleParserInterface
{
    public function parse(string $text): array
    {
        $pieces = [];

        $regexMatches = $this->findMatches($text);

        $fullMatches = $regexMatches[0];
        $exactMatches = $regexMatches[1];

        foreach ($fullMatches as $index => $match) {
            $explodedText = explode($match, $text, 2);

            if ('' !== $explodedText[0]) {
                $pieces[] = new UnhandledTextArea($explodedText[0]);
                $pieces[] = $this->factoryTextPiece($exactMatches[$index]);
                $text = $explodedText[1];
            } else {
                $pieces[] = $this->factoryTextPiece($exactMatches[$index]);
                $text = '' === $explodedText[1] ? '' : $explodedText[1];
            }
        }

        if (0 === \count($pieces) || '' !== $text) {
            $pieces[] = new UnhandledTextArea($text);
        }

        return $pieces;
    }

    abstract protected function factoryTextPiece(string $text): TextPieceInterface;

    abstract protected function getRegex(): string;

    private function findMatches(string $text): array
    {
        preg_match_all($this->getRegex(), $text, $matches);

        return $matches;
    }
}
