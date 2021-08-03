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

final class Version20210801201510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'New data storage.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE media__file (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, media_type VARCHAR(255) NOT NULL, url VARCHAR(2048) DEFAULT NULL, description LONGTEXT DEFAULT NULL, binary_content MEDIUMBLOB DEFAULT NULL, hash CHAR(32) NOT NULL, metadata JSON DEFAULT NULL, UNIQUE INDEX UNIQ_CEA382BAD7DF1668 (file_name), UNIQUE INDEX UNIQ_CEA382BAD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE media__file');
    }
}
