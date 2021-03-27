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

namespace App\Admin\Document;

use App\Admin\AbstractEntityAdmin;
use Sonata\AdminBundle\Form\FormMapper;

final class MaterialElementAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'document_material_element';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'document/material-element';

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with($this->getSectionLabel('basicInformation'))
                ->add('name', null, $this->createLabeledFormOptions('name'))
                ->add('partsCount', null, $this->createLabeledFormOptions('partsCount'))
                ->add('comment', null, $this->createLabeledFormOptions('comment'))
                ->add('storagePlace', null, $this->createLabeledFormOptions('storagePlace'))
                ->add('material', null, $this->createLabeledFormOptions('material'))
            ->end()
            ->with($this->getSectionLabel('size'))
                ->add('length', null, $this->createLabeledFormOptions('length'))
                ->add('innerLength', null, $this->createLabeledFormOptions('innerLength'))
                ->add('width', null, $this->createLabeledFormOptions('width'))
                ->add('innerWidth', null, $this->createLabeledFormOptions('innerWidth'))
                ->add('diameter', null, $this->createLabeledFormOptions('diameter'))
            ->end()
        ;
    }
}
