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

namespace App\Form\Document;

use App\Entity\Document\ContentElement\Category;
use App\Entity\Document\MaterialElement\Find\Excavation;
use App\Entity\Document\StateOfPreservation;
use App\Entity\Document\Town;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

final class DocumentsFilterType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $townIdsByName = $this->getTownIdsByName();

        $excavationIdsByName = $this->getExcavationIdsByName();

        $stateOfPreservationIdsByName = [];
        foreach ($this->entityManager->getRepository(StateOfPreservation::class)->findAll() as $stateOfPreservation) {
            $stateOfPreservationIdsByName[$stateOfPreservation->getName()] = $stateOfPreservation->getId();
        }

        $categoryIdsByName = [];
        foreach ($this->entityManager->getRepository(Category::class)->findAll() as $category) {
            $categoryIdsByName[$category->getName()] = $category->getId();
        }

        $builder
            ->add('conditionalDateInitialYear', HiddenType::class, [
                'label' => 'documentsFilter.conditionalDateInitialYear',
            ])
            ->add('conditionalDateFinalYear', HiddenType::class, [
                'label' => 'documentsFilter.conditionalDateFinalYear',
            ])
            ->add('town', ChoiceType::class, [
                'label' => 'documentsFilter.town',
                'multiple' => true,
                'choices' => $townIdsByName,
                'required' => false,
            ])
            ->add('excavation', ChoiceType::class, [
                'label' => 'documentsFilter.excavation',
                'multiple' => true,
                'choices' => $excavationIdsByName,
                'required' => false,
            ])
            ->add('stateOfPreservation', ChoiceType::class, [
                'label' => 'documentsFilter.stateOfPreservation',
                'multiple' => true,
                'choices' => $stateOfPreservationIdsByName,
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'documentsFilter.category',
                'multiple' => true,
                'choices' => $categoryIdsByName,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'DocumentsFilterType',
            'attr' => ['class' => 'row documents-filter'],
            'action' => $this->router->generate('birch_bark_document_filter'),
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    private function getTownIdsByName(): array
    {
        $townIdsByName = [];

        foreach ($this->entityManager->getRepository(Town::class)->findAll() as $town) {
            $townIdsByName[$town->getName()] = $town->getId();
        }

        uksort($townIdsByName, function ($a, $b) {
            return strnatcmp($a, $b);
        });

        return $townIdsByName;
    }

    private function getExcavationIdsByName(): array
    {
        $excavationById = [];

        foreach ($this->entityManager->getRepository(Excavation::class)->findAll() as $excavation) {
            $excavationById[$excavation->getId()] = $excavation;
        }

        uasort($excavationById, function (Excavation $a, Excavation $b) {
            if ($a->getTown() !== $b->getTown()) {
                return strnatcmp($a->getTown()->getName(), $b->getTown()->getName());
            }

            return strnatcmp($a->getName(), $b->getName());
        });

        $excavationIdsByName = [];

        foreach ($excavationById as $id => $excavation) {
            $formattedExcavationName = sprintf('%s (%s)', $excavation->getName(), $excavation->getTown()->getName());
            $excavationIdsByName[$formattedExcavationName] = $excavation->getId();
        }

        return $excavationIdsByName;
    }
}
