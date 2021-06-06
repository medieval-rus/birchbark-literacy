<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210606003818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'New bibliographic record structure.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bibliography__bibliographic_record ADD displayed_value TEXT NULL');
        $this->addSql('UPDATE bibliography__bibliographic_record SET displayed_value = CONCAT(IF(authors = \'\', \'\', CONCAT(authors, \'. \')), title, \' // \', details)');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record CHANGE displayed_value displayed_value TEXT NOT NULL');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP authors');
        $this->addSql('ALTER TABLE bibliography__bibliographic_record DROP details');
    }
}
