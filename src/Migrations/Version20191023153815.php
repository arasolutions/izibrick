<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023153815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF6BD1646');
        $this->addSql('DROP INDEX IDX_5A8A6C8DF6BD1646 ON post');
        $this->addSql('ALTER TABLE post CHANGE site_id blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DDAE07E97 ON post (blog_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DDAE07E97');
        $this->addSql('DROP INDEX IDX_5A8A6C8DDAE07E97 ON post');
        $this->addSql('ALTER TABLE post CHANGE blog_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF6BD1646 ON post (site_id)');
    }
}
