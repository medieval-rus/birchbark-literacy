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

final class Version20210305223454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial data structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__amendment (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, birch_bark_document_id INT NOT NULL, ngb_volume VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AA990656EA9FDD75 (media_id), INDEX IDX_AA990656ED389BD8 (birch_bark_document_id), UNIQUE INDEX ngb_volume__bb_document (ngb_volume, birch_bark_document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__book (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, pdf_document_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_46FFBB765E237E06 (name), UNIQUE INDEX UNIQ_46FFBB763DA5256D (image_id), UNIQUE INDEX UNIQ_46FFBB76CBFD05C (pdf_document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__book_part (id INT AUTO_INCREMENT NOT NULL, pdf_document_id INT DEFAULT NULL, book_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B0700047CBFD05C (pdf_document_id), INDEX IDX_B070004716A2B381 (book_id), UNIQUE INDEX name__book (name, book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__conditional_date_cell (id INT AUTO_INCREMENT NOT NULL, initial_year INT NOT NULL, final_year INT NOT NULL, UNIQUE INDEX UNIQ_495C891AF1E7577A (initial_year), UNIQUE INDEX UNIQ_495C891A78A0E576 (final_year), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__content_element__category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62E15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__content_element__content_element (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, language_id INT DEFAULT NULL, document_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, original_text TEXT DEFAULT NULL, translated_text TEXT DEFAULT NULL, INDEX IDX_8878A98012469DE2 (category_id), INDEX IDX_8878A9804296D31F (genre_id), INDEX IDX_8878A98082F1BAF4 (language_id), INDEX IDX_8878A980C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__content_element__genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E06B86C65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__content_element__language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D2970A955E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__dnd_section (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2278DE025E237E06 (name), INDEX IDX_2278DE02EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__document (id INT AUTO_INCREMENT NOT NULL, town_id INT NOT NULL, scribe_id INT DEFAULT NULL, state_of_preservation_id INT DEFAULT NULL, conditional_date_cell_id INT DEFAULT NULL, way_of_writing_id INT NOT NULL, dnd_section_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, is_shown_on_site TINYINT(1) DEFAULT \'0\' NOT NULL, is_preliminary_publication TINYINT(1) DEFAULT \'1\' NOT NULL, is_conditional_date_biased_backward TINYINT(1) DEFAULT \'0\' NOT NULL, is_conditional_date_biased_forward TINYINT(1) DEFAULT \'0\' NOT NULL, stratigraphical_date VARCHAR(255) DEFAULT NULL, non_stratigraphical_date VARCHAR(255) DEFAULT NULL, ngb_volume VARCHAR(255) DEFAULT NULL, INDEX IDX_EFBB1D7C75E23604 (town_id), INDEX IDX_EFBB1D7C5345EC48 (scribe_id), INDEX IDX_EFBB1D7CC02AF735 (state_of_preservation_id), INDEX IDX_EFBB1D7CA5270C65 (conditional_date_cell_id), INDEX IDX_EFBB1D7C35B07C66 (way_of_writing_id), INDEX IDX_EFBB1D7C4B645A82 (dnd_section_id), UNIQUE INDEX town__number (town_id, number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__document_record (birch_bark_document_id INT NOT NULL, bibliographic_record_id INT NOT NULL, INDEX IDX_1F8CBB63ED389BD8 (birch_bark_document_id), INDEX IDX_1F8CBB637ACA5D3F (bibliographic_record_id), PRIMARY KEY(birch_bark_document_id, bibliographic_record_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__document_photo (document_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_12D2A80BC33F7837 (document_id), INDEX IDX_12D2A80BEA9FDD75 (media_id), PRIMARY KEY(document_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__document_sketch (document_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_497AAD1C33F7837 (document_id), INDEX IDX_497AAD1EA9FDD75 (media_id), PRIMARY KEY(document_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element (id INT AUTO_INCREMENT NOT NULL, storage_place_id INT DEFAULT NULL, material_id INT NOT NULL, find_id INT NOT NULL, document_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, parts_count INT DEFAULT NULL, comment TEXT DEFAULT NULL, length NUMERIC(65, 2) DEFAULT NULL, inner_length NUMERIC(65, 2) DEFAULT NULL, width NUMERIC(65, 2) DEFAULT NULL, inner_width NUMERIC(65, 2) DEFAULT NULL, diameter NUMERIC(65, 2) DEFAULT NULL, INDEX IDX_782085EAE6D9E5E (storage_place_id), INDEX IDX_782085EE308AC6F (material_id), UNIQUE INDEX UNIQ_782085E51B74D69 (find_id), INDEX IDX_782085EC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find (id INT AUTO_INCREMENT NOT NULL, find_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__accidental (id INT NOT NULL, comment TEXT DEFAULT NULL, year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__archaeological (id INT NOT NULL, excavation_id INT DEFAULT NULL, relation_to_estates_id INT NOT NULL, relation_to_squares_id INT NOT NULL, relation_to_strata_id INT NOT NULL, initial_tier INT DEFAULT NULL, final_tier INT DEFAULT NULL, comment_on_tiers VARCHAR(255) DEFAULT NULL, initial_depth NUMERIC(65, 2) DEFAULT NULL, final_depth NUMERIC(65, 2) DEFAULT NULL, comment_on_depths VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, INDEX IDX_BE304CF38428BEC (excavation_id), UNIQUE INDEX UNIQ_BE304CF6BFE634A (relation_to_estates_id), UNIQUE INDEX UNIQ_BE304CF754B59E0 (relation_to_squares_id), UNIQUE INDEX UNIQ_BE304CF650F0CD1 (relation_to_strata_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__estate (id INT AUTO_INCREMENT NOT NULL, excavation_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_DBCE0B6138428BEC (excavation_id), UNIQUE INDEX UNIQ_DBCE0B615E237E0638428BEC (name, excavation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__excavation (id INT AUTO_INCREMENT NOT NULL, town_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4DA40C7075E23604 (town_id), UNIQUE INDEX UNIQ_4DA40C705E237E0675E23604 (name, town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates (id INT AUTO_INCREMENT NOT NULL, relation_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__not_related (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__palisade (id INT NOT NULL, estate_1_id INT NOT NULL, estate_2_id INT NOT NULL, INDEX IDX_B8AAA15FCDBE36DC (estate_1_id), INDEX IDX_B8AAA15FDF0B9932 (estate_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__roadway (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__roadway_estate (relation_id INT NOT NULL, estate_id INT NOT NULL, INDEX IDX_79BBBA093256915B (relation_id), INDEX IDX_79BBBA09900733ED (estate_id), PRIMARY KEY(relation_id, estate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__roadway_street (relation_id INT NOT NULL, street_id INT NOT NULL, INDEX IDX_51F737D3256915B (relation_id), INDEX IDX_51F737D87CF8EB (street_id), PRIMARY KEY(relation_id, street_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__single (id INT NOT NULL, estate_id INT NOT NULL, INDEX IDX_A4965FC900733ED (estate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__unknown (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares (id INT AUTO_INCREMENT NOT NULL, relation_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__between (id INT NOT NULL, square_1_id INT NOT NULL, square_2_id INT NOT NULL, INDEX IDX_60F842CFFBA3C910 (square_1_id), INDEX IDX_60F842CFE91666FE (square_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__one_of (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__one_of_square (relation_id INT NOT NULL, square_id INT NOT NULL, INDEX IDX_1492854D3256915B (relation_id), INDEX IDX_1492854D24CFF17F (square_id), PRIMARY KEY(relation_id, square_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__single (id INT NOT NULL, square_id INT NOT NULL, INDEX IDX_B89CF92A24CFF17F (square_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__unknown (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata (id INT AUTO_INCREMENT NOT NULL, relation_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__one_of (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__one_of_strata (relation_id INT NOT NULL, stratum_id INT NOT NULL, INDEX IDX_F74450A63256915B (relation_id), INDEX IDX_F74450A661C2061C (stratum_id), PRIMARY KEY(relation_id, stratum_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__single (id INT NOT NULL, stratum_id INT NOT NULL, INDEX IDX_EBBA718D61C2061C (stratum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__unknown (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__square (id INT AUTO_INCREMENT NOT NULL, excavation_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9A67796438428BEC (excavation_id), UNIQUE INDEX UNIQ_9A6779645E237E0638428BEC (name, excavation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__stratum (id INT AUTO_INCREMENT NOT NULL, excavation_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, initial_depth NUMERIC(65, 2) DEFAULT NULL, final_depth NUMERIC(65, 2) DEFAULT NULL, INDEX IDX_7E6131038428BEC (excavation_id), UNIQUE INDEX UNIQ_7E613105E237E0638428BEC (name, excavation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__find__street (id INT AUTO_INCREMENT NOT NULL, town_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A76AC21575E23604 (town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__material (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D75E39225E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__storage_place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8EFD1CEE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__ordered_list__item (id INT AUTO_INCREMENT NOT NULL, ordered_list_id INT NOT NULL, document_id INT NOT NULL, position INT NOT NULL, INDEX IDX_6A2C5B6A8148590 (ordered_list_id), INDEX IDX_6A2C5B6AC33F7837 (document_id), UNIQUE INDEX UNIQ_6A2C5B6A8148590C33F7837 (ordered_list_id, document_id), UNIQUE INDEX UNIQ_6A2C5B6A8148590462CE4F5 (ordered_list_id, position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__ordered_list__ordered_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8EF4158E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__scribe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A2911B955E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__state_of_preservation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_92FCDC5C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__town (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviated_name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, google_maps_place_id VARCHAR(255) NOT NULL, google_maps_lat_lng VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C1FCDFE35E237E06 (name), UNIQUE INDEX UNIQ_C1FCDFE3416850EB (abbreviated_name), UNIQUE INDEX UNIQ_C1FCDFE3E16C6B94 (alias), UNIQUE INDEX UNIQ_C1FCDFE3E91B5938 (google_maps_place_id), UNIQUE INDEX UNIQ_C1FCDFE357BFD6F1 (google_maps_lat_lng), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__way_of_writing (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_437ACF235E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliography__author (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1C152DB1DBC463C4 (full_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliography__authorship (id INT AUTO_INCREMENT NOT NULL, bibliographic_record_id INT DEFAULT NULL, author_id INT DEFAULT NULL, position INT NOT NULL, INDEX IDX_4CDFAD337ACA5D3F (bibliographic_record_id), INDEX IDX_4CDFAD33F675F31B (author_id), UNIQUE INDEX UNIQ_4CDFAD337ACA5D3FF675F31B (bibliographic_record_id, author_id), UNIQUE INDEX UNIQ_4CDFAD337ACA5D3F462CE4F5 (bibliographic_record_id, position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliography__record (id INT AUTO_INCREMENT NOT NULL, short_name VARCHAR(255) NOT NULL, authors VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, details TEXT NOT NULL, year INT NOT NULL, UNIQUE INDEX UNIQ_3A8E6AE83EE4B093 (short_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliography__references_list__item (id INT AUTO_INCREMENT NOT NULL, bibliographic_record_list_id INT DEFAULT NULL, bibliographic_record_id INT DEFAULT NULL, position INT NOT NULL, INDEX IDX_E0644C53B8550050 (bibliographic_record_list_id), INDEX IDX_E0644C537ACA5D3F (bibliographic_record_id), UNIQUE INDEX bibliographic_record_list_has_bibliographic_record (bibliographic_record_list_id, bibliographic_record_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bibliography__references_list__references_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__gallery_media (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_80D4C5414E7AF8F (gallery_id), INDEX IDX_80D4C541EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata JSON DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable TINYINT(1) DEFAULT NULL, cdn_flush_identifier VARCHAR(64) DEFAULT NULL, cdn_flush_at DATETIME DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, body LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, full_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bb__amendment ADD CONSTRAINT FK_AA990656EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__amendment ADD CONSTRAINT FK_AA990656ED389BD8 FOREIGN KEY (birch_bark_document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__book ADD CONSTRAINT FK_46FFBB763DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__book ADD CONSTRAINT FK_46FFBB76CBFD05C FOREIGN KEY (pdf_document_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__book_part ADD CONSTRAINT FK_B0700047CBFD05C FOREIGN KEY (pdf_document_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__book_part ADD CONSTRAINT FK_B070004716A2B381 FOREIGN KEY (book_id) REFERENCES bb__book (id)');
        $this->addSql('ALTER TABLE bb__content_element__content_element ADD CONSTRAINT FK_8878A98012469DE2 FOREIGN KEY (category_id) REFERENCES bb__content_element__category (id)');
        $this->addSql('ALTER TABLE bb__content_element__content_element ADD CONSTRAINT FK_8878A9804296D31F FOREIGN KEY (genre_id) REFERENCES bb__content_element__genre (id)');
        $this->addSql('ALTER TABLE bb__content_element__content_element ADD CONSTRAINT FK_8878A98082F1BAF4 FOREIGN KEY (language_id) REFERENCES bb__content_element__language (id)');
        $this->addSql('ALTER TABLE bb__content_element__content_element ADD CONSTRAINT FK_8878A980C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__dnd_section ADD CONSTRAINT FK_2278DE02EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7C75E23604 FOREIGN KEY (town_id) REFERENCES bb__town (id)');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7C5345EC48 FOREIGN KEY (scribe_id) REFERENCES bb__scribe (id)');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7CC02AF735 FOREIGN KEY (state_of_preservation_id) REFERENCES bb__state_of_preservation (id)');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7CA5270C65 FOREIGN KEY (conditional_date_cell_id) REFERENCES bb__conditional_date_cell (id)');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7C35B07C66 FOREIGN KEY (way_of_writing_id) REFERENCES bb__way_of_writing (id)');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7C4B645A82 FOREIGN KEY (dnd_section_id) REFERENCES bb__dnd_section (id)');
        $this->addSql('ALTER TABLE bb__document_record ADD CONSTRAINT FK_1F8CBB63ED389BD8 FOREIGN KEY (birch_bark_document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_record ADD CONSTRAINT FK_1F8CBB637ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__record (id)');
        $this->addSql('ALTER TABLE bb__document_photo ADD CONSTRAINT FK_12D2A80BC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_photo ADD CONSTRAINT FK_12D2A80BEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__document_sketch ADD CONSTRAINT FK_497AAD1C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__document_sketch ADD CONSTRAINT FK_497AAD1EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE bb__material_element ADD CONSTRAINT FK_782085EAE6D9E5E FOREIGN KEY (storage_place_id) REFERENCES bb__material_element__storage_place (id)');
        $this->addSql('ALTER TABLE bb__material_element ADD CONSTRAINT FK_782085EE308AC6F FOREIGN KEY (material_id) REFERENCES bb__material_element__material (id)');
        $this->addSql('ALTER TABLE bb__material_element ADD CONSTRAINT FK_782085E51B74D69 FOREIGN KEY (find_id) REFERENCES bb__material_element__find (id)');
        $this->addSql('ALTER TABLE bb__material_element ADD CONSTRAINT FK_782085EC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__accidental ADD CONSTRAINT FK_B97CF0EBBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_BE304CF38428BEC FOREIGN KEY (excavation_id) REFERENCES bb__material_element__find__excavation (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_BE304CF6BFE634A FOREIGN KEY (relation_to_estates_id) REFERENCES bb__material_element__find__relation_to_estates (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_BE304CF754B59E0 FOREIGN KEY (relation_to_squares_id) REFERENCES bb__material_element__find__relation_to_squares (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_BE304CF650F0CD1 FOREIGN KEY (relation_to_strata_id) REFERENCES bb__material_element__find__relation_to_strata (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_BE304CFBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__estate ADD CONSTRAINT FK_DBCE0B6138428BEC FOREIGN KEY (excavation_id) REFERENCES bb__material_element__find__excavation (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__excavation ADD CONSTRAINT FK_4DA40C7075E23604 FOREIGN KEY (town_id) REFERENCES bb__town (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__not_related ADD CONSTRAINT FK_2C147D8FBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade ADD CONSTRAINT FK_B8AAA15FCDBE36DC FOREIGN KEY (estate_1_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade ADD CONSTRAINT FK_B8AAA15FDF0B9932 FOREIGN KEY (estate_2_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade ADD CONSTRAINT FK_B8AAA15FBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway ADD CONSTRAINT FK_DB0E8844BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate ADD CONSTRAINT FK_79BBBA093256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_estates__roadway (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate ADD CONSTRAINT FK_79BBBA09900733ED FOREIGN KEY (estate_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street ADD CONSTRAINT FK_51F737D3256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_estates__roadway (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street ADD CONSTRAINT FK_51F737D87CF8EB FOREIGN KEY (street_id) REFERENCES bb__material_element__find__street (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single ADD CONSTRAINT FK_A4965FC900733ED FOREIGN KEY (estate_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single ADD CONSTRAINT FK_A4965FCBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__unknown ADD CONSTRAINT FK_7D865F72BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between ADD CONSTRAINT FK_60F842CFFBA3C910 FOREIGN KEY (square_1_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between ADD CONSTRAINT FK_60F842CFE91666FE FOREIGN KEY (square_2_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between ADD CONSTRAINT FK_60F842CFBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of ADD CONSTRAINT FK_31F5D68ABF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square ADD CONSTRAINT FK_1492854D3256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_squares__one_of (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square ADD CONSTRAINT FK_1492854D24CFF17F FOREIGN KEY (square_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single ADD CONSTRAINT FK_B89CF92A24CFF17F FOREIGN KEY (square_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single ADD CONSTRAINT FK_B89CF92ABF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__unknown ADD CONSTRAINT FK_1284FD0FBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of ADD CONSTRAINT FK_62D35E2DBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_strata (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata ADD CONSTRAINT FK_F74450A63256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_strata__one_of (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata ADD CONSTRAINT FK_F74450A661C2061C FOREIGN KEY (stratum_id) REFERENCES bb__material_element__find__stratum (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single ADD CONSTRAINT FK_EBBA718D61C2061C FOREIGN KEY (stratum_id) REFERENCES bb__material_element__find__stratum (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single ADD CONSTRAINT FK_EBBA718DBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_strata (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__unknown ADD CONSTRAINT FK_5A65EDCCBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_strata (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__square ADD CONSTRAINT FK_9A67796438428BEC FOREIGN KEY (excavation_id) REFERENCES bb__material_element__find__excavation (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__stratum ADD CONSTRAINT FK_7E6131038428BEC FOREIGN KEY (excavation_id) REFERENCES bb__material_element__find__excavation (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__street ADD CONSTRAINT FK_A76AC21575E23604 FOREIGN KEY (town_id) REFERENCES bb__town (id)');
        $this->addSql('ALTER TABLE bb__ordered_list__item ADD CONSTRAINT FK_6A2C5B6A8148590 FOREIGN KEY (ordered_list_id) REFERENCES bb__ordered_list__ordered_list (id)');
        $this->addSql('ALTER TABLE bb__ordered_list__item ADD CONSTRAINT FK_6A2C5B6AC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id)');
        $this->addSql('ALTER TABLE bibliography__authorship ADD CONSTRAINT FK_4CDFAD337ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__record (id)');
        $this->addSql('ALTER TABLE bibliography__authorship ADD CONSTRAINT FK_4CDFAD33F675F31B FOREIGN KEY (author_id) REFERENCES bibliography__author (id)');
        $this->addSql('ALTER TABLE bibliography__references_list__item ADD CONSTRAINT FK_E0644C53B8550050 FOREIGN KEY (bibliographic_record_list_id) REFERENCES bibliography__references_list__references_list (id)');
        $this->addSql('ALTER TABLE bibliography__references_list__item ADD CONSTRAINT FK_E0644C537ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__record (id)');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__book_part DROP FOREIGN KEY FK_B070004716A2B381');
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7CA5270C65');
        $this->addSql('ALTER TABLE bb__content_element__content_element DROP FOREIGN KEY FK_8878A98012469DE2');
        $this->addSql('ALTER TABLE bb__content_element__content_element DROP FOREIGN KEY FK_8878A9804296D31F');
        $this->addSql('ALTER TABLE bb__content_element__content_element DROP FOREIGN KEY FK_8878A98082F1BAF4');
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7C4B645A82');
        $this->addSql('ALTER TABLE bb__amendment DROP FOREIGN KEY FK_AA990656ED389BD8');
        $this->addSql('ALTER TABLE bb__content_element__content_element DROP FOREIGN KEY FK_8878A980C33F7837');
        $this->addSql('ALTER TABLE bb__document_record DROP FOREIGN KEY FK_1F8CBB63ED389BD8');
        $this->addSql('ALTER TABLE bb__document_photo DROP FOREIGN KEY FK_12D2A80BC33F7837');
        $this->addSql('ALTER TABLE bb__document_sketch DROP FOREIGN KEY FK_497AAD1C33F7837');
        $this->addSql('ALTER TABLE bb__material_element DROP FOREIGN KEY FK_782085EC33F7837');
        $this->addSql('ALTER TABLE bb__ordered_list__item DROP FOREIGN KEY FK_6A2C5B6AC33F7837');
        $this->addSql('ALTER TABLE bb__material_element DROP FOREIGN KEY FK_782085E51B74D69');
        $this->addSql('ALTER TABLE bb__material_element__find__accidental DROP FOREIGN KEY FK_B97CF0EBBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_BE304CFBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade DROP FOREIGN KEY FK_B8AAA15FCDBE36DC');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade DROP FOREIGN KEY FK_B8AAA15FDF0B9932');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate DROP FOREIGN KEY FK_79BBBA09900733ED');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single DROP FOREIGN KEY FK_A4965FC900733ED');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_BE304CF38428BEC');
        $this->addSql('ALTER TABLE bb__material_element__find__estate DROP FOREIGN KEY FK_DBCE0B6138428BEC');
        $this->addSql('ALTER TABLE bb__material_element__find__square DROP FOREIGN KEY FK_9A67796438428BEC');
        $this->addSql('ALTER TABLE bb__material_element__find__stratum DROP FOREIGN KEY FK_7E6131038428BEC');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_BE304CF6BFE634A');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__not_related DROP FOREIGN KEY FK_2C147D8FBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade DROP FOREIGN KEY FK_B8AAA15FBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway DROP FOREIGN KEY FK_DB0E8844BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single DROP FOREIGN KEY FK_A4965FCBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__unknown DROP FOREIGN KEY FK_7D865F72BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate DROP FOREIGN KEY FK_79BBBA093256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street DROP FOREIGN KEY FK_51F737D3256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_BE304CF754B59E0');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between DROP FOREIGN KEY FK_60F842CFBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of DROP FOREIGN KEY FK_31F5D68ABF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single DROP FOREIGN KEY FK_B89CF92ABF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__unknown DROP FOREIGN KEY FK_1284FD0FBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square DROP FOREIGN KEY FK_1492854D3256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_BE304CF650F0CD1');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of DROP FOREIGN KEY FK_62D35E2DBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single DROP FOREIGN KEY FK_EBBA718DBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__unknown DROP FOREIGN KEY FK_5A65EDCCBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata DROP FOREIGN KEY FK_F74450A63256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between DROP FOREIGN KEY FK_60F842CFFBA3C910');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between DROP FOREIGN KEY FK_60F842CFE91666FE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square DROP FOREIGN KEY FK_1492854D24CFF17F');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single DROP FOREIGN KEY FK_B89CF92A24CFF17F');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata DROP FOREIGN KEY FK_F74450A661C2061C');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single DROP FOREIGN KEY FK_EBBA718D61C2061C');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street DROP FOREIGN KEY FK_51F737D87CF8EB');
        $this->addSql('ALTER TABLE bb__material_element DROP FOREIGN KEY FK_782085EE308AC6F');
        $this->addSql('ALTER TABLE bb__material_element DROP FOREIGN KEY FK_782085EAE6D9E5E');
        $this->addSql('ALTER TABLE bb__ordered_list__item DROP FOREIGN KEY FK_6A2C5B6A8148590');
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7C5345EC48');
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7CC02AF735');
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7C75E23604');
        $this->addSql('ALTER TABLE bb__material_element__find__excavation DROP FOREIGN KEY FK_4DA40C7075E23604');
        $this->addSql('ALTER TABLE bb__material_element__find__street DROP FOREIGN KEY FK_A76AC21575E23604');
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7C35B07C66');
        $this->addSql('ALTER TABLE bibliography__authorship DROP FOREIGN KEY FK_4CDFAD33F675F31B');
        $this->addSql('ALTER TABLE bb__document_record DROP FOREIGN KEY FK_1F8CBB637ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__authorship DROP FOREIGN KEY FK_4CDFAD337ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__references_list__item DROP FOREIGN KEY FK_E0644C537ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__references_list__item DROP FOREIGN KEY FK_E0644C53B8550050');
        $this->addSql('ALTER TABLE media__gallery_media DROP FOREIGN KEY FK_80D4C5414E7AF8F');
        $this->addSql('ALTER TABLE bb__amendment DROP FOREIGN KEY FK_AA990656EA9FDD75');
        $this->addSql('ALTER TABLE bb__book DROP FOREIGN KEY FK_46FFBB763DA5256D');
        $this->addSql('ALTER TABLE bb__book DROP FOREIGN KEY FK_46FFBB76CBFD05C');
        $this->addSql('ALTER TABLE bb__book_part DROP FOREIGN KEY FK_B0700047CBFD05C');
        $this->addSql('ALTER TABLE bb__dnd_section DROP FOREIGN KEY FK_2278DE02EA9FDD75');
        $this->addSql('ALTER TABLE bb__document_photo DROP FOREIGN KEY FK_12D2A80BEA9FDD75');
        $this->addSql('ALTER TABLE bb__document_sketch DROP FOREIGN KEY FK_497AAD1EA9FDD75');
        $this->addSql('ALTER TABLE media__gallery_media DROP FOREIGN KEY FK_80D4C541EA9FDD75');
        $this->addSql('DROP TABLE bb__amendment');
        $this->addSql('DROP TABLE bb__book');
        $this->addSql('DROP TABLE bb__book_part');
        $this->addSql('DROP TABLE bb__conditional_date_cell');
        $this->addSql('DROP TABLE bb__content_element__category');
        $this->addSql('DROP TABLE bb__content_element__content_element');
        $this->addSql('DROP TABLE bb__content_element__genre');
        $this->addSql('DROP TABLE bb__content_element__language');
        $this->addSql('DROP TABLE bb__dnd_section');
        $this->addSql('DROP TABLE bb__document');
        $this->addSql('DROP TABLE bb__document_record');
        $this->addSql('DROP TABLE bb__document_photo');
        $this->addSql('DROP TABLE bb__document_sketch');
        $this->addSql('DROP TABLE bb__material_element');
        $this->addSql('DROP TABLE bb__material_element__find');
        $this->addSql('DROP TABLE bb__material_element__find__accidental');
        $this->addSql('DROP TABLE bb__material_element__find__archaeological');
        $this->addSql('DROP TABLE bb__material_element__find__estate');
        $this->addSql('DROP TABLE bb__material_element__find__excavation');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__not_related');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__palisade');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__roadway');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__roadway_estate');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__roadway_street');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__single');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_estates__unknown');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_squares');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_squares__between');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_squares__one_of');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_squares__one_of_square');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_squares__single');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_squares__unknown');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_strata');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_strata__one_of');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_strata__one_of_strata');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_strata__single');
        $this->addSql('DROP TABLE bb__material_element__find__relation_to_strata__unknown');
        $this->addSql('DROP TABLE bb__material_element__find__square');
        $this->addSql('DROP TABLE bb__material_element__find__stratum');
        $this->addSql('DROP TABLE bb__material_element__find__street');
        $this->addSql('DROP TABLE bb__material_element__material');
        $this->addSql('DROP TABLE bb__material_element__storage_place');
        $this->addSql('DROP TABLE bb__ordered_list__item');
        $this->addSql('DROP TABLE bb__ordered_list__ordered_list');
        $this->addSql('DROP TABLE bb__scribe');
        $this->addSql('DROP TABLE bb__state_of_preservation');
        $this->addSql('DROP TABLE bb__town');
        $this->addSql('DROP TABLE bb__way_of_writing');
        $this->addSql('DROP TABLE bibliography__author');
        $this->addSql('DROP TABLE bibliography__authorship');
        $this->addSql('DROP TABLE bibliography__record');
        $this->addSql('DROP TABLE bibliography__references_list__item');
        $this->addSql('DROP TABLE bibliography__references_list__references_list');
        $this->addSql('DROP TABLE media__gallery');
        $this->addSql('DROP TABLE media__gallery_media');
        $this->addSql('DROP TABLE media__media');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
    }
}
