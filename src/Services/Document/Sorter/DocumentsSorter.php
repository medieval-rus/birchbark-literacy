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

namespace App\Services\Document\Sorter;

use App\Entity\Document\Document;
use RuntimeException;

final class DocumentsSorter implements DocumentsSorterInterface
{
    /**
     * @param Document[] $documents
     *
     * @return Document[]
     */
    public function sort(array $documents): array
    {
        if (!usort($documents, [$this, 'compare'])) {
            throw new RuntimeException('Cannot sort birchbark documents');
        }

        return $documents;
    }

    public function compare(Document $a, Document $b): int
    {
        $townNameOfDocumentA = $a->getTown()->getName();
        $numberOfDocumentA = $a->getNumber();
        $townNameOfDocumentB = $b->getTown()->getName();
        $numberOfDocumentB = $b->getNumber();

        if ($townNameOfDocumentA === $townNameOfDocumentB) {
            $intNumberOfDocumentA = (int) $numberOfDocumentA;
            $intNumberOfDocumentB = (int) $numberOfDocumentB;

            if (0 === $intNumberOfDocumentA && 0 === $intNumberOfDocumentB) {
                preg_match('/\d+/', $numberOfDocumentA, $matches);
                $intNumberOfDocumentA = $matches[0];

                preg_match('/\d+/', $numberOfDocumentB, $matches);
                $intNumberOfDocumentB = $matches[0];

                if ($intNumberOfDocumentA === $intNumberOfDocumentB) {
                    return 0;
                }

                return $intNumberOfDocumentA > $intNumberOfDocumentB ? 1 : -1;
            }

            if (0 === $intNumberOfDocumentA) {
                return 1;
            }

            if (0 === $intNumberOfDocumentB) {
                return -1;
            }

            if ($intNumberOfDocumentA === $intNumberOfDocumentB) {
                return 0;
            }

            return $intNumberOfDocumentA > $intNumberOfDocumentB ? 1 : -1;
        }

        if ('Новгород' === $townNameOfDocumentA) {
            return -1;
        }

        if ('Новгород' === $townNameOfDocumentB) {
            return 1;
        }

        return strcmp($townNameOfDocumentA, $townNameOfDocumentB);
    }
}
