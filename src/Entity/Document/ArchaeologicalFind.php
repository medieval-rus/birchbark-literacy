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
 * @ORM\Table(name="bb__material_element__find__archaeological")
 * @ORM\Entity()
 */
class ArchaeologicalFind extends AbstractFind implements ExcavationDependentFindInterface
{
    // todo nullable false
    /**
     * @var Excavation|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Document\Excavation", cascade={"persist"})
     * @ORM\JoinColumn(name="excavation_id", referencedColumnName="id")
     */
    private $excavation;

    /**
     * @var AbstractRelationToEstates
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Document\AbstractRelationToEstates", cascade={"persist"})
     * @ORM\JoinColumn(name="relation_to_estates_id", referencedColumnName="id", nullable=false)
     */
    private $relationToEstates;

    /**
     * @var AbstractRelationToSquares
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Document\AbstractRelationToSquares", cascade={"persist"})
     * @ORM\JoinColumn(name="relation_to_squares_id", referencedColumnName="id", nullable=false)
     */
    private $relationToSquares;

    /**
     * @var AbstractRelationToStrata
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Document\AbstractRelationToStrata", cascade={"persist"})
     * @ORM\JoinColumn(name="relation_to_strata_id", referencedColumnName="id", nullable=false)
     */
    private $relationToStrata;

    /**
     * @var string|null
     *
     * @ORM\Column(name="initial_tier", type="integer", nullable=true)
     */
    private $initialTier;

    /**
     * @var string|null
     *
     * @ORM\Column(name="final_tier", type="integer", nullable=true)
     */
    private $finalTier;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment_on_tiers", type="string", length=255, nullable=true)
     */
    private $commentOnTiers;

    /**
     * @var string|null
     *
     * @ORM\Column(name="initial_depth", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $initialDepth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="final_depth", type="decimal", precision=65, scale=2, nullable=true)
     */
    private $finalDepth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment_on_depths", type="string", length=255, nullable=true)
     */
    private $commentOnDepths;

    /**
     * @var int|null
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    public function setExcavation(?Excavation $excavation): self
    {
        $this->excavation = $excavation;

        return $this;
    }

    public function getExcavation(): ?Excavation
    {
        return $this->excavation;
    }

    public function setRelationToEstates(AbstractRelationToEstates $relationToEstates): self
    {
        $this->relationToEstates = $relationToEstates;

        return $this;
    }

    public function getRelationToEstates(): AbstractRelationToEstates
    {
        return $this->relationToEstates;
    }

    public function setRelationToSquares(AbstractRelationToSquares $relationToSquares): self
    {
        $this->relationToSquares = $relationToSquares;

        return $this;
    }

    public function getRelationToSquares(): AbstractRelationToSquares
    {
        return $this->relationToSquares;
    }

    public function setRelationToStrata(AbstractRelationToStrata $relationToStrata): self
    {
        $this->relationToStrata = $relationToStrata;

        return $this;
    }

    public function getRelationToStrata(): AbstractRelationToStrata
    {
        return $this->relationToStrata;
    }

    public function setInitialTier(?string $initialTier): self
    {
        $this->initialTier = $initialTier;

        return $this;
    }

    public function getInitialTier(): ?string
    {
        return $this->initialTier;
    }

    public function setFinalTier(?string $finalTier): self
    {
        $this->finalTier = $finalTier;

        return $this;
    }

    public function getFinalTier(): ?string
    {
        return $this->finalTier;
    }

    public function setCommentOnTiers(?string $commentOnTiers): self
    {
        $this->commentOnTiers = $commentOnTiers;

        return $this;
    }

    public function getCommentOnTiers(): ?string
    {
        return $this->commentOnTiers;
    }

    public function setInitialDepth(?string $initialDepth): self
    {
        $this->initialDepth = $initialDepth;

        return $this;
    }

    public function getInitialDepth(): ?string
    {
        return $this->initialDepth;
    }

    public function setFinalDepth(?string $finalDepth): self
    {
        $this->finalDepth = $finalDepth;

        return $this;
    }

    public function getFinalDepth(): ?string
    {
        return $this->finalDepth;
    }

    public function setCommentOnDepths(?string $commentOnDepths): self
    {
        $this->commentOnDepths = $commentOnDepths;

        return $this;
    }

    public function getCommentOnDepths(): ?string
    {
        return $this->commentOnDepths;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }
}
