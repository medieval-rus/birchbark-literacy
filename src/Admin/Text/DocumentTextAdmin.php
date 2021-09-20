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
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\Form\Type\CollectionType;

final class DocumentTextAdmin extends AbstractEntityAdmin
{
    protected $baseRouteName = 'text_document';

    protected $baseRoutePattern = 'text/document';

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('number', null, $this->createLabeledListOptions('number'))
            ->add('town.name', null, $this->createLabeledListOptions('town.name'))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with($this->getSectionLabel('texts'))
                ->add(
                    'contentElements',
                    CollectionType::class,
                    $this->createLabeledFormOptions('contentElements', ['required' => false]),
                    [
                        'edit' => 'inline',
                        'inline' => 'table',
                        'admin_code' => 'admin.content_element_text',
                    ]
                )
            ->end()
        ;
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, AdminInterface $childAdmin = null): void
    {
        if ('edit' === $action || null !== $childAdmin) {
            $admin = $this->isChild() ? $this->getParent() : $this;

            if ((null !== $document = $this->getSubject()) && (null !== $document->getId())) {
                $menu->addChild(
                    'tabMenu.document.viewOnSite',
                    [
                        'uri' => $admin->getRouteGenerator()->generate(
                            'document__show',
                            [
                                'town' => urlencode($document->getTown()->getAlias()),
                                'number' => urlencode($document->getNumber()),
                            ]
                        ),
                        'linkAttributes' => [
                            'target' => '_blank',
                        ],
                    ]
                );
            }
        }
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->clearExcept(['list', 'edit']);
    }

    protected function getEntityKey(): string
    {
        return 'documentText';
    }
}
