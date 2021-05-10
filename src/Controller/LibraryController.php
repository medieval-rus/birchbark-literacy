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

use App\Entity\Book\Book;
use App\Form\Document\DocumentsSearchType;
use App\Repository\Content\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vyfony\Bundle\BibliographyBundle\Persistence\Entity\BibliographicRecord;

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
        return $this->render(
            'site/book/list.html.twig',
            [
                'translationContext' => 'controller.library.book.list',
                'assetsContext' => 'book/list',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'books' => $entityManager->getRepository(Book::class)->findAll(),
            ]
        );
    }

    /**
     * @Route("/book/{id}/", name="library__book_show")
     */
    public function showBook(int $id, EntityManagerInterface $entityManager): Response
    {
        return $this->render(
            'site/book/show.html.twig',
            [
                'translationContext' => 'controller.library.book.show',
                'assetsContext' => 'book/show',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'book' => $entityManager->getRepository(Book::class)->find($id),
            ]
        );
    }

    /**
     * @Route("/bibliography/", name="library__bibliography_list")
     */
    public function listBibliography(EntityManagerInterface $entityManager, PostRepository $postRepository): Response
    {
        return $this->render(
            'site/bibliography/list.html.twig',
            [
                'translationContext' => 'controller.library.bibliography.list',
                'assetsContext' => 'bibliography/list',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'records' => $entityManager
                    ->getRepository(BibliographicRecord::class)
                    ->findBy([], ['shortName' => 'ASC']),
                'post' => $postRepository->findFavoriteBibliographyDescription(),
            ]
        );
    }
}
