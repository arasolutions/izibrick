<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200223103144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_template ADD default_font_id INT NOT NULL');
        $this->addSql('UPDATE fir_template SET default_font_id = 1');
        $this->addSql('UPDATE fir_template SET default_font_id = 16 WHERE id = 4');
        $this->addSql('ALTER TABLE fir_template ADD CONSTRAINT FK_3A36BCD22572A8AE FOREIGN KEY (default_font_id) REFERENCES fir_font (id)');
        $this->addSql('CREATE INDEX IDX_3A36BCD22572A8AE ON fir_template (default_font_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_template DROP FOREIGN KEY FK_3A36BCD22572A8AE');
        $this->addSql('DROP INDEX IDX_3A36BCD22572A8AE ON fir_template');
        $this->addSql('ALTER TABLE fir_template DROP default_font_id');
    }
}
