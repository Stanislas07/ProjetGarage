-- Création de la base de données
CREATE DATABASE GarageVoituresOccasion;
USE GarageVoituresOccasion;

-- Table pour les marques
CREATE TABLE Marque (
    id_marque INT AUTO_INCREMENT PRIMARY KEY,
    nom_marque VARCHAR(50) NOT NULL
);

-- Table pour les modèles
CREATE TABLE Modele (
    id_modele INT AUTO_INCREMENT PRIMARY KEY,
    nom_modele VARCHAR(50) NOT NULL,
    id_marque INT NOT NULL,
    CONSTRAINT fk_modele_marque FOREIGN KEY (id_marque) REFERENCES Marque(id_marque) ON DELETE CASCADE
);

-- Table pour les kilométrages
CREATE TABLE Kilometrage (
    id_kilometrage INT AUTO_INCREMENT PRIMARY KEY,
    valeur_kilometrage INT NOT NULL
);

-- Table pour les prix
CREATE TABLE Prix (
    id_prix INT AUTO_INCREMENT PRIMARY KEY,
    valeur_prix DECIMAL(10, 2) NOT NULL
);

-- Table pour les couleurs
CREATE TABLE Couleur (
    id_couleur INT AUTO_INCREMENT PRIMARY KEY,
    nom_couleur VARCHAR(20) NOT NULL
);

-- Table pour les carburants
CREATE TABLE Carburant (
    id_carburant INT AUTO_INCREMENT PRIMARY KEY,
    type_carburant VARCHAR(20) NOT NULL
);

-- Table pour les transmissions (boite de vitesse)
CREATE TABLE Transmission (
    id_transmission INT AUTO_INCREMENT PRIMARY KEY,
    type_transmission VARCHAR(20) NOT NULL
);

-- Table pour les voitures
CREATE TABLE `voiture` (
	`id_voiture` INT(10) NOT NULL AUTO_INCREMENT,
	`id_marque` INT(10) NOT NULL,
	`id_modele` INT(10) NOT NULL,
	`id_kilometrage` INT(10) NOT NULL,
	`id_prix` INT(10) NOT NULL,
	`id_couleur` INT(10) NOT NULL,
	`id_carburant` INT(10) NOT NULL,
	`id_transmission` INT(10) NOT NULL,
	`date_arrivee` DATE NOT NULL,
	`description` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`image_url` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	PRIMARY KEY (`id_voiture`) USING BTREE,
	INDEX `fk_marque` (`id_marque`) USING BTREE,
	INDEX `fk_modele` (`id_modele`) USING BTREE,
	INDEX `fk_kilometrage` (`id_kilometrage`) USING BTREE,
	INDEX `fk_prix` (`id_prix`) USING BTREE,
	INDEX `fk_couleur` (`id_couleur`) USING BTREE,
	INDEX `fk_carburant` (`id_carburant`) USING BTREE,
	INDEX `fk_transmission` (`id_transmission`) USING BTREE,
	CONSTRAINT `fk_carburant` FOREIGN KEY (`id_carburant`) REFERENCES `carburant` (`id_carburant`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `fk_couleur` FOREIGN KEY (`id_couleur`) REFERENCES `couleur` (`id_couleur`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `fk_kilometrage` FOREIGN KEY (`id_kilometrage`) REFERENCES `kilometrage` (`id_kilometrage`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `fk_marque` FOREIGN KEY (`id_marque`) REFERENCES `marque` (`id_marque`) ON UPDATE NO ACTION ON DELETE CASCADE,
	CONSTRAINT `fk_modele` FOREIGN KEY (`id_modele`) REFERENCES `modele` (`id_modele`) ON UPDATE NO ACTION ON DELETE CASCADE,
	CONSTRAINT `fk_prix` FOREIGN KEY (`id_prix`) REFERENCES `prix` (`id_prix`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `fk_transmission` FOREIGN KEY (`id_transmission`) REFERENCES `transmission` (`id_transmission`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=7
;

-- Table pour les employés
CREATE TABLE Employe (
    id_employe INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    poste VARCHAR(50),
    date_embauche DATE NOT NULL,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE, -- Nom d'utilisateur pour la connexion
    mot_de_passe VARCHAR(255) NOT NULL, -- Mot de passe haché pour la sécurité
    role ENUM('admin', 'employe') NOT NULL -- Rôle : admin ou employé
);
