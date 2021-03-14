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

namespace App\Services\DocumentFormatter;

use App\Entity\Document\ConventionalDateCell;
use App\Entity\Document\Document;
use App\Entity\MediaBundle\Media;
use Doctrine\Common\Collections\Collection;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DocumentFormatter implements DocumentFormatterInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getLabel(Document $document): string
    {
        $townName = ('novgorod' === $document->getTown()->getAlias() ?
            '' :
            ' '.$document->getTown()->getAbbreviatedName().' '
        );

        $documentNumber = $document->getNumber();

        $formattedDocumentNumber = $this->translator->getCatalogue()->has('global.documentNumber.'.$documentNumber)
            ? $this->translator->trans('global.documentNumber.'.$documentNumber)
            : $documentNumber
        ;

        return $this->translator->trans('global.document').' №'.$townName.$formattedDocumentNumber;
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

    public function getNumber(Document $document): string
    {
        return $document->getNumber();
    }

    public function getIsPreliminaryPublication(Document $document): bool
    {
        return $document->getIsPreliminaryPublication();
    }

    public function getConventionalDate(Document $document): string
    {
        if (null === $document->getConventionalDate()) {
            return '?';
        }

        return $this->formatConventionalDate($document->getConventionalDate());
    }

    public function formatConventionalDate(ConventionalDateCell $conventionalDateCell): string
    {
        return sprintf('%s‒%s', $conventionalDateCell->getInitialYear(), $conventionalDateCell->getFinalYear());
    }

    /**
     * @return Collection|Media[]
     */
    public function getPhotos(Document $document): Collection
    {
        return $document->getPhotos();
    }

    /**
     * @return Collection|Media[]
     */
    public function getSketches(Document $document): Collection
    {
        return $document->getSketches();
    }

    /**
     * @return array|Media[]
     */
    public function getImages(Document $document): array
    {
        return array_merge($this->getPhotos($document)->toArray(), $this->getSketches($document)->toArray());
    }
}
