<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120091933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timer (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, project_id INT DEFAULT NULL, start_time DATETIME NOT NULL, UNIQUE INDEX UNIQ_6AD0DE1A7E3C61F9 (owner_id), INDEX IDX_6AD0DE1A166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A7E3C61F9');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A166D1F9C');
        $this->addSql('DROP TABLE timer');
    }
}
