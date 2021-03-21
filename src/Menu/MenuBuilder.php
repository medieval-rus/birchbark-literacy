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

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(FactoryInterface $factory, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->requestStack = $requestStack;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->get('_route');

        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'list-unstyled mr-sidebar')
        ;

        $menu
            ->addChild('page.menu.index', ['route' => 'index'])
        ;

        $menu
            ->addChild('page.menu.aboutSite', ['route' => 'information__about_site'])
        ;

        $menu
            ->addChild('page.menu.aboutSource', ['route' => 'information__about_source'])
            ->setCurrent(
                \in_array(
                    $currentRoute,
                    [
                        'information__about_source',
                        'information__about_source__general_information',
                    ],
                    true
                )
            )
        ;

        $menu
            ->addChild('page.menu.news', ['route' => 'information__news'])
        ;

        $menu
            ->addChild('page.menu.dataBase', ['route' => 'document__list'])
            ->setCurrent(
                \in_array(
                    $currentRoute,
                    [
                        'document__list',
                        'document__show',
                        'document__search',
                    ],
                    true
                )
            )
        ;

        $menu
            ->addChild('page.menu.library', ['route' => 'library__book_list'])
            ->setCurrent(
                \in_array(
                    $currentRoute,
                    [
                        'library__book_list',
                        'library__book_show',
                        'library__bibliography_list',
                    ],
                    true
                )
            )
        ;

        $menu
            ->addChild('page.menu.maps', ['route' => 'maps__index'])
            ->setCurrent(
                \in_array(
                    $currentRoute,
                    [
                        'maps__index',
                        'maps__towns',
                        'maps__excavations',
                    ],
                    true
                )
            )
        ;

        return $menu;
    }
}
