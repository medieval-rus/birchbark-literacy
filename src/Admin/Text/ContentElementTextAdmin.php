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

namespace App\Admin\Text;

use App\Admin\AbstractEntityAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class ContentElementTextAdmin extends AbstractEntityAdmin
{
    protected $baseRouteName = 'text_content_element';

    protected $baseRoutePattern = 'text/content-element';

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with($this->getSectionLabel('texts'))
                ->add('originalText', null, $this->createLabeledFormOptions('originalText', ['trim' => false]))
                ->add('translatedText', null, $this->createLabeledFormOptions('translatedText'))
            ->end()
        ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->clearExcept(['create', 'edit']);
    }

    protected function getEntityKey(): string
    {
        return 'contentElementText';
    }
}
