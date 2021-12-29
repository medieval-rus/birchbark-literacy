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

namespace App\Admin\Document;

use App\Admin\AbstractEntityAdmin;
use Sonata\AdminBundle\Form\FormMapper;

final class MaterialElementAdmin extends AbstractEntityAdmin
{
    protected $baseRouteName = 'document_material_element';

    protected $baseRoutePattern = 'document/material-element';

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab($this->getTabLabel('main'))
                ->with($this->getSectionLabel('basicInformation'))
                    ->add('name', null, $this->createFormOptions('name'))
                    ->add('partsCount', null, $this->createFormOptions('partsCount'))
                    ->add('comment', null, $this->createFormOptions('comment'))
                    ->add('storagePlace', null, $this->createFormOptions('storagePlace'))
                    ->add('material', null, $this->createFormOptions('material'))
                ->end()
                ->with($this->getSectionLabel('size'))
                    ->add('length', null, $this->createFormOptions('length'))
                    ->add('innerLength', null, $this->createFormOptions('innerLength'))
                    ->add('width', null, $this->createFormOptions('width'))
                    ->add('innerWidth', null, $this->createFormOptions('innerWidth'))
                    ->add('diameter', null, $this->createFormOptions('diameter'))
                ->end()
            ->end()
            ->tab($this->getTabLabel('find'))
                ->with($this->getSectionLabel('archaeologicalInformation'))
                    ->add('isArchaeologicalFind', null, $this->createFormOptions('isArchaeologicalFind'))
                    ->add('year', null, $this->createFormOptions('year'))
                    ->add('excavation', null, $this->createFormOptions('excavation'))
                    ->add('initialTier', null, $this->createFormOptions('initialTier'))
                    ->add('finalTier', null, $this->createFormOptions('finalTier'))
                    ->add('commentOnTiers', null, $this->createFormOptions('commentOnTiers'))
                    ->add('initialDepth', null, $this->createFormOptions('initialDepth'))
                    ->add('finalDepth', null, $this->createFormOptions('finalDepth'))
                    ->add('commentOnDepths', null, $this->createFormOptions('commentOnDepths'))
                    ->add('squares', null, $this->createManyToManyFormOptions('squares'))
                    ->add('strata', null, $this->createManyToManyFormOptions('strata'))
                ->end()
                ->with($this->getSectionLabel('socialInformation'))
                    ->add('estates', null, $this->createManyToManyFormOptions('estates'))
                    ->add('streets', null, $this->createManyToManyFormOptions('streets'))
                    ->add('isPalisade', null, $this->createFormOptions('isPalisade'))
                    ->add('isRoadway', null, $this->createFormOptions('isRoadway'))
                ->end()
            ->end()
        ;
    }
}
