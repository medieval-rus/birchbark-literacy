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
use App\Entity\Document\AccidentalFind;
use App\Entity\Document\ArchaeologicalFind;
use App\Entity\Document\ConventionalDateCell;
use App\Entity\Document\Document;
use App\Entity\Document\Excavation;
use App\Entity\Document\PalisadeBetweenEstates;
use App\Entity\Document\RoadwayBetweenEstates;
use App\Entity\Document\SingleEstate;
use App\Entity\Media\File;
use App\Services\Document\OriginalText\MarkupParser\OriginalTextMarkupParserInterface;
use App\Services\Document\OriginalText\MarkupParser\TextPiece\ModifiableTextPieceInterface;
use App\Services\Document\OriginalText\MarkupParser\TextPiece\TextPieceInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DocumentFormatter implements DocumentFormatterInterface
{
    private TranslatorInterface $translator;
    private OriginalTextMarkupParserInterface $originalTextMarkupParser;

    public function __construct(
        TranslatorInterface $translator,
        OriginalTextMarkupParserInterface $originalTextMarkupParser
    ) {
        $this->translator = $translator;
        $this->originalTextMarkupParser = $originalTextMarkupParser;
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
        $descriptions = [];

        foreach ($document->getContentElements() as $content) {
            if (null !== $content->getDescription()) {
                $descriptions[] = $content->getDescription();
            }
        }

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

    public function getCategory(Document $document): string
    {
        $categories = [];

        foreach ($document->getContentElements() as $content) {
            if (null !== $content->getCategory()) {
                $categories[(string) $content->getCategory()->getName()] = (string) $content->getCategory()->getName();
            }
        }

        if (\count($categories) > 0) {
            return implode(' // ', $categories);
        }

        return '-';
    }

    public function getDnd(Document $document): string
    {
        return $this->formatBibliographicRecords(
            $document,
            $document->getDndVolumes(),
            function (BibliographicRecord $bibliographicRecord, ?FileSupplement $fileSupplement) {
                $label = null;

                if (null !== $fileSupplement) {
                    $label = $fileSupplement->getFile()->getDescription();
                }

                if (null === $label) {
                    $label = $bibliographicRecord->getShortName();
                }

                return $label;
            },
            fn () => $this->translator->trans('global.document.notDescribedInDnd')
        );
    }

    public function getNgb(Document $document): string
    {
        return $this->formatBibliographicRecords(
            $document,
            $document->getNgbVolumes(),
            fn (BibliographicRecord $bibliographicRecord) => $bibliographicRecord->getShortName(),
            fn () => '-'
        );
    }

    public function getLiterature(Document $document): string
    {
        return $this->formatBibliographicRecords(
            $document,
            $document->getLiterature(),
            fn (BibliographicRecord $bibliographicRecord) => $bibliographicRecord->getShortName(),
            fn () => '-'
        );
    }

    public function getStoragePlace(Document $document): string
    {
        $storagePlaces = [];

        foreach ($document->getMaterialElements() as $materialElement) {
            if (null !== $materialElement->getStoragePlace()) {
                $storagePlaces[(string) $materialElement->getStoragePlace()->getName()] = (string) $materialElement
                    ->getStoragePlace()
                    ->getName();
            }
        }

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
                        fn (string $text): string => str_replace(['‐'.\PHP_EOL, ' '], [\PHP_EOL, ''], $text)
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
    public function getTranslatedText(Document $document): array
    {
        $textParts = [];

        foreach ($document->getContentElements() as $contentElement) {
            $textParts[] = $contentElement->getTranslatedText() ?? $this->translator->trans('global.noTranslatedText');
        }

        if (0 === \count($textParts)) {
            $textParts[] = $this->translator->trans('global.noTranslatedText');
        }

        return $textParts;
    }

    public function getExcavation(Document $document): string
    {
        $estatesNamesByExcavationName = [];

        foreach ($document->getMaterialElements() as $materialElement) {
            $find = $materialElement->getFind();

            if ($find instanceof ArchaeologicalFind) {
                $excavation = $find->getExcavation();

                if (null !== $excavation) {
                    if (!\array_key_exists($excavation->getName(), $estatesNamesByExcavationName)) {
                        $estatesNamesByExcavationName[(string) $excavation->getName()] = [];
                    }

                    $relationToEstates = $find->getRelationToEstates();

                    switch (true) {
                        case $relationToEstates instanceof SingleEstate:
                            $formattedRelationToEstates = $this->translator->trans(
                                'global.relationToEstates.single',
                                [
                                    '%estate%' => $relationToEstates->getEstate()->getName(),
                                ]
                            );
                            break;
                        case $relationToEstates instanceof RoadwayBetweenEstates:
                            $estates = $relationToEstates->getEstates();
                            $formattedEstates = [];
                            foreach ($estates as $estate) {
                                $formattedEstates[] = $estate->getName();
                            }
                            $formattedRelationToEstates = $this->translator->trans(
                                'global.relationToEstates.roadway',
                                [
                                    '%estates%' => implode(', ', $formattedEstates),
                                ]
                            )
                            ;

                            break;
                        case $relationToEstates instanceof PalisadeBetweenEstates:
                            $formattedRelationToEstates = $this->translator->trans(
                                'global.relationToEstates.palisade',
                                [
                                    '%estateOne%' => $relationToEstates->getEstateOne()->getName(),
                                    '%estateTwo%' => $relationToEstates->getEstateTwo()->getName(),
                                ]
                            );
                            break;
                    }

                    if (isset($formattedRelationToEstates)) {
                        $estatesNamesByExcavationName[(string) $excavation->getName()][$formattedRelationToEstates] =
                            $formattedRelationToEstates;
                    }
                }
            } elseif ($find instanceof AccidentalFind) {
                $estatesNamesByExcavationName[$this->translator->trans('global.find.accidental')] = [];
            }
        }

        $implodedExcavationsNamesWithEstatesNames = '-';

        if (\count($estatesNamesByExcavationName) > 0) {
            $excavationNameWithEstatesNamesCollection = [];

            foreach ($estatesNamesByExcavationName as $excavationName => $formattedEstates) {
                $excavationNameWithEstateNames = $excavationName;

                if (\count($formattedEstates) > 0) {
                    $excavationNameWithEstateNames .= ' ('.implode(', ', $formattedEstates).')';
                }

                $excavationNameWithEstatesNamesCollection[] = $excavationNameWithEstateNames;
            }

            $implodedExcavationsNamesWithEstatesNames = implode(' // ', $excavationNameWithEstatesNamesCollection);
        }

        return $implodedExcavationsNamesWithEstatesNames;
    }

    public function getExcavationWithTown(Excavation $excavation): string
    {
        return sprintf('%s (%s)', $excavation->getName(), $excavation->getTown()->getName());
    }

    public function formatConventionalDateCell(ConventionalDateCell $conventionalDateCell): string
    {
        return sprintf('%s‒%s', $conventionalDateCell->getInitialYear(), $conventionalDateCell->getFinalYear());
    }

    /**
     * @param Collection|BibliographicRecord[] $bibliographicRecords
     */
    private function formatBibliographicRecords(
        Document $document,
        Collection $bibliographicRecords,
        callable $labelProvider,
        callable $zeroRecordsValue
    ): string {
        $literatureItems = [];

        foreach ($bibliographicRecords as $bibliographicRecord) {
            $fileSupplement = $bibliographicRecord
                ->getFileSupplements()
                ->filter(fn (FileSupplement $supplement) => $supplement->getDocument()->getId() === $document->getId())
                ->first();

            if (false === $fileSupplement) {
                $fileSupplement = null;
            }

            $formattedItem = $labelProvider($bibliographicRecord, $fileSupplement);

            if ($fileSupplement instanceof FileSupplement) {
                $formattedItem = sprintf('<a href="%s">%s</a>', $fileSupplement->getFile()->getUrl(), $formattedItem);
            }

            $literatureItems[] = $formattedItem;
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
