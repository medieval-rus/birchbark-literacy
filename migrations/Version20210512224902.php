<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210512224902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Made Find information optional.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE bb__material_element CHANGE find_id find_id INT DEFAULT NULL');
    }
}
