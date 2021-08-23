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

final class Version20210823031313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Reworked books and bibliographic records.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__book_part DROP FOREIGN KEY FK_B070004716A2B381');
        $this->addSql('CREATE TABLE bibliography__file_supplement (id INT AUTO_INCREMENT NOT NULL, bibliographic_record_id INT NOT NULL, document_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_57C157A7ACA5D3F (bibliographic_record_id), INDEX IDX_57C157AC33F7837 (document_id), INDEX IDX_57C157A93CB796C (file_id), UNIQUE INDEX file_is_unique_within_document (document_id, file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliography__structural_component (id INT AUTO_INCREMENT NOT NULL, bibliographic_record_id INT NOT NULL, file_id INT DEFAULT NULL, position INT NOT NULL, level INT DEFAULT 1 NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_690050EE7ACA5D3F (bibliographic_record_id), UNIQUE INDEX UNIQ_690050EE93CB796C (file_id), UNIQUE INDEX file_is_unique_within_bibliographic_record (bibliographic_record_id, file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bibliography__file_supplement ADD CONSTRAINT FK_57C157A7ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id)');
        $this->addSql('ALTER TABLE bibliography__file_supplement ADD CONSTRAINT FK_57C157AC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bibliography__file_supplement ADD CONSTRAINT FK_57C157A93CB796C FOREIGN KEY (file_id) REFERENCES media__file (id)');
        $this->addSql('ALTER TABLE bibliography__structural_component ADD CONSTRAINT FK_690050EE7ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id)');
        $this->addSql('ALTER TABLE bibliography__structural_component ADD CONSTRAINT FK_690050EE93CB796C FOREIGN KEY (file_id) REFERENCES media__file (id)');
        $this->addSql('DROP TABLE bb__book');
        $this->addSql('DROP TABLE bb__book_part');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record ADD main_file_id INT DEFAULT NULL, ADD main_image_id INT DEFAULT NULL, ADD label TEXT DEFAULT NULL, ADD description TEXT DEFAULT NULL, CHANGE displayed_value formal_notation TEXT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record ADD CONSTRAINT FK_A98E1C236780D085 FOREIGN KEY (main_file_id) REFERENCES media__file (id)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record ADD CONSTRAINT FK_A98E1C23E4873418 FOREIGN KEY (main_image_id) REFERENCES media__file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A98E1C236780D085 ON bibliography__bibliographic_record (main_file_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A98E1C23E4873418 ON bibliography__bibliographic_record (main_image_id)');
        $this->addSql('DROP INDEX UNIQ_2EC905B5E237E06 ON bibliography__references_list');
        $this->addSql('ALTER TABLE bibliography__references_list_item DROP FOREIGN KEY FK_E3EFD6F1B8550050');
        $this->addSql('DROP INDEX IDX_2273013DB8550050 ON bibliography__references_list_item');
        $this->addSql('DROP INDEX bibliographic_record_list_has_bibliographic_record ON bibliography__references_list_item');
        $this->addSql('ALTER TABLE bibliography__references_list_item CHANGE bibliographic_record_list_id references_list_id INT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__references_list_item ADD CONSTRAINT FK_2273013DF529DD3E FOREIGN KEY (references_list_id) REFERENCES bibliography__references_list (id)');
        $this->addSql('CREATE INDEX IDX_2273013DF529DD3E ON bibliography__references_list_item (references_list_id)');
        $this->addSql('CREATE UNIQUE INDEX record_is_unique_within_list ON bibliography__references_list_item (references_list_id, bibliographic_record_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__book (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, pdf_document_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_46FFBB763DA5256D (image_id), UNIQUE INDEX UNIQ_46FFBB76CBFD05C (pdf_document_id), UNIQUE INDEX UNIQ_46FFBB765E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__book_part (id INT AUTO_INCREMENT NOT NULL, pdf_document_id INT DEFAULT NULL, book_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_B070004716A2B381 (book_id), UNIQUE INDEX UNIQ_B0700047CBFD05C (pdf_document_id), UNIQUE INDEX name__book (name, book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bb__book ADD CONSTRAINT FK_46FFBB763DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__book ADD CONSTRAINT FK_46FFBB76CBFD05C FOREIGN KEY (pdf_document_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__book_part ADD CONSTRAINT FK_B070004716A2B381 FOREIGN KEY (book_id) REFERENCES bb__book (id)');
        $this->addSql('ALTER TABLE bb__book_part ADD CONSTRAINT FK_B0700047CBFD05C FOREIGN KEY (pdf_document_id) REFERENCES media__media (id)');
        $this->addSql('DROP TABLE bibliography__file_supplement');
        $this->addSql('DROP TABLE bibliography__structural_component');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP FOREIGN KEY FK_A98E1C236780D085');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP FOREIGN KEY FK_A98E1C23E4873418');
        $this->addSql('DROP INDEX UNIQ_A98E1C236780D085 ON bibliography__bibliographic_record');
        $this->addSql('DROP INDEX UNIQ_A98E1C23E4873418 ON bibliography__bibliographic_record');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP main_file_id, DROP main_image_id, DROP label, DROP description, CHANGE formal_notation displayed_value TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2EC905B5E237E06 ON bibliography__references_list (name)');
        $this->addSql('ALTER TABLE bibliography__references_list_item DROP FOREIGN KEY FK_2273013DF529DD3E');
        $this->addSql('DROP INDEX IDX_2273013DF529DD3E ON bibliography__references_list_item');
        $this->addSql('DROP INDEX record_is_unique_within_list ON bibliography__references_list_item');
        $this->addSql('ALTER TABLE bibliography__references_list_item CHANGE references_list_id bibliographic_record_list_id INT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__references_list_item ADD CONSTRAINT FK_E3EFD6F1B8550050 FOREIGN KEY (bibliographic_record_list_id) REFERENCES bibliography__references_list (id)');
        $this->addSql('CREATE INDEX IDX_2273013DB8550050 ON bibliography__references_list_item (bibliographic_record_list_id)');
        $this->addSql('CREATE UNIQUE INDEX bibliographic_record_list_has_bibliographic_record ON bibliography__references_list_item (bibliographic_record_list_id, bibliographic_record_id)');
    }
}
