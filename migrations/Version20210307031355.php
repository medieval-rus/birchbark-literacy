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

final class Version20210307031355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bibliography: data structure of VyfonyBibliographyBundle.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('RENAME TABLE bibliography__authorship TO bibliography__bibliographic_record_author');
        $this->addSql('RENAME TABLE bibliography__record TO bibliography__bibliographic_record');
        $this->addSql('RENAME TABLE bibliography__references_list__item TO bibliography__references_list_item');
        $this->addSql('RENAME TABLE bibliography__references_list__references_list TO bibliography__references_list');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record CHANGE title title TEXT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record RENAME INDEX uniq_3a8e6ae83ee4b093 TO UNIQ_A98E1C233EE4B093');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author DROP FOREIGN KEY FK_26A68B447ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author DROP FOREIGN KEY FK_26A68B44F675F31B');
        $this->addSql('DROP INDEX UNIQ_4CDFAD337ACA5D3FF675F31B ON bibliography__bibliographic_record_author');
        $this->addSql('DROP INDEX UNIQ_4CDFAD337ACA5D3F462CE4F5 ON bibliography__bibliographic_record_author');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author DROP id, DROP position, CHANGE bibliographic_record_id bibliographic_record_id INT NOT NULL, CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author ADD CONSTRAINT FK_F06886D67ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author ADD CONSTRAINT FK_F06886D6F675F31B FOREIGN KEY (author_id) REFERENCES bibliography__author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author ADD PRIMARY KEY (bibliographic_record_id, author_id)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author RENAME INDEX idx_4cdfad337aca5d3f TO IDX_F06886D67ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author RENAME INDEX idx_4cdfad33f675f31b TO IDX_F06886D6F675F31B');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2EC905B5E237E06 ON bibliography__references_list (name)');
        $this->addSql('ALTER TABLE bibliography__references_list_item CHANGE bibliographic_record_list_id bibliographic_record_list_id INT NOT NULL, CHANGE bibliographic_record_id bibliographic_record_id INT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__references_list_item RENAME INDEX idx_e0644c53b8550050 TO IDX_2273013DB8550050');
        $this->addSql('ALTER TABLE bibliography__references_list_item RENAME INDEX idx_e0644c537aca5d3f TO IDX_2273013D7ACA5D3F');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bibliography__bibliographic_record CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record RENAME INDEX uniq_a98e1c233ee4b093 TO UNIQ_3A8E6AE83EE4B093');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author DROP FOREIGN KEY FK_F06886D67ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author DROP FOREIGN KEY FK_F06886D6F675F31B');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author ADD id INT AUTO_INCREMENT NOT NULL, ADD position INT NOT NULL, CHANGE bibliographic_record_id bibliographic_record_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author ADD CONSTRAINT FK_26A68B447ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author ADD CONSTRAINT FK_26A68B44F675F31B FOREIGN KEY (author_id) REFERENCES bibliography__author (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CDFAD337ACA5D3FF675F31B ON bibliography__bibliographic_record_author (bibliographic_record_id, author_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CDFAD337ACA5D3F462CE4F5 ON bibliography__bibliographic_record_author (bibliographic_record_id, position)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author RENAME INDEX idx_f06886d6f675f31b TO IDX_4CDFAD33F675F31B');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record_author RENAME INDEX idx_f06886d67aca5d3f TO IDX_4CDFAD337ACA5D3F');
        $this->addSql('DROP INDEX UNIQ_2EC905B5E237E06 ON bibliography__references_list');
        $this->addSql('ALTER TABLE bibliography__references_list_item CHANGE bibliographic_record_list_id bibliographic_record_list_id INT DEFAULT NULL, CHANGE bibliographic_record_id bibliographic_record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bibliography__references_list_item RENAME INDEX idx_2273013d7aca5d3f TO IDX_E0644C537ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__references_list_item RENAME INDEX idx_2273013db8550050 TO IDX_E0644C53B8550050');
        $this->addSql('RENAME TABLE bibliography__bibliographic_record_author TO bibliography__authorship');
        $this->addSql('RENAME TABLE bibliography__bibliographic_record TO bibliography__record');
        $this->addSql('RENAME TABLE bibliography__references_list_item TO bibliography__references_list__item');
        $this->addSql('RENAME TABLE bibliography__references_list TO bibliography__references_list__references_list');
    }
}
