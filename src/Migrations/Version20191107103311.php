<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107103311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site ADD code_promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_site ADD CONSTRAINT FK_D9265D4BD8B503F1 FOREIGN KEY (code_promotion_id) REFERENCES fir_product_code_promotion (id)');
        $this->addSql('CREATE INDEX IDX_D9265D4BD8B503F1 ON fir_site (code_promotion_id)');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4BD8B503F1');
        $this->addSql('DROP INDEX IDX_D9265D4BD8B503F1 ON fir_site');
        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site DROP code_promotion_id');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }
}
