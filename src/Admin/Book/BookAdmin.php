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
use Sonata\AdminBundle\Form\Type\ModelListType;

final class BookAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'book_book';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'book/book';

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, $this->createLabeledListOptions('id'))
            ->addIdentifier('name', null, $this->createLabeledListOptions('name'))
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', null, $this->createLabeledFormOptions('name'))
            ->add('description', null, $this->createLabeledFormOptions('description'))
            ->add(
                'image',
                ModelListType::class,
                $this->createLabeledFormOptions('description'),
                [
                    'link_parameters' => [
                        'context' => 'bb__book__image',
                        'hide_context' => true,
                        'provider' => 'sonata.media.provider.image',
                    ],
                ]
            )
            ->add(
                'pdfDocument',
                null,
                $this->createLabeledFormOptions('pdfDocument'),
                [
                    'link_parameters' => [
                        'context' => 'bb__book__pdf_document',
                        'hide_context' => true,
                        'provider' => 'sonata.media.provider.file',
                    ],
                ]
            )
        ;
    }
}
