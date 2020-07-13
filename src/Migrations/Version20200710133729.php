<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200710133729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_post DROP FOREIGN KEY FK_EAEF38224CDDBD2C');
        $this->addSql('ALTER TABLE fir_post DROP FOREIGN KEY FK_EAEF3822DAE07E97');
        $this->addSql('DROP INDEX IDX_EAEF38224CDDBD2C ON fir_post');
        $this->addSql('DROP INDEX IDX_EAEF3822DAE07E97 ON fir_post');
        $this->addSql('ALTER TABLE fir_post DROP blog_id, DROP page_type_blog_id');
        $this->addSql('ALTER TABLE fir_post RENAME INDEX fk_eaef3822c4663e4 TO IDX_EAEF3822C4663E4');
        $this->addSql('ALTER TABLE fir_site DROP INDEX FK_D9265D4BFEC332A1, ADD UNIQUE INDEX UNIQ_D9265D4BFEC332A1 (default_page_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_post ADD blog_id INT DEFAULT NULL, ADD page_type_blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_post ADD CONSTRAINT FK_EAEF38224CDDBD2C FOREIGN KEY (page_type_blog_id) REFERENCES fir_page_type_blog (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fir_post ADD CONSTRAINT FK_EAEF3822DAE07E97 FOREIGN KEY (blog_id) REFERENCES fir_blog (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_EAEF38224CDDBD2C ON fir_post (page_type_blog_id)');
        $this->addSql('CREATE INDEX IDX_EAEF3822DAE07E97 ON fir_post (blog_id)');
        $this->addSql('ALTER TABLE fir_post RENAME INDEX idx_eaef3822c4663e4 TO FK_EAEF3822C4663E4');
    }
}
