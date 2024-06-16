<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240607095719 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            INSERT INTO admin (email, roles, password) VALUES (
                \'admin@influ.pl\',
                \'["ROLE_ADMIN"]\',
                \'$2y$13$ECaYB9BXJ2uuTHnqm9NtZePIpqD6HHW9UL2l5DaM4SLkzInVPq1Ky\'
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE admin');
    }
}
