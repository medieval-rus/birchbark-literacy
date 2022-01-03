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

namespace App\Services\Corpus\Yaml;

use App\Repository\Document\DocumentRepository;
use App\Services\Corpus\Yaml\Models\YamlAnalysis;
use App\Services\Corpus\Yaml\Models\YamlDocument;
use App\Services\Corpus\Yaml\Models\YamlLine;
use App\Services\Corpus\Yaml\Models\YamlModel;
use App\Services\Corpus\Yaml\Models\YamlPage;
use App\Services\Corpus\Yaml\Models\YamlPiece;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use RuntimeException;

final class YamlParser implements YamlParserInterface
{
    private DocumentFormatterInterface $documentFormatter;
    private DocumentRepository $documentRepository;

    public function __construct(
        DocumentFormatterInterface $documentFormatter,
        DocumentRepository $documentRepository
    ) {
        $this->documentFormatter = $documentFormatter;
        $this->documentRepository = $documentRepository;
    }

    /**
     * @return YamlDocument[]
     */
    public function parseYaml(string $rawYaml): array
    {
        $lines = explode("\r\n", $rawYaml);

        $yamlModel = new YamlModel([]);

        for ($lineIndex = 0; $lineIndex < \count($lines); ++$lineIndex) {
            $humanReadableLineIndex = $lineIndex + 1;
            $line = $lines[$lineIndex];

            if (1 === preg_match('/^-document: (.+)$/', $line, $matches)) {
                $yamlModel->addDocument(new YamlDocument($matches[1], [], []));
            } elseif (1 === preg_match('/^-page: +(.+)$/', $line, $matches)) {
                $yamlModel->addPage($humanReadableLineIndex, new YamlPage($matches[1], []));
            } elseif (1 === preg_match('/^-line: *(.*)$/', $line, $matches)) {
                $yamlModel->addLine($humanReadableLineIndex, new YamlLine($matches[1], []));
            } elseif (1 === preg_match('/^-comment: +(.+)$/', $line, $matches)) {
                $yamlModel->addComment($humanReadableLineIndex, new YamlPiece('comment', $matches[1], []));
            } elseif (1 === preg_match('/^-fragment: *(.*)$/', $line, $matches)) {
                $yamlModel->addPiece($humanReadableLineIndex, new YamlPiece('fragment', $matches[1], []));
            } elseif (1 === preg_match('/^-punc: (.+)$/', $line, $matches)) {
                $yamlModel->addPiece($humanReadableLineIndex, new YamlPiece('punctuation', $matches[1], []));
            } elseif (1 === preg_match('/^-word: (.+)$/', $line, $matches)) {
                $yamlModel->addPiece($humanReadableLineIndex, new YamlPiece('word', $matches[1], []));
            } elseif (1 === preg_match('/^-part: (.+)$/', $line, $matches)) {
                $yamlModel->addWordPart($humanReadableLineIndex, new YamlPiece('part', $matches[1], []));
            } elseif (1 === preg_match('/^ -ana: (.+)$/', $line, $matches)) {
                $yamlModel->addAnalysis($humanReadableLineIndex, new YamlAnalysis($matches[1]));
            } elseif (1 === preg_match('/^ {1}([^- ].*): ?(.*)$/', $line, $matches)) {
                $yamlModel->addProperty($humanReadableLineIndex, 1, $matches[1], $matches[2]);
            } elseif (1 === preg_match('/^ {2}([^- ].*): ?(.*)$/', $line, $matches)) {
                $yamlModel->addProperty($humanReadableLineIndex, 2, $matches[1], $matches[2]);
            } elseif (1 === preg_match('/^\s*$/', $line)) {
                // ignore empty lines
            } else {
                throw new RuntimeException(sprintf('Cannot parse row %d ("%s").', $humanReadableLineIndex, $line));
            }
        }

        return $this->getDocumentsByNumber($yamlModel->getDocuments());
    }

    public function getNumber(YamlDocument $yamlDocument, array $numbersByConventionalName = null): string
    {
        $conventionalName = $this->getConventionalName($yamlDocument);

        if (null === $numbersByConventionalName) {
            $numbersByConventionalName = $this->getNumbersByConventionalName();
        }

        if (!\array_key_exists($conventionalName, $numbersByConventionalName)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot find document with number "%s" in db ("%s" in yaml file).',
                    $conventionalName,
                    $yamlDocument->getNumber()
                )
            );
        }

        return $numbersByConventionalName[$conventionalName];
    }

    public function getNumbersByConventionalName(): array
    {
        $numbersByConventionalName = [];

        foreach ($this->documentRepository->findAllInConventionalOrder(false, true) as $document) {
            $numbersByConventionalName[$document->getTown()->getAlias().' '.$document->getNumber()] = $this
                ->documentFormatter
                ->getNumber($document);
        }

        return $numbersByConventionalName;
    }

    private function getConventionalName(YamlDocument $yamlDocument): string
    {
        if (1 === preg_match('/^[\/\d]+$/', $yamlDocument->getNumber(), $matches)) {
            $town = 'novgorod';
            $number = implode(
                '/',
                array_map(
                    fn (string $numberPart): string => (string) (int) $numberPart,
                    explode('/', $yamlDocument->getNumber())
                )
            );
        } elseif (1 === preg_match('/[а-яА-Я]/', $yamlDocument->getNumber(), $matches)) {
            $parts = explode(' ', $yamlDocument->getNumber());
            $number = array_pop($parts);
            $town = implode(' ', $parts);
            switch ($town) {
                case 'Ст. Р.':
                    $town = 'staraya-russa';
                    break;
                case 'Ст. Ряз.':
                    $town = 'staraya-ryazan';
                    break;
                case 'Свинц.':
                    $town = 'novgorod';
                    $number = 'lead'.$number;
                    break;
                case 'Вол.':
                    $town = 'vologda';
                    break;
                case 'Мос.':
                    $town = 'moscow';
                    break;
                case 'Смол.':
                    $town = 'smolensk';
                    break;
                case 'Звен.':
                    $town = 'zvenigorod';
                    break;
                case 'Вит.':
                    $town = 'vitebsk';
                    break;
                case 'Город.':
                    $town = 'novgorod';
                    $number = '950';
                    break;
                case 'Пск.':
                    $town = 'pskov';
                    break;
                case 'Твер.':
                    $town = 'tver';
                    break;
                case 'Торж.':
                    $town = 'torzhok';
                    break;
                case 'Мст.':
                    $town = 'mstislavl';
                    break;
                default:
                    if ('915-И' === $yamlDocument->getNumber()) {
                        $town = 'novgorod';
                        $number = '915i';
                    } else {
                        throw new RuntimeException(
                            sprintf(
                                'Cannot find document number "%s" from yaml file in db.',
                                $yamlDocument->getNumber()
                            )
                        );
                    }
            }
        } else {
            $town = 'novgorod';
            $number = trim($yamlDocument->getNumber());
        }

        return $town.' '.$number;
    }

    /**
     * @param YamlDocument[] $yamlDocuments
     *
     * @return YamlDocument[]
     */
    private function getDocumentsByNumber(array $yamlDocuments): array
    {
        $numbersByConventionalName = $this->getNumbersByConventionalName();

        $yamlDocumentsByNumber = [];
        foreach ($yamlDocuments as $yamlDocument) {
            $yamlDocumentsByNumber[$this->getNumber($yamlDocument, $numbersByConventionalName)] = $yamlDocument;
        }

        return $yamlDocumentsByNumber;
    }
}
