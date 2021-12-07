-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 17 Juin 2016 à 13:38
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db_etml_leds`
--
USE db_etml_leds;
-- --------------------------------------------------------

--
-- Structure de la table `t_figure`
--

CREATE TABLE IF NOT EXISTS `t_figure` (
  `idFigure` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `figData` tinyint(1) NOT NULL,
  `figMove` varchar(10) NOT NULL,
  PRIMARY KEY (`idFigure`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `t_figure`
--

INSERT INTO `t_figure` (`idFigure`, `figData`, `figMove`) VALUES
(1, 0, 'Aucun'),
(2, 1, 'Gauche');

-- --------------------------------------------------------

--
-- Structure de la table `t_message`
--

CREATE TABLE IF NOT EXISTS `t_message` (
  `idMessage` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mesTimePassage` datetime NOT NULL,
  `mesColor` varchar(7) NOT NULL,
  `mesSpeed` tinyint(4) NOT NULL,
  `mesMessage` varchar(55) NOT NULL,
  `fkFigure` int(10) unsigned NOT NULL,
  `fkUser` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idMessage`),
  KEY `fkFigure` (`fkFigure`),
  KEY `fkUser` (`fkUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

--
-- Contenu de la table `t_message`
--

INSERT INTO `t_message` (`idMessage`, `mesTimePassage`, `mesColor`, `mesSpeed`, `mesMessage`, `fkFigure`, `fkUser`) VALUES
(105, '2016-06-15 09:14:26', '#ff0000', 0, 'ETml', 1, 1),
(111, '2016-06-15 10:03:30', '#ff0000', 0, 'ETml', 1, 2),
(112, '2016-06-15 10:18:55', '#ff0000', 0, 'ETml', 1, 2),
(113, '2016-06-15 10:20:31', '#ff0000', 0, 'ETml', 1, 2),
(114, '2016-06-15 15:41:05', '#ff0000', 0, 'ETml', 1, 2),
(115, '2016-06-15 15:43:53', '#ff0000', 0, 'ETml', 1, 2),
(116, '2016-06-15 15:45:36', '#ff0000', 1, 'L''ETML a 100 ans ', 2, 1),
(117, '2016-06-15 15:59:54', '#ff0000', 0, 'ETml', 1, 2),
(118, '2016-06-16 22:40:23', '#ff0000', 0, 'ETml', 1, 2),
(119, '2016-06-16 22:41:46', '#ff0000', 0, 'ETml', 1, 2),
(120, '2016-06-16 22:43:25', '#ff0000', 0, 'ETml', 1, 2),
(121, '2016-06-17 10:25:53', '#ff0000', 1, 'ETML 100 ans', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_text`
--

CREATE TABLE IF NOT EXISTS `t_text` (
  `idText` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texMessage` varchar(55) NOT NULL,
  `texIsArchived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fkUser` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idText`),
  KEY `fkUser` (`fkUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `t_text`
--

INSERT INTO `t_text` (`idText`, `texMessage`, `texIsArchived`, `fkUser`) VALUES
(7, 'L''ETML a 100 ans ', 1, 1),
(10, 'L''ETML a 100 ans ', 1, 1),
(11, 'La Suisse est belle !', 1, 1),
(12, '  ', 1, 1),
(13, '   aaa ', 1, 1),
(14, 'ETml', 1, 1),
(15, 'ETML 100 ans', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `idUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `useUsername` varchar(60) NOT NULL,
  `usePassword` varchar(100) NOT NULL,
  `useStatus` tinyint(1) NOT NULL DEFAULT '0',
  `useToken` varchar(100) NOT NULL,
  `useIsAdmin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1081 ;

--
-- Contenu de la table `t_user`
--

INSERT INTO `t_user` (`idUser`, `useUsername`, `usePassword`, `useStatus`, `useToken`, `useIsAdmin`) VALUE
(1, 'carvalhoda@etml.educanet2.ch', '$2y$10$8r6LXFCdDr.CCrnGMNEtyu6JkA40iJjpzNY56DTWvcKRvffh.o1PC', 1, 'd76c52eb4b5fc4e5feff59c683efedb0', 1),
(2, 'ServerAdmin', '$2y$10$8r6LXFCdDr.CCrnGMNEtyu6JkA40iJjpzNY56DTWvcKRvffh.o1PC', 1, '', 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `t_message`
--
ALTER TABLE `t_message`
  ADD CONSTRAINT `t_message_ibfk_2` FOREIGN KEY (`fkFigure`) REFERENCES `t_figure` (`idFigure`);

--
-- Contraintes pour la table `t_text`
--
ALTER TABLE `t_text`
  ADD CONSTRAINT `t_text_ibfk_1` FOREIGN KEY (`fkUser`) REFERENCES `t_user` (`idUser`) ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
