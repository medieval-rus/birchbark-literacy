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

namespace App\Entity\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__content_element")
 * @ORM\Entity()
 */
class ContentElement
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var ContentCategory|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\ContentCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var Genre|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Genre")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre;

    /**
     * @var Language|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * @var string|null
     *
     * @ORM\Column(name="original_text", type="text", length=65535, nullable=true)
     */
    private $originalText;

    /**
     * @var string|null
     *
     * @ORM\Column(name="translated_text", type="text", length=65535, nullable=true)
     */
    private $translatedText;

    /**
     * @var Document
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Document\Document",
     *     cascade={"persist"},
     *     inversedBy="contentElements"
     * )
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", nullable=false)
     */
    private $document;

    public function __toString(): string
    {
        return (string) $this->description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?ContentCategory
    {
        return $this->category;
    }

    public function setCategory(?ContentCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getOriginalText(): ?string
    {
        return $this->originalText;
    }

    public function setOriginalText(?string $originalText): self
    {
        $this->originalText = $originalText;

        return $this;
    }

    public function getTranslatedText(): ?string
    {
        return $this->translatedText;
    }

    public function setTranslatedText(?string $translatedText): self
    {
        $this->translatedText = $translatedText;

        return $this;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function setDocument(Document $document): self
    {
        $this->document = $document;

        return $this;
    }
}
