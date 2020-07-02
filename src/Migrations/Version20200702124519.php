<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200702124519 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_page_type_blog DROP INDEX IDX_E3678BDDC4663E4, ADD UNIQUE INDEX UNIQ_E3678BDDC4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_blog CHANGE page_id page_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_page_type_blog DROP INDEX UNIQ_E3678BDDC4663E4, ADD INDEX IDX_E3678BDDC4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_blog CHANGE page_id page_id INT NOT NULL');
    }
}
