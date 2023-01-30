<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130145407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time_entry (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, project_id INT DEFAULT NULL, client_id INT DEFAULT NULL, task_id INT DEFAULT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, INDEX IDX_6E537C0C7E3C61F9 (owner_id), INDEX IDX_6E537C0C166D1F9C (project_id), INDEX IDX_6E537C0C19EB6921 (client_id), INDEX IDX_6E537C0C8DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C7E3C61F9');
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C166D1F9C');
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C19EB6921');
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C8DB60186');
        $this->addSql('DROP TABLE time_entry');
    }
}
