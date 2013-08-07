-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 14 Octobre 2011 à 09:29
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `ptut`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idcat` char(4) NOT NULL DEFAULT '',
  `nomcat` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idcat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idcat`, `nomcat`) VALUES
('cat1', 'simple homme'),
('cat2', 'simple dame'),
('cat3', 'double homme'),
('cat4', 'double dame'),
('cat5', 'double mixte');

-- --------------------------------------------------------

--
-- Structure de la table `deroulementmatch`
--

CREATE TABLE IF NOT EXISTS `deroulementmatch` (
  `idterrain` int(10) unsigned NOT NULL DEFAULT '0',
  `idmatch` int(10) unsigned NOT NULL DEFAULT '0',
  `ordre` int(2) DEFAULT NULL,
  `idtournoi` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idterrain`,`idmatch`),
  KEY `fk_deroulement_tournoi` (`idtournoi`),
  KEY `fk_deroulementMatchs2` (`idmatch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `deroulementmatch`
--


-- --------------------------------------------------------

--
-- Structure de la table `equipe`
--

CREATE TABLE IF NOT EXISTS `equipe` (
  `idequipe` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomequipe` varchar(50) DEFAULT NULL,
  `nbvictoire` int(2) DEFAULT NULL,
  `nbdefaite` int(2) DEFAULT NULL,
  `idpoule` int(10) unsigned DEFAULT NULL,
  `idtournoi` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idequipe`),
  KEY `fk_equipe_poule` (`idpoule`),
  KEY `fk_equipe_tournoi` (`idtournoi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `equipe`
--

INSERT INTO `equipe` (`idequipe`, `nomequipe`, `nbvictoire`, `nbdefaite`, `idpoule`, `idtournoi`) VALUES
(2, 'Equipe1', 4, 2, 9, 1),
(3, 'Equipe2', 4, 3, 12, 1),
(4, 'Equipe3', 2, 4, 12, 1),
(5, 'Equipe4', 5, 3, 11, 1),
(6, 'Equipe5', 2, 3, 9, 1),
(7, 'Equipe6', 3, 4, 11, 1),
(8, 'Equipe7', 2, 3, 10, 1),
(9, 'Equipe8', 1, 3, 11, 1),
(10, 'Equipe9', 4, 1, 11, 1),
(11, 'Equipe10', 0, 4, 10, 1),
(12, 'Equipe11', 1, 4, 9, 1),
(13, 'Equipe12', 4, 2, 10, 1),
(14, 'Equipe13', 3, 2, 9, 1),
(15, 'Equipe14', 2, 3, 11, 1),
(16, 'Equipe15', 1, 3, 9, 1),
(17, 'Equipe16', 5, 1, 12, 1),
(18, 'Equipe17', 3, 2, 12, 1),
(19, 'Equipe18', 3, 2, 10, 1),
(20, 'Equipe19', 0, 4, 12, 1),
(21, 'Equipe20', 6, 2, 10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `matchs`
--

CREATE TABLE IF NOT EXISTS `matchs` (
  `idmatch` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idequipe1` int(10) unsigned DEFAULT NULL,
  `idequipe2` int(10) unsigned DEFAULT NULL,
  `score1` int(2) DEFAULT NULL,
  `score2` int(2) DEFAULT NULL,
  `heurematch` date DEFAULT NULL,
  `estfini` tinyint(1) DEFAULT NULL,
  `estEnCours` tinyint(1) DEFAULT NULL,
  `idtournoi` int(10) unsigned DEFAULT NULL,
  `idtypematch` int(10) unsigned DEFAULT NULL,
  `idterrainjoue` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idmatch`),
  KEY `fk_matchs_tournoi` (`idtournoi`),
  KEY `fk_match_equipe1` (`idequipe1`),
  KEY `fk_match_equipe2` (`idequipe2`),
  KEY `fk_match_typematch` (`idtypematch`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Contenu de la table `matchs`
--

INSERT INTO `matchs` (`idmatch`, `idequipe1`, `idequipe2`, `score1`, `score2`, `heurematch`, `estfini`, `estEnCours`, `idtournoi`, `idtypematch`, `idterrainjoue`) VALUES
(1, 14, 12, 21, 15, NULL, 1, 0, 1, 1, 1),
(2, 6, 16, 21, 15, NULL, 1, 0, 1, 1, 1),
(3, 2, 12, 21, 15, NULL, 1, 0, 1, 1, 1),
(4, 6, 14, 15, 21, NULL, 1, 0, 1, 1, 1),
(5, 16, 12, 21, 15, NULL, 1, 0, 1, 1, 1),
(6, 6, 2, 21, 15, NULL, 1, 0, 1, 1, 1),
(7, 16, 14, 15, 21, NULL, 1, 0, 1, 1, 1),
(8, 6, 12, 15, 21, NULL, 1, 0, 1, 1, 1),
(9, 2, 14, 21, 15, NULL, 1, 0, 1, 1, 1),
(10, 2, 16, 21, 15, NULL, 1, 0, 1, 1, 1),
(11, 19, 21, 21, 15, NULL, 1, 0, 1, 1, 2),
(12, 8, 13, 15, 21, NULL, 1, 0, 1, 1, 2),
(13, 21, 11, 21, 15, NULL, 1, 0, 1, 1, 2),
(14, 19, 13, 15, 21, NULL, 1, 0, 1, 1, 2),
(15, 8, 21, 21, 15, NULL, 1, 0, 1, 1, 2),
(16, 19, 11, 21, 15, NULL, 1, 0, 1, 1, 2),
(17, 13, 21, 15, 21, NULL, 1, 0, 1, 1, 2),
(18, 8, 19, 15, 21, NULL, 1, 0, 1, 1, 2),
(19, 13, 11, 21, 15, NULL, 1, 0, 1, 1, 2),
(20, 8, 11, 21, 15, NULL, 1, 0, 1, 1, 2),
(21, 10, 9, 21, 15, NULL, 1, 0, 1, 1, 3),
(22, 15, 7, 15, 21, NULL, 1, 0, 1, 1, 3),
(23, 5, 9, 21, 15, NULL, 1, 0, 1, 1, 3),
(24, 10, 7, 21, 15, NULL, 1, 0, 1, 1, 3),
(25, 15, 9, 21, 15, NULL, 1, 0, 1, 1, 3),
(26, 10, 5, 21, 15, NULL, 1, 0, 1, 1, 3),
(27, 7, 9, 15, 21, NULL, 1, 0, 1, 1, 3),
(28, 10, 15, 21, 15, NULL, 1, 0, 1, 1, 3),
(29, 5, 7, 21, 15, NULL, 1, 0, 1, 1, 3),
(30, 15, 5, 21, 15, NULL, 1, 0, 1, 1, 3),
(31, 17, 3, 21, 15, NULL, 1, 0, 1, 1, 4),
(32, 20, 4, 15, 21, NULL, 1, 0, 1, 1, 4),
(33, 17, 18, 21, 15, NULL, 1, 0, 1, 1, 4),
(34, 3, 20, 21, 15, NULL, 1, 0, 1, 1, 4),
(35, 18, 4, 21, 15, NULL, 1, 0, 1, 1, 4),
(36, 17, 20, 21, 15, NULL, 1, 0, 1, 1, 4),
(37, 3, 18, 15, 21, NULL, 1, 0, 1, 1, 4),
(38, 17, 4, 21, 15, NULL, 1, 0, 1, 1, 4),
(39, 18, 20, 21, 15, NULL, 1, 0, 1, 1, 4),
(40, 3, 4, 21, 15, NULL, 1, 0, 1, 1, 4),
(41, 6, 4, 15, 21, NULL, 1, 0, 1, 2, 1),
(42, 8, 7, 15, 21, NULL, 1, 0, 1, 2, 2),
(43, 15, 2, 15, 21, NULL, 1, 0, 1, 2, 3),
(44, 19, 5, 15, 21, NULL, 1, 0, 1, 2, 4),
(45, 12, 17, 15, 21, NULL, 1, 0, 1, 2, 5),
(46, 10, 3, 15, 21, NULL, 1, 0, 1, 2, 1),
(47, 18, 13, 15, 21, NULL, 1, 0, 1, 2, 2),
(48, 14, 21, 15, 21, NULL, 1, 0, 1, 2, 3),
(49, 4, 7, 15, 21, NULL, 1, 0, 1, 3, 1),
(50, 2, 5, 15, 21, NULL, 1, 0, 1, 3, 2),
(51, 17, 3, 15, 21, NULL, 1, 0, 1, 3, 3),
(52, 13, 21, 15, 21, NULL, 1, 0, 1, 3, 4),
(53, 7, 5, 15, 21, NULL, 1, 0, 1, 4, 1),
(54, 3, 21, 15, 21, NULL, 1, 0, 1, 4, 2),
(55, 5, 21, 15, 21, NULL, 1, 0, 1, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE IF NOT EXISTS `participant` (
  `idparticipant` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomparticipant` varchar(50) DEFAULT NULL,
  `prenomparticipant` varchar(30) DEFAULT NULL,
  `sexeparticipant` varchar(10) DEFAULT NULL,
  `idequipe` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idparticipant`),
  KEY `fk_participant_equipe` (`idequipe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `participant`
--


-- --------------------------------------------------------

--
-- Structure de la table `poule`
--

CREATE TABLE IF NOT EXISTS `poule` (
  `idpoule` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nompoule` varchar(30) DEFAULT NULL,
  `nbparticipants` int(2) DEFAULT NULL,
  `nbjoues` int(3) DEFAULT NULL,
  `idtournoi` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idpoule`),
  KEY `fk_poule_tournoi` (`idtournoi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `poule`
--

INSERT INTO `poule` (`idpoule`, `nompoule`, `nbparticipants`, `nbjoues`, `idtournoi`) VALUES
(9, 'poule 1', 5, 0, 1),
(10, 'poule 2', 5, 0, 1),
(11, 'poule 3', 5, 0, 1),
(12, 'poule 4', 5, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `terrains`
--

CREATE TABLE IF NOT EXISTS `terrains` (
  `idterrain` int(10) unsigned NOT NULL DEFAULT '0',
  `nomterrain` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idterrain`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `terrains`
--

INSERT INTO `terrains` (`idterrain`, `nomterrain`) VALUES
(1, 'Terrain 1'),
(2, 'Terrain 2'),
(3, 'Terrain 3'),
(4, 'Terrain 4'),
(5, 'Terrain 5'),
(6, 'Terrain 6'),
(7, 'Terrain 7'),
(8, 'Terrain 8'),
(9, 'Terrain 9'),
(10, 'Terrain 10');

-- --------------------------------------------------------

--
-- Structure de la table `tournoi`
--

CREATE TABLE IF NOT EXISTS `tournoi` (
  `idtournoi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomtournoi` varchar(50) DEFAULT NULL,
  `nbterrain` int(3) DEFAULT NULL,
  `nbjoueurparpoule` int(3) DEFAULT NULL,
  `nbpoule` int(3) DEFAULT NULL,
  `idcat` char(4) DEFAULT NULL,
  `estGenere` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idtournoi`),
  KEY `fk_tournoi_categorie` (`idcat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `tournoi`
--

INSERT INTO `tournoi` (`idtournoi`, `nomtournoi`, `nbterrain`, `nbjoueurparpoule`, `nbpoule`, `idcat`, `estGenere`) VALUES
(1, 'TounoiTest', 5, NULL, 4, 'cat5', 1),
(2, 'Tournoi BG', 4, NULL, 4, 'cat4', 0);

-- --------------------------------------------------------

--
-- Structure de la table `typematch`
--

CREATE TABLE IF NOT EXISTS `typematch` (
  `idtypematch` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomtypematch` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idtypematch`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `typematch`
--

INSERT INTO `typematch` (`idtypematch`, `nomtypematch`) VALUES
(1, 'match de poule'),
(2, 'huitième de finale'),
(3, 'quart de finale'),
(4, 'demi-finale'),
(5, 'finale');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `deroulementmatch`
--
ALTER TABLE `deroulementmatch`
  ADD CONSTRAINT `fk_deroulementMatch1` FOREIGN KEY (`idterrain`) REFERENCES `terrains` (`idterrain`),
  ADD CONSTRAINT `fk_deroulementMatchs2` FOREIGN KEY (`idmatch`) REFERENCES `matchs` (`idmatch`),
  ADD CONSTRAINT `fk_deroulement_tournoi` FOREIGN KEY (`idtournoi`) REFERENCES `tournoi` (`idtournoi`);

--
-- Contraintes pour la table `equipe`
--
ALTER TABLE `equipe`
  ADD CONSTRAINT `fk_equipe_poule` FOREIGN KEY (`idpoule`) REFERENCES `poule` (`idpoule`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_equipe_tournoi` FOREIGN KEY (`idtournoi`) REFERENCES `tournoi` (`idtournoi`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD CONSTRAINT `fk_matchs_tournoi` FOREIGN KEY (`idtournoi`) REFERENCES `tournoi` (`idtournoi`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_match_equipe1` FOREIGN KEY (`idequipe1`) REFERENCES `equipe` (`idequipe`),
  ADD CONSTRAINT `fk_match_equipe2` FOREIGN KEY (`idequipe2`) REFERENCES `equipe` (`idequipe`),
  ADD CONSTRAINT `fk_match_typematch` FOREIGN KEY (`idtypematch`) REFERENCES `typematch` (`idtypematch`);

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `fk_participant_equipe` FOREIGN KEY (`idequipe`) REFERENCES `equipe` (`idequipe`) ON DELETE CASCADE;

--
-- Contraintes pour la table `poule`
--
ALTER TABLE `poule`
  ADD CONSTRAINT `fk_poule_tournoi` FOREIGN KEY (`idtournoi`) REFERENCES `tournoi` (`idtournoi`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tournoi`
--
ALTER TABLE `tournoi`
  ADD CONSTRAINT `fk_tournoi_categorie` FOREIGN KEY (`idcat`) REFERENCES `categorie` (`idcat`);
