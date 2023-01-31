<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131154349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_entry ADD owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD client_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD project_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD task_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0C8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_6E537C0C7E3C61F9 ON time_entry (owner_id)');
        $this->addSql('CREATE INDEX IDX_6E537C0C19EB6921 ON time_entry (client_id)');
        $this->addSql('CREATE INDEX IDX_6E537C0C166D1F9C ON time_entry (project_id)');
        $this->addSql('CREATE INDEX IDX_6E537C0C8DB60186 ON time_entry (task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C7E3C61F9');
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C19EB6921');
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C166D1F9C');
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0C8DB60186');
        $this->addSql('DROP INDEX IDX_6E537C0C7E3C61F9 ON time_entry');
        $this->addSql('DROP INDEX IDX_6E537C0C19EB6921 ON time_entry');
        $this->addSql('DROP INDEX IDX_6E537C0C166D1F9C ON time_entry');
        $this->addSql('DROP INDEX IDX_6E537C0C8DB60186 ON time_entry');
        $this->addSql('ALTER TABLE time_entry DROP owner_id, DROP client_id, DROP project_id, DROP task_id');
    }
}
