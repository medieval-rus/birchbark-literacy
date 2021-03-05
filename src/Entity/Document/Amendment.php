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

namespace App\Entity\Document;

use App\Entity\MediaBundle\Media;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="bb__amendment",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ngb_volume__bb_document", columns={"ngb_volume", "birch_bark_document_id"})
 *     }
 * )
 * @ORM\Entity
 */
class Amendment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ngbVolume;

    /**
     * @var Media
     *
     * @ORM\OneToOne(targetEntity="App\Entity\MediaBundle\Media", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=false)
     */
    private $media;

    /**
     * @var Document
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Document\Document",
     *     cascade={"persist"},
     *     inversedBy="amendments"
     * )
     * @ORM\JoinColumn(name="birch_bark_document_id", referencedColumnName="id", nullable=false)
     */
    private $birchBarkDocument;

    public function getId(): int
    {
        return $this->id;
    }

    public function setNgbVolume(string $ngbVolume): self
    {
        $this->ngbVolume = $ngbVolume;

        return $this;
    }

    public function getNgbVolume(): string
    {
        return $this->ngbVolume;
    }

    public function setMedia(Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function setBirchBarkDocument(Document $birchBarkDocument): self
    {
        $this->birchBarkDocument = $birchBarkDocument;

        return $this;
    }

    public function getBirchBarkDocument(): Document
    {
        return $this->birchBarkDocument;
    }
}
