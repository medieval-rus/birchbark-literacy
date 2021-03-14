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

use App\Entity\Document\ConditionalDateCell;
use App\Entity\Document\Document;
use App\Entity\MediaBundle\Media;
use Doctrine\Common\Collections\Collection;

interface DocumentFormatterInterface
{
    public function getLabel(Document $document): string;

    public function getTown(Document $document): string;

    public function getUrlEncodedTownAlias(Document $document): string;

    public function getDescription(Document $document): string;

    public function getNumber(Document $document): string;

    public function getIsPreliminaryPublication(Document $document): bool;

    public function getConditionalDate(Document $document): string;

    public function formatConditionalDate(ConditionalDateCell $conditionalDateCell): string;

    /**
     * @return Collection|Media[]
     */
    public function getPhotos(Document $document): Collection;

    /**
     * @return Collection|Media[]
     */
    public function getSketches(Document $document): Collection;

    /**
     * @return array|Media[]
     */
    public function getImages(Document $document): array;
}
