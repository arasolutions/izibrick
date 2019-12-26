<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223112207 extends AbstractMigration
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
        $this->addSql('ALTER TABLE fir_site CHANGE stripe_subscription_id stripe_subscription_id VARCHAR(65) DEFAULT NULL');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site CHANGE stripe_subscription_id stripe_subscription_id VARCHAR(65) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }
}
