<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620143420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synchronization_log ADD resource VARCHAR(255) DEFAULT NULL, ADD message VARCHAR(255) DEFAULT NULL, ADD status VARCHAR(255) DEFAULT NULL, DROP queue, DROP model');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synchronization_log ADD queue VARCHAR(255) DEFAULT NULL, ADD model VARCHAR(255) DEFAULT NULL, DROP resource, DROP message, DROP status');
    }
}
