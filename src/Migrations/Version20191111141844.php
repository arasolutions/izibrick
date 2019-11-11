<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111141844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4B9395C3F3');
        $this->addSql('DROP TABLE fir_customer');
        $this->addSql('DROP INDEX IDX_D9265D4B9395C3F3 ON fir_site');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site DROP customer_id');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_customer (id INT AUTO_INCREMENT NOT NULL, business_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, address2 VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, post_code VARCHAR(15) DEFAULT NULL COLLATE utf8mb4_unicode_ci, city VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, country VARCHAR(63) DEFAULT NULL COLLATE utf8mb4_unicode_ci, manager_last_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, manager_first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, manager_phone VARCHAR(31) NOT NULL COLLATE utf8mb4_unicode_ci, manager_mail VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_site ADD CONSTRAINT FK_D9265D4B9395C3F3 FOREIGN KEY (customer_id) REFERENCES fir_customer (id)');
        $this->addSql('CREATE INDEX IDX_D9265D4B9395C3F3 ON fir_site (customer_id)');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }
}
