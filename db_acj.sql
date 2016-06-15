-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 15 Juin 2016 à 15:43
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db_acj`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_concours`
--

CREATE TABLE IF NOT EXISTS `t_concours` (
  `id_concours` int(11) NOT NULL AUTO_INCREMENT,
  `intitule` varchar(50) NOT NULL,
  `lieu` varchar(100) NOT NULL,
  `nb_places` int(6) NOT NULL,
  `date_concours` date NOT NULL,
  `date_limite_inscription` date NOT NULL,
  PRIMARY KEY (`id_concours`),
  UNIQUE KEY `intitule` (`intitule`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Contenu de la table `t_concours`
--

INSERT INTO `t_concours` (`id_concours`, `intitule`, `lieu`, `nb_places`, `date_concours`, `date_limite_inscription`) VALUES
(12, 'Adieux', 'CFPT', 124, '2016-06-15', '2016-06-08'),
(13, 'passe', 'meinier', 12, '2016-04-01', '2016-05-25'),
(14, 'Vieux', 'Wakfu', 13, '2016-05-12', '2016-05-05'),
(15, 'Nouveau', '12345', 2, '2016-05-23', '2016-06-16'),
(23, 'test2', 'dsaf', 12, '2016-06-23', '2016-06-16');

-- --------------------------------------------------------

--
-- Structure de la table `t_inscrits`
--

CREATE TABLE IF NOT EXISTS `t_inscrits` (
  `id_concours` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `score` int(3) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id_concours`,`id_membre`),
  KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_inscrits`
--

INSERT INTO `t_inscrits` (`id_concours`, `id_membre`, `score`) VALUES
(13, 1, 422),
(13, 2, 586),
(14, 2, 600),
(15, 1, -1),
(15, 2, -1);

-- --------------------------------------------------------

--
-- Structure de la table `t_membres`
--

CREATE TABLE IF NOT EXISTS `t_membres` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `num_licence` int(5) NOT NULL,
  `mdp` varchar(40) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `date_naissance` date NOT NULL,
  `est_admin` tinyint(1) NOT NULL DEFAULT '0',
  `est_valide` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_membre`),
  UNIQUE KEY `num_licence` (`num_licence`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `t_membres`
--

INSERT INTO `t_membres` (`id_membre`, `num_licence`, `mdp`, `nom`, `prenom`, `date_naissance`, `est_admin`, `est_valide`) VALUES
(1, 10000, 'f6889fc97e14b42dec11a8c183ea791c5465b658', 'Admin', 'Admin', '2016-06-10', 1, 1),
(2, 20002, 'f6889fc97e14b42dec11a8c183ea791c5465b658', 'Test', 'Test', '2016-06-09', 0, 1),
(17, 30000, 'f6889fc97e14b42dec11a8c183ea791c5465b658', 'Delobruno', 'Fabio', '2016-02-01', 0, 1),
(19, 12355, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Budry', 'Nohan', '1997-04-17', 0, 1),
(21, 12345, 'f6889fc97e14b42dec11a8c183ea791c5465b658', 'Thomas', 'Carreira', '2016-06-10', 0, 1),
(22, 21412, 'f6889fc97e14b42dec11a8c183ea791c5465b658', 'Perez', 'Marlon', '1993-06-16', 0, 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `t_inscrits`
--
ALTER TABLE `t_inscrits`
  ADD CONSTRAINT `t_inscrits_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `t_membres` (`id_membre`),
  ADD CONSTRAINT `t_inscrits_ibfk_2` FOREIGN KEY (`id_concours`) REFERENCES `t_concours` (`id_concours`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
