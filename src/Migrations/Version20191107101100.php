<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107101100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_product_code_promotion ADD date_begin DATE NOT NULL, ADD date_end DATE DEFAULT NULL, ADD code VARCHAR(15) NOT NULL, CHANGE price price_decrease NUMERIC(5, 2) NOT NULL');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
        $this->addSql('ALTER TABLE audit_logs CHANGE diff diff JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE audit_logs CHANGE diff diff TEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE fir_product_code_promotion DROP date_begin, DROP date_end, DROP code, CHANGE price_decrease price NUMERIC(5, 2) NOT NULL');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }
}
