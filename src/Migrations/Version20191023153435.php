<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191023153435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, introduction VARCHAR(1023) NOT NULL, image VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, date_create DATETIME NOT NULL, INDEX IDX_5A8A6C8DF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE blog DROP title, DROP introduction, DROP image, DROP content, DROP date_create');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE blog ADD title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD introduction VARCHAR(1023) NOT NULL COLLATE utf8mb4_unicode_ci, ADD image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD content LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD date_create DATETIME NOT NULL');
    }
}
