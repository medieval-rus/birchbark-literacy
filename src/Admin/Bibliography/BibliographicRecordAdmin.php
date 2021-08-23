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

namespace App\Admin\Bibliography;

use App\Admin\AbstractEntityAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;

final class BibliographicRecordAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'bibliography_record';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'bibliography/bibliographic-record';

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('shortName', null, $this->createLabeledListOptions('shortName'))
            ->add('title', null, $this->createLabeledListOptions('title'))
            ->add('year', null, $this->createLabeledListOptions('year'))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab($this->getTabLabel('main'))
                ->with('form.bibliographicRecord.section.basicInformation.label', ['class' => 'col-md-7'])
                    ->add('shortName', null, $this->createLabeledFormOptions('shortName'))
                    ->add('title', null, $this->createLabeledFormOptions('title'))
                    ->add('year', null, $this->createLabeledFormOptions('year'))
                    ->add('authors', null, $this->createLabeledManyToManyFormOptions('authors'))
                ->end()
                ->with('form.bibliographicRecord.section.details.label', ['class' => 'col-md-5'])
                    ->add('formalNotation', null, $this->createLabeledFormOptions('formalNotation'))
                    ->add('description', null, $this->createLabeledFormOptions('description'))
                ->end()
            ->end()
            ->tab($this->getTabLabel('media'))
                ->with($this->getSectionLabel('media'))
                    ->add('mainFile', null, $this->createLabeledFormOptions('mainFile'))
                    ->add('mainImage', null, $this->createLabeledFormOptions('mainImage'))
                ->end()
            ->end()
            ->tab($this->getTabLabel('supplements'))
                ->with($this->getSectionLabel('fileSupplements'))
                    ->add(
                        'fileSupplements',
                        CollectionType::class,
                        $this->createLabeledFormOptions(
                            'fileSupplements',
                            ['required' => false]
                        ),
                        ['edit' => 'inline', 'admin_code' => 'admin.bibliography.file_supplement']
                    )
                ->end()
                ->with($this->getSectionLabel('structuralComponents'))
                    ->add(
                        'structuralComponents',
                        CollectionType::class,
                        $this->createLabeledFormOptions(
                            'structuralComponents',
                            ['required' => false]
                        ),
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'position',
                            'admin_code' => 'admin.bibliography.structural_component',
                        ]
                    )
                ->end()
            ->end()
        ;
    }
}
