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

namespace App\Services\Corpus\Morphy;

use App\Repository\Document\DocumentRepository;
use App\Services\Corpus\Morphy\Models\Xhtml\XhtmlDocument;
use App\Services\Corpus\Morphy\Models\Yaml\YamlAnalysis;
use App\Services\Corpus\Morphy\Models\Yaml\YamlDocument;
use App\Services\Corpus\Morphy\Models\Yaml\YamlLine;
use App\Services\Corpus\Morphy\Models\Yaml\YamlModel;
use App\Services\Corpus\Morphy\Models\Yaml\YamlPage;
use App\Services\Corpus\Morphy\Models\Yaml\YamlPiece;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use RuntimeException;

final class MorphyParser implements MorphyParserInterface
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

        return $this->getDocumentsByNumber(
            $yamlModel->getDocuments(),
            fn (YamlDocument $yamlDocument): string => $yamlDocument->getNumber()
        );
    }

    /**
     * @return XhtmlDocument[]
     */
    public function parseXhtml(string $rawXhtml): array
    {
        $rawXhtml = preg_replace(
            '/<\/page>\s*<\/body>\s*<\/html>\s*$/m',
            "</page>\n</document>\n</body></html>",
            $rawXhtml
        );

        $rawXhtml = preg_replace(
            '/<\/page>\s*<\/page>/m',
            '</page>',
            $rawXhtml
        );

        $xhtmlDocuments = [];

        if (preg_match_all('/<document id="([^>]+)">(.*?)<\/document>\n*/s', $rawXhtml, $matches, \PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $xhtmlDocuments[] = new XhtmlDocument(trim($match[1]), trim($match[2]));
            }
        }

        return $this->getDocumentsByNumber(
            $xhtmlDocuments,
            fn (XhtmlDocument $xhtmlDocument): string => $xhtmlDocument->getNumber()
        );
    }

    public function getNumber(string $morphyNumber, array $numbersByConventionalName = null): string
    {
        $conventionalName = $this->getConventionalName($morphyNumber);

        if (null === $numbersByConventionalName) {
            $numbersByConventionalName = $this->getNumbersByConventionalName();
        }

        if (!\array_key_exists($conventionalName, $numbersByConventionalName)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot find document with number "%s" in db ("%s" in morphy file).',
                    $conventionalName,
                    $morphyNumber
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

    private function getConventionalName(string $morphyNumber): string
    {
        if (1 === preg_match('/^[\/\d]+$/', $morphyNumber, $matches)) {
            $town = 'novgorod';
            $number = implode(
                '/',
                array_map(
                    fn (string $numberPart): string => (string) (int) $numberPart,
                    explode('/', $morphyNumber)
                )
            );
        } elseif (1 === preg_match('/[а-яА-Я]/', $morphyNumber, $matches)) {
            $parts = explode(' ', $morphyNumber);
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
                    if ('915-И' === $morphyNumber) {
                        $town = 'novgorod';
                        $number = '915i';
                    } else {
                        throw new RuntimeException(
                            sprintf(
                                'Cannot find document number "%s" from yaml file in db.',
                                $morphyNumber
                            )
                        );
                    }
            }
        } else {
            $town = 'novgorod';
            $number = trim($morphyNumber);
        }

        return $town.' '.$number;
    }

    /**
     * @param YamlDocument[] $yamlDocuments
     *
     * @return YamlDocument[]
     */
    private function getDocumentsByNumber(array $yamlDocuments, callable $documentNumberGetter): array
    {
        $numbersByConventionalName = $this->getNumbersByConventionalName();

        $yamlDocumentsByNumber = [];
        foreach ($yamlDocuments as $yamlDocument) {
            $documentNumber = $this->getNumber($documentNumberGetter($yamlDocument), $numbersByConventionalName);

            $yamlDocumentsByNumber[$documentNumber] = $yamlDocument;
        }

        return $yamlDocumentsByNumber;
    }
}
