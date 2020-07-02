<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200702123738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_page_type_blog (id INT AUTO_INCREMENT NOT NULL, page_id INT NOT NULL, INDEX IDX_E3678BDDC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_page_type_blog ADD CONSTRAINT FK_E3678BDDC4663E4 FOREIGN KEY (page_id) REFERENCES fir_page (id)');
        $this->addSql('ALTER TABLE fir_post ADD page_type_blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_post ADD CONSTRAINT FK_EAEF38224CDDBD2C FOREIGN KEY (page_type_blog_id) REFERENCES fir_page_type_blog (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_EAEF38224CDDBD2C ON fir_post (page_type_blog_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_post DROP FOREIGN KEY FK_EAEF38224CDDBD2C');
        $this->addSql('DROP TABLE fir_page_type_blog');
        $this->addSql('DROP INDEX IDX_EAEF38224CDDBD2C ON fir_post');
        $this->addSql('ALTER TABLE fir_post DROP page_type_blog_id');
    }
}
