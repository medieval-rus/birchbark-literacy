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

namespace App\Admin\Bibliography;

use App\Admin\AbstractEntityAdmin;
use App\DataStorage\DataStorageManagerInterface;
use Sonata\AdminBundle\Form\FormMapper;

final class FileSupplementAdmin extends AbstractEntityAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'bibliography_file_supplement';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'bibliography/file-supplement';

    /**
     * @var DataStorageManagerInterface
     */
    private $dataStorageManager;

    public function __construct(
        string $code,
        string $class,
        string $baseControllerName,
        DataStorageManagerInterface $dataStorageManager
    ) {
        parent::__construct($code, $class, $baseControllerName);

        $this->dataStorageManager = $dataStorageManager;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with($this->getSectionLabel('common'))
                ->add('document', null, $this->createLabeledFormOptions('document'))
                ->add(
                    'file',
                    null,
                    $this->createLabeledFormOptions(
                        'file',
                        [
                            'choice_filter' => $this
                                ->dataStorageManager
                                ->getFolderFilter('bibliography_file_supplement'),
                            'query_builder' => $this
                                ->dataStorageManager
                                ->getQueryBuilder(),
                        ]
                    )
                )
            ->end()
        ;
    }
}
