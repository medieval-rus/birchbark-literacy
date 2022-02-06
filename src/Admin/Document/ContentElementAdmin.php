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

final class ContentElementAdmin extends AbstractEntityAdmin
{
    protected $baseRouteName = 'document_content_element';

    protected $baseRoutePattern = 'document/content-element';

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with($this->getSectionLabel('basicInformation'))
                ->add('description', null, $this->createFormOptions('description'))
                ->add('categories', null, $this->createManyToManyFormOptions('categories'))
                ->add('genres', null, $this->createManyToManyFormOptions('genres'))
                ->add('languages', null, $this->createManyToManyFormOptions('languages'))
                ->add('originalText', null, $this->createFormOptions('originalText', ['trim' => false]))
                ->add('translationRussian', null, $this->createFormOptions('translationRussian'))
                ->add('translationEnglishKovalev', null, $this->createFormOptions('translationEnglishKovalev'))
                ->add('translationEnglishSchaeken', null, $this->createFormOptions('translationEnglishSchaeken'))
            ->end()
        ;
    }
}
