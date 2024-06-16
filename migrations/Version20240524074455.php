<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240524074455 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bid_image (id INT AUTO_INCREMENT NOT NULL, bid_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DA06278A4D9866B8 (bid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bid_image ADD CONSTRAINT FK_DA06278A4D9866B8 FOREIGN KEY (bid_id) REFERENCES bid (id)');
        $this->addSql('ALTER TABLE user CHANGE is_deleted is_deleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bid_image DROP FOREIGN KEY FK_DA06278A4D9866B8');
        $this->addSql('DROP TABLE bid_image');
        $this->addSql('ALTER TABLE user CHANGE is_deleted is_deleted TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
