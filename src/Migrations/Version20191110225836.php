<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191110225836 extends AbstractMigration
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
        $this->addSql('ALTER TABLE fir_site ADD text_color VARCHAR(7) NOT NULL');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX site_domain_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site DROP text_color');
        $this->addSql('CREATE INDEX site_domain_idx ON fir_site (domain)');
    }
}
