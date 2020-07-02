<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200702124755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_page_type_contact DROP INDEX IDX_7E334E64C4663E4, ADD UNIQUE INDEX UNIQ_7E334E64C4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_contact CHANGE page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_page_type_home DROP INDEX IDX_52A4D64EC4663E4, ADD UNIQUE INDEX UNIQ_52A4D64EC4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_home CHANGE page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fir_page_type_presentation DROP INDEX IDX_6E54EB45C4663E4, ADD UNIQUE INDEX UNIQ_6E54EB45C4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_presentation CHANGE page_id page_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_page_type_contact DROP INDEX UNIQ_7E334E64C4663E4, ADD INDEX IDX_7E334E64C4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_contact CHANGE page_id page_id INT NOT NULL');
        $this->addSql('ALTER TABLE fir_page_type_home DROP INDEX UNIQ_52A4D64EC4663E4, ADD INDEX IDX_52A4D64EC4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_home CHANGE page_id page_id INT NOT NULL');
        $this->addSql('ALTER TABLE fir_page_type_presentation DROP INDEX UNIQ_6E54EB45C4663E4, ADD INDEX IDX_6E54EB45C4663E4 (page_id)');
        $this->addSql('ALTER TABLE fir_page_type_presentation CHANGE page_id page_id INT NOT NULL');
    }
}
