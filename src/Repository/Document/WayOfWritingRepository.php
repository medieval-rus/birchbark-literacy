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

namespace App\Repository\Document;

use App\Entity\Document\WayOfWriting;
use App\Repository\AbstractRepository;

/**
 * @method WayOfWriting|null find(int $id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method WayOfWriting|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method WayOfWriting[]    findAll()
 * @method WayOfWriting[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
final class WayOfWritingRepository extends AbstractRepository
{
    public function findIncised(): ?WayOfWriting
    {
        return $this->find(1);
    }
}
