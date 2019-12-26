<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223112128 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site ADD stripe_subscription_id VARCHAR(65) NOT NULL');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
        $this->addSql('ALTER TABLE audit_logs CHANGE diff diff JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE audit_logs CHANGE diff diff TEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site DROP stripe_subscription_id');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }
}
