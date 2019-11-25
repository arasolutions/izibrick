<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191125161806 extends AbstractMigration
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
        $this->addSql('ALTER TABLE fir_site DROP header_background_color, DROP header_text_color, DROP content_background_color, DROP content_text_color, DROP footer_background_color, DROP footer_text_color');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX site_internal_name_idx ON fir_site');
        $this->addSql('ALTER TABLE fir_site ADD header_background_color VARCHAR(9) NOT NULL COLLATE utf8mb4_unicode_ci, ADD header_text_color VARCHAR(9) NOT NULL COLLATE utf8mb4_unicode_ci, ADD content_background_color VARCHAR(9) NOT NULL COLLATE utf8mb4_unicode_ci, ADD content_text_color VARCHAR(9) NOT NULL COLLATE utf8mb4_unicode_ci, ADD footer_background_color VARCHAR(9) NOT NULL COLLATE utf8mb4_unicode_ci, ADD footer_text_color VARCHAR(9) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE INDEX site_internal_name_idx ON fir_site (internal_name)');
    }
}
