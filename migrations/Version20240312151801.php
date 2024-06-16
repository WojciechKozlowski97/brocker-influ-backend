<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240312151801 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_activation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, email_verification_token VARCHAR(64) DEFAULT NULL, UNIQUE INDEX UNIQ_BB0FA69BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_activation ADD CONSTRAINT FK_BB0FA69BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_activation DROP FOREIGN KEY FK_BB0FA69BA76ED395');
        $this->addSql('DROP TABLE user_activation');
    }
}
