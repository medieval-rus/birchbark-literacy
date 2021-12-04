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

final class Version20211204192126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Material element refactoring: part 1.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__material_element__estate (material_element_id INT NOT NULL, estate_id INT NOT NULL, INDEX IDX_932ADA02BF2D5646 (material_element_id), INDEX IDX_932ADA02900733ED (estate_id), PRIMARY KEY(material_element_id, estate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__square (material_element_id INT NOT NULL, square_id INT NOT NULL, INDEX IDX_D283A807BF2D5646 (material_element_id), INDEX IDX_D283A80724CFF17F (square_id), PRIMARY KEY(material_element_id, square_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__stratum (material_element_id INT NOT NULL, stratum_id INT NOT NULL, INDEX IDX_D315C723BF2D5646 (material_element_id), INDEX IDX_D315C72361C2061C (stratum_id), PRIMARY KEY(material_element_id, stratum_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__material_element__street (material_element_id INT NOT NULL, street_id INT NOT NULL, INDEX IDX_EF8E1376BF2D5646 (material_element_id), INDEX IDX_EF8E137687CF8EB (street_id), PRIMARY KEY(material_element_id, street_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bb__material_element__estate ADD CONSTRAINT FK_932ADA02BF2D5646 FOREIGN KEY (material_element_id) REFERENCES bb__material_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__estate ADD CONSTRAINT FK_932ADA02900733ED FOREIGN KEY (estate_id) REFERENCES bb__material_element__find__estate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__square ADD CONSTRAINT FK_D283A807BF2D5646 FOREIGN KEY (material_element_id) REFERENCES bb__material_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__square ADD CONSTRAINT FK_D283A80724CFF17F FOREIGN KEY (square_id) REFERENCES bb__material_element__find__square (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__stratum ADD CONSTRAINT FK_D315C723BF2D5646 FOREIGN KEY (material_element_id) REFERENCES bb__material_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__stratum ADD CONSTRAINT FK_D315C72361C2061C FOREIGN KEY (stratum_id) REFERENCES bb__material_element__find__stratum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__street ADD CONSTRAINT FK_EF8E1376BF2D5646 FOREIGN KEY (material_element_id) REFERENCES bb__material_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__street ADD CONSTRAINT FK_EF8E137687CF8EB FOREIGN KEY (street_id) REFERENCES bb__material_element__find__street (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element ADD excavation_id INT DEFAULT NULL, ADD year INT DEFAULT NULL, ADD initial_tier INT DEFAULT NULL, ADD final_tier INT DEFAULT NULL, ADD comment_on_tiers VARCHAR(255) DEFAULT NULL, ADD initial_depth NUMERIC(65, 2) DEFAULT NULL, ADD final_depth NUMERIC(65, 2) DEFAULT NULL, ADD comment_on_depths VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_782085E38428BEC ON bb__material_element (excavation_id)');
        $this->addSql('ALTER TABLE bb__material_element ADD is_archaeological_find TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE bb__material_element ADD is_palisade TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_roadway TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE bb__material_element ADD CONSTRAINT FK_782085E38428BEC FOREIGN KEY (excavation_id) REFERENCES bb__material_element__find__excavation (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__stratum CHANGE name name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bb__material_element__estate');
        $this->addSql('DROP TABLE bb__material_element__square');
        $this->addSql('DROP TABLE bb__material_element__stratum');
        $this->addSql('DROP TABLE bb__material_element__street');
        $this->addSql('ALTER TABLE bb__material_element DROP FOREIGN KEY FK_782085E38428BEC');
        $this->addSql('DROP INDEX IDX_782085E38428BEC ON bb__material_element');
        $this->addSql('ALTER TABLE bb__material_element DROP excavation_id, DROP year, DROP initial_tier, DROP final_tier, DROP comment_on_tiers, DROP initial_depth, DROP final_depth, DROP comment_on_depths');
        $this->addSql('ALTER TABLE bb__material_element DROP is_archaeological_find');
        $this->addSql('ALTER TABLE bb__material_element DROP is_palisade, DROP is_roadway');
        $this->addSql('ALTER TABLE bb__material_element__find__stratum CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
