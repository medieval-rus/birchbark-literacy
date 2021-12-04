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

final class Version20211204223125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Material element refactoring: part 2.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bb__material_element DROP FOREIGN KEY FK_B2FE592051B74D69');
        $this->addSql('ALTER TABLE bb__material_element__find__accidental DROP FOREIGN KEY FK_16757CDDBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_5978E98CBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_36EBFDEC6BFE634A');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__not_related DROP FOREIGN KEY FK_16127972BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade DROP FOREIGN KEY FK_F2F8BC32BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway DROP FOREIGN KEY FK_56B6A420BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single DROP FOREIGN KEY FK_BA68F4E3BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__unknown DROP FOREIGN KEY FK_F03E7316BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate DROP FOREIGN KEY FK_49A83A813256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street DROP FOREIGN KEY FK_350CF3F53256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_36EBFDEC754B59E0');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between DROP FOREIGN KEY FK_60F842CFBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of DROP FOREIGN KEY FK_31F5D68ABF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single DROP FOREIGN KEY FK_B89CF92ABF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__unknown DROP FOREIGN KEY FK_42EBEFC0BF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square DROP FOREIGN KEY FK_1492854D3256915B');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological DROP FOREIGN KEY FK_BE304CF650F0CD1');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of DROP FOREIGN KEY FK_62D35E2DBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single DROP FOREIGN KEY FK_EBBA718DBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__unknown DROP FOREIGN KEY FK_5A65EDCCBF396750');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata DROP FOREIGN KEY FK_F74450A63256915B');
        $this->addSql('DROP TABLE bb__material_element__find');
        $this->addSql('DROP TABLE bb__material_element__find__accidental');
        $this->addSql('DROP TABLE bb__material_element__find__archaeological');
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
        $this->addSql('DROP INDEX UNIQ_782085E51B74D69 ON bb__material_element');
        $this->addSql('ALTER TABLE bb__material_element DROP find_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__material_element__find (id INT AUTO_INCREMENT NOT NULL, find_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__accidental (id INT NOT NULL, comment TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__archaeological (id INT NOT NULL, excavation_id INT DEFAULT NULL, relation_to_estates_id INT NOT NULL, relation_to_squares_id INT NOT NULL, relation_to_strata_id INT NOT NULL, initial_tier INT DEFAULT NULL, final_tier INT DEFAULT NULL, comment_on_tiers VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, initial_depth NUMERIC(65, 2) DEFAULT NULL, final_depth NUMERIC(65, 2) DEFAULT NULL, comment_on_depths VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, year INT DEFAULT NULL, UNIQUE INDEX UNIQ_BE304CF6BFE634A (relation_to_estates_id), UNIQUE INDEX UNIQ_BE304CF650F0CD1 (relation_to_strata_id), UNIQUE INDEX UNIQ_BE304CF754B59E0 (relation_to_squares_id), INDEX IDX_BE304CF38428BEC (excavation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates (id INT AUTO_INCREMENT NOT NULL, relation_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__not_related (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__palisade (id INT NOT NULL, estate_1_id INT NOT NULL, estate_2_id INT NOT NULL, INDEX IDX_B8AAA15FCDBE36DC (estate_1_id), INDEX IDX_B8AAA15FDF0B9932 (estate_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__roadway (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__roadway_estate (relation_id INT NOT NULL, estate_id INT NOT NULL, INDEX IDX_79BBBA09900733ED (estate_id), INDEX IDX_79BBBA093256915B (relation_id), PRIMARY KEY(relation_id, estate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__roadway_street (relation_id INT NOT NULL, street_id INT NOT NULL, INDEX IDX_51F737D87CF8EB (street_id), INDEX IDX_51F737D3256915B (relation_id), PRIMARY KEY(relation_id, street_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__single (id INT NOT NULL, estate_id INT NOT NULL, INDEX IDX_A4965FC900733ED (estate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_estates__unknown (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares (id INT AUTO_INCREMENT NOT NULL, relation_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__between (id INT NOT NULL, square_1_id INT NOT NULL, square_2_id INT NOT NULL, INDEX IDX_60F842CFFBA3C910 (square_1_id), INDEX IDX_60F842CFE91666FE (square_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__one_of (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__one_of_square (relation_id INT NOT NULL, square_id INT NOT NULL, INDEX IDX_1492854D24CFF17F (square_id), INDEX IDX_1492854D3256915B (relation_id), PRIMARY KEY(relation_id, square_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__single (id INT NOT NULL, square_id INT NOT NULL, INDEX IDX_B89CF92A24CFF17F (square_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_squares__unknown (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata (id INT AUTO_INCREMENT NOT NULL, relation_type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__one_of (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__one_of_strata (relation_id INT NOT NULL, stratum_id INT NOT NULL, INDEX IDX_F74450A661C2061C (stratum_id), INDEX IDX_F74450A63256915B (relation_id), PRIMARY KEY(relation_id, stratum_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__single (id INT NOT NULL, stratum_id INT NOT NULL, INDEX IDX_EBBA718D61C2061C (stratum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bb__material_element__find__relation_to_strata__unknown (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bb__material_element__find__accidental ADD CONSTRAINT FK_16757CDDBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_5978E98CBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_BE304CF650F0CD1 FOREIGN KEY (relation_to_strata_id) REFERENCES bb__material_element__find__relation_to_strata (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_36EBFDEC754B59E0 FOREIGN KEY (relation_to_squares_id) REFERENCES bb__material_element__find__relation_to_squares (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_A9A2C87938428BEC FOREIGN KEY (excavation_id) REFERENCES bb__material_element__find__excavation (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__archaeological ADD CONSTRAINT FK_36EBFDEC6BFE634A FOREIGN KEY (relation_to_estates_id) REFERENCES bb__material_element__find__relation_to_estates (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__not_related ADD CONSTRAINT FK_16127972BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade ADD CONSTRAINT FK_F2F8BC32DF0B9932 FOREIGN KEY (estate_2_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade ADD CONSTRAINT FK_F2F8BC32CDBE36DC FOREIGN KEY (estate_1_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__palisade ADD CONSTRAINT FK_F2F8BC32BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway ADD CONSTRAINT FK_56B6A420BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate ADD CONSTRAINT FK_49A83A81900733ED FOREIGN KEY (estate_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_estate ADD CONSTRAINT FK_49A83A813256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_estates__roadway (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street ADD CONSTRAINT FK_350CF3F587CF8EB FOREIGN KEY (street_id) REFERENCES bb__material_element__find__street (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__roadway_street ADD CONSTRAINT FK_350CF3F53256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_estates__roadway (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single ADD CONSTRAINT FK_BA68F4E3BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__single ADD CONSTRAINT FK_BA68F4E3900733ED FOREIGN KEY (estate_id) REFERENCES bb__material_element__find__estate (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_estates__unknown ADD CONSTRAINT FK_F03E7316BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_estates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between ADD CONSTRAINT FK_60F842CFFBA3C910 FOREIGN KEY (square_1_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between ADD CONSTRAINT FK_60F842CFE91666FE FOREIGN KEY (square_2_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__between ADD CONSTRAINT FK_60F842CFBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of ADD CONSTRAINT FK_31F5D68ABF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square ADD CONSTRAINT FK_1492854D3256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_squares__one_of (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__one_of_square ADD CONSTRAINT FK_1492854D24CFF17F FOREIGN KEY (square_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single ADD CONSTRAINT FK_B89CF92ABF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__single ADD CONSTRAINT FK_B89CF92A24CFF17F FOREIGN KEY (square_id) REFERENCES bb__material_element__find__square (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_squares__unknown ADD CONSTRAINT FK_42EBEFC0BF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_squares (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of ADD CONSTRAINT FK_62D35E2DBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_strata (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata ADD CONSTRAINT FK_F74450A661C2061C FOREIGN KEY (stratum_id) REFERENCES bb__material_element__find__stratum (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__one_of_strata ADD CONSTRAINT FK_F74450A63256915B FOREIGN KEY (relation_id) REFERENCES bb__material_element__find__relation_to_strata__one_of (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single ADD CONSTRAINT FK_EBBA718DBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_strata (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__single ADD CONSTRAINT FK_EBBA718D61C2061C FOREIGN KEY (stratum_id) REFERENCES bb__material_element__find__stratum (id)');
        $this->addSql('ALTER TABLE bb__material_element__find__relation_to_strata__unknown ADD CONSTRAINT FK_5A65EDCCBF396750 FOREIGN KEY (id) REFERENCES bb__material_element__find__relation_to_strata (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__material_element ADD find_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bb__material_element ADD CONSTRAINT FK_B2FE592051B74D69 FOREIGN KEY (find_id) REFERENCES bb__material_element__find (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_782085E51B74D69 ON bb__material_element (find_id)');
    }
}
