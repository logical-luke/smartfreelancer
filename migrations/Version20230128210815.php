<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128210815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timer DROP INDEX IDX_6AD0DE1A166D1F9C, ADD UNIQUE INDEX UNIQ_6AD0DE1A166D1F9C (project_id)');
        $this->addSql('ALTER TABLE timer ADD client_id INT DEFAULT NULL, ADD task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AD0DE1A19EB6921 ON timer (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AD0DE1A8DB60186 ON timer (task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timer DROP INDEX UNIQ_6AD0DE1A166D1F9C, ADD INDEX IDX_6AD0DE1A166D1F9C (project_id)');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A19EB6921');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A8DB60186');
        $this->addSql('DROP INDEX UNIQ_6AD0DE1A19EB6921 ON timer');
        $this->addSql('DROP INDEX UNIQ_6AD0DE1A8DB60186 ON timer');
        $this->addSql('ALTER TABLE timer DROP client_id, DROP task_id');
    }
}
