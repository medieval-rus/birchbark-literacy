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

final class Version20210606003818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'New bibliographic record structure.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bibliography__bibliographic_record ADD displayed_value TEXT NULL');
        $this->addSql('UPDATE bibliography__bibliographic_record SET displayed_value = CONCAT(IF(authors = \'\', \'\', CONCAT(authors, \'. \')), title, \' // \', details)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record CHANGE displayed_value displayed_value TEXT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP authors');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP details');
    }
}
