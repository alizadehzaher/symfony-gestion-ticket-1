<?php
// migrations/Version20240101000000.php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create User and Ticket tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, responsible_id INT DEFAULT NULL, author VARCHAR(255) NOT NULL, open_date DATETIME NOT NULL, close_date DATETIME DEFAULT NULL, description LONGTEXT NOT NULL, category VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_97A0ADA3602AD315 (responsible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3602AD315 FOREIGN KEY (responsible_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3602AD315');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
    }
}