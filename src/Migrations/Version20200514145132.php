<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514145132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_custom_page (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, place INT DEFAULT NULL, name_menu TINYTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, INDEX IDX_7F20211CF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_custom_page ADD CONSTRAINT FK_7F20211CF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id) ON DELETE SET NULL');
        $this->addSql('DROP INDEX site_domain_host_idx ON fir_site');
        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('CREATE INDEX site_domain_host_idx ON fir_site (domain_host)');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
        $this->addSql('ALTER TABLE fir_custom_page CHANGE place place INT DEFAULT 1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fir_custom_page');
        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('DROP INDEX site_domain_host_idx ON fir_site');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
        $this->addSql('CREATE INDEX site_domain_host_idx ON fir_site (domain_host)');
    }
}
