<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703063025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE synchronization_log (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', request_id VARBINARY(255) NOT NULL, payload JSON NOT NULL, queue VARCHAR(255) DEFAULT NULL, action VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, INDEX IDX_F78BDE9EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE synchronization_log ADD CONSTRAINT FK_F78BDE9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synchronization_log DROP FOREIGN KEY FK_F78BDE9EA76ED395');
        $this->addSql('DROP TABLE synchronization_log');
    }
}
