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
use App\Form\Rnc\RncDiffFormType;
use App\Repository\Document\DocumentRepository;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use App\Services\Document\Sorter\DocumentsSorterInterface;
use App\Services\Rnc\RncDataProviderInterface;
use App\Services\Rnc\Yaml\YamlDocument;
use App\Services\Rnc\Yaml\YamlLine;
use App\Services\Rnc\Yaml\YamlLineElement;
use App\Services\Rnc\Yaml\YamlPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

/**
 * @Route("/rnc")
 */
final class RncController extends AbstractController
{
    /**
     * @Route("/yaml/", name="rnc__yaml", methods={"GET", "POST"})
     */
    public function yaml(
        Request $request,
        RncDataProviderInterface $rncDataProvider,
        DocumentRepository $documentRepository,
        DocumentsSorterInterface $documentsSorter,
        DocumentFormatterInterface $documentFormatter
    ): Response {
        $form = $this->createForm(RncDiffFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('site/rnc/yaml.html.twig', ['form' => $form->createView()]);
        }

        $documents = $documentRepository->findAllInConventionalOrder();
        $documentsByNumber = array_combine(
            array_map(fn (Document $document): string => $documentFormatter->getNumber($document), $documents),
            $documents
        );

        $sorter = fn (string $a, string $b): int => $documentsSorter->compare(
            $documentsByNumber[$a],
            $documentsByNumber[$b]
        );

        /**
         * @var $yamlFile UploadedFile
         */
        $yamlFile = $form->get('yaml')->getData();
        $yamlDocumentsByNumber = $rncDataProvider->parseYaml($yamlFile->getContent());
        uksort($yamlDocumentsByNumber, $sorter);

        $dbTexts = $rncDataProvider->getTexts(true);
        $dbTextsByNumber = array_combine(
            array_map(fn (array $document): string => $document['number'], $dbTexts),
            $dbTexts
        );
        uksort($dbTextsByNumber, $sorter);

        $zipFileName = tempnam('/tmp', 'zip');

        $zip = new ZipArchive();
        $zip->open($zipFileName, ZipArchive::CREATE);

        $zip->addFromString(
            sprintf('yaml_%s.txt', $yamlFile->getClientOriginalName()),
            implode(
                "\r\n\r\n",
                array_map(
                    fn (string $documentNumber, YamlDocument $yamlDocument): string => $documentNumber."\r\n".implode(
                        "\r\n",
                        array_map(
                            fn (YamlPage $yamlPage): string => implode(
                                "\r\n",
                                array_map(
                                    fn (YamlLine $yamlLine): string => implode(
                                        ' ',
                                        array_map(
                                            fn (YamlLineElement $element): string => $element->getValue(),
                                            array_filter(
                                                $yamlLine->getElements(),
                                                fn (YamlLineElement $element): bool => null !== $element->getValue() &&
                                                    empty($element->getProperty('label'))
                                            )
                                        )
                                    ),
                                    $yamlPage->getLines()
                                )
                            ),
                            $yamlDocument->getPages()
                        )
                    ),
                    array_keys($yamlDocumentsByNumber),
                    $yamlDocumentsByNumber
                )
            )
        );
        $zip->addFromString(
            sprintf('db_%s.txt', $yamlFile->getClientOriginalName()),
            implode(
                "\r\n\r\n",
                array_map(
                    fn (array $dbDocument): string => $dbDocument['number']."\r\n".implode(
                        "\r\n",
                        array_filter(
                            array_map(
                                fn (array $dbPage): ?string => $dbPage['text'],
                                $dbDocument['pages']
                            ),
                            fn (?string $text): bool => null !== $text && '' !== trim($text)
                        )
                    ),
                    $dbTextsByNumber
                )
            )
        );

        $zip->close();

        $returnedFileName = sprintf('%s_diff.zip', $yamlFile->getClientOriginalName());

        $response = new Response(file_get_contents($zipFileName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$returnedFileName.'"');
        $response->headers->set('Content-length', filesize($zipFileName));

        return $response;
    }
}
