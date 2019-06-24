-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 23 juin 2019 à 14:16
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `soextreme`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity`
--

DROP TABLE IF EXISTS `activity`;
CREATE TABLE IF NOT EXISTS `activity` (
  `act_id` int(11) NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL,
  `act_description` text NOT NULL,
  `act_resume` text NOT NULL,
  `act_is_special_offer` tinyint(4) NOT NULL,
  `act_description_special_offer` text,
  `act_monitor_nb` int(11) NOT NULL DEFAULT '0',
  `act_operator_nb` int(11) NOT NULL DEFAULT '0',
  `act_created_at` datetime NOT NULL,
  `act_updated_at` datetime NOT NULL,
  `act_status` tinyint(4) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`act_id`),
  KEY `id_category` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_created_at` datetime NOT NULL,
  `cat_updated_at` datetime NOT NULL,
  `cat_status` int(11) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id_evaluation` int(11) NOT NULL AUTO_INCREMENT,
  `grade` int(11) NOT NULL,
  `comment` text NOT NULL,
  `id_activity` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_evaluation`),
  KEY `id_activity` (`id_activity`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mails`
--

DROP TABLE IF EXISTS `mails`;
CREATE TABLE IF NOT EXISTS `mails` (
  `id_mail` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id_mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id_menu` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE IF NOT EXISTS `menu_categories` (
  `id_menu` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  KEY `id_menu` (`id_menu`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id_option` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `price_rule` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  PRIMARY KEY (`id_option`),
  KEY `id_activity` (`id_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `options_activites`
--

DROP TABLE IF EXISTS `options_activites`;
CREATE TABLE IF NOT EXISTS `options_activites` (
  `id_option` int(11) NOT NULL,
  `id_activity` int(11) NOT NULL,
  KEY `id_option` (`id_option`),
  KEY `id_activity` (`id_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pass`
--

DROP TABLE IF EXISTS `pass`;
CREATE TABLE IF NOT EXISTS `pass` (
  `id_pass` int(11) NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `title` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `date_use` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `is_gift` tinyint(4) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  PRIMARY KEY (`id_pass`),
  KEY `id_reservation` (`id_reservation`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_amount` float NOT NULL,
  `pay_state` int(11) NOT NULL,
  `pay_bank_response` text NOT NULL,
  `res_id` int(11) NOT NULL,
  PRIMARY KEY (`pay_id`) USING BTREE,
  KEY `res_id` (`res_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `period`
--

DROP TABLE IF EXISTS `period`;
CREATE TABLE IF NOT EXISTS `period` (
  `per_id` int(11) NOT NULL AUTO_INCREMENT,
  `per_date_start` date NOT NULL,
  `per_date_end` date NOT NULL,
  `act_id` int(11) NOT NULL,
  PRIMARY KEY (`per_id`) USING BTREE,
  KEY `act_id` (`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

DROP TABLE IF EXISTS `picture`;
CREATE TABLE IF NOT EXISTS `picture` (
  `pic_id` int(11) NOT NULL AUTO_INCREMENT,
  `pic_path` varchar(255) NOT NULL,
  `pic_alt` varchar(255) NOT NULL,
  `tic_id` int(11) NOT NULL,
  PRIMARY KEY (`pic_id`),
  KEY `id_activity` (`tic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `planning`
--

DROP TABLE IF EXISTS `planning`;
CREATE TABLE IF NOT EXISTS `planning` (
  `pla_id` int(11) NOT NULL,
  `per_id` int(11) NOT NULL,
  `tsl_id` int(11) NOT NULL,
  `act_id` int(11) NOT NULL,
  PRIMARY KEY (`pla_id`),
  KEY `act_id` (`act_id`),
  KEY `per_id` (`per_id`),
  KEY `tsl_id` (`tsl_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_hour_start` time DEFAULT NULL,
  `pro_hour_end` time DEFAULT NULL,
  `pro_date_start` date DEFAULT NULL,
  `pro_date_end` date DEFAULT NULL,
  `pro_cart_amount` int(11) DEFAULT NULL,
  `pro_discount_fix` int(11) DEFAULT NULL,
  `pro_discount_percent` int(11) DEFAULT NULL,
  `pro_code` varchar(255) DEFAULT NULL,
  `pro_max_use` int(11) DEFAULT NULL,
  `pro_is_active` int(11) DEFAULT NULL,
  `pro_user_ids` varchar(255) DEFAULT NULL,
  `pro_act_ids` varchar(255) NOT NULL,
  `pro_cat_ids` varchar(255) NOT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `res_id` int(11) NOT NULL AUTO_INCREMENT,
  `res_created_at` datetime NOT NULL,
  `res_updated_at` datetime NOT NULL,
  `usr_id` int(11) NOT NULL,
  `act_id` int(11) NOT NULL,
  PRIMARY KEY (`res_id`),
  KEY `usr_id` (`usr_id`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE IF NOT EXISTS `ticket` (
  `tic_id` int(11) NOT NULL,
  `tic_firstname` varchar(255) NOT NULL,
  `tic_lastname` varchar(255) NOT NULL,
  `tic_email` varchar(255) NOT NULL,
  `tic_message` text NOT NULL,
  `tic_created_at` datetime NOT NULL,
  `tic_updated_at` datetime NOT NULL,
  `tic_status` int(11) NOT NULL,
  `tic_usr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_reservation_link`
--

DROP TABLE IF EXISTS `ticket_reservation_link`;
CREATE TABLE IF NOT EXISTS `ticket_reservation_link` (
  `trl_id` int(11) NOT NULL,
  `tic_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  PRIMARY KEY (`trl_id`),
  KEY `tic_id` (`tic_id`),
  KEY `res_id` (`res_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `time_slot`
--

DROP TABLE IF EXISTS `time_slot`;
CREATE TABLE IF NOT EXISTS `time_slot` (
  `tsl_id` int(11) NOT NULL AUTO_INCREMENT,
  `tsl_duration` time NOT NULL,
  `tsl_hour_start` time NOT NULL,
  `tsl_hour_end` time NOT NULL,
  `tsl_day_index` int(11) NOT NULL,
  `act_id` int(11) NOT NULL,
  PRIMARY KEY (`tsl_id`),
  KEY `id_activity` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_firstname` varchar(255) NOT NULL,
  `usr_lastname` varchar(255) NOT NULL,
  `usr_email` varchar(255) NOT NULL,
  `usr_password` varchar(255) NOT NULL,
  `usr_created_at` datetime NOT NULL,
  `usr_updated_at` datetime NOT NULL,
  `usr_status` int(11) NOT NULL,
  `ust_id` int(11) NOT NULL,
  PRIMARY KEY (`usr_id`),
  KEY `ust_id` (`ust_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_picture`
--

DROP TABLE IF EXISTS `user_picture`;
CREATE TABLE IF NOT EXISTS `user_picture` (
  `upi_id` int(11) NOT NULL AUTO_INCREMENT,
  `upi_path` varchar(255) NOT NULL,
  `id_tic` int(11) NOT NULL,
  PRIMARY KEY (`upi_id`),
  KEY `id_tic` (`id_tic`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_picture_comment`
--

DROP TABLE IF EXISTS `user_picture_comment`;
CREATE TABLE IF NOT EXISTS `user_picture_comment` (
  `upc_id` int(11) NOT NULL AUTO_INCREMENT,
  `upc_comment` text NOT NULL,
  `upi_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`upc_id`),
  UNIQUE KEY `upi_id` (`upi_id`),
  KEY `usr_id` (`usr_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE IF NOT EXISTS `user_type` (
  `ust_id` int(11) NOT NULL AUTO_INCREMENT,
  `ust_value` varchar(255) NOT NULL,
  PRIMARY KEY (`ust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_type`
--

INSERT INTO `user_type` (`ust_id`, `ust_value`) VALUES
(1, 'customer'),
(2, 'monitor'),
(3, 'operator'),
(4, 'administratrator');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
