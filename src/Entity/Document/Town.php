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

use App\Repository\Document\TownRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bb__town")
 * @ORM\Entity(repositoryClass=TownRepository::class)
 */
class Town
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviated_name", type="string", length=255, unique=true)
     */
    private $abbreviatedName;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, unique=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="google_maps_place_id", type="string", length=255, unique=true)
     */
    private $googleMapsPlaceId;

    // это поле дублирует инфу из поля $googleMapsPlaceId (по $googleMapsPlaceId можно получить координаты)
    // сейчас оно используется, чтобы уменьшить количество запросов через Google Maps Geocoder API
    // todo подумать, как с этим быть
    /**
     * @var string
     *
     * @ORM\Column(name="google_maps_lat_lng", type="string", length=255, unique=true)
     */
    private $googleMapsLatLng;

    /**
     * @var Collection|Document[]
     *
     * @ORM\OneToMany(targetEntity="Document", mappedBy="town")
     */
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setAbbreviatedName(string $abbreviatedName): self
    {
        $this->abbreviatedName = $abbreviatedName;

        return $this;
    }

    public function getAbbreviatedName(): ?string
    {
        return $this->abbreviatedName;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setGoogleMapsPlaceId(string $googleMapsPlaceId): self
    {
        $this->googleMapsPlaceId = $googleMapsPlaceId;

        return $this;
    }

    public function getGoogleMapsPlaceId(): ?string
    {
        return $this->googleMapsPlaceId;
    }

    public function setGoogleMapsLatLng(string $googleMapsLatLng): self
    {
        $this->googleMapsLatLng = $googleMapsLatLng;

        return $this;
    }

    public function getGoogleMapsLatLng(): ?string
    {
        return $this->googleMapsLatLng;
    }

    /**
     * @param Collection|Document[] $documents
     *
     * @return Town
     */
    public function setDocuments(Collection $documents): self
    {
        $this->documents = new ArrayCollection();

        foreach ($documents as $document) {
            $this->addDocument($document);
        }

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): void
    {
        $document->setTown($this);

        $this->documents[] = $document;
    }
}
