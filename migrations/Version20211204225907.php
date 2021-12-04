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

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211204225907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Material element refactoring: part 4.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__content_category RENAME INDEX uniq_62e15e237e06 TO UNIQ_A3A37EE65E237E06');
        $this->addSql('ALTER TABLE bb__content_element RENAME INDEX idx_8878a98012469de2 TO IDX_A492121E12469DE2');
        $this->addSql('ALTER TABLE bb__content_element RENAME INDEX idx_8878a9804296d31f TO IDX_A492121E4296D31F');
        $this->addSql('ALTER TABLE bb__content_element RENAME INDEX idx_8878a98082f1baf4 TO IDX_A492121E82F1BAF4');
        $this->addSql('ALTER TABLE bb__content_element RENAME INDEX idx_8878a980c33f7837 TO IDX_A492121EC33F7837');
        $this->addSql('ALTER TABLE bb__document RENAME INDEX town__number TO number_is_unique_within_town');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__dnd DROP FOREIGN KEY FK_F987788AC33F7837');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__dnd DROP FOREIGN KEY FK_F987788A7ACA5D3F');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__dnd ADD CONSTRAINT FK_6F37A7C5C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__dnd ADD CONSTRAINT FK_6F37A7C57ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__dnd RENAME INDEX idx_f987788ac33f7837 TO IDX_6F37A7C5C33F7837');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__dnd RENAME INDEX idx_f987788a7aca5d3f TO IDX_6F37A7C57ACA5D3F');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__ngb DROP FOREIGN KEY FK_CCB1E320C33F7837');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__ngb DROP FOREIGN KEY FK_CCB1E3207ACA5D3F');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__ngb ADD CONSTRAINT FK_5A013C6FC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__ngb ADD CONSTRAINT FK_5A013C6F7ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__ngb RENAME INDEX idx_ccb1e320c33f7837 TO IDX_5A013C6FC33F7837');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record__ngb RENAME INDEX idx_ccb1e3207aca5d3f TO IDX_5A013C6F7ACA5D3F');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record DROP FOREIGN KEY FK_EF42C224C0C5167B');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record DROP FOREIGN KEY FK_A43BEC5C33F7837');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record ADD CONSTRAINT FK_53A9C311C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record ADD CONSTRAINT FK_53A9C3117ACA5D3F FOREIGN KEY (bibliographic_record_id) REFERENCES bibliography__bibliographic_record (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record RENAME INDEX idx_a43bec5c33f7837 TO IDX_53A9C311C33F7837');
        $this->addSql('ALTER TABLE bb__document__bibliographic_record RENAME INDEX idx_a43bec57aca5d3f TO IDX_53A9C3117ACA5D3F');
        $this->addSql('ALTER TABLE bb__document__photos RENAME INDEX idx_8ccec42bc33f7837 TO IDX_A2722C4EC33F7837');
        $this->addSql('ALTER TABLE bb__document__photos RENAME INDEX idx_8ccec42b93cb796c TO IDX_A2722C4E93CB796C');
        $this->addSql('ALTER TABLE bb__document__drawings RENAME INDEX idx_3f703019c33f7837 TO IDX_892BEB0DC33F7837');
        $this->addSql('ALTER TABLE bb__document__drawings RENAME INDEX idx_3f70301993cb796c TO IDX_892BEB0D93CB796C');
        $this->addSql('ALTER TABLE bb__document_list__item RENAME INDEX idx_6a2c5b6a99c1b057 TO IDX_4E4F929F99C1B057');
        $this->addSql('ALTER TABLE bb__document_list__item RENAME INDEX idx_6a2c5b6ac33f7837 TO IDX_4E4F929FC33F7837');
        $this->addSql('ALTER TABLE bb__estate RENAME INDEX idx_dbce0b6138428bec TO IDX_201ED62238428BEC');
        $this->addSql('ALTER TABLE bb__estate RENAME INDEX uniq_dbce0b615e237e0638428bec TO estate_is_unique_within_excavation');
        $this->addSql('ALTER TABLE bb__excavation RENAME INDEX idx_4da40c7075e23604 TO IDX_EA03B34B75E23604');
        $this->addSql('ALTER TABLE bb__excavation RENAME INDEX uniq_4da40c705e237e0675e23604 TO excavation_is_unique_within_town');
        $this->addSql('ALTER TABLE bb__genre RENAME INDEX uniq_e06b86c65e237e06 TO UNIQ_6B65FDD35E237E06');
        $this->addSql('ALTER TABLE bb__language RENAME INDEX uniq_d2970a955e237e06 TO UNIQ_E309E6BF5E237E06');
        $this->addSql('ALTER TABLE bb__material RENAME INDEX uniq_d75e39225e237e06 TO UNIQ_4B6CE29F5E237E06');
        $this->addSql('ALTER TABLE bb__square RENAME INDEX idx_9a67796438428bec TO IDX_61B7A42738428BEC');
        $this->addSql('ALTER TABLE bb__square RENAME INDEX uniq_9a6779645e237e0638428bec TO square_is_unique_within_excavation');
        $this->addSql('ALTER TABLE bb__storage_place RENAME INDEX uniq_8efd1cee5e237e06 TO UNIQ_6DE41A3B5E237E06');
        $this->addSql('ALTER TABLE bb__stratum RENAME INDEX idx_7e6131038428bec TO IDX_E8C8D3E738428BEC');
        $this->addSql('ALTER TABLE bb__stratum RENAME INDEX uniq_7e613105e237e0638428bec TO stratum_is_unique_within_excavation');
        $this->addSql('CREATE UNIQUE INDEX street_is_unique_within_town ON bb__street (name, town_id)');
        $this->addSql('ALTER TABLE bb__street RENAME INDEX idx_a76ac21575e23604 TO IDX_5CBA1F5675E23604');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record__author RENAME INDEX idx_f06886d67aca5d3f TO IDX_BEDEC5767ACA5D3F');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record__author RENAME INDEX idx_f06886d6f675f31b TO IDX_BEDEC576F675F31B');
        $this->addSql('ALTER TABLE bibliography__references_list__item RENAME INDEX idx_2273013df529dd3e TO IDX_E0644C53F529DD3E');
        $this->addSql('ALTER TABLE bibliography__references_list__item RENAME INDEX idx_2273013d7aca5d3f TO IDX_E0644C537ACA5D3F');
        $this->addSql('ALTER TABLE security__user RENAME INDEX uniq_8d93d649f85e0677 TO UNIQ_3C0A45F8F85E0677');
    }
}
