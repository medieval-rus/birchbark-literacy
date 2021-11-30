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

namespace App\Services\Rnc;

use App\Entity\Bibliography\BibliographicRecord;
use App\Entity\Bibliography\FileSupplement;
use App\Entity\Document\AbstractFind;
use App\Entity\Document\ArchaeologicalFind;
use App\Entity\Document\ContentElement;
use App\Entity\Document\Document;
use App\Entity\Document\MaterialElement;
use App\Helper\StringHelper;
use App\Repository\Document\DocumentRepository;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use App\Services\Rnc\Yaml\YamlAnalysis;
use App\Services\Rnc\Yaml\YamlDocument;
use App\Services\Rnc\Yaml\YamlLine;
use App\Services\Rnc\Yaml\YamlLineElement;
use App\Services\Rnc\Yaml\YamlModel;
use App\Services\Rnc\Yaml\YamlPage;
use RuntimeException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RncDataProvider implements RncDataProviderInterface
{
    private DocumentFormatterInterface $documentFormatter;
    private UrlGeneratorInterface $urlGenerator;
    private DocumentRepository $documentRepository;

    public function __construct(
        DocumentFormatterInterface $documentFormatter,
        UrlGeneratorInterface $urlGenerator,
        DocumentRepository $documentRepository
    ) {
        $this->documentFormatter = $documentFormatter;
        $this->urlGenerator = $urlGenerator;
        $this->documentRepository = $documentRepository;
    }

    public function getMetadata(string $baseUrl, bool $onlyShownOnSite = false): array
    {
        return array_map(
            fn (Document $document): array => $this->getMetadataRow($document, $baseUrl),
            $this->documentRepository->findAllInConventionalOrder($onlyShownOnSite, true)
        );
    }

    public function getTexts(bool $onlyShownOnSite = false): array
    {
        return array_map(
            fn (Document $document) => $this->getText($document),
            $this->documentRepository->findAllInConventionalOrder($onlyShownOnSite, true)
        );
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
                $yamlModel->addComment($humanReadableLineIndex, new YamlLineElement('comment', $matches[1], []));
            } elseif (1 === preg_match('/^-fragment: *(.*)$/', $line, $matches)) {
                $yamlModel->addElement($humanReadableLineIndex, new YamlLineElement('fragment', $matches[1], []));
            } elseif (1 === preg_match('/^-punc: (.+)$/', $line, $matches)) {
                $yamlModel->addElement($humanReadableLineIndex, new YamlLineElement('punctuation', $matches[1], []));
            } elseif (1 === preg_match('/^-word: (.+)$/', $line, $matches)) {
                $yamlModel->addElement($humanReadableLineIndex, new YamlLineElement('word', $matches[1], []));
            } elseif (1 === preg_match('/^-part: (.+)$/', $line, $matches)) {
                $yamlModel->addWordPart($humanReadableLineIndex, new YamlLineElement('part', $matches[1], []));
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

        return $this->alignWithDb($yamlModel);
    }

    private function getMetadataRow(Document $document, string $baseUrl): array
    {
        return [
            'path' => $this->getPath($document, fn (string $number) => sprintf('%03d', (int) $number), 'St_R_'),
            'number' => $this->getPath($document, fn (string $number) => sprintf('%04d', (int) $number), 'St_r_'),
            'header' => StringHelper::startsWith($document->getNumber(), 'lead')
                ? sprintf('Свинцовая грамота %s', StringHelper::removeFromStart($document->getNumber(), 'lead'))
                : sprintf('Берестяная грамота %s', $this->documentFormatter->getNumber($document)),
            'subcorp' => 'birchbark',
            'tagging' => 'manual',
            'town' => $document->getTown()->getName(),
            'state_of_preservation' => null !== $document->getStateOfPreservation()
                ? $document->getStateOfPreservation()->getName()
                : '',
            'lang' => implode(
                ' | ',
                array_unique(
                    $document
                        ->getContentElements()
                        ->filter(fn (ContentElement $contentElement): bool => null !== $contentElement->getLanguage())
                        ->map(fn (ContentElement $contentElement): string => $contentElement->getLanguage()->getName())
                        ->toArray()
                )
            ),
            'ngb_volume' => implode(
                ', ',
                $document
                    ->getNgbVolumes()
                    ->map(fn (BibliographicRecord $record): string => $record->getShortName())
                    ->filter(fn (string $shortName): bool => StringHelper::startsWith($shortName, 'НГБ '))
                    ->map(fn (string $shortName): string => StringHelper::removeFromStart($shortName, 'НГБ '))
                    ->toArray()
            ),
            'dnd_page' => implode(
                ', ',
                $document
                    ->getDndVolumes()
                    ->map(
                        fn (BibliographicRecord $record) => $record
                            ->getFileSupplements()
                            ->filter(fn (FileSupplement $supplement): bool => $supplement->getDocument() === $document)
                            ->first()
                    )
                    ->filter(fn ($supplement) => false !== $supplement)
                    ->map(fn (FileSupplement $supplement): string => $supplement->getFile()->getDescription())
                    ->filter(fn (?string $description) => null !== $description)
                    ->toArray()
            ),
            'stratigraphic_date' => str_replace(';', ',', $document->getStratigraphicalDate() ?? ''),
            'approx_date' => null !== $document->getConventionalDate()
                ? $this->documentFormatter->getConventionalDate($document)
                : '',
            'genre' => implode(
                ' | ',
                array_unique(
                    $document
                        ->getContentElements()
                        ->filter(fn (ContentElement $contentElement): bool => null !== $contentElement->getCategory())
                        ->map(fn (ContentElement $contentElement): string => $contentElement->getCategory()->getName())
                        ->toArray()
                )
            ),
            'summary' => implode(
                ' | ',
                array_unique(
                    $document
                        ->getContentElements()
                        ->map(fn (ContentElement $contentElement): ?string => $contentElement->getDescription())
                        ->filter(fn (?string $description): bool => null !== $description)
                        ->map(fn (string $description): string => str_replace(';', ',', $description))
                        ->toArray()
                )
            ),
            'link' => $baseUrl.$this->urlGenerator->generate(
                'document__show',
                [
                    'town' => $document->getTown()->getAlias(),
                    'number' => $document->getNumber(),
                ]
            ),
            'type' => implode(
                ' | ',
                array_unique(
                    $document
                        ->getContentElements()
                        ->filter(fn (ContentElement $contentElement): bool => null !== $contentElement->getGenre())
                        ->map(fn (ContentElement $contentElement): string => $contentElement->getGenre()->getName())
                        ->toArray()
                )
            ),
            'excavation' => implode(
                ', ',
                array_unique(
                    $document
                        ->getMaterialElements()
                        ->map(fn (MaterialElement $materialElement) => $materialElement->getFind())
                        ->filter(fn (?AbstractFind $find) => $find instanceof ArchaeologicalFind)
                        ->filter(fn (ArchaeologicalFind $find): bool => null !== $find->getExcavation())
                        ->map(fn (ArchaeologicalFind $find): string => $find->getExcavation()->getName())
                        ->toArray()
                )
            ),
            'non_stratigraphic_date' => str_replace(';', ',', $document->getNonStratigraphicalDate() ?? ''),
        ];
    }

    private function getPath(Document $document, callable $numberFormatter, string $starayaRussaPrefix): string
    {
        $documentNumber = $document->getNumber();

        switch ($document->getTown()->getAlias()) {
            case 'novgorod':
                if (StringHelper::startsWith($documentNumber, 'lead')) {
                    return 'lead_'.StringHelper::removeFromStart($documentNumber, 'lead');
                }

                if ('915i' === $documentNumber) {
                    return $documentNumber;
                }

                if (str_contains($documentNumber, '/')) {
                    $parts = explode('/', $documentNumber);
                    $parts = array_map(fn (string $part): string => $numberFormatter($part), $parts);

                    return implode('_', $parts);
                }

                return $numberFormatter($documentNumber);
            case 'staraya-russa':
                return $starayaRussaPrefix.str_replace('/', '_', $documentNumber);
            case 'vologda':
                return 'Vol_'.str_replace('/', '_', $documentNumber);
            case 'moscow':
                return 'Mosk_'.str_replace('/', '_', $documentNumber);
            case 'smolensk':
                return 'Smol_'.str_replace('/', '_', $documentNumber);
            case 'zvenigorod':
                return 'Zven_'.str_replace('/', '_', $documentNumber);
            case 'vitebsk':
                return 'Vit_'.str_replace('/', '_', $documentNumber);
            case 'pskov':
                return 'Psk_'.str_replace('/', '_', $documentNumber);
            case 'tver':
                return 'Tver_'.str_replace('/', '_', $documentNumber);
            case 'torzhok':
                return 'Torzh_'.str_replace('/', '_', $documentNumber);
            case 'mstislavl':
                return 'Mst_'.str_replace('/', '_', $documentNumber);
            case 'st-ryazan':
                return 'St_Ryazan_'.str_replace('/', '_', $documentNumber);
            default:
                throw new RuntimeException(
                    sprintf('Town "%s" does not have a proper mapping to RNC format.', $document->getTown()->getAlias())
                );
        }
    }

    private function getText(Document $document): array
    {
        return [
            'number' => $this->documentFormatter->getNumber($document),
            'pages' => $document
                ->getContentElements()
                ->map(fn (ContentElement $contentElement): array => $this->getPage($contentElement))
                ->toArray(),
        ];
    }

    private function getPage(ContentElement $contentElement): array
    {
        return [
            'description' => $contentElement->getDescription(),
            'text' => $contentElement->getOriginalText(),
            'translations' => [
                'ru' => $contentElement->getTranslatedText(),
            ],
        ];
    }

    /**
     * @return YamlDocument[]
     */
    private function alignWithDb(YamlModel $yamlModel): array
    {
        $numberByConventionalName = [];

        foreach ($this->documentRepository->findAllInConventionalOrder(false, true) as $document) {
            $numberByConventionalName[$document->getTown()->getAlias().' '.$document->getNumber()] = $this
                ->documentFormatter
                ->getNumber($document);
        }

        $yamlDocumentsByNumber = [];
        foreach ($yamlModel->getDocuments() as $yamlDocument) {
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

            $conventionalName = $town.' '.$number;

            if (!\array_key_exists($conventionalName, $numberByConventionalName)) {
                throw new RuntimeException(
                    sprintf(
                        'Cannot find document with number "%s" in db ("%s" in yaml file).',
                        $conventionalName,
                        $yamlDocument->getNumber()
                    )
                );
            }

            $yamlDocumentsByNumber[$numberByConventionalName[$conventionalName]] = $yamlDocument;
        }

        return $yamlDocumentsByNumber;
    }
}
