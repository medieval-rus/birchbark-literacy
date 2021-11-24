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

use App\Services\Rnc\RncMetadataExporterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/v1/public")
 */
final class PublicController extends AbstractController
{
    /**
     * @Route("/rnc-metadata/", name="api__public__rnc_metadata", methods={"GET"})
     */
    public function rncMetadata(RncMetadataExporterInterface $metadataExporter, Request $request): Response
    {
        $response = new Response();

        $metadata = $metadataExporter->getMetadata($request->getSchemeAndHttpHost(), true);

        if (0 === \count($metadata)) {
            return $response;
        }

        array_unshift($metadata, array_keys($metadata[0]));

        $response->setContent(implode("\r\n", array_map(fn (array $row): string => implode(';', $row), $metadata)));

        return $response;
    }
}
