<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210314024155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Renamed ConditionalDate to ConventionalDate.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE bb__document DROP FOREIGN KEY FK_EFBB1D7CA5270C65');
        $this->addSql('DROP INDEX IDX_EFBB1D7CA5270C65 ON bb__document');
        $this->addSql('ALTER TABLE bb__document CHANGE is_conditional_date_biased_backward is_conventional_date_biased_backward TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE bb__document CHANGE is_conditional_date_biased_forward is_conventional_date_biased_forward TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE bb__document CHANGE conditional_date_cell_id conventional_date_cell_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bb__document ADD CONSTRAINT FK_EFBB1D7C9F95AAC5 FOREIGN KEY (conventional_date_cell_id) REFERENCES bb__conditional_date_cell (id)');
        $this->addSql('CREATE INDEX IDX_EFBB1D7C9F95AAC5 ON bb__document (conventional_date_cell_id)');
    }
}
