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

final class Version20220206193131 extends AbstractMigration
{
    private array $categories = [];
    private array $genres = [];
    private array $languages = [];

    public function getDescription(): string
    {
        return 'Multiple content categories, genres and languages support.';
    }

    public function preUp(Schema $schema): void
    {
        $categories = $this
            ->connection
            ->prepare('SELECT id as content_element_id, category_id FROM bb__content_element WHERE category_id IS NOT NULL')
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($categories as $categoryData) {
            $this->categories[] = [$categoryData['content_element_id'], $categoryData['category_id']];
        }

        $genres = $this
            ->connection
            ->prepare('SELECT id as content_element_id, genre_id FROM bb__content_element WHERE genre_id IS NOT NULL')
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($genres as $genreData) {
            $this->genres[] = [$genreData['content_element_id'], $genreData['genre_id']];
        }

        $languages = $this
            ->connection
            ->prepare('SELECT id as content_element_id, language_id FROM bb__content_element WHERE language_id IS NOT NULL')
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($languages as $languageData) {
            $this->languages[] = [$languageData['content_element_id'], $languageData['language_id']];
        }
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE content_element_category (content_element_id INT NOT NULL, content_category_id INT NOT NULL, INDEX IDX_D281169E86269CD6 (content_element_id), INDEX IDX_D281169E416C3764 (content_category_id), PRIMARY KEY(content_element_id, content_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_element_genre (content_element_id INT NOT NULL, genre_id INT NOT NULL, INDEX IDX_1CA8617086269CD6 (content_element_id), INDEX IDX_1CA861704296D31F (genre_id), PRIMARY KEY(content_element_id, genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_element_language (content_element_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_167EEA86269CD6 (content_element_id), INDEX IDX_167EEA82F1BAF4 (language_id), PRIMARY KEY(content_element_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_element_category ADD CONSTRAINT FK_D281169E86269CD6 FOREIGN KEY (content_element_id) REFERENCES bb__content_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_element_category ADD CONSTRAINT FK_D281169E416C3764 FOREIGN KEY (content_category_id) REFERENCES bb__content_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_element_genre ADD CONSTRAINT FK_1CA8617086269CD6 FOREIGN KEY (content_element_id) REFERENCES bb__content_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_element_genre ADD CONSTRAINT FK_1CA861704296D31F FOREIGN KEY (genre_id) REFERENCES bb__genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_element_language ADD CONSTRAINT FK_167EEA86269CD6 FOREIGN KEY (content_element_id) REFERENCES bb__content_element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_element_language ADD CONSTRAINT FK_167EEA82F1BAF4 FOREIGN KEY (language_id) REFERENCES bb__language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__content_element DROP FOREIGN KEY FK_FEC530A94296D31F');
        $this->addSql('ALTER TABLE bb__content_element DROP FOREIGN KEY FK_FEC530A912469DE2');
        $this->addSql('ALTER TABLE bb__content_element DROP FOREIGN KEY FK_FEC530A982F1BAF4');
        $this->addSql('DROP INDEX IDX_A492121E12469DE2 ON bb__content_element');
        $this->addSql('DROP INDEX IDX_A492121E82F1BAF4 ON bb__content_element');
        $this->addSql('DROP INDEX IDX_A492121E4296D31F ON bb__content_element');
        $this->addSql('ALTER TABLE bb__content_element DROP category_id, DROP genre_id, DROP language_id');
    }

    public function postUp(Schema $schema): void
    {
        $format = fn (array $data): string => sprintf('(%d, %d)', $data[0], $data[1]);

        $this->connection->executeStatement('INSERT INTO content_element_category (content_element_id, content_category_id) VALUES '.implode(', ', array_map($format, $this->categories)));
        $this->connection->executeStatement('INSERT INTO content_element_genre (content_element_id, genre_id) VALUES '.implode(', ', array_map($format, $this->genres)));
        $this->connection->executeStatement('INSERT INTO content_element_language (content_element_id, language_id) VALUES '.implode(', ', array_map($format, $this->languages)));
    }
}
