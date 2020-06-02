<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200525130101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_page_type_contact (id INT AUTO_INCREMENT NOT NULL, page_id INT NOT NULL, presentation VARCHAR(512) DEFAULT NULL, email VARCHAR(512) DEFAULT NULL, phone VARCHAR(128) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, post_code VARCHAR(32) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, opening_time VARCHAR(1028) DEFAULT NULL, INDEX IDX_7E334E64C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_page_type_presentation (id INT AUTO_INCREMENT NOT NULL, page_id INT NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_6E54EB45C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_page_type_contact ADD CONSTRAINT FK_7E334E64C4663E4 FOREIGN KEY (page_id) REFERENCES fir_page (id)');
        $this->addSql('ALTER TABLE fir_page_type_presentation ADD CONSTRAINT FK_6E54EB45C4663E4 FOREIGN KEY (page_id) REFERENCES fir_page (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fir_page_type_contact');
        $this->addSql('DROP TABLE fir_page_type_presentation');
    }
}
