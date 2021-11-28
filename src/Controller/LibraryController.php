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

namespace App\Controller;

use App\Entity\Bibliography\BibliographicRecord;
use App\Entity\Bibliography\ReferencesList;
use App\Entity\Bibliography\ReferencesListItem;
use App\Form\Document\DocumentsSearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/library")
 */
final class LibraryController extends AbstractController
{
    /**
     * @Route("/book/", name="library__book_list")
     */
    public function listBooks(EntityManagerInterface $entityManager): Response
    {
        $bibliographicRecords = $entityManager
            ->getRepository(ReferencesList::class)
            ->findBooks()
            ->getItems()
            ->map(fn (ReferencesListItem $item) => $item->getBibliographicRecord())
            ->toArray();

        return $this->render(
            'site/book/list.html.twig',
            [
                'translationContext' => 'controller.library.list',
                'assetsContext' => 'book/list',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'bibliographicRecords' => $bibliographicRecords,
            ]
        );
    }

    /**
     * @Route("/book/{id}/", name="library__book_show")
     */
    public function showBook(int $id, EntityManagerInterface $entityManager): Response
    {
        $bibliographicRecord = $entityManager->getRepository(BibliographicRecord::class)->findBook($id);

        if (null === $bibliographicRecord) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'site/book/show.html.twig',
            [
                'translationContext' => 'controller.library.show',
                'assetsContext' => 'book/show',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'bibliographicRecord' => $bibliographicRecord,
            ]
        );
    }
}
