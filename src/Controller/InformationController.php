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

namespace App\Controller;

use App\Form\Document\DocumentsSearchType;
use App\Repository\Content\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationController extends AbstractController
{
    /**
     * @Route("/about-site/", name="information__about_site")
     */
    public function aboutSite(PostRepository $postRepository): Response
    {
        return $this->render(
            'site/content/post.html.twig',
            [
                'translationContext' => 'controller.information.aboutSite',
                'assetsContext' => 'content/post',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'post' => $postRepository->findAboutSite(),
            ]
        );
    }

    /**
     * @Route("/about-source/", name="information__about_source")
     */
    public function aboutSource(PostRepository $postRepository): Response
    {
        return $this->render(
            'site/content/post.html.twig',
            [
                'translationContext' => 'controller.information.aboutSource',
                'assetsContext' => 'content/post',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'post' => $postRepository->findAboutSource(),
            ]
        );
    }

    /**
     * @Route("/about-source/general-information", name="information__about_source__general_information")
     */
    public function generalInformation(PostRepository $postRepository): Response
    {
        return $this->render(
            'site/content/post.html.twig',
            [
                'translationContext' => 'controller.information.generalInformation',
                'assetsContext' => 'content/post',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'post' => $postRepository->findGeneralInformationAboutBirchbarkDocuments(),
            ]
        );
    }

    /**
     * @Route("/news/", name="information__news")
     */
    public function news(PostRepository $postRepository): Response
    {
        return $this->render(
            'site/content/post.html.twig',
            [
                'translationContext' => 'controller.information.news',
                'assetsContext' => 'content/news',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'post' => $postRepository->findNews(),
            ]
        );
    }
}
