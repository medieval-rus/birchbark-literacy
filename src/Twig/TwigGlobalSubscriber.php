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

namespace App\Twig;

use App\Form\Document\DocumentsSearchType;
use App\Repository\Content\PostRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

final class TwigGlobalSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private PostRepository $postRepository;
    private FormFactoryInterface $formFactory;

    public function __construct(Environment $twig, PostRepository $postRepository, FormFactoryInterface $formFactory)
    {
        $this->twig = $twig;
        $this->postRepository = $postRepository;
        $this->formFactory = $formFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'injectGlobalVariables',
        ];
    }

    public function injectGlobalVariables(): void
    {
        $this->twig->addGlobal('footer', $this->postRepository->findFooter());
        $this->twig->addGlobal('documentsSearchForm', $this->formFactory->create(DocumentsSearchType::class)->createView());
    }
}
