<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260924040355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_attribute DROP CONSTRAINT fk_fa9a621fa76ed395');
        $this->addSql('DROP INDEX idx_fa9a621fa76ed395');
        $this->addSql('ALTER TABLE user_attribute DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_attribute ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_attribute ADD CONSTRAINT fk_fa9a621fa76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fa9a621fa76ed395 ON user_attribute (user_id)');
    }
}
