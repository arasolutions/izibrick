<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125162812 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_pricing (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, display TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D4DABB59F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_pricing ADD CONSTRAINT FK_D4DABB59F6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE fir_quote ADD display TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fir_pricing');
        $this->addSql('ALTER TABLE fir_quote DROP display');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }
}
