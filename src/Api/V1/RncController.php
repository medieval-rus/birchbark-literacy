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

namespace App\Api\V1;

use App\Services\Rnc\RncDataProviderInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1/rnc")
 */
final class RncController extends AbstractController
{
    /**
     * @Route("/metadata/", name="api__v1__rnc__metadata", methods={"GET"})
     */
    public function metadata(RncDataProviderInterface $rncDataProvider, Request $request): Response
    {
        $metadata = $rncDataProvider->getMetadata($request->getSchemeAndHttpHost(), true);

        if ('true' === $request->query->get('csv')) {
            if (\count($metadata) > 0) {
                array_unshift($metadata, array_keys($metadata[0]));

                $response = new Response(
                    implode(
                        "\r\n",
                        array_map(fn (array $row): string => implode(';', $row), $metadata)
                    )
                );

                $disposition = $response->headers->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    sprintf('%s_rnc_metadata_%s.csv', $request->getHost(), (new DateTime())->format('Y-m-d-H-i-s'))
                );

                $response->headers->set('Content-Disposition', $disposition);

                return $response;
            }
        }

        return new Response($this->toJson($metadata));
    }

    /**
     * @Route("/texts/", name="api__v1__rnc__texts", methods={"GET"})
     */
    public function texts(RncDataProviderInterface $rncDataProvider): Response
    {
        $texts = $rncDataProvider->getTexts(true);

        $response = new Response();

        $response->setContent($this->toJson($texts));

        return $response;
    }

    private function toJson(array $array): string
    {
        return json_encode($array, \JSON_UNESCAPED_UNICODE | \JSON_PRETTY_PRINT);
    }
}
