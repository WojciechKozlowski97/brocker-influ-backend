<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240223192255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, user_instagram_id_id INT DEFAULT NULL, site_id_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, price INT NOT NULL, INDEX IDX_4AF2B3F358DDAE46 (user_instagram_id_id), INDEX IDX_4AF2B3F3BB1E4E52 (site_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal (id INT AUTO_INCREMENT NOT NULL, bid_id_id INT DEFAULT NULL, content LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, delivery_days INT NOT NULL, revisions INT NOT NULL, INDEX IDX_E3FEC116607FBD82 (bid_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal_accepted (id INT AUTO_INCREMENT NOT NULL, deal_id_id INT DEFAULT NULL, user_advertiser_id_id INT DEFAULT NULL, INDEX IDX_959AE7D4399B5D4B (deal_id_id), INDEX IDX_959AE7D4AA0F6A5 (user_advertiser_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal_feature (id INT AUTO_INCREMENT NOT NULL, deal_id_id INT DEFAULT NULL, name LONGTEXT DEFAULT NULL, INDEX IDX_FBCDFDEE399B5D4B (deal_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_advertiser (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_instagram (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, instagram_id INT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, followers_count INT NOT NULL, likes_count INT NOT NULL, UNIQUE INDEX UNIQ_8448F3279D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_instagram_category (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, category_id_id INT DEFAULT NULL, INDEX IDX_4D3DCDB29D86650F (user_id_id), INDEX IDX_4D3DCDB29777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_manager (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_manager_user_instagram (id INT AUTO_INCREMENT NOT NULL, user_manager_id INT DEFAULT NULL, user_instagram_id INT DEFAULT NULL, INDEX IDX_8FA48178DF59F28F (user_manager_id), INDEX IDX_8FA481781CD5EE0D (user_instagram_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F358DDAE46 FOREIGN KEY (user_instagram_id_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3BB1E4E52 FOREIGN KEY (site_id_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC116607FBD82 FOREIGN KEY (bid_id_id) REFERENCES bid (id)');
        $this->addSql('ALTER TABLE deal_accepted ADD CONSTRAINT FK_959AE7D4399B5D4B FOREIGN KEY (deal_id_id) REFERENCES deal (id)');
        $this->addSql('ALTER TABLE deal_accepted ADD CONSTRAINT FK_959AE7D4AA0F6A5 FOREIGN KEY (user_advertiser_id_id) REFERENCES user_advertiser (id)');
        $this->addSql('ALTER TABLE deal_feature ADD CONSTRAINT FK_FBCDFDEE399B5D4B FOREIGN KEY (deal_id_id) REFERENCES deal (id)');
        $this->addSql('ALTER TABLE user_instagram ADD CONSTRAINT FK_8448F3279D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_instagram_category ADD CONSTRAINT FK_4D3DCDB29D86650F FOREIGN KEY (user_id_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE user_instagram_category ADD CONSTRAINT FK_4D3DCDB29777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE user_manager_user_instagram ADD CONSTRAINT FK_8FA48178DF59F28F FOREIGN KEY (user_manager_id) REFERENCES user_manager (id)');
        $this->addSql('ALTER TABLE user_manager_user_instagram ADD CONSTRAINT FK_8FA481781CD5EE0D FOREIGN KEY (user_instagram_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, ADD description LONGTEXT DEFAULT NULL, DROP last_name, DROP profile_url, DROP likes_count, DROP followers_count, DROP phone_number, CHANGE name name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F358DDAE46');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3BB1E4E52');
        $this->addSql('ALTER TABLE deal DROP FOREIGN KEY FK_E3FEC116607FBD82');
        $this->addSql('ALTER TABLE deal_accepted DROP FOREIGN KEY FK_959AE7D4399B5D4B');
        $this->addSql('ALTER TABLE deal_accepted DROP FOREIGN KEY FK_959AE7D4AA0F6A5');
        $this->addSql('ALTER TABLE deal_feature DROP FOREIGN KEY FK_FBCDFDEE399B5D4B');
        $this->addSql('ALTER TABLE user_instagram DROP FOREIGN KEY FK_8448F3279D86650F');
        $this->addSql('ALTER TABLE user_instagram_category DROP FOREIGN KEY FK_4D3DCDB29D86650F');
        $this->addSql('ALTER TABLE user_instagram_category DROP FOREIGN KEY FK_4D3DCDB29777D11E');
        $this->addSql('ALTER TABLE user_manager_user_instagram DROP FOREIGN KEY FK_8FA48178DF59F28F');
        $this->addSql('ALTER TABLE user_manager_user_instagram DROP FOREIGN KEY FK_8FA481781CD5EE0D');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE deal');
        $this->addSql('DROP TABLE deal_accepted');
        $this->addSql('DROP TABLE deal_feature');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE user_advertiser');
        $this->addSql('DROP TABLE user_instagram');
        $this->addSql('DROP TABLE user_instagram_category');
        $this->addSql('DROP TABLE user_manager');
        $this->addSql('DROP TABLE user_manager_user_instagram');
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(255) DEFAULT NULL, ADD profile_url VARCHAR(255) DEFAULT NULL, ADD likes_count INT DEFAULT NULL, ADD followers_count INT DEFAULT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL, DROP is_verified, DROP description, CHANGE name name VARCHAR(255) DEFAULT NULL');
    }
}
