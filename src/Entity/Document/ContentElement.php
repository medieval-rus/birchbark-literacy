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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var Collection|ContentCategory[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\ContentCategory", cascade={"persist"})
     * @ORM\JoinTable(name="content_element_category")
     */
    private $categories;

    /**
     * @var Collection|Genre[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Genre", cascade={"persist"})
     * @ORM\JoinTable(name="content_element_genre")
     */
    private $genres;

    /**
     * @var Collection|Language[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Document\Language", cascade={"persist"})
     * @ORM\JoinTable(name="content_element_language")
     */
    private $languages;

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

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->languages = new ArrayCollection();
    }

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

    /**
     * @return Collection|ContentCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection|ContentCategory[] $categories
     */
    public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    /**
     * @param Collection|Genre[] $genres
     */
    public function setGenres(Collection $genres): self
    {
        $this->genres = $genres;

        return $this;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    /**
     * @param Collection|Language[] $languages
     */
    public function setLanguages(Collection $languages): self
    {
        $this->languages = $languages;

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
