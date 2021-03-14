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
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class DocumentAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'document_document';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'document/document';

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
            ->tab($this->getTabLabel('basicInformation'))
                ->with($this->getSectionLabel('basicInformation'), ['class' => 'col-md-6'])
                    ->add('number', null, $this->createLabeledFormOptions('number'))
                    ->add('town', null, $this->createLabeledFormOptions('town'))
                    ->add(
                        'isShownOnSite',
                        CheckboxType::class,
                        $this->createLabeledFormOptions('isShownOnSite', ['required' => false])
                    )
                    ->add('scribe', null, $this->createLabeledFormOptions('scribe'))
                    ->add('stateOfPreservation', null, $this->createLabeledFormOptions('stateOfPreservation'))
                    ->add('wayOfWriting', null, $this->createLabeledFormOptions('wayOfWriting'))
                ->end()
                ->with($this->getSectionLabel('dates'), ['class' => 'col-md-6'])
                    ->add('conventionalDate', null, $this->createLabeledFormOptions('conventionalDate'))
                    ->add(
                        'isConventionalDateBiasedBackward',
                        CheckboxType::class,
                        $this->createLabeledFormOptions('isConventionalDateBiasedBackward', ['required' => false])
                    )
                    ->add(
                        'isConventionalDateBiasedForward',
                        CheckboxType::class,
                        $this->createLabeledFormOptions('isConventionalDateBiasedForward', ['required' => false])
                    )
                    ->add('stratigraphicalDate', null, $this->createLabeledFormOptions('stratigraphicalDate'))
                    ->add('nonStratigraphicalDate', null, $this->createLabeledFormOptions('nonStratigraphicalDate'))
                ->end()
            ->end()
//            ->tab('form.tab.sources')
//                ->with('form.group.sources')
//                    ->add('literature', ModelType::class, [
//                        'required' => false,
//                        'class' => Record::class,
//                        'property' => 'shortName',
//                        'label' => 'birchBarkDocument.literature',
//                        'multiple' => true,
//                    ], [
//                        'admin_code' => 'bibl.admin.bibliography.record',
//                    ])
//                    ->add('dndSection', TextType::class, array(
//                        'required' => false,
//                        'label' => 'birchBarkDocument.dndSection'
//                    ))
//                    ->add('ngbVolume', TextType::class, [
//                        'required' => false,
//                        'label' => 'birchBarkDocument.ngbVolume',
//                    ])
//                ->end()
//            ->end()

//            ->tab('form.tab.photosAndSketches')
//                ->with('form.group.photos', ['class' => 'col-md-6'])
//                    ->add('photos', CollectionType::class, [
//                        'required' => false,
//                        'label' => 'birchBarkDocument.photos',
//                        'btn_add' => 'button.add.photo',
//                        'btn_catalogue' => 'DocumentAdmin',
//                    ], [
//                        'edit' => 'inline',
//                        'inline' => 'table',
//                        'sortable' => 'position',
//                        'admin_code' => 'bbd.admin.birch_bark.photo',
//                    ])
//                ->end()
//                ->with('form.group.sketches', ['class' => 'col-md-6'])
//                    ->add('sketches', CollectionType::class, [
//                        'required' => false,
//                        'label' => 'birchBarkDocument.sketches',
//                        'btn_add' => 'button.add.sketch',
//                        'btn_catalogue' => 'DocumentAdmin',
//                    ], [
//                        'edit' => 'inline',
//                        'inline' => 'table',
//                        'sortable' => 'position',
//                        'admin_code' => 'bbd.admin.birch_bark.sketch',
//                    ])
//                ->end()
//            ->end()
        ;
    }

    /**
     * @param string $action
     */
    protected function configureTabMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null): void
    {
        if ('edit' === $action || null !== $childAdmin) {
            $admin = $this->isChild() ? $this->getParent() : $this;

            if ((null !== $document = $this->getSubject()) && (null !== $document->getId())) {
                $menu->addChild('tabMenu.document.viewOnSite', [
                    'uri' => $admin->getRouteGenerator()->generate('index', [
                        'town' => urlencode($document->getTown()->getAlias()),
                        'number' => urlencode($document->getNumber()),
                    ]),
                ]);
            }
        }
    }
}
