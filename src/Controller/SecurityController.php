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

namespace App\Controller;

use App\Form\Document\DocumentsSearchType;
use App\Form\Security\AdminLoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security__login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('admin');
        }

        return $this->render(
            'site/security/login.html.twig',
            [
                'translationContext' => 'controller.security.login',
                'assetsContext' => 'security/login',
                'documentsSearchForm' => $this->createForm(DocumentsSearchType::class)->createView(),
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
                'form' => $this
                    ->createForm(
                        AdminLoginForm::class,
                        [
                            'username' => $authenticationUtils->getLastUsername(),
                        ]
                    )
                    ->createView(),
            ]
        );
    }

    /**
     * @Route("/logout", name="security__logout")
     */
    public function logout(): void
    {
    }
}
