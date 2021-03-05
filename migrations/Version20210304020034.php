<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210304020034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'User and Post entities.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, body LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, full_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO post VALUES (1, "О проекте", "<p>Текст для этой страницы еще не написан</p>")');
        $this->addSql('INSERT INTO post VALUES (2, "Новости", "<p>04 марта 2021 - создан каркас сайта</p>")');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
    }
}
