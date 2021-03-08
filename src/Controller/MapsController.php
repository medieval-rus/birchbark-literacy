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

use App\Entity\Document\Town;
use App\Form\Document\DocumentsSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MapsController extends AbstractController
{
    /**
     * @Route("/maps/", name="maps_index")
     */
    public function index(): Response
    {
        return $this->render(
            'site/maps/index.html.twig',
            [
                'translationContext' => 'controller.maps.index',
                'assetsContext' => 'maps/index',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
            ]
        );
    }

    /**
     * @Route("/maps/towns/", name="maps_towns")
     */
    public function townsMap(): Response
    {
        $townsData = [];

        foreach ($this->getDoctrine()->getRepository(Town::class)->findAll() as $town) {
            $townsData[] = [
                'name' => $town->getName(),
                'documentsCount' => $town->getDocuments()->count(),
                'latLng' => $town->getGoogleMapsLatLng(),
            ];
        }

        return $this->render(
            'site/maps/towns.html.twig',
            [
                'translationContext' => 'controller.maps.towns',
                'assetsContext' => 'maps/towns',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'townsData' => $townsData,
                'apiKey' => $this->getParameter('google_maps_api_key'),
            ]
        );
    }

    /**
     * @Route("/maps/excavations/", name="maps_excavations")
     */
    public function excavationsMap(): Response
    {
        return $this->render(
            'site/maps/excavations.html.twig',
            [
                'translationContext' => 'controller.maps.excavations',
                'assetsContext' => 'maps/excavations',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
            ]
        );
    }
}
