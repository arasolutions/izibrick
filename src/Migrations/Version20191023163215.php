<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023163215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog DROP INDEX IDX_C0155143F6BD1646, ADD UNIQUE INDEX UNIQ_C0155143F6BD1646 (site_id)');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143F6BD1646');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog DROP INDEX UNIQ_C0155143F6BD1646, ADD INDEX IDX_C0155143F6BD1646 (site_id)');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143F6BD1646');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE SET NULL');
    }
}
