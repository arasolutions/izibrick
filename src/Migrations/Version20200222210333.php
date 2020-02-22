<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222210333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4BD7F7F9EB');
        $this->addSql('CREATE TABLE fir_font (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(15) NOT NULL, real_font_name VARCHAR(15) NOT NULL, active TINYINT(1) NOT NULL, path VARCHAR(127) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE font');
        $this->addSql('INSERT INTO fir_font (id, name, real_font_name, active, path) VALUES (NULL, \'Poppins\', \'Poppins\', 1, \'Poppins/Poppins-Regular.ttf\')');
        $this->addSql('UPDATE fir_site SET font_id = 1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4BD7F7F9EB');
        $this->addSql('CREATE TABLE font (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, real_font_name VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, active TINYINT(1) NOT NULL, path VARCHAR(127) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE fir_font');
    }
}
