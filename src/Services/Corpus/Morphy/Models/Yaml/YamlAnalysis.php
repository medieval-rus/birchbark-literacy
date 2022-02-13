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

use App\Helper\StringHelper;
use Exception;

final class YamlAnalysis implements YamlPropertyContainerInterface
{
    use PropertyContainer;

    private string $name;
    private YamlPiece $piece;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModifiers(): YamlAnalysisModifiers
    {
        if (StringHelper::endsWith($this->getLemma(), '*?') || StringHelper::endsWith($this->getLemma(), '?*')) {
            return new YamlAnalysisModifiers(true, true);
        }

        if (StringHelper::endsWith($this->getLemma(), '?')) {
            return new YamlAnalysisModifiers(true, false);
        }

        if (StringHelper::endsWith($this->getLemma(), '*')) {
            return new YamlAnalysisModifiers(false, true);
        }

        return new YamlAnalysisModifiers(false, false);
    }

    public function getLemmaWithoutModifiers(): string
    {
        return rtrim($this->getLemma(), '?*');
    }

    public function getLemma(): string
    {
        $lemmas = $this->getProperty('lemma');
        if (1 !== \count($lemmas)) {
            throw $this->createAnalysisException('lemma', $lemmas);
        }

        return $lemmas[0];
    }

    public function getPartOfSpeech(): string
    {
        $partsOfSpeech = $this->getProperty('grdic');
        if (1 !== \count($partsOfSpeech)) {
            throw $this->createAnalysisException('grdic', $partsOfSpeech);
        }

        return $partsOfSpeech[0];
    }

    public function getPiece(): YamlPiece
    {
        return $this->piece;
    }

    public function setPiece(YamlPiece $piece): void
    {
        $this->piece = $piece;
    }

    private function createAnalysisException(
        string $propertyName,
        array $properties
    ): Exception {
        return new Exception(
            sprintf(
                'Found %d "%s" tags: document = %s; page = %s; line = %s; value = %s',
                \count($properties),
                $propertyName,
                $this->piece->getLine()->getPage()->getDocument()->getNumber(),
                $this->piece->getLine()->getPage()->getName(),
                $this->piece->getLine()->getName() ?? '',
                $this->piece->getValue() ?? ''
            )
        );
    }
}
