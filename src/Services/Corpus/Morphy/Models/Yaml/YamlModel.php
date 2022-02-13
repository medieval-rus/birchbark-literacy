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

final class YamlModel
{
    /**
     * @var YamlDocument[]
     */
    private array $documents;

    /**
     * @var YamlPropertyContainerInterface[]
     */
    private array $lastObjectsByLevel;

    /**
     * @param YamlDocument[] $documents
     */
    public function __construct(array $documents)
    {
        $this->documents = $documents;
        $this->lastObjectsByLevel = [];
    }

    /**
     * @return YamlDocument[]
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    public function getLastDocument(string $parsingEntityName, int $parsingLineIndex): YamlDocument
    {
        if (0 === \count($this->documents)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot parse line %d: %s doesn\'t belong to any document.',
                    $parsingLineIndex,
                    $parsingEntityName
                )
            );
        }

        return end($this->documents);
    }

    public function addDocument(YamlDocument $document): void
    {
        $this->documents[] = $document;

        $this->lastObjectsByLevel[1] = $document;
    }

    public function addPage(int $parsingLineIndex, YamlPage $page): void
    {
        $this
            ->getLastDocument('page', $parsingLineIndex)
            ->addPage($page);

        $this->lastObjectsByLevel[1] = $page;
    }

    public function addLine(int $parsingLineIndex, YamlLine $line): void
    {
        $this
            ->getLastDocument('line', $parsingLineIndex)
            ->getLastPage('line', $parsingLineIndex)
            ->addLine($line);

        $this->lastObjectsByLevel[1] = $line;
    }

    public function addComment(int $parsingLineIndex, YamlPiece $comment): void
    {
        $page = $this
            ->getLastDocument('comment', $parsingLineIndex)
            ->getLastPage('comment', $parsingLineIndex);

        try {
            $line = $page->getLastLine('comment', $parsingLineIndex);
        } catch (\Exception $exception) {
            // comment may occur outside the line, ignoring it for now
            return;
        }

        $line->addPiece($comment);

        $this->lastObjectsByLevel[1] = $line;
    }

    public function addPiece(int $parsingLineIndex, YamlPiece $piece): void
    {
        $this
            ->getLastDocument($piece->getType(), $parsingLineIndex)
            ->getLastPage($piece->getType(), $parsingLineIndex)
            ->getLastLine($piece->getType(), $parsingLineIndex)
            ->addPiece($piece);

        $this->lastObjectsByLevel[1] = $piece;
    }

    public function addWordPart(int $parsingLineIndex, YamlPiece $wordPart): void
    {
        $document = $this->getLastDocument('part', $parsingLineIndex);

        $document->registerWordPart($wordPart);

        $document
            ->getLastPage('part', $parsingLineIndex)
            ->getLastLine('part', $parsingLineIndex)
            ->addPiece($wordPart);

        $this->lastObjectsByLevel[1] = $wordPart;
    }

    public function addAnalysis(int $parsingLineIndex, YamlAnalysis $analysis): void
    {
        $this
            ->getLastDocument('analysis', $parsingLineIndex)
            ->getLastPage('analysis', $parsingLineIndex)
            ->getLastLine('analysis', $parsingLineIndex)
            ->getLastPiece('analysis', $parsingLineIndex)
            ->addAnalysis($analysis);

        $this->lastObjectsByLevel[2] = $analysis;
    }

    public function addProperty(int $parsingLineIndex, int $level, string $key, string $value): void
    {
        if (!\array_key_exists($level, $this->lastObjectsByLevel) ||
            null === $propertyContainer = $this->lastObjectsByLevel[$level]
        ) {
            throw new RuntimeException(
                sprintf(
                    'Cannot parse line %d: parent object for property "%s: %s" not found.',
                    $parsingLineIndex,
                    $key,
                    $value
                )
            );
        }

        $propertyContainer->addProperty($parsingLineIndex, $key, $value);
    }
}
