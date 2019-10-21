<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191021202150 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog ADD seo_title VARCHAR(128) DEFAULT NULL, ADD seo_description VARCHAR(256) DEFAULT NULL');
        $this->addSql('ALTER TABLE home ADD seo_title VARCHAR(128) DEFAULT NULL, ADD seo_description VARCHAR(256) DEFAULT NULL');
        $this->addSql('ALTER TABLE presentation ADD seo_title VARCHAR(128) DEFAULT NULL, ADD seo_description VARCHAR(256) DEFAULT NULL');
        $this->addSql('ALTER TABLE quote ADD seo_title VARCHAR(128) DEFAULT NULL, ADD seo_description VARCHAR(256) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog DROP seo_title, DROP seo_description');
        $this->addSql('ALTER TABLE home DROP seo_title, DROP seo_description');
        $this->addSql('ALTER TABLE presentation DROP seo_title, DROP seo_description');
        $this->addSql('ALTER TABLE quote DROP seo_title, DROP seo_description');
    }
}
