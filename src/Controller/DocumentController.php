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

use App\Entity\Document\Document;
use App\Entity\Document\Town;
use App\Form\Document\DocumentsSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vyfony\Bundle\FilterableTableBundle\Table\TableInterface;

/**
 * @Route("/document")
 */
final class DocumentController extends AbstractController
{
    /**
     * @Route("/list/", name="document__list")
     */
    public function list(TableInterface $filterableTable): Response
    {
        return $this->render(
            'site/document/list.html.twig',
            [
                'translationContext' => 'controller.document.list',
                'assetsContext' => 'document/list',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'filterForm' => $filterableTable->getFormView(),
                'table' => $filterableTable->getTableMetadata(),
            ]
        );
    }

    /**
     * @Route(
     *     "/show/{town}/{number}/",
     *     name="document__show",
     *     requirements={"number"="(?!/).+(?<!/)$"}
     * )
     */
    public function showAction(string $town, string $number): Response
    {
        $document = $this
            ->getDoctrine()
            ->getRepository(Document::class)
            ->findOneByTownAliasAndNumber(urldecode($town), $number, true);

        return $this->render(
            'site/document/show.html.twig',
            [
                'translationContext' => 'controller.document.show',
                'assetsContext' => 'document/show',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'document' => $document,
            ]
        );
    }
}
