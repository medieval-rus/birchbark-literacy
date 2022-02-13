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

use App\Entity\Document\ContentElement;
use App\Entity\Document\Document;
use App\Form\Corpus\CorpusXhtmlFormType;
use App\Form\Corpus\CorpusYamlFormType;
use App\Helper\StringHelper;
use App\Repository\Document\DocumentRepository;
use App\Services\Corpus\CorpusDataProviderInterface;
use App\Services\Corpus\Indices\IndexGeneratorInterface;
use App\Services\Corpus\Indices\Models\InflectedForm;
use App\Services\Corpus\Indices\Models\InflectedFromEntry;
use App\Services\Corpus\Indices\Models\Word;
use App\Services\Corpus\Indices\Models\WordIndex;
use App\Services\Corpus\Morphy\Models\Xhtml\XhtmlDocument;
use App\Services\Corpus\Morphy\Models\Yaml\YamlDocument;
use App\Services\Corpus\Morphy\Models\Yaml\YamlLine;
use App\Services\Corpus\Morphy\Models\Yaml\YamlPage;
use App\Services\Corpus\Morphy\Models\Yaml\YamlPiece;
use App\Services\Corpus\Morphy\MorphyParserInterface;
use App\Services\Document\Formatter\DocumentFormatterInterface;
use App\Services\Document\Sorting\DocumentComparerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
        MorphyParserInterface $morphyParser,
        IndexGeneratorInterface $indexGenerator
    ): Response {
        [$isSuccessful, $form] = $this->handleRequest($request, CorpusYamlFormType::class);

        if (!$isSuccessful) {
            return $this->renderInputForm($form);
        }

        [$yamlFileName, $yamlDocumentsByNumber] = $this->readYaml($form, $morphyParser);

        $index = $indexGenerator->generate($yamlDocumentsByNumber);

        $forwardIndexEntries = $this->formatIndex($index);
        $backwardIndexEntries = $forwardIndexEntries;

        $forwardIndexEntries = $this->filterIndex($forwardIndexEntries, true);
        $backwardIndexEntries = $this->filterIndex($backwardIndexEntries, false);

        usort(
            $forwardIndexEntries,
            fn (array $a, array $b): int => $this->compareLemmas($a['label'], $b['label'])
        );
        usort(
            $backwardIndexEntries,
            fn (array $a, array $b): int => $this->compareLemmas(strrev($a['label']), strrev($b['label']))
        );

        return $this->createZipResponse(
            sprintf('%s_indices.zip', $yamlFileName),
            [
                sprintf('forward_index_for_%s.txt', $yamlFileName) => implode(
                    "\r\n",
                    array_column($forwardIndexEntries, 'value')
                ),
                sprintf('backward_index_for_%s.txt', $yamlFileName) => implode(
                    "\r\n",
                    array_column($backwardIndexEntries, 'value')
                ),
            ]
        );
    }

    /**
     * @Route("/diff/", name="corpus_yaml__diff", methods={"GET", "POST"})
     */
    public function diff(
        Request $request,
        CorpusDataProviderInterface $corpusDataProvider,
        MorphyParserInterface $morphyParser,
        DocumentRepository $documentRepository,
        DocumentComparerInterface $documentComparer,
        DocumentFormatterInterface $documentFormatter
    ): Response {
        [$isSuccessful, $form] = $this->handleRequest($request, CorpusYamlFormType::class);

        if (!$isSuccessful) {
            return $this->renderInputForm($form);
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

        [$yamlFileName, $yamlDocumentsByNumber] = $this->readYaml($form, $morphyParser);
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
                                                    fn (YamlPiece $pieces): string => $pieces->getValue(),
                                                    array_filter(
                                                        $yamlLine->getPieces(),
                                                        fn (YamlPiece $pieces): bool => null !== $pieces->getValue() &&
                                                            empty($pieces->getProperty('label'))
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

    /**
     * @Route("/parallelize/", name="corpus_yaml__parallelize", methods={"GET", "POST"})
     */
    public function parallelize(
        Request $request,
        MorphyParserInterface $morphyParser,
        DocumentRepository $documentRepository
    ): Response {
        [$isSuccessful, $form] = $this->handleRequest($request, CorpusXhtmlFormType::class);

        if (!$isSuccessful) {
            return $this->renderInputForm($form);
        }

        /**
         * @var $xhtmlDocumentsByNumber XhtmlDocument[]
         */
        [$xhtmlFileName, $xhtmlDocumentsByNumber] = $this->readXhtml($form, $morphyParser);

        $documentsByNumber = $documentRepository->getAllDocumentsByNumber(false, true);

        $parallelDocuments = [];
        foreach ($xhtmlDocumentsByNumber as $number => $xhtmlDocument) {
            $document = $documentsByNumber[$number];

            $parallelDocuments[] = $this->formatParallelXhtmlDocument(
                $xhtmlDocument->getNumber(),
                [
                    $this->wrapWithLanguageTag($xhtmlDocument->getContent(), 'orv'),
                    $this->wrapTranslation(
                        $document,
                        fn (ContentElement $contentElement): ?string => $contentElement->getTranslationRussian(),
                        'ru'
                    ),
                    $this->wrapTranslation(
                        $document,
                        fn (ContentElement $contentElement): ?string => $contentElement->getTranslationEnglishKovalev(),
                        'en',
                        '1'
                    ),
                    $this->wrapTranslation(
                        $document,
                        fn (ContentElement $contentElement): ?string => $contentElement->getTranslationEnglishSchaeken(),
                        'en',
                        '2'
                    ),
                ]
            );
        }

        $response = new Response($this->formatXhtmlDocument(implode("\n", $parallelDocuments)));
        $response->headers->set(
            'Content-Disposition',
            $response
                ->headers
                ->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    sprintf('%s_parallel.xhtml', $xhtmlFileName)
                )
        );

        return $response;
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
        $response->headers->set(
            'Content-Disposition',
            $response
                ->headers
                ->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $returnedFileName)
        );
        $response->headers->set('Content-length', filesize($zipFileName));

        return $response;
    }

    private function handleRequest(Request $request, string $formType): array
    {
        $form = $this->createForm($formType);
        $form->handleRequest($request);

        return [$form->isSubmitted() && $form->isValid(), $form];
    }

    private function readYaml(FormInterface $form, MorphyParserInterface $morphyParser): array
    {
        /**
         * @var $yamlFile UploadedFile
         */
        $yamlFile = $form->get('yaml')->getData();

        return [$yamlFile->getClientOriginalName(), $morphyParser->parseYaml($yamlFile->getContent())];
    }

    private function readXhtml(FormInterface $form, MorphyParserInterface $morphyParser): array
    {
        /**
         * @var $xhtmlFile UploadedFile
         */
        $xhtmlFile = $form->get('xhtml')->getData();

        return [$xhtmlFile->getClientOriginalName(), $morphyParser->parseXhtml($xhtmlFile->getContent())];
    }

    private function renderInputForm(FormInterface $form): Response
    {
        return $this->renderForm('site/corpus/form.html.twig', ['form' => $form]);
    }

    private function compareLemmas(string $a, string $b): int
    {
        return strnatcmp($this->prepareLemmaForComparison($a), $this->prepareLemmaForComparison($b));
    }

    private function filterIndex(array $indexEntries, bool $isForward): array
    {
        $ellipsis = '…';

        return array_filter(
            $indexEntries,
            fn (array $indexEntryData): bool => $isForward ?
                !StringHelper::startsWith($indexEntryData['label'], $ellipsis) :
                !StringHelper::endsWith($indexEntryData['label'], $ellipsis),
        );
    }

    private function prepareInflectedForm(string $form): string
    {
        $form = StringHelper::removeFromStart($form, ')');
        $form = StringHelper::removeFromStart($form, ']');
        $form = StringHelper::removeFromEnd($form, '(');
        $form = StringHelper::removeFromEnd($form, '[');

        if (1 === preg_match('/^[^(]+\).*/', $form)) {
            $form = '('.$form;
        }

        if (1 === preg_match('/^[^[]+\].*/', $form)) {
            $form = '['.$form;
        }

        if (1 === preg_match('/.*\([^)]+$/', $form)) {
            $form = $form.')';
        }

        if (1 === preg_match('/.*\[[^]]+$/', $form)) {
            $form = $form.']';
        }

        return $form;
    }

    private function prepareLemmaForComparison(string $lemma): string
    {
        return mb_strtolower(
            str_replace(
                ['(', ')', '[', ']', '?', '*'],
                ['', '', '', '', '', ''],
                $lemma
            )
        );
    }

    private function formatIndex(WordIndex $index): array
    {
        return array_map(
            fn (Word $word): array => [
                'label' => $word->getLemma(),
                'value' => $this->formatWord($word),
            ],
            $index->getWords()
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
            '%s%s (%s)%s%s%s',
            $this->prepareInflectedForm($inflectedForm->getForm()),
            $inflectedForm->getIsMisspelled() ? ' (!)' : '',
            implode(
                ', ',
                array_map(
                    fn (InflectedFromEntry $entry): string => $this->formatInflectedFromEntry($entry),
                    $inflectedForm->getEntries()
                )
            ),
            $inflectedForm->getIsUnsure() ? ' (?)' : '',
            $inflectedForm->getIsPhonemicUnsure() ? ' (*)' : '',
            $inflectedForm->getIsReconstruction() ? ' (реконстр.)' : ''
        );
    }

    private function formatInflectedFromEntry(InflectedFromEntry $entry): string
    {
        return sprintf('%s - %s', $entry->getDocumentNumber(), $entry->getCount());
    }

    private function wrapWithLanguageTag(
        string $document,
        string $languageTag,
        ?string $languageVariantId = null
    ): string {
        $trimmed = trim($document);

        return sprintf(
            '<se lang="%s"%s>%s</se>',
            $languageTag,
            null === $languageVariantId ? '' : sprintf(' variant_id="%s"', $languageVariantId),
            '' === $trimmed ? '' : sprintf("\n%s\n", $trimmed)
        );
    }

    private function wrapTranslation(
        Document $document,
        callable $translationGetter,
        string $languageTag,
        ?string $languageVariantId = null
    ): string {
        $translation = implode(
            "\n",
            $document
                ->getContentElements()
                ->map($translationGetter)
                ->filter(fn (?string $translation): bool => null !== $translation)
                ->toArray()
        );

        return $this->wrapWithLanguageTag($translation, $languageTag, $languageVariantId);
    }

    private function formatXhtmlDocument(string $content): string
    {
        return sprintf(
            <<<'XHTML'
<?xml version="1.0" encoding="utf-8"?>
<html>
<head>
</head>
<body>
%s
</body>
</html>
XHTML,
            trim($content)
        );
    }

    private function formatParallelXhtmlDocument(string $number, array $translations): string
    {
        return sprintf(
            <<<'XHTML'
<document id="%s">
%s
</document>
XHTML,
            $number,
            implode("\n", $translations)
        );
    }
}
