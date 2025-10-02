-- database.sql
CREATE DATABASE IF NOT EXISTS gestion_tickets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_tickets;

-- Table user
CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL,
    email VARCHAR(180) NOT NULL,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

-- Table ticket
CREATE TABLE ticket (
    id INT AUTO_INCREMENT NOT NULL,
    responsible_id INT DEFAULT NULL,
    author VARCHAR(255) NOT NULL,
    open_date DATETIME NOT NULL,
    close_date DATETIME DEFAULT NULL,
    description LONGTEXT NOT NULL,
    category VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL,
    INDEX IDX_97A0ADA3602AD315 (responsible_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

-- Clé étrangère
ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3602AD315 FOREIGN KEY (responsible_id) REFERENCES user (id);

-- Insertion des utilisateurs de test
-- Mot de passe : "password" crypté
INSERT INTO user (id, email, roles, password, name) VALUES
(1, 'admin@example.com', '[\"ROLE_ADMIN\"]', '$2y$13$p2e./5O5Q5Q5Q5Q5Q5Q5O5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5', 'Administrateur'),
(2, 'staff@example.com', '[\"ROLE_STAFF\"]', '$2y$13$p2e./5O5Q5Q5Q5Q5Q5Q5O5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5', 'Employé 1'),
(3, 'staff2@example.com', '[\"ROLE_STAFF\"]', '$2y$13$p2e./5O5Q5Q5Q5Q5Q5Q5O5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5', 'Employé 2');

-- Insertion des tickets de test
INSERT INTO ticket (id, responsible_id, author, open_date, close_date, description, category, status) VALUES
(1, NULL, 'client1@example.com', NOW(), NULL, 'Problème de connexion à l application. Je ne peux pas me connecter depuis hier matin.', 'Incident', 'Nouveau'),
(2, 2, 'client2@example.com', NOW(), NULL, 'Demande d ajout d une nouvelle fonctionnalité pour exporter les données en CSV.', 'Évolution', 'Ouvert'),
(3, 3, 'client3@example.com', NOW(), NULL, 'Le système de paiement ne fonctionne pas correctement sur mobile.', 'Panne', 'Résolu'),
(4, 2, 'client1@example.com', NOW(), NOW(), 'Question concernant l utilisation de l API et la documentation.', 'Information', 'Fermé'),
(5, NULL, 'client4@example.com', NOW(), NULL, 'Bug dans l affichage des graphiques sur le tableau de bord.', 'Anomalie', 'Nouveau');