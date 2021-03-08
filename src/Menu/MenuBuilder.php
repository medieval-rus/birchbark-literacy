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
            ->setChildrenAttribute('class', 'nav flex-column mr-nav')
        ;

        $menu
            ->addChild('page.menu.index', ['route' => 'index'])
        ;

        $menu
            ->addChild('page.menu.about', ['route' => 'information__about'])
        ;

        $menu
            ->addChild('page.menu.news', ['route' => 'information__news'])
        ;

        $menu
            ->addChild('page.menu.library', ['route' => 'library_book_list'])->setCurrent(
                \in_array(
                    $currentRoute,
                    [
                        'library_book_list',
                        'library_book_show',
                        'library_bibliography_list',
                    ],
                    true
                )
            )
        ;

        $menu
            ->addChild('page.menu.maps', ['route' => 'maps_index'])
            ->setCurrent(
                \in_array(
                    $currentRoute,
                    [
                        'maps_index',
                        'maps_towns',
                        'maps_excavations',
                    ],
                    true
                )
            )
        ;

        foreach ($menu->getChildren() as $child) {
            $child
                ->setAttribute('class', 'nav-item mr-nav-item')
                ->setLinkAttribute('class', 'nav-link mr-nav-link')
            ;
        }

        return $menu;
    }
}
