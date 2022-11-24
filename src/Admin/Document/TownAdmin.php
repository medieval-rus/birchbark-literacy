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
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class TownAdmin extends AbstractEntityAdmin
{
    protected $baseRouteName = 'document_town';

    protected $baseRoutePattern = 'document/town';

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id', null, $this->createListOptions('id'))
            ->add('name', null, $this->createListOptions('name'))
            ->add('abbreviatedName', null, $this->createListOptions('abbreviatedName'))
            ->add('alias', null, $this->createListOptions('alias'))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with($this->getSectionLabel('common'))
                ->add('name', null, $this->createFormOptions('name'))
                ->add('abbreviatedName', null, $this->createFormOptions('abbreviatedName'))
                ->add('alias', null, $this->createFormOptions('alias'))
                ->add('googleMapsPlaceId', null, $this->createFormOptions('googleMapsPlaceId'))
                ->add('googleMapsLatLng', null, $this->createFormOptions('googleMapsLatLng'))
            ->end()
        ;
    }
}
