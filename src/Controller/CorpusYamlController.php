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
use App\Form\Corpus\CorpusYamlFormType;
use App\Repository\Document\DocumentRepository;
use App\Services\Corpus\CorpusDataProviderInterface;
use App\Services\Corpus\Indices\IndexGeneratorInterface;
use App\Services\Corpus\Indices\Models\InflectedForm;
use App\Services\Corpus\Indices\Models\InflectedFromEntry;
use App\Services\Corpus\Indices\Models\Word;
use App\Services\Corpus\Indices\Models\WordIndex;
use App\Services\Corpus\Yaml\Models\YamlDocument;
use App\Services\Corpus\Yaml\Models\YamlLine;
use App\Services\Corpus\Yaml\Models\YamlLineElement;
use App\Services\Corpus\Yaml\Models\YamlPage;
use App\Services\Corpus\Yaml\YamlParserInterface;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use App\Services\Document\Sorting\DocumentComparerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

/**
 * @Route("/corpus/yaml")
 */
final class CorpusYamlController extends AbstractController
{
    /**
     * @Route("/index/", name="corpus_yaml__index", methods={"GET", "POST"})
     */
    public function index(
        Request $request,
        YamlParserInterface $yamlParser,
        IndexGeneratorInterface $indexGenerator
    ): Response {
        [$isSuccessful, $form] = $this->handleRequest($request);

        if (!$isSuccessful) {
            return $this->renderYamlForm($form);
        }

        [$yamlFileName, $yamlDocumentsByNumber] = $this->readYaml($form, $yamlParser);

        $index = $indexGenerator->generate($yamlDocumentsByNumber);

        $forwardIndexEntries = $this->formatIndex($index);
        $backwardIndexEntries = $forwardIndexEntries;

        uksort($forwardIndexEntries, fn (string $a, string $b): int => $this->compareLemmas($a, $b));
        uksort($backwardIndexEntries, fn (string $a, string $b): int => $this->compareLemmas(strrev($a), strrev($b)));

        return $this->createZipResponse(
            sprintf('%s_indices.zip', $yamlFileName),
            [
                sprintf('forward_index_for_%s.txt', $yamlFileName) => implode("\r\n", $forwardIndexEntries),
                sprintf('backward_index_for_%s.txt', $yamlFileName) => implode("\r\n", $backwardIndexEntries),
            ]
        );
    }

    /**
     * @Route("/diff/", name="corpus_yaml__diff", methods={"GET", "POST"})
     */
    public function diff(
        Request $request,
        CorpusDataProviderInterface $corpusDataProvider,
        YamlParserInterface $yamlParser,
        DocumentRepository $documentRepository,
        DocumentComparerInterface $documentComparer,
        DocumentFormatterInterface $documentFormatter
    ): Response {
        [$isSuccessful, $form] = $this->handleRequest($request);

        if (!$isSuccessful) {
            return $this->renderYamlForm($form);
        }

        $documents = $documentRepository->findAllInConventionalOrder(false, true);
        $documentsByNumber = array_combine(
            array_map(fn (Document $document): string => $documentFormatter->getNumber($document), $documents),
            $documents
        );

        $documentComparer = fn (string $a, string $b): int => $documentComparer->compare(
            $documentsByNumber[$a],
            $documentsByNumber[$b]
        );

        [$yamlFileName, $yamlDocumentsByNumber] = $this->readYaml($form, $yamlParser);
        uksort($yamlDocumentsByNumber, $documentComparer);

        $dbTexts = $corpusDataProvider->getTexts(true);
        $dbTextsByNumber = array_combine(
            array_map(fn (array $document): string => $document['number'], $dbTexts),
            $dbTexts
        );
        uksort($dbTextsByNumber, $documentComparer);

        return $this->createZipResponse(
            sprintf('%s_diff.zip', $yamlFileName),
            [
                sprintf('yaml_%s.txt', $yamlFileName) => implode(
                    "\r\n\r\n",
                    array_map(
                        fn (string $documentNumber, YamlDocument $yamlDocument): string => $documentNumber.
                            "\r\n".
                            implode(
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
                ),
                sprintf('db_%s.txt', $yamlFileName) => implode(
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
                ),
            ]
        );
    }

    private function createZipResponse(string $returnedFileName, array $items): Response
    {
        $zipFileName = tempnam('/tmp', 'zip');

        $zip = new ZipArchive();
        $zip->open($zipFileName, ZipArchive::CREATE);

        foreach ($items as $fileName => $item) {
            $zip->addFromString($fileName, $item);
        }

        $zip->close();

        $response = new Response(file_get_contents($zipFileName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$returnedFileName.'"');
        $response->headers->set('Content-length', filesize($zipFileName));

        return $response;
    }

    private function handleRequest(Request $request): array
    {
        $form = $this->createForm(CorpusYamlFormType::class);
        $form->handleRequest($request);

        return [$form->isSubmitted() && $form->isValid(), $form];
    }

    private function readYaml(FormInterface $form, YamlParserInterface $yamlParser): array
    {
        /**
         * @var $yamlFile UploadedFile
         */
        $yamlFile = $form->get('yaml')->getData();

        return [$yamlFile->getClientOriginalName(), $yamlParser->parseYaml($yamlFile->getContent())];
    }

    private function renderYamlForm($form): Response
    {
        return $this->render('site/corpus/yaml.html.twig', ['form' => $form->createView()]);
    }

    private function compareLemmas(string $a, string $b): int
    {
        return strnatcmp($a, $b);
    }

    private function formatIndex(WordIndex $index): array
    {
        return array_combine(
            array_map(fn (Word $word): string => $word->getLemma(), $index->getWords()),
            array_map(fn (Word $word): string => $this->formatWord($word), $index->getWords())
        );
    }

    private function formatWord(Word $word): string
    {
        return sprintf(
            '%s (%s): %s',
            $word->getLemma(),
            $word->getPartOfSpeech(),
            implode(
                '; ',
                array_map(
                    fn (InflectedForm $inflectedForm): string => $this->formatInflectedForm($inflectedForm),
                    $word->getInflectedForms()
                )
            )
        );
    }

    private function formatInflectedForm(InflectedForm $inflectedForm): string
    {
        return sprintf(
            '%s (%s)',
            $inflectedForm->getWord(),
            implode(
                ', ',
                array_map(
                    fn (InflectedFromEntry $entry): string => $this->formatInflectedFromEntry($entry),
                    $inflectedForm->getEntries()
                )
            )
        );
    }

    private function formatInflectedFromEntry(InflectedFromEntry $entry): string
    {
        return sprintf('%s - %s', $entry->getDocumentNumber(), $entry->getCount());
    }
}
