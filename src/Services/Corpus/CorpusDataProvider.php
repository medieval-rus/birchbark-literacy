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

namespace App\Services\Corpus;

use App\Entity\Bibliography\BibliographicRecord;
use App\Entity\Bibliography\FileSupplement;
use App\Entity\Document\ContentElement;
use App\Entity\Document\Document;
use App\Entity\Document\MaterialElement;
use App\Helper\StringHelper;
use App\Repository\Document\DocumentRepository;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use RuntimeException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CorpusDataProvider implements CorpusDataProviderInterface
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

    public function getStatistics(bool $onlyShownOnSite = false): array
    {
        $texts = $this->getTexts($onlyShownOnSite);

        return [
            'documentsCount' => \count($texts),
            'piecesCount' => array_sum(
                array_map(
                    fn (string $text): int => 1 + substr_count(
                            str_replace(
                                "\n",
                                ' ',
                                str_replace(
                                    "‐\n",
                                    '',
                                    str_replace(
                                        "\r\n",
                                        "\n",
                                        $text
                                    )
                                )
                            ),
                            ' '
                        ),
                    array_filter(
                        array_map(
                            fn (array $documentData): ?string => implode(
                                "\r\n",
                                array_map(
                                    fn (array $pageData): ?string => trim($pageData['text']),
                                    $documentData['pages']
                                )
                            ),
                            $texts
                        ),
                        fn (?string $text): bool => null !== $text && mb_strlen($text) > 0
                    )
                )
            ),
        ];
    }

    private function getMetadataRow(Document $document, string $baseUrl): array
    {
        return [
            'path' => $this->getPath($document),
            'number' => StringHelper::removeFromStart($document->getNumber(), 'lead'),
            'material' => implode(
                ', ',
                array_unique(
                    $document
                        ->getMaterialElements()
                        ->map(
                            fn (MaterialElement $materialElement): string => $materialElement
                                ->getMaterial()
                                ->getName()
                        )
                        ->toArray()
                )
            ),
            'header' => StringHelper::startsWith($document->getNumber(), 'lead')
                ? sprintf('Свинцовая грамота %s', StringHelper::removeFromStart($document->getNumber(), 'lead'))
                : sprintf('Берестяная грамота %s', $this->documentFormatter->getNumber($document)),
            'subcorp' => 'birchbark',
            'tagging' => 'manual',
            'town' => $document->getTown()->getName(),
            'state_of_preservation' => null !== $document->getStateOfPreservation()
                ? $document->getStateOfPreservation()->getName()
                : null,
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
            'stratigraphic_date' => $document->getStratigraphicalDate(),
            'non_stratigraphic_date' => $document->getNonStratigraphicalDate(),
            'approx_date' => null !== $document->getConventionalDate()
                ? str_replace('‒', '-', $this->documentFormatter->getConventionalDate($document))
                : null,
            'category' => implode(
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
            'genre' => implode(
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
                        ->filter(fn (MaterialElement $element): bool => null !== $element->getExcavation())
                        ->map(fn (MaterialElement $element): string => $element->getExcavation()->getName())
                        ->toArray()
                )
            ),
        ];
    }

    private function getPath(Document $document): string
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
                    $parts = array_map(fn (string $part): string => sprintf('%03d', (int) $part), $parts);

                    return implode('_', $parts);
                }

                return sprintf('%03d', $documentNumber);
            case 'staraya-russa':
                return 'St_R_'.str_replace('/', '_', $documentNumber);
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
            case 'staraya-ryazan':
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
}
