-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 25 mai 2025 à 21:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eco`
--

-- --------------------------------------------------------

--
-- Structure de la table `cati`
--

CREATE TABLE `cati` (
  `id` int(100) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cati`
--

INSERT INTO `cati` (`id`, `libelle`, `description`, `date`) VALUES
(2, 'viti', 'ffffg', '2025-02-27 01:07:50'),
(3, 'fuit', 'fuit', '2025-02-27 23:37:05');

-- --------------------------------------------------------

--
-- Structure de la table `pro`
--

CREATE TABLE `pro` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `discount` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pro`
--

INSERT INTO `pro` (`id`, `libelle`, `prix`, `discount`, `id_cat`, `date`) VALUES
(1, 'danon', 8, 8, 1, '2025-02-27 00:00:00'),
(2, 'hh', 4, 1, 1, '2025-02-27 00:00:00'),
(3, 'd', 4, 8, 1, '2025-02-27 00:00:00'),
(4, 'd', 4, 8, 1, '2025-02-27 00:00:00'),
(7, 'danon', 10, 2, 2, '2025-02-27 00:00:00'),
(8, 'fuit', 4, 5, 2, '2025-02-27 00:00:00'),
(9, 'hh', 82, 4, 2, '2025-02-27 00:00:00'),
(10, 'kiri', 6, 4, 2, '2025-02-27 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `date`) VALUES
(18, 'skizopan', '', '2025-02-25'),
(19, 'skizopan', '', '2025-02-26'),
(20, 'admin', '123456', '2025-02-26'),
(21, 'admin', '', '2025-02-26'),
(22, 'skizopand', 'd', '0000-00-00'),
(23, 'skizopan', '$2y$10$4Qw2k.2Ssmpe2xVbycDaXOIbACk0m7R3u/3yaRg2u6odGDVbXiLb6', '2025-02-27');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cati`
--
ALTER TABLE `cati`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pro`
--
ALTER TABLE `pro`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cati`
--
ALTER TABLE `cati`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `pro`
--
ALTER TABLE `pro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
