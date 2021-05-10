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
use App\Services\Document\Sorter\SortEngine\DocumentsSortEngineInterface;
use RuntimeException;

final class DocumentsSorter implements DocumentsSorterInterface
{
    /**
     * @var DocumentsSortEngineInterface
     */
    private $sortEngine;

    public function __construct(DocumentsSortEngineInterface $sortEngine)
    {
        $this->sortEngine = $sortEngine;
    }

    /**
     * @param Document[] $documents
     *
     * @return Document[]
     */
    public function sort(array $documents): array
    {
        if (!usort($documents, [$this, 'sortCallback'])) {
            throw new RuntimeException('Cannot sort birch bark documents');
        }

        return $documents;
    }

    private function sortCallback(Document $a, Document $b): int
    {
        return $this->sortEngine->getPosition(
            $a->getTown()->getName(),
            $a->getNumber(),
            $b->getTown()->getName(),
            $b->getNumber()
        );
    }
}
