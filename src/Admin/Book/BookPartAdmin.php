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

namespace App\Admin\Book;

use App\Admin\AbstractEntityAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class BookPartAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'book_book_part';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'book/book-part';

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, $this->createLabeledListOptions('id'))
            ->addIdentifier('name', null, $this->createLabeledListOptions('name'))
            ->addIdentifier('book', null, $this->createLabeledListOptions('book'))
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with($this->getSectionLabel('common'))
                ->add('name', null, $this->createLabeledFormOptions('name'))
                ->add('book', null, $this->createLabeledFormOptions('book'))
            ->end()
        ;
    }
}
