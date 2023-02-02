<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201114650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE description description VARCHAR(10000) DEFAULT NULL');
        $this->addSql('ALTER TABLE task CHANGE description description VARCHAR(10000) DEFAULT NULL');
        $this->addSql('ALTER TABLE time_entry CHANGE start_time start_time DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', CHANGE end_time end_time DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE timer CHANGE start_time start_time DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE timer CHANGE start_time start_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE time_entry CHANGE start_time start_time DATETIME NOT NULL, CHANGE end_time end_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE task CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
