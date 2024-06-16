<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240606134202 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE deal_accepted DROP FOREIGN KEY FK_959AE7D4399B5D4B');
        $this->addSql('ALTER TABLE deal_accepted DROP FOREIGN KEY FK_959AE7D4AA0F6A5');
        $this->addSql('ALTER TABLE deal_feature DROP FOREIGN KEY FK_FBCDFDEE399B5D4B');
        $this->addSql('ALTER TABLE user_manager_user_instagram DROP FOREIGN KEY FK_8FA481781CD5EE0D');
        $this->addSql('ALTER TABLE user_manager_user_instagram DROP FOREIGN KEY FK_8FA48178DF59F28F');
        $this->addSql('DROP TABLE deal_accepted');
        $this->addSql('DROP TABLE user_manager');
        $this->addSql('DROP TABLE user_advertiser');
        $this->addSql('DROP TABLE deal_feature');
        $this->addSql('DROP TABLE user_manager_user_instagram');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE deal_accepted (id INT AUTO_INCREMENT NOT NULL, deal_id_id INT DEFAULT NULL, user_advertiser_id_id INT DEFAULT NULL, INDEX IDX_959AE7D4399B5D4B (deal_id_id), INDEX IDX_959AE7D4AA0F6A5 (user_advertiser_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_manager (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_advertiser (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE deal_feature (id INT AUTO_INCREMENT NOT NULL, deal_id_id INT DEFAULT NULL, name LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_FBCDFDEE399B5D4B (deal_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_manager_user_instagram (id INT AUTO_INCREMENT NOT NULL, user_manager_id INT DEFAULT NULL, user_instagram_id INT DEFAULT NULL, INDEX IDX_8FA481781CD5EE0D (user_instagram_id), INDEX IDX_8FA48178DF59F28F (user_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE deal_accepted ADD CONSTRAINT FK_959AE7D4399B5D4B FOREIGN KEY (deal_id_id) REFERENCES deal (id)');
        $this->addSql('ALTER TABLE deal_accepted ADD CONSTRAINT FK_959AE7D4AA0F6A5 FOREIGN KEY (user_advertiser_id_id) REFERENCES user_advertiser (id)');
        $this->addSql('ALTER TABLE deal_feature ADD CONSTRAINT FK_FBCDFDEE399B5D4B FOREIGN KEY (deal_id_id) REFERENCES deal (id)');
        $this->addSql('ALTER TABLE user_manager_user_instagram ADD CONSTRAINT FK_8FA481781CD5EE0D FOREIGN KEY (user_instagram_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE user_manager_user_instagram ADD CONSTRAINT FK_8FA48178DF59F28F FOREIGN KEY (user_manager_id) REFERENCES user_manager (id)');
    }
}
