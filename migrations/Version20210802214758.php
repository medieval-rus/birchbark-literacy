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

final class Version20210802214758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removed old tables photos and drawings tables.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE bb__document_photo');
        $this->addSql('DROP TABLE bb__document_sketch');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__document_photo (document_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_12D2A80BC33F7837 (document_id), INDEX IDX_12D2A80BEA9FDD75 (media_id), PRIMARY KEY(document_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__document_sketch (document_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_497AAD1C33F7837 (document_id), INDEX IDX_497AAD1EA9FDD75 (media_id), PRIMARY KEY(document_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bb__document_photo ADD CONSTRAINT FK_12D2A80BC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_photo ADD CONSTRAINT FK_12D2A80BEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__document_sketch ADD CONSTRAINT FK_497AAD1C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_sketch ADD CONSTRAINT FK_497AAD1EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
    }
}
