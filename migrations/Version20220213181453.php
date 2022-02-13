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

final class Version20220213181453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fixed tables naming.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('RENAME TABLE content_element_category TO bb__content_element__content_category');
        $this->addSql('RENAME TABLE content_element_genre TO bb__content_element__genre');
        $this->addSql('RENAME TABLE content_element_language TO bb__content_element__language');
        $this->addSql('ALTER TABLE bb__content_element__content_category RENAME INDEX idx_d281169e86269cd6 TO IDX_B4382A1E86269CD6');
        $this->addSql('ALTER TABLE bb__content_element__content_category RENAME INDEX idx_d281169e416c3764 TO IDX_B4382A1E416C3764');
        $this->addSql('ALTER TABLE bb__content_element__genre RENAME INDEX idx_1ca8617086269cd6 TO IDX_E06B86C686269CD6');
        $this->addSql('ALTER TABLE bb__content_element__genre RENAME INDEX idx_1ca861704296d31f TO IDX_E06B86C64296D31F');
        $this->addSql('ALTER TABLE bb__content_element__language RENAME INDEX idx_167eea86269cd6 TO IDX_D2970A9586269CD6');
        $this->addSql('ALTER TABLE bb__content_element__language RENAME INDEX idx_167eea82f1baf4 TO IDX_D2970A9582F1BAF4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('RENAME TABLE bb__content_element__content_category TO content_element_category');
        $this->addSql('RENAME TABLE bb__content_element__genre TO content_element_genre');
        $this->addSql('RENAME TABLE bb__content_element__language TO content_element_language');
        $this->addSql('ALTER TABLE bb__content_element__content_category RENAME INDEX idx_b4382a1e86269cd6 TO IDX_D281169E86269CD6');
        $this->addSql('ALTER TABLE bb__content_element__content_category RENAME INDEX idx_b4382a1e416c3764 TO IDX_D281169E416C3764');
        $this->addSql('ALTER TABLE bb__content_element__genre RENAME INDEX idx_e06b86c64296d31f TO IDX_1CA861704296D31F');
        $this->addSql('ALTER TABLE bb__content_element__genre RENAME INDEX idx_e06b86c686269cd6 TO IDX_1CA8617086269CD6');
        $this->addSql('ALTER TABLE bb__content_element__language RENAME INDEX idx_d2970a9586269cd6 TO IDX_167EEA86269CD6');
        $this->addSql('ALTER TABLE bb__content_element__language RENAME INDEX idx_d2970a9582f1baf4 TO IDX_167EEA82F1BAF4');
    }
}
