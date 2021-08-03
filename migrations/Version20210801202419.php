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

final class Version20210801202419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'New data storage for photos and drawings.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bb__document_photos (document_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_8CCEC42BC33F7837 (document_id), INDEX IDX_8CCEC42B93CB796C (file_id), PRIMARY KEY(document_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bb__document_drawings (document_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_3F703019C33F7837 (document_id), INDEX IDX_3F70301993CB796C (file_id), PRIMARY KEY(document_id, file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bb__document_photos ADD CONSTRAINT FK_8CCEC42BC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document_photos ADD CONSTRAINT FK_8CCEC42B93CB796C FOREIGN KEY (file_id) REFERENCES media__file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document_drawings ADD CONSTRAINT FK_3F703019C33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__document_drawings ADD CONSTRAINT FK_3F70301993CB796C FOREIGN KEY (file_id) REFERENCES media__file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bb__document_photos');
        $this->addSql('DROP TABLE bb__document_drawings');
    }
}
