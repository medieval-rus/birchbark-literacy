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

use Exception;

final class YamlAnalysis implements YamlPropertyContainerInterface
{
    use PropertyContainer;

    private string $name;
    private YamlLineElement $element;

    public function __construct(string $name)
    {
        $this->name = $name;
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

    public function getElement(): YamlLineElement
    {
        return $this->element;
    }

    public function setElement(YamlLineElement $element): void
    {
        $this->element = $element;
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
                $this->element->getLine()->getPage()->getDocument()->getNumber(),
                $this->element->getLine()->getPage()->getName(),
                $this->element->getLine()->getName() ?? '',
                $this->element->getValue() ?? ''
            )
        );
    }
}
