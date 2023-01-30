<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130195153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_C74404557E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) DEFAULT NULL, description VARCHAR(10000) DEFAULT NULL, INDEX IDX_2FB3D0EE7E3C61F9 (owner_id), INDEX IDX_2FB3D0EE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', project_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_527EDB257E3C61F9 (owner_id), INDEX IDX_527EDB25166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_entry (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', project_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', task_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', start_time DATETIME NOT NULL, UNIQUE INDEX UNIQ_6AD0DE1A7E3C61F9 (owner_id), UNIQUE INDEX UNIQ_6AD0DE1A19EB6921 (client_id), UNIQUE INDEX UNIQ_6AD0DE1A166D1F9C (project_id), UNIQUE INDEX UNIQ_6AD0DE1A8DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404557E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE timer ADD CONSTRAINT FK_6AD0DE1A8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404557E3C61F9');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7E3C61F9');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19EB6921');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB257E3C61F9');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A7E3C61F9');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A19EB6921');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A166D1F9C');
        $this->addSql('ALTER TABLE timer DROP FOREIGN KEY FK_6AD0DE1A8DB60186');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE time_entry');
        $this->addSql('DROP TABLE timer');
        $this->addSql('DROP TABLE user');
    }
}
