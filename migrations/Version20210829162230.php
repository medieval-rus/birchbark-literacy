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

final class Version20210829162230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'New literature structure.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__document_bibliographic_record_dnd (document_id INT NOT NULL, bibliographic_record_id INT NOT NULL, INDEX IDX_F987788AC33F7837 (document_id), INDEX IDX_F987788A7ACA5D3F (bibliographic_record_id), PRIMARY KEY(document_id, bibliographic_record_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__document_bibliographic_record_ngb (document_id INT NOT NULL, bibliographic_record_id INT NOT NULL, INDEX IDX_CCB1E320C33F7837 (document_id), INDEX IDX_CCB1E3207ACA5D3F (bibliographic_record_id), PRIMARY KEY(document_id, bibliographic_record_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record_dnd ADD CONSTRAINT FK_F987788AC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record_dnd ADD CONSTRAINT FK_F987788A7ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record_ngb ADD CONSTRAINT FK_CCB1E320C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_bibliographic_record_ngb ADD CONSTRAINT FK_CCB1E3207ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bb__document_bibliographic_record_dnd');
        $this->addSql('DROP TABLE bb__document_bibliographic_record_ngb');
    }
}
