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

namespace App\Services\Document\OriginalText\MarkupParser;

use App\Services\Document\OriginalText\MarkupParser\RuleParser\RuleParserInterface;
use App\Services\Document\OriginalText\MarkupParser\TextPiece\NormalTextPiece;
use App\Services\Document\OriginalText\MarkupParser\TextPiece\TextPieceInterface;
use App\Services\Document\OriginalText\MarkupParser\UnhandledTextArea\UnhandledTextAreaInterface;
use RuntimeException;

final class OriginalTextMarkupParser implements OriginalTextMarkupParserInterface
{
    /**
     * @var RuleParserInterface[]
     */
    private $ruleParsers = [];

    /**
     * @return TextPieceInterface[]
     */
    public function parse(string $originalText): array
    {
        return $this->applyRuleParsers($originalText);
    }

    public function addRuleParser(RuleParserInterface $ruleParser): void
    {
        $this->ruleParsers[] = $ruleParser;
    }

    /**
     * @return TextPieceInterface[]
     */
    private function applyRuleParsers(string $text, int $initialRuleParserIndex = 0): array
    {
        $ruleParser = $this->getRuleParser($initialRuleParserIndex);

        if (null === $ruleParser) {
            return [new NormalTextPiece($text)];
        }

        $parseResults = $ruleParser->parse($text);

        $finalResult = [];

        foreach ($parseResults as $parseResult) {
            if ($parseResult instanceof TextPieceInterface) {
                $finalResult[] = $parseResult;
            } elseif ($parseResult instanceof UnhandledTextAreaInterface) {
                foreach ($this->applyRuleParsers($parseResult->getText(), $initialRuleParserIndex + 1) as $textPiece) {
                    $finalResult[] = $textPiece;
                }
            } else {
                throw new RuntimeException('Unexpected rule parser result');
            }
        }

        return $finalResult;
    }

    private function getRuleParser(int $index): ?RuleParserInterface
    {
        return \array_key_exists($index, $this->ruleParsers) ? $this->ruleParsers[$index] : null;
    }
}
