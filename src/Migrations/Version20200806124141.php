<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806124141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_page_type_blog ADD template_id INT NOT NULL');
        $this->addSql('ALTER TABLE fir_template ADD type VARCHAR(255) NOT NULL');
        $this->addSql("INSERT INTO fir_template (id, name, description, active, url_example, image_example, public, default_font_id, type) VALUES (7, 'Classic', '', '1', '', '', '1', '1', 'blog')");
        $this->addSql("INSERT INTO fir_template (id, name, description, active, url_example, image_example, public, default_font_id, type) VALUES (8, 'Mansory', '', '1', '', '', '1', '1', 'blog')");
        $this->addSql("UPDATE fir_page_type_blog SET template_id = 7");
        ;
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_page_type_blog DROP template_id');
        $this->addSql('ALTER TABLE fir_template DROP type');
    }
}
