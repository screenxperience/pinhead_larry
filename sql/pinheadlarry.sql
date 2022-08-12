-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 12. Aug 2022 um 10:07
-- Server Version: 5.6.13
-- PHP-Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `pinheadlarry`
--
CREATE DATABASE IF NOT EXISTS `pinheadlarry` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pinheadlarry`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bulletin_board`
--

CREATE TABLE IF NOT EXISTS `bulletin_board` (
  `bulletin_board_id` int(255) NOT NULL AUTO_INCREMENT,
  `bulletin_board_name` varchar(20) NOT NULL,
  `bulletin_board_img` varchar(200) NOT NULL,
  `bulletin_board_user_id` int(255) NOT NULL,
  PRIMARY KEY (`bulletin_board_id`),
  UNIQUE KEY `bulletin_board_name` (`bulletin_board_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `bulletin_board`
--

INSERT INTO `bulletin_board` (`bulletin_board_id`, `bulletin_board_name`, `bulletin_board_img`, `bulletin_board_user_id`) VALUES
(8, 'EinsFltl1 ITSysBtrb', 'bulletin_board_placeholder_red.svg', 1),
(10, 'TEST', 'bulletin_board_placeholder_red.svg', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `permission_id` int(255) NOT NULL AUTO_INCREMENT,
  `permission_bulletin_board_id` int(255) NOT NULL,
  `permission_user_id` int(255) NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `permission`
--

INSERT INTO `permission` (`permission_id`, `permission_bulletin_board_id`, `permission_user_id`) VALUES
(2, 10, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pin`
--

CREATE TABLE IF NOT EXISTS `pin` (
  `pin_id` int(255) NOT NULL AUTO_INCREMENT,
  `pin_title` varchar(20) NOT NULL,
  `pin_description` varchar(200) NOT NULL,
  `pin_createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pin_bulletin_board_id` int(255) NOT NULL,
  `pin_user_id` int(255) NOT NULL,
  PRIMARY KEY (`pin_id`),
  UNIQUE KEY `post_title` (`pin_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `pin`
--

INSERT INTO `pin` (`pin_id`, `pin_title`, `pin_description`, `pin_createtime`, `pin_bulletin_board_id`, `pin_user_id`) VALUES
(12, 'TEST', 'TEST', '2022-08-12 05:34:34', 8, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_uid` varchar(200) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_salt` varchar(10) NOT NULL,
  `user_admin` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_uid` (`user_uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `user_uid`, `user_password`, `user_salt`, `user_admin`) VALUES
(1, 'AlexanderBrosch', '78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56', 'j2GVy94PPP', '1'),
(2, 'PhilippBrosch', '78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56', 'j2GVy94PPP', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
