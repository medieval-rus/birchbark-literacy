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

final class Version20210824195020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Dropped amendments.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE bb__amendment');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__amendment (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, birch_bark_document_id INT NOT NULL, ngb_volume VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_AA990656ED389BD8 (birch_bark_document_id), UNIQUE INDEX ngb_volume__bb_document (ngb_volume, birch_bark_document_id), UNIQUE INDEX UNIQ_AA990656EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bb__amendment ADD CONSTRAINT FK_AA990656EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__amendment ADD CONSTRAINT FK_AA990656ED389BD8 FOREIGN KEY (birch_bark_document_id) REFERENCES bb__document (id)');
    }
}
