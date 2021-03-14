<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210314012031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Refactored OrderedList to DocumentList.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_8EF4158E5E237E06 ON bb__ordered_list__ordered_list');
        $this->addSql('ALTER TABLE bb__ordered_list__ordered_list ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE bb__ordered_list__item MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE bb__ordered_list__item DROP FOREIGN KEY FK_1C5191F18148590');
        $this->addSql('ALTER TABLE bb__ordered_list__item DROP FOREIGN KEY FK_6A2C5B6AC33F7837');
        $this->addSql('DROP INDEX IDX_6A2C5B6A8148590 ON bb__ordered_list__item');
        $this->addSql('DROP INDEX UNIQ_6A2C5B6A8148590C33F7837 ON bb__ordered_list__item');
        $this->addSql('DROP INDEX UNIQ_6A2C5B6A8148590462CE4F5 ON bb__ordered_list__item');
        $this->addSql('ALTER TABLE bb__ordered_list__item DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE bb__ordered_list__item CHANGE ordered_list_id document_list_id INT NOT NULL;');
        $this->addSql('ALTER TABLE bb__ordered_list__item DROP id, DROP position, ADD PRIMARY KEY (document_list_id, document_id)');
        $this->addSql('ALTER TABLE bb__ordered_list__item ADD CONSTRAINT FK_6A2C5B6A99C1B057 FOREIGN KEY (document_list_id) REFERENCES bb__ordered_list__ordered_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bb__ordered_list__item ADD CONSTRAINT FK_6A2C5B6AC33F7837 FOREIGN KEY (document_id) REFERENCES bb__document (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6A2C5B6A99C1B057 ON bb__ordered_list__item (document_list_id)');
    }
}
