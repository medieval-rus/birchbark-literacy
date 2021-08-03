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

final class Version20210314024155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renamed ConditionalDate to ConventionalDate.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7CA5270C65');
        $this->addSql('DROP INDEX IDX_EFBB1D7CA5270C65 ON bb__document');
        $this->addSql('ALTER TABLE bb__document CHANGE is_conditional_date_biased_backward is_conventional_date_biased_backward TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE bb__document CHANGE is_conditional_date_biased_forward is_conventional_date_biased_forward TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE bb__document CHANGE conditional_date_cell_id conventional_date_cell_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7C9F95AAC5 FOREIGN KEY (conventional_date_cell_id) REFERENCES bb__conditional_date_cell (id)');
        $this->addSql('CREATE INDEX IDX_EFBB1D7C9F95AAC5 ON bb__document (conventional_date_cell_id)');
    }
}
