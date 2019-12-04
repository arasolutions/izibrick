<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204221654 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_pricing_product ADD devise VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_pricing_product DROP devise');
        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }
}
