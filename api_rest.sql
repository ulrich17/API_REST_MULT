-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 24 sep. 2025 à 15:23
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api_rest`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie_vehicule`
--

DROP TABLE IF EXISTS `categorie_vehicule`;
CREATE TABLE IF NOT EXISTS `categorie_vehicule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle_categorie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_vehicule`
--

INSERT INTO `categorie_vehicule` (`id`, `libelle_categorie`) VALUES
(1, 'Léger'),
(2, 'Lourd');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datenaissance` date NOT NULL,
  `lieunaissance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adressemail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codepostal` int NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `datenaissance`, `lieunaissance`, `adressemail`, `telephone`, `adresse`, `codepostal`, `ville`) VALUES
(1, 'FLORIAN', 'Portrait', '2000-03-22', 'Brazzaville', 'portrait@gmail.com', '0696792342', '23 Bd DON QUICHOTTE', 84000, 'La Roche-Sur-Yon'),
(2, 'HAUTE VILLE', 'Erick', '1970-06-12', 'La Rochelle', 'erick@gmail.com', '0753622942', '15 Rue François Vaux de Foletier', 17000, 'La Rochelle'),
(3, 'MAHE', 'Elisabeth', '1980-07-18', 'Paris', 'mahe@gmail.com', '0667987654', '10 Rue des pépinières', 94400, 'Vitry-Sur-Seine'),
(4, 'MARTIN', 'Paul', '1988-11-05', 'Marseille', 'paul.martin@example.com', '0687654321', '45 avenue du Prado', 13008, 'Marseille');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250905150719', '2025-09-05 15:07:31', 22),
('DoctrineMigrations\\Version20250905154905', '2025-09-05 15:49:12', 67),
('DoctrineMigrations\\Version20250908074348', '2025-09-08 07:44:05', 109),
('DoctrineMigrations\\Version20250911082246', '2025-09-11 08:23:35', 98),
('DoctrineMigrations\\Version20250911115611', '2025-09-11 11:58:46', 80),
('DoctrineMigrations\\Version20250911115852', '2025-09-11 11:59:00', 29),
('DoctrineMigrations\\Version20250911120709', '2025-09-11 12:07:16', 129),
('DoctrineMigrations\\Version20250911122756', '2025-09-11 12:28:01', 148),
('DoctrineMigrations\\Version20250911124109', '2025-09-11 12:53:43', 315),
('DoctrineMigrations\\Version20250911124853', '2025-09-11 12:53:43', 12),
('DoctrineMigrations\\Version20250911125136', '2025-09-11 12:53:43', 8),
('DoctrineMigrations\\Version20250911125142', '2025-09-11 12:53:43', 6),
('DoctrineMigrations\\Version20250911125338', '2025-09-11 12:53:43', 8),
('DoctrineMigrations\\Version20250911130014', '2025-09-11 13:00:19', 154),
('DoctrineMigrations\\Version20250912080727', '2025-09-12 08:07:36', 190),
('DoctrineMigrations\\Version20250912131508', '2025-09-12 13:15:16', 91),
('DoctrineMigrations\\Version20250922151343', '2025-09-22 15:14:10', 119);

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vehicule_id` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E9E89CB19EB6921` (`client_id`),
  KEY `IDX_5E9E89CB4A4A3511` (`vehicule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `location`
--

INSERT INTO `location` (`id`, `vehicule_id`, `date_debut`, `date_fin`, `client_id`) VALUES
(1, 1, '2025-09-06', '2025-09-15', 1),
(2, 5, '2025-09-04', '2025-09-06', 2),
(3, 3, '2025-09-04', '2025-09-12', 3),
(4, 7, '2025-09-10', '2025-10-10', 1),
(5, 1, '2025-06-12', '2025-07-21', 2),
(6, 8, '2025-09-26', '2025-09-30', 3);

-- --------------------------------------------------------

--
-- Structure de la table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE IF NOT EXISTS `maintenance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idvehicule` int NOT NULL,
  `typemaintenance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_planifie` date NOT NULL,
  `date_effectuee` date NOT NULL,
  `cout` double NOT NULL,
  `kilometrage` int NOT NULL,
  `observations` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `typealerte` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `maintenance`
--

INSERT INTO `maintenance` (`id`, `idvehicule`, `typemaintenance`, `description`, `date_planifie`, `date_effectuee`, `cout`, `kilometrage`, `observations`, `typealerte`) VALUES
(1, 1, 'Revision', 'Remplacement de l’huile moteur et du filtre à huile.', '2024-09-15', '2024-09-16', 120.5, 45200, 'Niveau d’huile initialement très bas, contrôle conseillé dans 3 000 km.', 'Preventive'),
(2, 3, 'Révision des freins', 'Remplacement des plaquettes de frein avant et arrière.', '2024-10-05', '2024-10-06', 320, 60500, 'Usure avancée détectée sur les plaquettes arrière.', 'Critique'),
(13, 8, 'Changement des plaquettes de frein', 'Remplacement des plaquettes avant et arrière pour sécurité optimale.', '2024-09-12', '2024-09-17', 590.9, 72200, 'Usure régulière, pas d’anomalie détectée.', 'Critique'),
(14, 8, 'Remplacement batterie', 'Installation d’une nouvelle batterie 12V avec garantie 2 ans.', '2024-11-12', '2024-11-13', 230.75, 65200, 'Ancienne batterie fortement déchargée, démarrage difficile.', 'Critique'),
(16, 5, 'Changement des plaquettes de frein', 'Remplacement des plaquettes avant et arrière pour sécurité optimale.', '2024-09-20', '2025-09-24', 590.9, 72200, 'Usure régulière, pas d’anomalie détectée.', 'Critique');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sinistres`
--

DROP TABLE IF EXISTS `sinistres`;
CREATE TABLE IF NOT EXISTS `sinistres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_vehicule` int NOT NULL,
  `date_sinistre` date NOT NULL,
  `lieu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluation_degat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant_estime` double NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observations` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sinistre` (`id_vehicule`,`date_sinistre`,`description`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sinistres`
--

INSERT INTO `sinistres` (`id`, `id_vehicule`, `date_sinistre`, `lieu`, `description`, `evaluation_degat`, `montant_estime`, `statut`, `observations`) VALUES
(1, 5, '2022-09-16', 'Bordeaux', 'Véhicule endommagé lors d’une forte grêle.', 'Nombreuses bosses sur le toit et le capot.', 3000, 'En cours d’expertise', 'Attente du rapport de l’expert mandaté par l’assurance.'),
(6, 1, '2024-08-25', 'Vitry-Sur-Seine', 'Collision avec un autre véhicule.', 'Nombreuses bosses sur le toit et le capot.', 3000, 'En cours d\'expertise', 'Aucun blessé signalé, constat amiable rempli sur place.');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(1, 'admin', '[]', '$2y$10$NeFp5EKutv6JjCspTuI1WO1kkXwDLZC2bE3wrpLDQo8sQMoWGst.m'),
(3, 'user', '[\"ROLE_USER\"]', '$2y$13$doxJPm8XTcqXUjx3dZUUaOwjnzLCuL0PcqUgqY1wDROPglIEQ31nC');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

DROP TABLE IF EXISTS `vehicule`;
CREATE TABLE IF NOT EXISTS `vehicule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_vehicule_id` int NOT NULL,
  `immatriculation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marque` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modele` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee` int NOT NULL,
  `couleur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_entree` date NOT NULL,
  `date_sortie` date DEFAULT NULL,
  `observations` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_292FFF1DBE73422E` (`immatriculation`),
  KEY `IDX_292FFF1D95B41318` (`categorie_vehicule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`id`, `categorie_vehicule_id`, `immatriculation`, `marque`, `modele`, `annee`, `couleur`, `etat`, `date_entree`, `date_sortie`, `observations`, `statut`) VALUES
(1, 1, 'DC-158-HH', 'Nissan', 'Santa Fe', 2016, 'Blanche', 'Disponible', '2020-09-02', '2021-09-02', '', 'Disponible'),
(3, 1, 'BB-145-GU', 'TOYOTA', 'Corolla', 2023, 'Noire', 'Pret', '2012-12-27', '2021-10-12', '', 'Disponible'),
(5, 1, 'BC-128-GA', 'TOYOTA', 'Corolla', 2001, 'Grise', 'Pret', '2020-09-02', '2021-10-12', '', 'Disponible'),
(6, 1, 'DC-165-AA', 'TOYOTA', 'Corolla', 2001, 'Grise', 'Pret', '2020-09-02', '2020-10-02', '', 'Non disponible'),
(7, 1, 'BB-245-EE', 'TOYOTA', 'Corolla', 2001, 'Grise', 'Pret', '2020-09-02', '2021-09-02', '', 'Non disponible'),
(8, 1, 'CA-128-AK', 'Renault', 'Clio', 2015, 'Blanc', 'Prêt', '2024-05-12', '2024-07-22', '', 'Non disponible');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `FK_5E9E89CB19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_5E9E89CB4A4A3511` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicule` (`id`);

--
-- Contraintes pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `FK_292FFF1D95B41318` FOREIGN KEY (`categorie_vehicule_id`) REFERENCES `categorie_vehicule` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
