<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124182432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_pricing_product ADD site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_pricing_product ADD CONSTRAINT FK_C9F0B7F6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C9F0B7F6BD1646 ON fir_pricing_product (site_id)');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_pricing_product DROP FOREIGN KEY FK_C9F0B7F6BD1646');
        $this->addSql('DROP INDEX IDX_C9F0B7F6BD1646 ON fir_pricing_product');
        $this->addSql('ALTER TABLE fir_pricing_product DROP site_id');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }
}
