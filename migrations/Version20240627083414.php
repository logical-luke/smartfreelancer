<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627083414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synchronization_log CHANGE action action VARCHAR(255) NOT NULL, CHANGE resource resource VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE task ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP created_at');
        $this->addSql('ALTER TABLE synchronization_log CHANGE resource resource VARCHAR(255) DEFAULT NULL, CHANGE action action VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
