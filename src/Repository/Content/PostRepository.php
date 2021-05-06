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

namespace App\Repository\Content;

use App\Entity\Content\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find(int $id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 */
final class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAboutSite(): Post
    {
        return $this->find(1);
    }

    public function findNews(): Post
    {
        return $this->find(2);
    }

    public function findAboutSource(): Post
    {
        return $this->find(3);
    }

    public function findGeneralInformationAboutBirchbarkDocuments(): Post
    {
        return $this->find(4);
    }

    public function findFavoriteBibliographyDescription(): Post
    {
        return $this->find(5);
    }
}
