<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610121527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F358DDAE46');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3BB1E4E52');
        $this->addSql('DROP INDEX IDX_4AF2B3F358DDAE46 ON bid');
        $this->addSql('DROP INDEX IDX_4AF2B3F3BB1E4E52 ON bid');
        $this->addSql('ALTER TABLE bid ADD user_instagram_id INT DEFAULT NULL, ADD site_id INT DEFAULT NULL, DROP user_instagram_id_id, DROP site_id_id');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F31CD5EE0D FOREIGN KEY (user_instagram_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_4AF2B3F31CD5EE0D ON bid (user_instagram_id)');
        $this->addSql('CREATE INDEX IDX_4AF2B3F3F6BD1646 ON bid (site_id)');
        $this->addSql('ALTER TABLE deal DROP FOREIGN KEY FK_E3FEC116607FBD82');
        $this->addSql('DROP INDEX IDX_E3FEC116607FBD82 ON deal');
        $this->addSql('ALTER TABLE deal CHANGE bid_id_id bid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC1164D9866B8 FOREIGN KEY (bid_id) REFERENCES bid (id)');
        $this->addSql('CREATE INDEX IDX_E3FEC1164D9866B8 ON deal (bid_id)');
        $this->addSql('ALTER TABLE user_instagram_category DROP FOREIGN KEY FK_4D3DCDB29D86650F');
        $this->addSql('ALTER TABLE user_instagram_category DROP FOREIGN KEY FK_4D3DCDB29777D11E');
        $this->addSql('DROP INDEX IDX_4D3DCDB29D86650F ON user_instagram_category');
        $this->addSql('DROP INDEX IDX_4D3DCDB29777D11E ON user_instagram_category');
        $this->addSql('ALTER TABLE user_instagram_category ADD user_id INT DEFAULT NULL, ADD category_id INT DEFAULT NULL, DROP user_id_id, DROP category_id_id');
        $this->addSql('ALTER TABLE user_instagram_category ADD CONSTRAINT FK_4D3DCDB2A76ED395 FOREIGN KEY (user_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE user_instagram_category ADD CONSTRAINT FK_4D3DCDB212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_4D3DCDB2A76ED395 ON user_instagram_category (user_id)');
        $this->addSql('CREATE INDEX IDX_4D3DCDB212469DE2 ON user_instagram_category (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deal DROP FOREIGN KEY FK_E3FEC1164D9866B8');
        $this->addSql('DROP INDEX IDX_E3FEC1164D9866B8 ON deal');
        $this->addSql('ALTER TABLE deal CHANGE bid_id bid_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC116607FBD82 FOREIGN KEY (bid_id_id) REFERENCES bid (id)');
        $this->addSql('CREATE INDEX IDX_E3FEC116607FBD82 ON deal (bid_id_id)');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F31CD5EE0D');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3F6BD1646');
        $this->addSql('DROP INDEX IDX_4AF2B3F31CD5EE0D ON bid');
        $this->addSql('DROP INDEX IDX_4AF2B3F3F6BD1646 ON bid');
        $this->addSql('ALTER TABLE bid ADD user_instagram_id_id INT DEFAULT NULL, ADD site_id_id INT DEFAULT NULL, DROP user_instagram_id, DROP site_id');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F358DDAE46 FOREIGN KEY (user_instagram_id_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3BB1E4E52 FOREIGN KEY (site_id_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_4AF2B3F358DDAE46 ON bid (user_instagram_id_id)');
        $this->addSql('CREATE INDEX IDX_4AF2B3F3BB1E4E52 ON bid (site_id_id)');
        $this->addSql('ALTER TABLE user_instagram_category DROP FOREIGN KEY FK_4D3DCDB2A76ED395');
        $this->addSql('ALTER TABLE user_instagram_category DROP FOREIGN KEY FK_4D3DCDB212469DE2');
        $this->addSql('DROP INDEX IDX_4D3DCDB2A76ED395 ON user_instagram_category');
        $this->addSql('DROP INDEX IDX_4D3DCDB212469DE2 ON user_instagram_category');
        $this->addSql('ALTER TABLE user_instagram_category ADD user_id_id INT DEFAULT NULL, ADD category_id_id INT DEFAULT NULL, DROP user_id, DROP category_id');
        $this->addSql('ALTER TABLE user_instagram_category ADD CONSTRAINT FK_4D3DCDB29D86650F FOREIGN KEY (user_id_id) REFERENCES user_instagram (id)');
        $this->addSql('ALTER TABLE user_instagram_category ADD CONSTRAINT FK_4D3DCDB29777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_4D3DCDB29D86650F ON user_instagram_category (user_id_id)');
        $this->addSql('CREATE INDEX IDX_4D3DCDB29777D11E ON user_instagram_category (category_id_id)');
    }
}
