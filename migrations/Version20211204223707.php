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

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211204223707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Material element refactoring: part 3.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('RENAME TABLE bb__material_element__find__estate TO bb__estate');
        $this->addSql('RENAME TABLE bb__material_element__find__square TO bb__square');
        $this->addSql('RENAME TABLE bb__material_element__find__stratum TO bb__stratum');
        $this->addSql('RENAME TABLE bb__material_element__find__street TO bb__street');
        $this->addSql('RENAME TABLE bb__material_element__find__excavation TO bb__excavation');
        $this->addSql('RENAME TABLE bb__material_element__material TO bb__material');
        $this->addSql('RENAME TABLE bb__material_element__storage_place TO bb__storage_place');
        $this->addSql('RENAME TABLE bibliography__bibliographic_record_author TO bibliography__bibliographic_record__author');
        $this->addSql('RENAME TABLE post TO content__post');
        $this->addSql('RENAME TABLE user TO security__user');
        $this->addSql('RENAME TABLE bb__ordered_list__ordered_list TO bb__document_list');
        $this->addSql('RENAME TABLE bb__ordered_list__item TO bb__document_list__item');
        $this->addSql('RENAME TABLE bb__content_element__category TO bb__content_category');
        $this->addSql('RENAME TABLE bb__content_element__content_element TO bb__content_element');
        $this->addSql('RENAME TABLE bb__content_element__genre TO bb__genre');
        $this->addSql('RENAME TABLE bb__content_element__language TO bb__language');
        $this->addSql('RENAME TABLE bb__document_bibliographic_record_dnd TO bb__document__bibliographic_record__dnd');
        $this->addSql('RENAME TABLE bb__document_bibliographic_record_ngb TO bb__document__bibliographic_record__ngb');
        $this->addSql('RENAME TABLE bb__document_bibliographic_record TO bb__document__bibliographic_record');
        $this->addSql('RENAME TABLE bb__document_photos TO bb__document__photos');
        $this->addSql('RENAME TABLE bb__document_drawings TO bb__document__drawings');
        $this->addSql('RENAME TABLE bibliography__references_list_item TO bibliography__references_list__item');
    }
}
