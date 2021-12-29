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
use App\DataStorage\DataStorageManagerInterface;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class DocumentAdmin extends AbstractEntityAdmin
{
    protected $baseRouteName = 'document_document';

    protected $baseRoutePattern = 'document/document';

    private DataStorageManagerInterface $dataStorageManager;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        DataStorageManagerInterface $dataStorageManager
    ) {
        parent::__construct($code, $class, $baseControllerName);

        $this->dataStorageManager = $dataStorageManager;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('number', null, $this->createListOptions('number'))
            ->add('town.name', null, $this->createListOptions('town.name'))
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab($this->getTabLabel('basicInformation'))
                ->with($this->getSectionLabel('basicInformation'), ['class' => 'col-md-6'])
                    ->add('number', null, $this->createFormOptions('number'))
                    ->add('town', null, $this->createFormOptions('town'))
                    ->add(
                        'isShownOnSite',
                        CheckboxType::class,
                        $this->createFormOptions('isShownOnSite', ['required' => false])
                    )
                    ->add(
                        'isPartOfCorpus',
                        CheckboxType::class,
                        $this->createFormOptions('isPartOfCorpus', ['required' => false])
                    )
                    ->add(
                        'isPreliminaryPublication',
                        CheckboxType::class,
                        $this->createFormOptions('isPreliminaryPublication', ['required' => false])
                    )
                    ->add('scribe', null, $this->createFormOptions('scribe'))
                    ->add('stateOfPreservation', null, $this->createFormOptions('stateOfPreservation'))
                    ->add('wayOfWriting', null, $this->createFormOptions('wayOfWriting'))
                ->end()
                ->with($this->getSectionLabel('dates'), ['class' => 'col-md-6'])
                    ->add('conventionalDate', null, $this->createFormOptions('conventionalDate'))
                    ->add(
                        'isConventionalDateBiasedBackward',
                        CheckboxType::class,
                        $this->createFormOptions('isConventionalDateBiasedBackward', ['required' => false])
                    )
                    ->add(
                        'isConventionalDateBiasedForward',
                        CheckboxType::class,
                        $this->createFormOptions('isConventionalDateBiasedForward', ['required' => false])
                    )
                    ->add('stratigraphicalDate', null, $this->createFormOptions('stratigraphicalDate'))
                    ->add('nonStratigraphicalDate', null, $this->createFormOptions('nonStratigraphicalDate'))
                ->end()
            ->end()
            ->tab($this->getTabLabel('contentElements'))
                ->with($this->getSectionLabel('contentElements'))
                    ->add(
                        'contentElements',
                        CollectionType::class,
                        $this->createFormOptions('contentElements', ['required' => false]),
                        [
                            'edit' => 'inline',
                            'admin_code' => 'admin.content_element',
                        ]
                    )
                ->end()
            ->end()
            ->tab($this->getTabLabel('materialElements'))
                ->with($this->getSectionLabel('materialElements'))
                    ->add(
                        'materialElements',
                        CollectionType::class,
                        $this->createFormOptions('materialElements', ['required' => false]),
                        [
                            'edit' => 'inline',
                        ]
                    )
                ->end()
            ->end()
            ->tab($this->getTabLabel('sources'))
                ->with($this->getSectionLabel('sources'))
                    ->add('dndVolumes', null, $this->createManyToManyFormOptions('dndVolumes'))
                    ->add('ngbVolumes', null, $this->createManyToManyFormOptions('ngbVolumes'))
                    ->add('literature', null, $this->createManyToManyFormOptions('literature'))
                ->end()
            ->end()
            ->tab($this->getTabLabel('media'))
                ->with($this->getSectionLabel('photos'), ['class' => 'col-md-6'])
                    ->add(
                        'photos',
                        null,
                        $this->createManyToManyFormOptions(
                            'photos',
                            [
                                'choice_filter' => $this->dataStorageManager->getFolderFilter('photo'),
                                'query_builder' => $this->dataStorageManager->getQueryBuilder(),
                            ]
                        )
                    )
                ->end()
                ->with($this->getSectionLabel('drawings'), ['class' => 'col-md-6'])
                    ->add(
                        'drawings',
                        null,
                        $this->createManyToManyFormOptions(
                            'drawings',
                            [
                                'choice_filter' => $this->dataStorageManager->getFolderFilter('drawing'),
                                'query_builder' => $this->dataStorageManager->getQueryBuilder(),
                            ]
                        )
                    )
                ->end()
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
}
