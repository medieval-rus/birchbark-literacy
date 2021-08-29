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

final class Version20210829154251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renamed table fields.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__document_bibliographic_record DROP FOREIGN KEY FK_EF42C224ED389BD8');
        $this->addSql('DROP INDEX IDX_1F8CBB63ED389BD8 ON bb__document_bibliographic_record');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record CHANGE birch_bark_document_id document_id INT NOT NULL');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record ADD CONSTRAINT FK_A43BEC5C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('CREATE INDEX IDX_A43BEC5C33F7837 ON bb__document_bibliographic_record (document_id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record ADD PRIMARY KEY (document_id, bibliographic_record_id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record RENAME INDEX idx_1f8cbb637aca5d3f TO IDX_A43BEC57ACA5D3F');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__document_bibliographic_record DROP FOREIGN KEY FK_A43BEC5C33F7837');
        $this->addSql('DROP INDEX IDX_A43BEC5C33F7837 ON bb__document_bibliographic_record');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record CHANGE document_id birch_bark_document_id INT NOT NULL');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record ADD CONSTRAINT FK_EF42C224ED389BD8 FOREIGN KEY (birch_bark_document_id) REFERENCES bb__document (id)');
        $this->addSql('CREATE INDEX IDX_1F8CBB63ED389BD8 ON bb__document_bibliographic_record (birch_bark_document_id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record ADD PRIMARY KEY (birch_bark_document_id, bibliographic_record_id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record RENAME INDEX idx_a43bec57aca5d3f TO IDX_1F8CBB637ACA5D3F');
    }
}
