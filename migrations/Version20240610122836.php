<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610122836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_instagram DROP FOREIGN KEY FK_8448F3279D86650F');
        $this->addSql('DROP INDEX UNIQ_8448F3279D86650F ON user_instagram');
        $this->addSql('ALTER TABLE user_instagram CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_instagram ADD CONSTRAINT FK_8448F327A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8448F327A76ED395 ON user_instagram (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_instagram DROP FOREIGN KEY FK_8448F327A76ED395');
        $this->addSql('DROP INDEX UNIQ_8448F327A76ED395 ON user_instagram');
        $this->addSql('ALTER TABLE user_instagram CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_instagram ADD CONSTRAINT FK_8448F3279D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8448F3279D86650F ON user_instagram (user_id_id)');
    }
}
