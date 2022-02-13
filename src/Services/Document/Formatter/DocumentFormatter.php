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

namespace App\Services\Document\Formatter;

use App\Entity\Bibliography\BibliographicRecord;
use App\Entity\Bibliography\FileSupplement;
use App\Entity\Document\ContentCategory;
use App\Entity\Document\ContentElement;
use App\Entity\Document\ConventionalDateCell;
use App\Entity\Document\Document;
use App\Entity\Document\Estate;
use App\Entity\Document\Excavation;
use App\Entity\Document\Genre;
use App\Entity\Document\MaterialElement;
use App\Entity\Media\File;
use App\Services\Bibliography\Sorting\BibliographicRecordComparerInterface;
use App\Services\Document\OriginalText\MarkupParser\OriginalTextMarkupParserInterface;
use App\Services\Document\OriginalText\MarkupParser\TextPiece\ModifiableTextPieceInterface;
use App\Services\Document\OriginalText\MarkupParser\TextPiece\TextPieceInterface;
use App\Services\Media\Thumbnails\ThumbnailsGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DocumentFormatter implements DocumentFormatterInterface
{
    private TranslatorInterface $translator;
    private OriginalTextMarkupParserInterface $originalTextMarkupParser;
    private BibliographicRecordComparerInterface $bibliographicRecordComparer;
    private UrlGeneratorInterface $urlGenerator;
    private ThumbnailsGeneratorInterface $thumbnailsGenerator;

    public function __construct(
        TranslatorInterface $translator,
        OriginalTextMarkupParserInterface $originalTextMarkupParser,
        BibliographicRecordComparerInterface $bibliographicRecordComparer,
        UrlGeneratorInterface $urlGenerator,
        ThumbnailsGeneratorInterface $thumbnailsGenerator
    ) {
        $this->translator = $translator;
        $this->originalTextMarkupParser = $originalTextMarkupParser;
        $this->bibliographicRecordComparer = $bibliographicRecordComparer;
        $this->urlGenerator = $urlGenerator;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
    }

    public function getNumber(Document $document): string
    {
        $townName = $this->isDocumentFromNovgorod($document)
            ? ''
            : $document->getTown()->getAbbreviatedName().' '
        ;

        $documentNumber = $document->getNumber();

        $formattedDocumentNumber = $this->translator->getCatalogue()->has('global.documentNumber.'.$documentNumber)
            ? $this->translator->trans('global.documentNumber.'.$documentNumber)
            : $documentNumber;

        return str_replace(' ', "\u{a0}", $townName.$formattedDocumentNumber);
    }

    public function getLabel(Document $document): string
    {
        $space = $this->isDocumentFromNovgorod($document) ? '' : ' ';

        return $this->translator->trans(
            'global.document.label',
            ['%number%' => $space.$this->getNumber($document)]
        );
    }

    public function getTown(Document $document): string
    {
        return $document->getTown()->getName();
    }

    public function getUrlEncodedTownAlias(Document $document): string
    {
        return urlencode($document->getTown()->getAlias());
    }

    public function getDescription(Document $document): string
    {
        $descriptions = $document
            ->getContentElements()
            ->filter(fn (ContentElement $contentElement): bool => null !== $contentElement->getDescription())
            ->map(fn (ContentElement $contentElement): string => $contentElement->getDescription())
            ->toArray();

        if (\count($descriptions) > 0) {
            return implode(' // ', $descriptions);
        }

        return '-';
    }

    public function getConventionalDate(Document $document): string
    {
        if (null === $document->getConventionalDate()) {
            return '?';
        }

        return $this->formatConventionalDateCell($document->getConventionalDate());
    }

    public function getConventionalDateWithBiases(Document $document): string
    {
        if (null === $document->getConventionalDate()) {
            return '?';
        }

        $biasClasses = [];
        if ($document->getIsConventionalDateBiasedBackward()) {
            $biasClasses[] = 'biased-left';
        }

        if ($document->getIsConventionalDateBiasedForward()) {
            $biasClasses[] = 'biased-right';
        }

        return
            '<span class="conventional-date-container '.implode(' ', $biasClasses).'">'.
            '<span class="bias-arrow left-bias-arrow">←</span>'.
            $this->formatConventionalDateCell($document->getConventionalDate()).
            '<span class="bias-arrow right-bias-arrow">→</span>'.
            '</span>';
    }

    public function getConventionalDateWithDescription(Document $document): string
    {
        if (null === $document->getConventionalDate()) {
            return '?';
        }

        $bias = '';
        $isConventionalDateBiasedBackward = $document->getIsConventionalDateBiasedBackward();
        $isConventionalDateBiasedForward = $document->getIsConventionalDateBiasedForward();
        if ($isConventionalDateBiasedBackward || $isConventionalDateBiasedForward) {
            $bias .= ' ('.$this->translator->trans('global.withAProbableBias').' ';

            if ($isConventionalDateBiasedBackward && $isConventionalDateBiasedForward) {
                $bias .= $this->translator->trans('global.backward').' '.
                    $this->translator->trans('global.or').' '.
                    $this->translator->trans('global.forward');
            } elseif ($isConventionalDateBiasedBackward) {
                $bias .= $this->translator->trans('global.backward');
            } elseif ($isConventionalDateBiasedForward) {
                $bias .= $this->translator->trans('global.forward');
            }

            $bias .= ')';
        }

        return $this->formatConventionalDateCell($document->getConventionalDate()).$bias;
    }

    /**
     * @return array|File[]
     */
    public function getImages(Document $document): array
    {
        return array_merge($document->getPhotos()->toArray(), $document->getDrawings()->toArray());
    }

    public function getContentCategory(Document $document): string
    {
        $contentCategories = array_unique(
            array_map(
                fn (ContentCategory $contentCategory): string => $contentCategory->getName(),
                array_merge(
                    ...array_map(
                        fn (ContentElement $contentElement): array => $contentElement->getContentCategories()->toArray(),
                        $document->getContentElements()->toArray()
                    )
                )
            )
        );

        if (\count($contentCategories) > 0) {
            return implode(' // ', $contentCategories);
        }

        return '-';
    }

    public function getGenre(Document $document): string
    {
        $genres = array_unique(
            array_map(
                fn (Genre $genre): string => $genre->getName(),
                array_merge(
                    ...array_map(
                        fn (ContentElement $contentElement): array => $contentElement->getGenres()->toArray(),
                        $document->getContentElements()->toArray()
                    )
                )
            )
        );

        if (\count($genres) > 0) {
            return implode(' // ', $genres);
        }

        return '-';
    }

    public function getDnd(Document $document): string
    {
        return $this->formatBibliographicRecords(
            $document,
            $document->getDndVolumes()->toArray(),
            fn () => $this->translator->trans('global.document.notDescribedInDnd')
        );
    }

    public function getNgb(Document $document): string
    {
        return $this->formatBibliographicRecords(
            $document,
            $document->getNgbVolumes()->toArray(),
            fn () => '-'
        );
    }

    public function getLiterature(Document $document): string
    {
        return $this->formatBibliographicRecords(
            $document,
            $document->getLiterature()->toArray(),
            fn () => '-'
        );
    }

    public function getStoragePlace(Document $document): string
    {
        $storagePlaces = array_unique(
            $document
                ->getMaterialElements()
                ->filter(fn (MaterialElement $materialElement): bool => null !== $materialElement->getStoragePlace())
                ->map(fn (MaterialElement $materialElement): string => $materialElement->getStoragePlace()->getName())
                ->toArray()
        );

        if (\count($storagePlaces) > 0) {
            return implode(' // ', $storagePlaces);
        }

        return '-';
    }

    /**
     * @return TextPieceInterface[][]
     */
    public function getOriginalTextWithoutDivisionIntoWords(Document $document): array
    {
        $originalTextParts = $this->getOriginalTextParts($document);

        foreach ($originalTextParts as $textPieces) {
            foreach ($textPieces as $textPiece) {
                if ($textPiece instanceof ModifiableTextPieceInterface) {
                    $textPiece->modify(
                        fn (string $text): string => str_replace(
                            ["‐\r\n", "‐\n", ' ', '{', '}'],
                            ["\r\n", "\n", '', '', ''],
                            $text
                        )
                    );
                }
            }
        }

        return $originalTextParts;
    }

    /**
     * @return TextPieceInterface[][]
     */
    public function getOriginalTextWithDivisionIntoWords(Document $document): array
    {
        return $this->getOriginalTextParts($document);
    }

    /**
     * @return string[]
     */
    public function getTranslationRussian(Document $document, string $placeholder): array
    {
        return $this->getTranslation(
            $document,
            $placeholder,
            fn (ContentElement $contentElement): ?string => $contentElement->getTranslationRussian()
        );
    }

    public function getTranslationEnglishKovalev(Document $document, string $placeholder): array
    {
        return $this->getTranslation(
            $document,
            $placeholder,
            fn (ContentElement $contentElement): ?string => $contentElement->getTranslationEnglishKovalev()
        );
    }

    public function getTranslationEnglishSchaeken(Document $document, string $placeholder): array
    {
        return $this->getTranslation(
            $document,
            $placeholder,
            fn (ContentElement $contentElement): ?string => $contentElement->getTranslationEnglishSchaeken()
        );
    }

    public function getExcavation(Document $document): string
    {
        $estatesNamesByExcavationName = [];
        $modifiersByExcavationName = [];

        foreach ($document->getMaterialElements() as $materialElement) {
            if ($materialElement->getIsArchaeologicalFind()) {
                $excavation = $materialElement->getExcavation();

                if (null !== $excavation) {
                    if (!\array_key_exists($excavation->getName(), $estatesNamesByExcavationName)) {
                        $estatesNamesByExcavationName[(string) $excavation->getName()] = [];
                    }

                    $estates = $materialElement->getEstates()->toArray();

                    $formattedEstates = null;
                    if (\count($estates) > 0) {
                        $formattedEstates = $this->translator->trans(
                            1 === \count($estates)
                                ? 'global.materialElement.singleEstate'
                                : 'global.materialElement.manyEstates',
                            [
                                '%estates%' => implode(
                                    ', ',
                                    array_map(fn (Estate $estate): string => $estate->getName(), $estates)
                                ),
                            ]
                        );
                    }

                    $modifiers = [];

                    if ($materialElement->getIsRoadway()) {
                        $modifiers[] = $this->translator->trans('global.materialElement.roadway');
                    }

                    if ($materialElement->getIsPalisade()) {
                        $modifiers[] = $this->translator->trans('global.materialElement.palisade');
                    }

                    $formattedModifiers = null;
                    if (\count($modifiers) > 0) {
                        $formattedModifiers = implode(', ', $modifiers);
                    }

                    if (null !== $formattedEstates) {
                        $estatesNamesByExcavationName[$excavation->getName()][$formattedEstates] = $formattedEstates;
                    }

                    if (null !== $formattedModifiers) {
                        $modifiersByExcavationName[$excavation->getName()][$formattedModifiers] = $formattedModifiers;
                    }
                }
            } else {
                $estatesNamesByExcavationName[$this->translator->trans('global.find.accidental')] = [];
            }
        }

        if (0 === \count($estatesNamesByExcavationName)) {
            return '-';
        }

        $excavationNameWithEstatesNamesCollection = [];

        foreach ($estatesNamesByExcavationName as $excavationName => $estates) {
            $modifiers = \array_key_exists($excavationName, $modifiersByExcavationName)
                ? $modifiersByExcavationName[$excavationName]
                : [];

            $excavationNameWithEstateNames = $excavationName;

            $formattedModifiers = implode(', ', $modifiers);
            $formattedEstates = implode(', ', $estates);

            if (\count($estates) > 0 && \count($modifiers) > 0) {
                $excavationNameWithEstateNames .= sprintf(' (%s; %s)', $formattedEstates, $formattedModifiers);
            } elseif (\count($estates) > 0) {
                $excavationNameWithEstateNames .= sprintf(' (%s)', $formattedEstates);
            } elseif (\count($modifiers) > 0) {
                $excavationNameWithEstateNames .= sprintf(' (%s)', $formattedModifiers);
            }

            $excavationNameWithEstatesNamesCollection[] = $excavationNameWithEstateNames;
        }

        return implode(' // ', $excavationNameWithEstatesNamesCollection);
    }

    public function getExcavationWithTown(Excavation $excavation): string
    {
        return sprintf('%s (%s)', $excavation->getName(), $excavation->getTown()->getName());
    }

    public function getBibliographicRecordName(BibliographicRecord $record, string $downloadIcon = null): string
    {
        $downloadLink = null !== $downloadIcon && null !== $record->getMainFile()
            ? sprintf(
                '<a class="mr-download-icon" href="%s">%s</a>',
                $this->thumbnailsGenerator->getThumbnail($record->getMainFile(), 'document'),
                $downloadIcon
            )
            : '';

        $remark = null === $record->getYear()
            ? sprintf(
                '<span aria-label="%s" data-microtip-position="top" role="tooltip">'.
                '<span class="mr-bibliography-remark"><sup>%s</sup></span>'.
                '</span>',
                $this->translator->trans('global.bibliography.prePublicationRemark'),
                $this->translator->trans('global.bibliography.prePublicationMark')
            )
            : '';

        return sprintf(
            '<span><span>%s</span>%s%s</span>',
            $record->getShortName(),
            $downloadLink,
            $remark
        );
    }

    private function getTranslation(Document $document, string $placeholder, callable $getter)
    {
        $textParts = $document
            ->getContentElements()
            ->map(fn (ContentElement $contentElement): string => $getter($contentElement) ?? $placeholder)
            ->toArray();

        if (0 === \count($textParts)) {
            $textParts[] = $placeholder;
        }

        return $textParts;
    }

    private function formatConventionalDateCell(ConventionalDateCell $conventionalDateCell): string
    {
        return sprintf('%s‒%s', $conventionalDateCell->getInitialYear(), $conventionalDateCell->getFinalYear());
    }

    /**
     * @param BibliographicRecord[] $records
     */
    private function formatBibliographicRecords(
        Document $document,
        array $records,
        callable $zeroRecordsValue
    ): string {
        usort(
            $records,
            fn (BibliographicRecord $a, BibliographicRecord $b): int => $this
                ->bibliographicRecordComparer
                ->compareByName($a, $b)
        );

        $literatureItems = [];

        foreach ($records as $record) {
            $fileSupplement = $record
                ->getFileSupplements()
                ->filter(fn (FileSupplement $supplement) => $supplement->getDocument()->getId() === $document->getId())
                ->first();

            if (false === $fileSupplement) {
                $fileSupplement = null;
            }

            $formattedItem = sprintf(
                '<a href="%s#%s">%s</a>',
                $this->urlGenerator->generate('bibliographic_record__list'),
                $record->getShortName(),
                $this->getBibliographicRecordName($record)
            );

            if ($fileSupplement instanceof FileSupplement) {
                $formattedItem .= sprintf(
                    ' (<a href="%s">%s</a>)',
                    $this->thumbnailsGenerator->getThumbnail($fileSupplement->getFile(), 'document'),
                    $fileSupplement->getFile()->getDescription() ??
                    $this->translator->trans('global.bibliography.fileSupplement')
                );
            }

            $literatureItems[] = sprintf('<span>%s</span>', $formattedItem);
        }

        if (\count($literatureItems) > 0) {
            return implode(', ', $literatureItems);
        }

        return $zeroRecordsValue();
    }

    /**
     * @return TextPieceInterface[][]
     */
    private function getOriginalTextParts(Document $document): array
    {
        $originalTextParts = [];

        foreach ($document->getContentElements() as $contentElement) {
            $originalTextParts[] = $this->originalTextMarkupParser->parse($contentElement->getOriginalText() ?? '-');
        }

        return $originalTextParts;
    }

    private function isDocumentFromNovgorod(Document $document): bool
    {
        return 'novgorod' === $document->getTown()->getAlias();
    }
}
