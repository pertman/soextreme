-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 22 juil. 2019 à 09:17
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `so_extreme`
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
  `act_duration` time NOT NULL,
  `act_base_price` float NOT NULL,
  `act_created_at` datetime NOT NULL,
  `act_updated_at` datetime NOT NULL,
  `act_status` varchar(20) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`act_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

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
  `cat_status` varchar(20) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_created_at`, `cat_updated_at`, `cat_status`) VALUES
(1, 'Eau', '2019-07-15 10:55:56', '2019-07-15 10:55:56', '1'),
(2, 'Terre', '2019-07-15 10:56:06', '2019-07-15 10:56:06', '1'),
(3, 'Air', '2019-07-15 10:56:22', '2019-07-15 10:56:22', '1');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

DROP TABLE IF EXISTS `evaluation`;
CREATE TABLE IF NOT EXISTS `evaluation` (
  `eva_id` int(11) NOT NULL AUTO_INCREMENT,
  `eva_value` int(11) NOT NULL,
  `eva_comment` text NOT NULL,
  `tic_id` int(11) NOT NULL,
  PRIMARY KEY (`eva_id`),
  KEY `tic_id` (`tic_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mailing`
--

DROP TABLE IF EXISTS `mailing`;
CREATE TABLE IF NOT EXISTS `mailing` (
  `mai_id` int(11) NOT NULL AUTO_INCREMENT,
  `mai_recipient` varchar(255) NOT NULL,
  `mai_subject` varchar(255) NOT NULL,
  `mai_content` text NOT NULL,
  `mai_type` varchar(255) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`mai_id`),
  KEY `usr_id` (`usr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `med_id` int(11) NOT NULL AUTO_INCREMENT,
  `med_type` varchar(255) NOT NULL,
  `med_path` varchar(255) NOT NULL,
  `med_alt` varchar(255) NOT NULL,
  PRIMARY KEY (`med_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `men_id` int(11) NOT NULL AUTO_INCREMENT,
  `men_name` varchar(255) NOT NULL,
  PRIMARY KEY (`men_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `menu_category_link`
--

DROP TABLE IF EXISTS `menu_category_link`;
CREATE TABLE IF NOT EXISTS `menu_category_link` (
  `mcl_id` int(11) NOT NULL AUTO_INCREMENT,
  `men_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `mcl_index` int(11) DEFAULT NULL,
  PRIMARY KEY (`mcl_id`),
  KEY `men_id` (`men_id`) USING BTREE,
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Structure de la table `planning`
--

DROP TABLE IF EXISTS `planning`;
CREATE TABLE IF NOT EXISTS `planning` (
  `pla_id` int(11) NOT NULL AUTO_INCREMENT,
  `pla_date_start` date NOT NULL,
  `pla_date_end` date NOT NULL,
  `act_id` int(11) NOT NULL,
  PRIMARY KEY (`pla_id`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_type` varchar(255) NOT NULL,
  `pro_name` varchar(255) NOT NULL,
  `pro_description` text NOT NULL,
  `pro_is_main_page` int(11) NOT NULL,
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
  PRIMARY KEY (`res_id`),
  KEY `usr_id` (`usr_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE IF NOT EXISTS `ticket` (
  `tic_id` int(11) NOT NULL AUTO_INCREMENT,
  `tic_firstname` varchar(255) NOT NULL,
  `tic_lastname` varchar(255) NOT NULL,
  `tic_email` varchar(255) NOT NULL,
  `tic_title` varchar(255) NOT NULL,
  `tic_message` text NOT NULL,
  `tic_price` float NOT NULL,
  `tic_activity_date` date NOT NULL,
  `tic_is_used` int(11) NOT NULL,
  `tic_is_gift` int(11) NOT NULL,
  `tic_created_at` datetime NOT NULL,
  `tic_updated_at` datetime NOT NULL,
  `tic_status` varchar(20) NOT NULL,
  `usr_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`tic_id`),
  KEY `usr_id` (`usr_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_promotions_link`
--

DROP TABLE IF EXISTS `ticket_promotions_link`;
CREATE TABLE IF NOT EXISTS `ticket_promotions_link` (
  `tpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `tic_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  PRIMARY KEY (`tpl_id`),
  KEY `tic_id` (`tic_id`),
  KEY `pro_id` (`pro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

-- --------------------------------------------------------

--
-- Structure de la table `time_slot`
--

DROP TABLE IF EXISTS `time_slot`;
CREATE TABLE IF NOT EXISTS `time_slot` (
  `tsl_id` int(11) NOT NULL AUTO_INCREMENT,
  `tsl_hour_start` time NOT NULL,
  `tsl_hour_end` time NOT NULL,
  `tsl_day_index` int(11) NOT NULL,
  PRIMARY KEY (`tsl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `time_slot_planning_link`
--

DROP TABLE IF EXISTS `time_slot_planning_link`;
CREATE TABLE IF NOT EXISTS `time_slot_planning_link` (
  `tsp_id` int(11) NOT NULL AUTO_INCREMENT,
  `tsl_id` int(11) NOT NULL,
  `pla_id` int(11) NOT NULL,
  PRIMARY KEY (`tsp_id`),
  KEY `tsl_id` (`tsl_id`),
  KEY `pla_id` (`pla_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

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
  `usr_phone` varchar(10) NOT NULL,
  `usr_created_at` datetime NOT NULL,
  `usr_updated_at` datetime NOT NULL,
  `usr_status` varchar(20) NOT NULL,
  `ust_id` int(11) NOT NULL,
  PRIMARY KEY (`usr_id`),
  KEY `ust_id` (`ust_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_picture`
--

DROP TABLE IF EXISTS `user_picture`;
CREATE TABLE IF NOT EXISTS `user_picture` (
  `upi_id` int(11) NOT NULL AUTO_INCREMENT,
  `upi_path` varchar(255) NOT NULL,
  `upi_admin_validation` int(11) NOT NULL,
  `act_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  PRIMARY KEY (`upi_id`),
  KEY `act_id` (`act_id`) USING BTREE,
  KEY `usr_id` (`usr_id`)
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
  KEY `usr_id` (`usr_id`) USING BTREE,
  KEY `upi_id` (`upi_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_planning_reservation`
--

DROP TABLE IF EXISTS `user_planning_reservation`;
CREATE TABLE IF NOT EXISTS `user_planning_reservation` (
  `upr_id` int(11) NOT NULL AUTO_INCREMENT,
  `upr_datetime_start` datetime NOT NULL,
  `upr_datetime_end` datetime NOT NULL,
  `act_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  PRIMARY KEY (`upr_id`),
  KEY `res_id` (`res_id`),
  KEY `act_id` (`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin7 COLLATE=latin7_general_cs;

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

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `fk_evaluation_tic_id` FOREIGN KEY (`tic_id`) REFERENCES `ticket` (`tic_id`);

--
-- Contraintes pour la table `mailing`
--
ALTER TABLE `mailing`
  ADD CONSTRAINT `fk_mailing_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`);

--
-- Contraintes pour la table `menu_category_link`
--
ALTER TABLE `menu_category_link`
  ADD CONSTRAINT `fk_menu_category_link_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`),
  ADD CONSTRAINT `fk_menu_category_link_men_id` FOREIGN KEY (`men_id`) REFERENCES `menu` (`men_id`);

--
-- Contraintes pour la table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_res_id` FOREIGN KEY (`res_id`) REFERENCES `reservation` (`res_id`);

--
-- Contraintes pour la table `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `fk_planning_act_id` FOREIGN KEY (`act_id`) REFERENCES `activity` (`act_id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `usr_reservation_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`);

--
-- Contraintes pour la table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_ticket_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`);

--
-- Contraintes pour la table `ticket_promotions_link`
--
ALTER TABLE `ticket_promotions_link`
  ADD CONSTRAINT `fk_ticket_promotion_link_pro_id` FOREIGN KEY (`pro_id`) REFERENCES `promotion` (`pro_id`),
  ADD CONSTRAINT `fk_ticket_promotion_link_tic_id` FOREIGN KEY (`tic_id`) REFERENCES `ticket` (`tic_id`);

--
-- Contraintes pour la table `ticket_reservation_link`
--
ALTER TABLE `ticket_reservation_link`
  ADD CONSTRAINT `fk_ticket_reservation_link_res_id` FOREIGN KEY (`res_id`) REFERENCES `reservation` (`res_id`),
  ADD CONSTRAINT `fk_ticket_reservation_link_tic_id` FOREIGN KEY (`tic_id`) REFERENCES `ticket` (`tic_id`);

--
-- Contraintes pour la table `time_slot_planning_link`
--
ALTER TABLE `time_slot_planning_link`
  ADD CONSTRAINT `fk_time_slot_planning_link_pla_id` FOREIGN KEY (`pla_id`) REFERENCES `planning` (`pla_id`),
  ADD CONSTRAINT `fk_time_slot_planning_link_tsl_id` FOREIGN KEY (`tsl_id`) REFERENCES `time_slot` (`tsl_id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_ust_id` FOREIGN KEY (`ust_id`) REFERENCES `user_type` (`ust_id`);

--
-- Contraintes pour la table `user_picture`
--
ALTER TABLE `user_picture`
  ADD CONSTRAINT `fk_user_picture_act_id` FOREIGN KEY (`act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `fk_user_picture_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`);

--
-- Contraintes pour la table `user_picture_comment`
--
ALTER TABLE `user_picture_comment`
  ADD CONSTRAINT `fk_user_picture_comment_upi_id` FOREIGN KEY (`upi_id`) REFERENCES `user_picture` (`upi_id`),
  ADD CONSTRAINT `fk_user_picture_comment_usr_id` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`);

--
-- Contraintes pour la table `user_planning_reservation`
--
ALTER TABLE `user_planning_reservation`
  ADD CONSTRAINT `fk_user_planning_reservation_act_id` FOREIGN KEY (`act_id`) REFERENCES `activity` (`act_id`),
  ADD CONSTRAINT `fk_user_planning_reservation_res_id` FOREIGN KEY (`res_id`) REFERENCES `reservation` (`res_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
