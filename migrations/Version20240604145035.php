<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240604145035 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_instagram ADD username VARCHAR(255) NOT NULL, DROP photo, DROP likes_count, CHANGE instagram_id instagram_id BIGINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_instagram ADD photo VARCHAR(255) DEFAULT NULL, ADD likes_count INT NOT NULL, DROP username, CHANGE instagram_id instagram_id INT DEFAULT NULL');
    }
}
