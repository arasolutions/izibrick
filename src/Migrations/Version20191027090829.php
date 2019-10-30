<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191027090829 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fir_invoice (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, first_name VARCHAR(256) DEFAULT NULL, last_name VARCHAR(256) DEFAULT NULL, invoice_number VARCHAR(256) DEFAULT NULL, title VARCHAR(1024) DEFAULT NULL, description VARCHAR(1024) DEFAULT NULL, quantity INT DEFAULT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, vat_rate DOUBLE PRECISION DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, date_create DATETIME NOT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, post_code VARCHAR(15) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(63) DEFAULT NULL, INDEX IDX_A14E002EF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_post (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, introduction VARCHAR(1023) NOT NULL, image VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, date_create DATETIME NOT NULL, INDEX IDX_EAEF3822DAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_blog (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, UNIQUE INDEX UNIQ_707005ECF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_tracking_contact (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, name VARCHAR(256) DEFAULT NULL, email VARCHAR(256) DEFAULT NULL, content LONGTEXT DEFAULT NULL, date_create DATETIME NOT NULL, INDEX IDX_8E0FED66F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_tracking_quote (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, name VARCHAR(256) DEFAULT NULL, email VARCHAR(256) DEFAULT NULL, content LONGTEXT DEFAULT NULL, date_create DATETIME NOT NULL, INDEX IDX_4FBE7200F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_contact (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, presentation VARCHAR(512) DEFAULT NULL, email VARCHAR(512) DEFAULT NULL, phone VARCHAR(128) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, post_code VARCHAR(32) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, opening_time VARCHAR(1028) DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, UNIQUE INDEX UNIQ_7D49F152F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_customer (id INT AUTO_INCREMENT NOT NULL, business_name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, post_code VARCHAR(15) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(63) DEFAULT NULL, manager_last_name VARCHAR(255) NOT NULL, manager_first_name VARCHAR(255) NOT NULL, manager_phone VARCHAR(31) NOT NULL, manager_mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_home (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL, mainPicture VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, UNIQUE INDEX UNIQ_C1B3587FF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_presentation (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, UNIQUE INDEX UNIQ_776F46EAF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, price NUMERIC(5, 2) NOT NULL, code_promotion VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_quote (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, presentation VARCHAR(512) DEFAULT NULL, email VARCHAR(512) DEFAULT NULL, seo_title VARCHAR(128) DEFAULT NULL, seo_description VARCHAR(256) DEFAULT NULL, UNIQUE INDEX UNIQ_2DA810D9F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_site (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, template_id INT NOT NULL, product_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, color_theme VARCHAR(9) NOT NULL, status VARCHAR(5) NOT NULL, logo VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, key_words VARCHAR(1023) DEFAULT NULL, favicon VARCHAR(255) DEFAULT NULL, facebook VARCHAR(1023) DEFAULT NULL, instagram VARCHAR(1023) DEFAULT NULL, twitter VARCHAR(1023) DEFAULT NULL, INDEX IDX_D9265D4B9395C3F3 (customer_id), INDEX IDX_D9265D4B5DA0FB8 (template_id), INDEX IDX_D9265D4B4584665A (product_id), INDEX site_domain_idx (domain), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_support (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(256) DEFAULT NULL, content LONGTEXT DEFAULT NULL, date_create DATETIME NOT NULL, INDEX IDX_B12FFCCFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, url_example VARCHAR(255) NOT NULL, image_example VARCHAR(255) NOT NULL, public TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', firstname VARCHAR(63) DEFAULT NULL, lastname VARCHAR(63) DEFAULT NULL, phone VARCHAR(14) DEFAULT NULL, UNIQUE INDEX UNIQ_3DF682E692FC23A8 (username_canonical), UNIQUE INDEX UNIQ_3DF682E6A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_3DF682E6C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fir_user_site (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_F0372ECA76ED395 (user_id), INDEX IDX_F0372ECF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audit_associations (id INT AUTO_INCREMENT NOT NULL, typ VARCHAR(128) NOT NULL, tbl VARCHAR(128) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, fk VARCHAR(255) NOT NULL, class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audit_logs (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, target_id INT DEFAULT NULL, blame_id INT DEFAULT NULL, action VARCHAR(12) NOT NULL, tbl VARCHAR(128) NOT NULL, diff TEXT DEFAULT NULL, logged_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D62F2858953C1C61 (source_id), UNIQUE INDEX UNIQ_D62F2858158E0B66 (target_id), UNIQUE INDEX UNIQ_D62F28588C082A2E (blame_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fir_invoice ADD CONSTRAINT FK_A14E002EF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fir_post ADD CONSTRAINT FK_EAEF3822DAE07E97 FOREIGN KEY (blog_id) REFERENCES fir_blog (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fir_blog ADD CONSTRAINT FK_707005ECF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE fir_tracking_contact ADD CONSTRAINT FK_8E0FED66F6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fir_tracking_quote ADD CONSTRAINT FK_4FBE7200F6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fir_contact ADD CONSTRAINT FK_7D49F152F6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE fir_home ADD CONSTRAINT FK_C1B3587FF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE fir_presentation ADD CONSTRAINT FK_776F46EAF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE fir_quote ADD CONSTRAINT FK_2DA810D9F6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE fir_site ADD CONSTRAINT FK_D9265D4B9395C3F3 FOREIGN KEY (customer_id) REFERENCES fir_customer (id)');
        $this->addSql('ALTER TABLE fir_site ADD CONSTRAINT FK_D9265D4B5DA0FB8 FOREIGN KEY (template_id) REFERENCES fir_template (id)');
        $this->addSql('ALTER TABLE fir_site ADD CONSTRAINT FK_D9265D4B4584665A FOREIGN KEY (product_id) REFERENCES fir_product (id)');
        $this->addSql('ALTER TABLE fir_support ADD CONSTRAINT FK_B12FFCCFA76ED395 FOREIGN KEY (user_id) REFERENCES fir_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fir_user_site ADD CONSTRAINT FK_F0372ECA76ED395 FOREIGN KEY (user_id) REFERENCES fir_user (id)');
        $this->addSql('ALTER TABLE fir_user_site ADD CONSTRAINT FK_F0372ECF6BD1646 FOREIGN KEY (site_id) REFERENCES fir_site (id)');
        $this->addSql('ALTER TABLE audit_logs ADD CONSTRAINT FK_D62F2858953C1C61 FOREIGN KEY (source_id) REFERENCES audit_associations (id)');
        $this->addSql('ALTER TABLE audit_logs ADD CONSTRAINT FK_D62F2858158E0B66 FOREIGN KEY (target_id) REFERENCES audit_associations (id)');
        $this->addSql('ALTER TABLE audit_logs ADD CONSTRAINT FK_D62F28588C082A2E FOREIGN KEY (blame_id) REFERENCES audit_associations (id)');
        $this->addSql('INSERT INTO `fir_template` (`id`, `name`, `description`, `active`, `url_example`, `image_example`) VALUES (1, \'Multi-page 1\', \'Super joli\', 1, \'http://www.pika-electricite.eldotravo.test\', \'\')');
        $this->addSql('INSERT INTO `fir_product` (`id`, `name`, `active`, `price`, `code_promotion`) VALUES (1, \'PREMIUM Eldotravo\', 1, \'59.00\', \'ELDOTRAVO\'), (2, \'PREMIUM\', 1, \'79.00\', NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fir_post DROP FOREIGN KEY FK_EAEF3822DAE07E97');
        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4B9395C3F3');
        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4B4584665A');
        $this->addSql('ALTER TABLE fir_invoice DROP FOREIGN KEY FK_A14E002EF6BD1646');
        $this->addSql('ALTER TABLE fir_blog DROP FOREIGN KEY FK_707005ECF6BD1646');
        $this->addSql('ALTER TABLE fir_tracking_contact DROP FOREIGN KEY FK_8E0FED66F6BD1646');
        $this->addSql('ALTER TABLE fir_tracking_quote DROP FOREIGN KEY FK_4FBE7200F6BD1646');
        $this->addSql('ALTER TABLE fir_contact DROP FOREIGN KEY FK_7D49F152F6BD1646');
        $this->addSql('ALTER TABLE fir_home DROP FOREIGN KEY FK_C1B3587FF6BD1646');
        $this->addSql('ALTER TABLE fir_presentation DROP FOREIGN KEY FK_776F46EAF6BD1646');
        $this->addSql('ALTER TABLE fir_quote DROP FOREIGN KEY FK_2DA810D9F6BD1646');
        $this->addSql('ALTER TABLE fir_user_site DROP FOREIGN KEY FK_F0372ECF6BD1646');
        $this->addSql('ALTER TABLE fir_site DROP FOREIGN KEY FK_D9265D4B5DA0FB8');
        $this->addSql('ALTER TABLE fir_support DROP FOREIGN KEY FK_B12FFCCFA76ED395');
        $this->addSql('ALTER TABLE fir_user_site DROP FOREIGN KEY FK_F0372ECA76ED395');
        $this->addSql('ALTER TABLE audit_logs DROP FOREIGN KEY FK_D62F2858953C1C61');
        $this->addSql('ALTER TABLE audit_logs DROP FOREIGN KEY FK_D62F2858158E0B66');
        $this->addSql('ALTER TABLE audit_logs DROP FOREIGN KEY FK_D62F28588C082A2E');
        $this->addSql('DROP TABLE fir_invoice');
        $this->addSql('DROP TABLE fir_post');
        $this->addSql('DROP TABLE fir_blog');
        $this->addSql('DROP TABLE fir_tracking_contact');
        $this->addSql('DROP TABLE fir_tracking_quote');
        $this->addSql('DROP TABLE fir_contact');
        $this->addSql('DROP TABLE fir_customer');
        $this->addSql('DROP TABLE fir_home');
        $this->addSql('DROP TABLE fir_presentation');
        $this->addSql('DROP TABLE fir_product');
        $this->addSql('DROP TABLE fir_quote');
        $this->addSql('DROP TABLE fir_site');
        $this->addSql('DROP TABLE fir_support');
        $this->addSql('DROP TABLE fir_template');
        $this->addSql('DROP TABLE fir_user');
        $this->addSql('DROP TABLE fir_user_site');
        $this->addSql('DROP TABLE audit_associations');
        $this->addSql('DROP TABLE audit_logs');
    }
}
