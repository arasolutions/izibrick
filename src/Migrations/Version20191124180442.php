<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124180442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_pricing_category (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_FBFD634FF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_pricing_category ADD CONSTRAINT FK_FBFD634FF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id) ON DELETE SET NULL');
        $this->addSql('CREATE TABLE fir_pricing_product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, active TINYINT(1) NOT NULL, price NUMERIC(5, 2) NOT NULL, INDEX IDX_C9F0B712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_pricing_product ADD CONSTRAINT FK_C9F0B712469DE2 FOREIGN KEY (category_id) REFERENCES fir_pricing_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fir_pricing_product');
        $this->addSql('DROP TABLE fir_pricing_category');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
    }
}
