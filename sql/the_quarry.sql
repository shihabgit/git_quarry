-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2015 at 12:40 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `the_quarry`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE IF NOT EXISTS `auth_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Table structure for table `auth_login_attempts`
--

CREATE TABLE IF NOT EXISTS `auth_login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE IF NOT EXISTS `auth_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, NULL, 1268889823, 1423480211, 1, 'Noushad', 'PM', 'ADMIN', '0'),
(2, '127.0.0.1', 'administrator', '$2y$08$rs9nHaD68kfotUVBofalveZfZRo.DsDwZh5fUmObDxM08tcn7TyeG', 'RGuaK4OKfDAHN866npm7RO', '', NULL, NULL, NULL, NULL, 1423480270, 1423480270, 1, 'D1 F1_w1', '', '', ''),
(3, '127.0.0.1', '', '0', 'WQXdsUJ5os7H7WnOorvwNO', '', NULL, NULL, NULL, NULL, 1423480446, 1423480446, 1, 'D2 F1_W2', '', '', ''),
(4, '127.0.0.1', '', '0', 'UudK60uxACVTtx80zpDNHu', '', NULL, NULL, NULL, NULL, 1423480685, 1423480685, 1, 'D3 F1_W1_W2', '', '', ''),
(5, '127.0.0.1', '', '0', 'Y/UTISpFDD76hH.9Rxbjwu', '', NULL, NULL, NULL, NULL, 1423480791, 1423480791, 1, 'L1 F1_W1', '', '', ''),
(6, '127.0.0.1', '', '0', 'M.cEJeq45YLFINwHfy0SK.', '', NULL, NULL, NULL, NULL, 1423480828, 1423480828, 1, 'L2 F1_W2', '', '', ''),
(7, '127.0.0.1', '', '0', 'pp0FFqaE52I7GKammrhDfe', '', NULL, NULL, NULL, NULL, 1423480855, 1423480855, 1, 'L3 F1_W1_W2', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `auth_users_groups`
--

CREATE TABLE IF NOT EXISTS `auth_users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `auth_users_groups`
--

INSERT INTO `auth_users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2),
(4, 4, 2),
(5, 5, 2),
(6, 6, 2),
(7, 7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE IF NOT EXISTS `backups` (
  `bkp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bkp_fk_worklogs` int(11) unsigned NOT NULL,
  `bkp_ref_table` varchar(50) DEFAULT NULL,
  `bkp_ref_id` int(11) unsigned NOT NULL,
  `bkp_data` text,
  PRIMARY KEY (`bkp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `backups`
--

INSERT INTO `backups` (`bkp_id`, `bkp_fk_worklogs`, `bkp_ref_table`, `bkp_ref_id`, `bkp_data`) VALUES
(1, 4, 'workcentres', 1, 'a:8:{s:8:"wcntr_id";s:1:"1";s:14:"wcntr_fk_firms";s:1:"1";s:10:"wcntr_date";s:10:"2015-02-09";s:15:"wcntr_ownership";s:1:"1";s:13:"wcntr_capital";s:4:"0.00";s:10:"wcntr_name";s:18:"Default Workcentre";s:40:"wcntr_fk_workcentre_registration_details";s:1:"0";s:12:"wcntr_status";s:1:"1";}'),
(2, 6, 'workcentres', 2, 'a:8:{s:8:"wcntr_id";s:1:"2";s:14:"wcntr_fk_firms";s:1:"1";s:10:"wcntr_date";s:10:"2015-02-09";s:15:"wcntr_ownership";s:1:"1";s:13:"wcntr_capital";s:4:"0.00";s:10:"wcntr_name";s:5:"F1_w2";s:40:"wcntr_fk_workcentre_registration_details";s:1:"0";s:12:"wcntr_status";s:1:"1";}'),
(3, 11, 'employees', 2, 'a:10:{s:6:"emp_id";s:1:"2";s:12:"emp_category";s:1:"4";s:8:"emp_name";s:5:"F1_w1";s:8:"emp_date";s:10:"2015-02-09";s:11:"emp_address";s:0:"";s:10:"emp_status";s:1:"1";s:8:"username";s:0:"";s:8:"password";s:1:"0";s:5:"email";s:0:"";s:5:"phone";s:0:"";}');

-- --------------------------------------------------------

--
-- Table structure for table `destination_workcentres`
--

CREATE TABLE IF NOT EXISTS `destination_workcentres` (
  `dwc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dwc_fk_workcentres` int(11) unsigned NOT NULL,
  `dwc_fk_party_destinations` int(11) unsigned NOT NULL,
  `dwc_date` datetime DEFAULT NULL,
  `dwc_ob` decimal(13,2) DEFAULT NULL,
  `dwc_ob_mode` tinyint(1) DEFAULT NULL,
  `dwc_credit_lmt` decimal(13,2) DEFAULT NULL,
  `dwc_debt_lmt` decimal(13,2) DEFAULT NULL,
  `dwc_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`dwc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `destination_workcentres`
--

INSERT INTO `destination_workcentres` (`dwc_id`, `dwc_fk_workcentres`, `dwc_fk_party_destinations`, `dwc_date`, `dwc_ob`, `dwc_ob_mode`, `dwc_credit_lmt`, `dwc_debt_lmt`, `dwc_status`) VALUES
(1, 1, 1, '2015-02-09 00:00:00', '0.00', 0, '0.00', '0.00', 1),
(2, 2, 2, '2015-02-09 00:00:00', '0.00', 0, '0.00', '0.00', 1),
(3, 1, 3, '2015-02-09 00:00:00', '0.00', 0, '0.00', '0.00', 1),
(4, 2, 4, '2015-02-09 00:00:00', '0.00', 0, '0.00', '0.00', 1),
(5, 2, 5, '2015-02-09 00:00:00', '0.00', 0, '0.00', '0.00', 1),
(6, 2, 6, '2015-02-09 00:00:00', '0.00', 0, '0.00', '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `emp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emp_category` tinyint(1) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `emp_date` date DEFAULT NULL,
  `emp_address` varchar(100) DEFAULT NULL,
  `emp_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `emp_category`, `emp_name`, `emp_date`, `emp_address`, `emp_status`) VALUES
(1, 1, 'Noushad PM', '2014-10-25', 'KOTTAPPURAM PO\r\nKONDOTY VIA\r\nMALAPPURAM DT', 1),
(2, 4, 'D1 F1_w1', '2015-02-09', '', 1),
(3, 4, 'D2 F1_W2', '2015-02-09', '', 1),
(4, 4, 'D3 F1_W1_W2', '2015-02-09', '', 1),
(5, 5, 'L1 F1_W1', '2015-02-09', '', 1),
(6, 5, 'L2 F1_W2', '2015-02-09', '', 1),
(7, 5, 'L3 F1_W1_W2', '2015-02-09', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_work_centre`
--

CREATE TABLE IF NOT EXISTS `employee_work_centre` (
  `ewp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ewp_date` date DEFAULT NULL,
  `ewp_fk_auth_users` int(11) unsigned NOT NULL,
  `ewp_fk_workcentres` int(11) unsigned NOT NULL,
  `ewp_ob` decimal(13,2) DEFAULT NULL,
  `ewp_ob_mode` tinyint(1) DEFAULT NULL,
  `ewp_day_wage` decimal(13,2) DEFAULT NULL,
  `ewp_day_hourly_wage` decimal(13,2) DEFAULT NULL,
  `ewp_day_ot_wage` decimal(13,2) DEFAULT NULL,
  `ewp_night_wage` decimal(13,2) DEFAULT NULL,
  `ewp_night_hourly_wage` decimal(13,2) DEFAULT NULL,
  `ewp_night_ot_wage` decimal(13,2) DEFAULT NULL,
  `ewp_salary_wage` decimal(13,2) DEFAULT NULL,
  `ewp_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ewp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `employee_work_centre`
--

INSERT INTO `employee_work_centre` (`ewp_id`, `ewp_date`, `ewp_fk_auth_users`, `ewp_fk_workcentres`, `ewp_ob`, `ewp_ob_mode`, `ewp_day_wage`, `ewp_day_hourly_wage`, `ewp_day_ot_wage`, `ewp_night_wage`, `ewp_night_hourly_wage`, `ewp_night_ot_wage`, `ewp_salary_wage`, `ewp_status`) VALUES
(1, '2015-02-09', 1, 1, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(2, '2015-02-09', 1, 2, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(3, '2015-02-09', 1, 3, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(4, '2015-02-09', 2, 1, '10000.00', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(5, '2015-02-09', 3, 2, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(6, '2015-02-09', 4, 1, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(7, '2015-02-09', 4, 2, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(8, '2015-02-09', 5, 1, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(9, '2015-02-09', 6, 2, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(10, '2015-02-09', 7, 1, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1),
(11, '2015-02-09', 7, 2, '0.00', 2, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `firms`
--

CREATE TABLE IF NOT EXISTS `firms` (
  `firm_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firm_date` date DEFAULT NULL,
  `firm_name` varchar(20) NOT NULL,
  `firm_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`firm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `firms`
--

INSERT INTO `firms` (`firm_id`, `firm_date`, `firm_name`, `firm_status`) VALUES
(1, '2015-02-09', 'Firm 1', 1),
(2, '2015-02-09', 'Firm 2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `firm_settings`
--

CREATE TABLE IF NOT EXISTS `firm_settings` (
  `frmset_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `frmset_fk_settings` int(11) unsigned NOT NULL,
  `frmset_fk_firms` int(11) unsigned NOT NULL,
  `frmset_value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`frmset_id`),
  KEY `FKey_firm_settings_frmset_fk_settings` (`frmset_fk_settings`),
  KEY `FKey_firm_settings_frmset_fk_firms` (`frmset_fk_firms`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `firm_settings`
--

INSERT INTO `firm_settings` (`frmset_id`, `frmset_fk_settings`, `frmset_fk_firms`, `frmset_value`) VALUES
(1, 4, 1, '2'),
(2, 2, 1, '2'),
(3, 1, 1, '1'),
(4, 3, 1, '2,3'),
(5, 4, 2, '2'),
(6, 2, 2, '2'),
(7, 1, 2, '1'),
(8, 3, 2, '2,3'),
(9, 5, 1, '4'),
(10, 5, 2, '4'),
(11, 6, 1, '1'),
(12, 6, 2, '1'),
(13, 7, 1, '2015'),
(14, 7, 2, '2015'),
(15, 8, 1, '2'),
(16, 8, 2, '2');

-- --------------------------------------------------------

--
-- Table structure for table `form_inputs`
--

CREATE TABLE IF NOT EXISTS `form_inputs` (
  `fip_clsfunc` varchar(100) DEFAULT NULL,
  `fip_values` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_inputs`
--

INSERT INTO `form_inputs` (`fip_clsfunc`, `fip_values`) VALUES
('workcentres/index', 'a:2:{s:6:"offset";i:0;s:8:"PER_PAGE";i:100;}'),
('employees/index', 'a:2:{s:6:"offset";i:0;s:8:"PER_PAGE";i:100;}'),
('vehicles/index', 'a:2:{s:6:"offset";i:0;s:8:"PER_PAGE";i:10;}'),
('parties/index', 'a:2:{s:6:"offset";i:0;s:8:"PER_PAGE";i:10;}');

-- --------------------------------------------------------

--
-- Table structure for table `freight_charges`
--

CREATE TABLE IF NOT EXISTS `freight_charges` (
  `fc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fc_fk_workcentres` int(11) unsigned NOT NULL,
  `fc_fk_party_destinations` int(11) unsigned NOT NULL,
  `fc_fk_vehicles` int(11) unsigned NOT NULL,
  `fc_rent` decimal(13,2) DEFAULT NULL,
  `fc_add_rent` tinyint(1) DEFAULT NULL,
  `fc_bata` decimal(13,2) DEFAULT NULL,
  `fc_add_bata` tinyint(1) DEFAULT NULL,
  `fc_loading` decimal(13,2) DEFAULT NULL,
  `fc_add_loading` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`fc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `individual_rates`
--

CREATE TABLE IF NOT EXISTS `individual_rates` (
  `indv_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `indv_fk_workcentres` int(11) unsigned NOT NULL,
  `indv_fk_party_destinations` int(11) unsigned NOT NULL,
  `indv_fk_items` int(11) unsigned NOT NULL,
  `indv_fk_units` int(11) unsigned NOT NULL,
  `indv_p_rate` decimal(13,2) DEFAULT NULL,
  `indv_s_rate` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`indv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inter_freight_charges`
--

CREATE TABLE IF NOT EXISTS `inter_freight_charges` (
  `ifc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ifc_fk_workcentres_from` int(11) unsigned NOT NULL,
  `ifc_fk_workcentres_to` int(11) unsigned NOT NULL,
  `ifc_fkey_vehicles` int(11) unsigned NOT NULL,
  `ifc_rent` decimal(13,2) DEFAULT NULL,
  `ifc_add_rent` tinyint(1) DEFAULT NULL,
  `ifc_bata` decimal(13,2) DEFAULT NULL,
  `ifc_add_bata` tinyint(1) DEFAULT NULL,
  `ifc_loading` decimal(13,2) DEFAULT NULL,
  `ifc_add_loading` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ifc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `itm_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `itm_fk_item_head` int(11) unsigned NOT NULL,
  `itm_name` varchar(20) DEFAULT NULL,
  `itm_fk_units` int(11) unsigned NOT NULL,
  `itm_p_vat` decimal(13,2) DEFAULT NULL,
  `itm_p_cess` decimal(13,2) DEFAULT NULL,
  `itm_s_vat` decimal(13,2) DEFAULT NULL,
  `itm_s_cess` decimal(13,2) DEFAULT NULL,
  `itm_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`itm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE IF NOT EXISTS `item_category` (
  `itmcat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `itmcat_name` varchar(20) DEFAULT NULL,
  `itmcat_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`itmcat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_heads`
--

CREATE TABLE IF NOT EXISTS `item_heads` (
  `itmhd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `itmhd_fk_item_category` int(11) unsigned NOT NULL,
  `itmhd_name` varchar(20) DEFAULT NULL,
  `itmhd_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`itmhd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_units_n_rates`
--

CREATE TABLE IF NOT EXISTS `item_units_n_rates` (
  `iur_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iur_fk_workcentres` int(11) unsigned NOT NULL,
  `iur_fk_items` int(11) unsigned NOT NULL,
  `iur_fk_units` int(11) unsigned NOT NULL,
  `iur_p_rate` decimal(13,2) DEFAULT NULL,
  `iur_s_rate` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`iur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `opening_stock`
--

CREATE TABLE IF NOT EXISTS `opening_stock` (
  `ostk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ostk_fk_workcentre` int(11) unsigned NOT NULL,
  `ostk_fk_items` int(11) unsigned NOT NULL,
  `ostk_date` datetime DEFAULT NULL,
  `ostk_qty` decimal(13,2) DEFAULT NULL,
  `ostk_fk_units` int(11) unsigned NOT NULL,
  `ostk_rate` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`ostk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE IF NOT EXISTS `owners` (
  `ownr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ownr_date` date DEFAULT NULL,
  `ownr_name` varchar(20) DEFAULT NULL,
  `ownr_address` varchar(30) DEFAULT NULL,
  `ownr_phone` varchar(20) DEFAULT NULL,
  `ownr_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ownr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE IF NOT EXISTS `parties` (
  `pty_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pty_name` varchar(20) DEFAULT NULL,
  `pty_date` date DEFAULT NULL,
  `pty_phone` varchar(20) DEFAULT NULL,
  `pty_email` varchar(30) DEFAULT NULL,
  `pty_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`pty_id`, `pty_name`, `pty_date`, `pty_phone`, `pty_email`, `pty_status`) VALUES
(1, 'P1', '2015-02-09', '', '', 1),
(2, 'P2', '2015-02-09', '', '', 1),
(3, 'P3', '2015-02-09', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `party_destinations`
--

CREATE TABLE IF NOT EXISTS `party_destinations` (
  `pdst_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pdst_date` date DEFAULT NULL,
  `pdst_fk_party_license_details` int(11) unsigned NOT NULL,
  `pdst_fk_parties` int(11) unsigned NOT NULL,
  `pdst_name` varchar(20) DEFAULT NULL,
  `pdst_phone` varchar(20) DEFAULT NULL,
  `pdst_email` varchar(30) DEFAULT NULL,
  `pdst_category` tinyint(1) DEFAULT NULL,
  `pdst_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pdst_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `party_destinations`
--

INSERT INTO `party_destinations` (`pdst_id`, `pdst_date`, `pdst_fk_party_license_details`, `pdst_fk_parties`, `pdst_name`, `pdst_phone`, `pdst_email`, `pdst_category`, `pdst_status`) VALUES
(1, '2015-02-09', 0, 1, 'P1 D1 F1_w1', '', '', 2, 1),
(2, '2015-02-09', 0, 1, 'P1 D2 F1_w2', '', '', 2, 1),
(3, '2015-02-09', 0, 2, 'P2 D1 F1_w1', '', '', 2, 1),
(4, '2015-02-09', 0, 3, 'P3 D1 F1_w2', '', '', 2, 1),
(5, '2015-02-09', 0, 3, 'P3 D2 F1_w2', '', '', 2, 1),
(6, '2015-02-09', 0, 3, 'P3 D3 F1_w2', '', '', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `party_license_details`
--

CREATE TABLE IF NOT EXISTS `party_license_details` (
  `pld_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pld_date` date DEFAULT NULL,
  `pld_firm_name` varchar(50) DEFAULT NULL,
  `pld_address` varchar(250) DEFAULT NULL,
  `pld_phone` varchar(20) DEFAULT NULL,
  `pld_email` varchar(30) DEFAULT NULL,
  `pld_tin` varchar(20) DEFAULT NULL,
  `pld_licence` varchar(20) DEFAULT NULL,
  `pld_cst` varchar(20) DEFAULT NULL,
  `pld_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pld_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `party_vehicles`
--

CREATE TABLE IF NOT EXISTS `party_vehicles` (
  `pvhcl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pvhcl_fk_parties` int(11) unsigned NOT NULL,
  `pvhcl_name` varchar(20) DEFAULT NULL,
  `pvhcl_no` varchar(20) DEFAULT NULL,
  `pvhcl_length` decimal(13,2) DEFAULT NULL,
  `pvhcl_breadth` decimal(13,2) DEFAULT NULL,
  `pvhcl_height` decimal(13,2) DEFAULT NULL,
  `pvhcl_xheight` decimal(13,2) DEFAULT NULL,
  `pvhcl_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pvhcl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `party_vehicles`
--

INSERT INTO `party_vehicles` (`pvhcl_id`, `pvhcl_fk_parties`, `pvhcl_name`, `pvhcl_no`, `pvhcl_length`, `pvhcl_breadth`, `pvhcl_height`, `pvhcl_xheight`, `pvhcl_status`) VALUES
(1, 1, '', 'P1 V1', '0.00', '0.00', '0.00', '0.00', 1),
(2, 1, '', 'P1 V2', '0.00', '0.00', '0.00', '0.00', 1),
(3, 1, '', 'P1 V3', '0.00', '0.00', '0.00', '0.00', 1),
(4, 2, '', 'P2 V1', '0.00', '0.00', '0.00', '0.00', 1),
(5, 2, '', 'P2 V2', '0.00', '0.00', '0.00', '0.00', 1),
(6, 3, '', 'P3 V1', '0.00', '0.00', '0.00', '0.00', 1),
(7, 3, '', 'P3 V2', '0.00', '0.00', '0.00', '0.00', 1),
(8, 3, '', 'P3 V3', '0.00', '0.00', '0.00', '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `party_vehicle_rents`
--

CREATE TABLE IF NOT EXISTS `party_vehicle_rents` (
  `pvr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pvr_fk_workcentres` int(11) unsigned NOT NULL,
  `pvr_fk_party_destinations` int(11) unsigned NOT NULL,
  `pvr_fk_party_vehicles` int(11) unsigned NOT NULL,
  `pvr_rent` decimal(13,2) DEFAULT NULL,
  `pvr_add_rent` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pvr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `party_vehicle_rents`
--

INSERT INTO `party_vehicle_rents` (`pvr_id`, `pvr_fk_workcentres`, `pvr_fk_party_destinations`, `pvr_fk_party_vehicles`, `pvr_rent`, `pvr_add_rent`) VALUES
(1, 2, 4, 6, '15000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_billnumber_notax`
--

CREATE TABLE IF NOT EXISTS `purchase_billnumber_notax` (
  `pbntx_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pbntx_fk_workcentres` int(11) unsigned NOT NULL,
  `pbntx_no` int(11) unsigned NOT NULL,
  `pbntx_fyear` int(11) unsigned NOT NULL,
  PRIMARY KEY (`pbntx_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_billnumber_tax`
--

CREATE TABLE IF NOT EXISTS `purchase_billnumber_tax` (
  `pbtx_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pbtx_fk_workcentre_registration_details` int(11) unsigned NOT NULL,
  `pbtx_no` int(11) unsigned NOT NULL,
  `pbtx_fyear` int(11) unsigned NOT NULL,
  PRIMARY KEY (`pbtx_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill_additives`
--

CREATE TABLE IF NOT EXISTS `purchase_bill_additives` (
  `pba_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pba_fk_purchase_bill_head` int(11) unsigned NOT NULL,
  `pba_name` varchar(25) DEFAULT NULL,
  `pba_amount` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`pba_id`),
  KEY `FKey_purchase_bill_additives_pba_fk_purchase_bill_head` (`pba_fk_purchase_bill_head`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill_body`
--

CREATE TABLE IF NOT EXISTS `purchase_bill_body` (
  `pbb_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pbb_fk_purchase_bill_head` int(11) unsigned NOT NULL,
  `pbb_fk_items` int(11) unsigned NOT NULL,
  `pbb_qty` decimal(13,2) DEFAULT NULL,
  `pbb_fk_units` int(11) unsigned NOT NULL,
  `pbb_rate` decimal(13,2) DEFAULT NULL,
  `pbb_rate_declared` decimal(13,2) DEFAULT NULL,
  `pbb_tax` decimal(13,2) DEFAULT NULL,
  `pbb_cess` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`pbb_id`),
  KEY `FKey_purchase_bill_body_pbb_fk_purchase_bill_head` (`pbb_fk_purchase_bill_head`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill_deductives`
--

CREATE TABLE IF NOT EXISTS `purchase_bill_deductives` (
  `pbd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pbd_fk_purchase_bill_head` int(11) unsigned NOT NULL,
  `pbd_name` varchar(25) DEFAULT NULL,
  `pbd_amount` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`pbd_id`),
  KEY `FKey_purchase_bill_deductives_pbd_fk_purchase_bill_head` (`pbd_fk_purchase_bill_head`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill_head`
--

CREATE TABLE IF NOT EXISTS `purchase_bill_head` (
  `pbh_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pbh_datetime` datetime DEFAULT NULL,
  `pbh_fk_workcentres` int(11) unsigned NOT NULL,
  `pbh_fk_party_destinations` int(11) unsigned NOT NULL,
  `pbh_temp_party` varchar(30) DEFAULT NULL,
  `pbh_fk_purchase_billnumber_tax` int(11) unsigned DEFAULT NULL,
  `pbh_fk_purchase_billnumber_notax` int(11) unsigned DEFAULT NULL,
  `pbh_ref_no` int(11) DEFAULT NULL,
  `pbh_fk_party_vehicles` int(11) unsigned DEFAULT NULL,
  `pbh_pty_veh_rent` decimal(13,2) DEFAULT NULL,
  `pbh_pty_veh_rent_declared` decimal(13,2) DEFAULT NULL,
  `pbh_pty_add_rent` tinyint(1) DEFAULT NULL,
  `pbh_pty_add_rent_declared` tinyint(1) DEFAULT NULL,
  `pbh_fk_vehicles` int(11) unsigned DEFAULT NULL,
  `pbh_temp_vehicle` varchar(30) DEFAULT NULL,
  `pbh_rent` decimal(13,2) DEFAULT NULL,
  `pbh_rent_declared` decimal(13,2) DEFAULT NULL,
  `pbh_fk_driver` int(11) unsigned DEFAULT NULL,
  `pbh_fk_driver_declared` int(11) unsigned DEFAULT NULL,
  `pbh_bata` decimal(13,2) DEFAULT NULL,
  `pbh_bata_declared` decimal(13,2) DEFAULT NULL,
  `pbh_loading` decimal(13,2) DEFAULT NULL,
  `pbh_loading_declared` decimal(13,2) DEFAULT NULL,
  `pbh_loading_mode` tinyint(1) DEFAULT NULL,
  `pbh_loading_mode_declared` tinyint(1) DEFAULT NULL,
  `pbh_round_off` decimal(13,2) DEFAULT NULL,
  `pbh_paid` decimal(13,2) DEFAULT NULL,
  `pbh_remarks` varchar(30) DEFAULT NULL,
  `pbh_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`pbh_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_bill_loaders`
--

CREATE TABLE IF NOT EXISTS `purchase_bill_loaders` (
  `pbl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pbl_fk_purchase_bill_head` int(11) unsigned NOT NULL,
  `pbl_loader` int(11) unsigned NOT NULL,
  `pbl_loading_charge` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`pbl_id`),
  KEY `FKey_purchase_bill_loaders_pbl_fk_purchase_bill_head` (`pbl_fk_purchase_bill_head`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rental_details`
--

CREATE TABLE IF NOT EXISTS `rental_details` (
  `rntdt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rntdt_fk_workcentre` int(11) unsigned NOT NULL,
  `rntdt_fk_owners` int(11) unsigned NOT NULL,
  `rntdt_date` date DEFAULT NULL,
  `rntdt_advance` decimal(13,2) NOT NULL,
  `rntdt_ob` decimal(13,2) NOT NULL,
  `rntdt_ob_mode` tinyint(1) DEFAULT NULL,
  `rntdt_instalment_amount` decimal(13,2) NOT NULL,
  `rntdt_instalment_period` tinyint(1) DEFAULT NULL,
  `rntdt_auto_add` tinyint(1) DEFAULT NULL,
  `rntdt_start_from` date DEFAULT NULL,
  PRIMARY KEY (`rntdt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rent_payables`
--

CREATE TABLE IF NOT EXISTS `rent_payables` (
  `rntpybl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rntpybl_fk_workcentre` int(11) unsigned NOT NULL,
  `rntpybl_fk_owners` int(11) unsigned NOT NULL,
  `rntpybl_date` date DEFAULT NULL,
  `rntpybl_period_belonged_to` varchar(40) DEFAULT NULL,
  `rntpybl_amount` decimal(13,2) NOT NULL,
  `rntpybl_amount_declared` decimal(13,2) NOT NULL,
  PRIMARY KEY (`rntpybl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_billnumber_notax`
--

CREATE TABLE IF NOT EXISTS `sale_billnumber_notax` (
  `sbntx_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sbntx_fk_workcentres` int(11) unsigned NOT NULL,
  `sbntx_no` int(11) unsigned NOT NULL,
  `sbntx_fyear` int(11) unsigned NOT NULL,
  PRIMARY KEY (`sbntx_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_billnumber_tax`
--

CREATE TABLE IF NOT EXISTS `sale_billnumber_tax` (
  `sbtx_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sbtx_fk_workcentre_registration_details` int(11) unsigned NOT NULL,
  `sbtx_no` int(11) unsigned NOT NULL,
  `sbtx_fyear` int(11) unsigned NOT NULL,
  PRIMARY KEY (`sbtx_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `set_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `set_title` varchar(100) DEFAULT NULL,
  `set_key` varchar(20) DEFAULT NULL,
  `set_default_value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`set_id`, `set_title`, `set_key`, `set_default_value`) VALUES
(1, 'Theme', 'THEME', '1'),
(2, 'Automatic redirection', 'REDIRECT', '2'),
(3, 'Verifiers', 'VERIFIERS', '2,3'),
(4, 'Mark current user''s worklog as "verified" to him.', 'MY_WORKLOG', '2'),
(5, 'At which month, the financial year must be changed', 'FYEAR_CHANGE_MONTH', '4'),
(6, 'Change financial year', 'FYEAR_CHANGE_MODE', '1'),
(7, 'Current financial year', 'FYEAR', '2015'),
(8, 'Loading charge payment mode', 'LOADING_PAY_MODE', '2');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `tsk_id` int(11) NOT NULL AUTO_INCREMENT,
  `tsk_name` varchar(50) DEFAULT NULL,
  `tsk_description` varchar(100) DEFAULT NULL,
  `tsk_url` varchar(50) DEFAULT NULL,
  `tsk_parent` int(11) DEFAULT NULL,
  `tsk_pos` tinyint(4) DEFAULT NULL,
  `tsk_display` tinyint(1) DEFAULT NULL,
  `tsk_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`tsk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`tsk_id`, `tsk_name`, `tsk_description`, `tsk_url`, `tsk_parent`, `tsk_pos`, `tsk_display`, `tsk_status`) VALUES
(1, 'Firms', 'Firms', '#', NULL, 2, 2, 2),
(2, 'Add', 'Add Firm', 'firms/add', 1, 1, 2, 2),
(3, 'Login', 'Log in to firm', 'firms/login', 1, 2, 2, 1),
(4, 'Worklogs', 'Worklogs', 'worklogs', 5, 3, 2, 2),
(5, 'Utilities', 'Utilities', '#', NULL, 6, 1, 2),
(6, 'Developer', 'Developer', 'developer', 5, 1, 1, 2),
(7, 'Owners', 'Owners', 'ownners', 9, 4, 1, 2),
(8, 'Add', 'Add owner', 'owners/add', 9, 5, 2, 2),
(9, 'Members', 'Members', '#', NULL, 3, 1, 2),
(10, 'Employee', 'Employees', 'employees', 9, 1, 1, 2),
(11, 'Add', 'Add Employees', 'employees/add', 9, 2, 2, 2),
(12, 'Edit', 'Edit Vehicle', 'vehicles/edit', 9, 9, 2, 2),
(13, 'Workcentre', 'Workcentre', '#', 1, 4, 1, 2),
(14, 'Add', 'Add Workcentre', 'workcentres/add', 13, 1, 2, 2),
(15, 'List Workcentres', 'List Workcentre', 'workcentres', 13, 2, 1, 2),
(16, 'Tasks', 'Manage Tasks Of Employees', 'user_tasks/add', 5, 2, 1, 2),
(18, 'Home', 'Home page', 'index', NULL, 1, 2, 1),
(19, 'Reports', 'Reports', '#', NULL, 7, 1, 2),
(20, 'Balance Sheet', 'Balance Sheet', 'reports/balanceSheet', 19, 1, 1, 2),
(21, 'Cash In Hand', 'Cash In Hand', 'reports/cashInHand', 19, 2, 1, 2),
(22, 'Edit', 'Edit Workcentre', 'workcentres/edit', 13, 3, 2, 2),
(23, 'Edit', 'Edit Firm', 'firms/edit', 1, 3, 2, 2),
(24, 'Employees', 'Employee Availability', 'employee_work_centre', 13, 5, 1, 2),
(25, 'Edit', 'Edit Employee', 'employees/edit', 9, 3, 2, 2),
(26, 'Settings', 'Firm Settings', 'settings', 1, 21, 1, 2),
(27, 'Add', 'Add Firm Settings', 'settings/add', 1, 22, 2, 2),
(28, 'Edit', 'Edit Owner', 'owners/edit', 9, 6, 2, 2),
(29, 'Edit', 'Edit Firm Settings', 'firm_settings/edit', 1, 23, 2, 2),
(30, 'Vehicles', 'List Vehicles', 'vehicles', 9, 7, 1, 2),
(31, 'Add', 'Add Vehicle', 'vehicles/add', 9, 8, 2, 2),
(32, 'Particulars', 'Particulars', '#', NULL, 4, 1, 2),
(33, 'Item Category', 'Show Item Categories', 'item_category', 32, 1, 2, 2),
(34, 'ADD', 'Add Item Category', 'item_category/add', 32, 2, 2, 2),
(35, 'Edit', 'Edit Item Category', 'item_category/edit', 32, 3, 2, 2),
(36, 'Item Heads', 'Show Item Heads', 'item_heads', 32, 4, 1, 2),
(37, 'Add', 'Add Item Heads', 'item_heads/add', 32, 5, 2, 2),
(38, 'Edit', 'Edit Item Heads', 'item_heads/edit', 32, 6, 2, 2),
(39, 'Units', 'Units Of Items', 'units', 32, 7, 2, 2),
(40, 'Add', 'Add Units', 'units/add', 32, 8, 2, 2),
(41, 'Edit', 'Edit Units', 'units/edit', 32, 9, 2, 2),
(42, 'Items', 'Items', 'items', 32, 10, 1, 2),
(43, 'Add', 'Add Items', 'items/add', 32, 11, 2, 2),
(44, 'Edit', 'Edit Items', 'items/edit', 32, 12, 2, 2),
(45, 'Parties', 'Parties', 'parties', 9, 10, 1, 2),
(46, 'ADD', 'Add A Party', 'parties/add', 9, 11, 2, 2),
(47, 'Edit', 'Edit Parties', 'parties/edit', 9, 12, 2, 2),
(48, 'TAX BILL', 'Tax Bill', '#', 32, 13, 1, 2),
(49, 'COMPOUNDED BILL', 'Compounded Bill', '#', 32, 14, 1, 2),
(50, 'Purchase', 'Purchase Bill (Taxable)', 'purchase_bill_head', 48, 1, 1, 2),
(51, 'Add', 'Add Purchase Bill (Taxable)', 'purchase_bill_head/add_tax_bill', 48, 2, 2, 2),
(52, 'Edit', 'Edit Purchase Bill (Taxable)', 'purchase_bill_head/edit_tax_bill', 48, 3, 2, 2),
(53, 'Delete', 'Delete Purchase Bill (Taxable).', 'purchase_bill_head/delete_tax_bill', 48, 4, 2, 2),
(54, 'Purchase', 'Purchase Bill (Compounted)', 'purchase_bill_head', 49, 1, 1, 2),
(55, 'Add', 'Add Purchase Bill (Compounted)', 'purchase_bill_head/add', 49, 2, 2, 2),
(56, 'Edit', 'Edit Purchase Bill (Compounted)', 'purchase_bill_head/edit', 49, 3, 2, 2),
(57, 'Delete', 'Delete Purchase Bill (Compounted).', 'purchase_bill_head/delete', 49, 4, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE IF NOT EXISTS `units` (
  `unt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `unt_batch` int(11) DEFAULT NULL,
  `unt_name` varchar(20) DEFAULT NULL,
  `unt_parent` int(11) DEFAULT NULL,
  `unt_is_parent` tinyint(1) DEFAULT NULL,
  `unt_relation` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`unt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_tasks`
--

CREATE TABLE IF NOT EXISTS `user_tasks` (
  `utsk_id` int(11) NOT NULL AUTO_INCREMENT,
  `utsk_fk_auth_users` int(11) DEFAULT NULL,
  `utsk_fk_tasks` int(11) DEFAULT NULL,
  PRIMARY KEY (`utsk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `user_tasks`
--

INSERT INTO `user_tasks` (`utsk_id`, `utsk_fk_auth_users`, `utsk_fk_tasks`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 18),
(18, 1, 19),
(19, 1, 20),
(20, 1, 21),
(21, 1, 22),
(22, 1, 23),
(23, 1, 24),
(24, 1, 25),
(25, 1, 26),
(26, 1, 27),
(27, 1, 28),
(28, 1, 29),
(29, 1, 30),
(30, 1, 31),
(31, 1, 32),
(32, 1, 33),
(33, 1, 34),
(34, 1, 35),
(35, 1, 36),
(36, 1, 37),
(37, 1, 38),
(38, 1, 39),
(39, 1, 40),
(40, 1, 41),
(41, 1, 42),
(42, 1, 43),
(43, 1, 44),
(44, 1, 45),
(45, 1, 46),
(46, 1, 47),
(47, 1, 48),
(48, 1, 49),
(49, 1, 50),
(50, 1, 51),
(51, 1, 52),
(52, 1, 53),
(53, 1, 54),
(54, 1, 55),
(55, 1, 56),
(56, 1, 57);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE IF NOT EXISTS `vehicles` (
  `vhcl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vhcl_date` date DEFAULT NULL,
  `vhcl_no` varchar(20) DEFAULT NULL,
  `vhcl_name` varchar(25) DEFAULT NULL,
  `vhcl_length` decimal(13,2) DEFAULT NULL,
  `vhcl_breadth` decimal(13,2) DEFAULT NULL,
  `vhcl_height` decimal(13,2) DEFAULT NULL,
  `vhcl_xheight` decimal(13,2) DEFAULT NULL,
  `vhcl_remarks` varchar(50) DEFAULT NULL,
  `vhcl_ownership` tinyint(1) DEFAULT NULL,
  `vhcl_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`vhcl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vhcl_id`, `vhcl_date`, `vhcl_no`, `vhcl_name`, `vhcl_length`, `vhcl_breadth`, `vhcl_height`, `vhcl_xheight`, `vhcl_remarks`, `vhcl_ownership`, `vhcl_status`) VALUES
(1, '2015-02-09', 'V1 F1_W1', '', '0.00', '0.00', '0.00', '0.00', '', 1, 1),
(2, '2015-02-09', 'V2 F1_W2', '', '0.00', '0.00', '0.00', '0.00', '', 1, 1),
(3, '2015-02-09', 'V3 F1_W1_W2 (OTHERS)', '', '0.00', '0.00', '0.00', '0.00', '', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles_employees`
--

CREATE TABLE IF NOT EXISTS `vehicles_employees` (
  `vemp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vemp_fk_employees` int(11) unsigned NOT NULL,
  `vemp_fk_vehicles` int(11) unsigned NOT NULL,
  `vemp_is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`vemp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_workcentres`
--

CREATE TABLE IF NOT EXISTS `vehicle_workcentres` (
  `vwc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vwc_date` date DEFAULT NULL,
  `vwc_fk_workcentres` int(11) unsigned NOT NULL,
  `vwc_fk_vehicles` int(11) unsigned NOT NULL,
  `vwc_cost` decimal(13,2) DEFAULT NULL,
  `vwc_ob` decimal(13,2) DEFAULT NULL,
  `vwc_ob_mode` tinyint(1) DEFAULT NULL,
  `vwc_hourly_rate` decimal(13,2) DEFAULT NULL,
  `vwc_daily_rate` decimal(13,2) DEFAULT NULL,
  `vwc_monthly_rate` decimal(13,2) DEFAULT NULL,
  `vwc_sold_price` decimal(13,2) DEFAULT NULL,
  `vwc_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`vwc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `vehicle_workcentres`
--

INSERT INTO `vehicle_workcentres` (`vwc_id`, `vwc_date`, `vwc_fk_workcentres`, `vwc_fk_vehicles`, `vwc_cost`, `vwc_ob`, `vwc_ob_mode`, `vwc_hourly_rate`, `vwc_daily_rate`, `vwc_monthly_rate`, `vwc_sold_price`, `vwc_status`) VALUES
(1, '2015-02-09', 1, 1, '0.00', '0.00', 2, '0.00', '0.00', '0.00', NULL, 1),
(2, '2015-02-09', 2, 2, '0.00', '0.00', 2, '0.00', '0.00', '0.00', NULL, 1),
(3, '2015-02-09', 1, 3, '0.00', '0.00', 2, '0.00', '0.00', '0.00', NULL, 1),
(4, '2015-02-09', 2, 3, '0.00', '0.00', 2, '0.00', '0.00', '0.00', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE IF NOT EXISTS `verify` (
  `verify_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `verify_fk_worklog_workcentres` int(11) unsigned NOT NULL,
  `verify_fk_auth_users` int(11) unsigned NOT NULL,
  `verify_datime` datetime DEFAULT NULL,
  `verify_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`verify_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `verify`
--

INSERT INTO `verify` (`verify_id`, `verify_fk_worklog_workcentres`, `verify_fk_auth_users`, `verify_datime`, `verify_status`) VALUES
(1, 1, 1, '2015-02-09 11:11:50', 2),
(2, 2, 1, '2015-02-09 11:11:50', 2),
(3, 3, 1, '2015-02-09 11:16:35', 2),
(4, 4, 1, '2015-02-09 11:17:19', 2),
(5, 5, 1, '2015-02-09 11:17:41', 2),
(6, 6, 1, '2015-02-09 11:18:02', 2),
(7, 7, 1, '2015-02-09 11:18:17', 2),
(8, 8, 1, '2015-02-09 11:18:17', 2),
(9, 9, 1, '2015-02-09 16:41:10', 2),
(10, 10, 1, '2015-02-09 16:41:10', 2),
(11, 11, 1, '2015-02-09 16:41:40', 2),
(12, 12, 1, '2015-02-09 16:44:06', 2),
(13, 13, 1, '2015-02-09 16:44:06', 2),
(14, 14, 1, '2015-02-09 16:48:05', 2),
(15, 15, 1, '2015-02-09 16:48:06', 2),
(16, 16, 1, '2015-02-09 16:48:06', 2),
(17, 17, 1, '2015-02-09 16:49:51', 2),
(18, 18, 1, '2015-02-09 16:49:51', 2),
(19, 19, 1, '2015-02-09 16:50:28', 2),
(20, 20, 1, '2015-02-09 16:50:28', 2),
(21, 21, 1, '2015-02-09 16:50:55', 2),
(22, 22, 1, '2015-02-09 16:50:55', 2),
(23, 23, 1, '2015-02-09 16:50:55', 2),
(24, 24, 1, '2015-02-09 17:00:23', 2),
(25, 25, 1, '2015-02-09 17:00:23', 2),
(26, 26, 1, '2015-02-09 17:00:49', 2),
(27, 27, 1, '2015-02-09 17:00:49', 2),
(28, 28, 1, '2015-02-09 17:01:21', 2),
(29, 29, 1, '2015-02-09 17:01:21', 2),
(30, 30, 1, '2015-02-09 17:01:21', 2),
(31, 31, 1, '2015-02-09 17:04:02', 2),
(32, 32, 1, '2015-02-09 17:04:02', 2),
(33, 33, 1, '2015-02-09 17:04:02', 2),
(34, 34, 1, '2015-02-09 17:04:02', 2),
(35, 35, 1, '2015-02-09 17:04:02', 2),
(36, 36, 1, '2015-02-09 17:04:02', 2),
(37, 37, 1, '2015-02-09 17:04:59', 2),
(38, 38, 1, '2015-02-09 17:04:59', 2),
(39, 39, 1, '2015-02-09 17:06:42', 2),
(40, 40, 1, '2015-02-09 17:06:42', 2),
(41, 41, 1, '2015-02-09 17:06:42', 2),
(42, 42, 1, '2015-02-09 17:06:42', 2),
(43, 43, 1, '2015-02-09 17:06:42', 2),
(44, 44, 1, '2015-02-09 17:09:14', 2),
(45, 45, 1, '2015-02-09 17:09:14', 2),
(46, 46, 1, '2015-02-09 17:09:14', 2),
(47, 47, 1, '2015-02-09 17:09:14', 2),
(48, 48, 1, '2015-02-09 17:09:14', 2),
(49, 49, 1, '2015-02-09 17:09:14', 2),
(50, 50, 1, '2015-02-09 17:09:14', 2),
(51, 51, 1, '2015-02-09 17:09:14', 2),
(52, 52, 1, '2015-02-09 17:09:14', 2),
(53, 53, 1, '2015-02-09 17:09:14', 2),
(54, 54, 1, '2015-02-09 17:10:11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `workcentres`
--

CREATE TABLE IF NOT EXISTS `workcentres` (
  `wcntr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wcntr_fk_firms` int(11) unsigned NOT NULL,
  `wcntr_date` date DEFAULT NULL,
  `wcntr_ownership` tinyint(1) DEFAULT NULL,
  `wcntr_capital` decimal(13,2) NOT NULL,
  `wcntr_name` varchar(20) DEFAULT NULL,
  `wcntr_fk_workcentre_registration_details` int(11) unsigned NOT NULL,
  `wcntr_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`wcntr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `workcentres`
--

INSERT INTO `workcentres` (`wcntr_id`, `wcntr_fk_firms`, `wcntr_date`, `wcntr_ownership`, `wcntr_capital`, `wcntr_name`, `wcntr_fk_workcentre_registration_details`, `wcntr_status`) VALUES
(1, 1, '2015-02-09', 1, '0.00', 'F1_w1', 1, 1),
(2, 1, '2015-02-09', 1, '0.00', 'F1_w2', 0, 1),
(3, 2, '2015-02-09', 1, '0.00', 'Default Workcentre', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `workcentre_rates`
--

CREATE TABLE IF NOT EXISTS `workcentre_rates` (
  `wrt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wrt_fk_workcentres_from` int(11) unsigned NOT NULL,
  `wrt_fk_workcentres_to` int(11) unsigned NOT NULL,
  `wrt_fk_items` int(11) unsigned NOT NULL,
  `wrt_fk_units` int(11) unsigned NOT NULL,
  `wrt_s_rate` decimal(13,2) DEFAULT NULL,
  PRIMARY KEY (`wrt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `workcentre_registration_details`
--

CREATE TABLE IF NOT EXISTS `workcentre_registration_details` (
  `wrd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wrd_fk_firms` int(11) unsigned NOT NULL,
  `wrd_date` date DEFAULT NULL,
  `wrd_name` varchar(50) DEFAULT NULL,
  `wrd_address` varchar(250) DEFAULT NULL,
  `wrd_phone` varchar(20) DEFAULT NULL,
  `wrd_email` varchar(40) DEFAULT NULL,
  `wrd_tin` varchar(20) DEFAULT NULL,
  `wrd_licence` varchar(20) DEFAULT NULL,
  `wrd_cst` varchar(20) DEFAULT NULL,
  `wrd_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`wrd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `workcentre_registration_details`
--

INSERT INTO `workcentre_registration_details` (`wrd_id`, `wrd_fk_firms`, `wrd_date`, `wrd_name`, `wrd_address`, `wrd_phone`, `wrd_email`, `wrd_tin`, `wrd_licence`, `wrd_cst`, `wrd_status`) VALUES
(1, 1, '2015-02-09', 'F1_w1 Pvt Ltd', 'Pulikkal', '998898978', 'f1_w1@gmail.com', '32145200', 'L 3654', 'KL 3265', 1);

-- --------------------------------------------------------

--
-- Table structure for table `worklogs`
--

CREATE TABLE IF NOT EXISTS `worklogs` (
  `wlog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wlog_fk_auth_users` int(11) unsigned NOT NULL,
  `wlog_firms` varchar(100) DEFAULT NULL,
  `wlog_created` datetime DEFAULT NULL,
  `wlog_warnings` tinyint(1) DEFAULT NULL,
  `wlog_ref_table` varchar(50) DEFAULT NULL,
  `wlog_ref_id` int(11) unsigned NOT NULL,
  `wlog_ref_url` varchar(70) DEFAULT NULL,
  `wlog_popup_id` varchar(50) DEFAULT NULL,
  `wlog_clsfunc` varchar(70) DEFAULT NULL,
  `wlog_ref_action` tinyint(1) DEFAULT NULL,
  `wlog_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`wlog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `worklogs`
--

INSERT INTO `worklogs` (`wlog_id`, `wlog_fk_auth_users`, `wlog_firms`, `wlog_created`, `wlog_warnings`, `wlog_ref_table`, `wlog_ref_id`, `wlog_ref_url`, `wlog_popup_id`, `wlog_clsfunc`, `wlog_ref_action`, `wlog_status`) VALUES
(1, 1, '1', '2015-02-09 11:11:50', 2, 'firms', 1, '', '', 'firms/add', 1, 1),
(2, 1, '1', '2015-02-09 11:11:50', 2, 'workcentres', 1, 'workcentres/index', 'pop_wlog_common', 'firms/add', 1, 2),
(3, 1, '1', '2015-02-09 11:16:35', 2, 'workcentre_registration_details', 1, '', 'pop_wlog_common', 'workcentre_registration_details/add', 1, 1),
(4, 1, '1', '2015-02-09 11:17:19', 2, 'workcentres', 1, 'workcentres/index', 'pop_wlog_common', 'workcentres/edit', 2, 1),
(5, 1, '1', '2015-02-09 11:17:41', 2, 'workcentres', 2, 'workcentres/index', 'pop_wlog_common', 'workcentres/add', 1, 2),
(6, 1, '1', '2015-02-09 11:18:02', 2, 'workcentres', 2, 'workcentres/index', 'pop_wlog_common', 'workcentres/edit', 2, 1),
(7, 1, '1,2', '2015-02-09 11:18:17', 2, 'firms', 2, '', '', 'firms/add', 1, 1),
(8, 1, '2', '2015-02-09 11:18:17', 2, 'workcentres', 3, 'workcentres/index', 'pop_wlog_common', 'firms/add', 1, 1),
(9, 1, '1', '2015-02-09 16:41:10', 2, 'employees', 2, '', 'pop_wlog_common', 'employees/add', 1, 2),
(10, 1, '1', '2015-02-09 16:41:10', 2, 'employee_work_centre', 4, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(11, 1, '1', '2015-02-09 16:41:40', 2, 'employees', 2, '', 'pop_wlog_common', 'employees/edit', 2, 1),
(12, 1, '1', '2015-02-09 16:44:06', 2, 'employees', 3, '', 'pop_wlog_common', 'employees/add', 1, 1),
(13, 1, '1', '2015-02-09 16:44:06', 2, 'employee_work_centre', 5, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(14, 1, '1', '2015-02-09 16:48:05', 2, 'employees', 4, '', 'pop_wlog_common', 'employees/add', 1, 1),
(15, 1, '1', '2015-02-09 16:48:06', 2, 'employee_work_centre', 6, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(16, 1, '1', '2015-02-09 16:48:06', 2, 'employee_work_centre', 7, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(17, 1, '1', '2015-02-09 16:49:51', 2, 'employees', 5, '', 'pop_wlog_common', 'employees/add', 1, 1),
(18, 1, '1', '2015-02-09 16:49:51', 2, 'employee_work_centre', 8, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(19, 1, '1', '2015-02-09 16:50:28', 2, 'employees', 6, '', 'pop_wlog_common', 'employees/add', 1, 1),
(20, 1, '1', '2015-02-09 16:50:28', 2, 'employee_work_centre', 9, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(21, 1, '1', '2015-02-09 16:50:55', 2, 'employees', 7, '', 'pop_wlog_common', 'employees/add', 1, 1),
(22, 1, '1', '2015-02-09 16:50:55', 2, 'employee_work_centre', 10, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(23, 1, '1', '2015-02-09 16:50:55', 2, 'employee_work_centre', 11, 'employee_work_centre/index', 'pop_wlog_common', 'employees/add', 1, 1),
(24, 1, '1', '2015-02-09 17:00:23', 2, 'vehicles', 1, 'vehicles/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(25, 1, '1', '2015-02-09 17:00:23', 2, 'vehicle_workcentres', 1, 'vehicle_workcentres/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(26, 1, '1', '2015-02-09 17:00:49', 2, 'vehicles', 2, 'vehicles/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(27, 1, '1', '2015-02-09 17:00:49', 2, 'vehicle_workcentres', 2, 'vehicle_workcentres/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(28, 1, '1', '2015-02-09 17:01:21', 2, 'vehicles', 3, 'vehicles/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(29, 1, '1', '2015-02-09 17:01:21', 2, 'vehicle_workcentres', 3, 'vehicle_workcentres/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(30, 1, '1', '2015-02-09 17:01:21', 2, 'vehicle_workcentres', 4, 'vehicle_workcentres/index', 'pop_wlog_common', 'vehicles/add', 1, 1),
(31, 1, '1', '2015-02-09 17:04:02', 2, 'parties', 1, '', 'pop_wlog_common', 'parties/add', 1, 1),
(32, 1, '1', '2015-02-09 17:04:02', 2, 'party_vehicles', 1, '', 'pop_wlog_common', 'parties/add', 1, 1),
(33, 1, '1', '2015-02-09 17:04:02', 2, 'party_vehicles', 2, '', 'pop_wlog_common', 'parties/add', 1, 1),
(34, 1, '1', '2015-02-09 17:04:02', 2, 'party_vehicles', 3, '', 'pop_wlog_common', 'parties/add', 1, 1),
(35, 1, '1', '2015-02-09 17:04:02', 2, 'party_destinations', 1, '', 'pop_wlog_common', 'parties/add', 1, 1),
(36, 1, '1', '2015-02-09 17:04:02', 2, 'destination_workcentres', 1, '', 'pop_wlog_common', 'parties/add', 1, 1),
(37, 1, '1', '2015-02-09 17:04:59', 2, 'party_destinations', 2, '', 'pop_wlog_common', 'party_destinations/add', 1, 1),
(38, 1, '1', '2015-02-09 17:04:59', 2, 'destination_workcentres', 2, '', 'pop_wlog_common', 'party_destinations/add', 1, 1),
(39, 1, '1', '2015-02-09 17:06:42', 2, 'parties', 2, '', 'pop_wlog_common', 'parties/add', 1, 1),
(40, 1, '1', '2015-02-09 17:06:42', 2, 'party_vehicles', 4, '', 'pop_wlog_common', 'parties/add', 1, 1),
(41, 1, '1', '2015-02-09 17:06:42', 2, 'party_vehicles', 5, '', 'pop_wlog_common', 'parties/add', 1, 1),
(42, 1, '1', '2015-02-09 17:06:42', 2, 'party_destinations', 3, '', 'pop_wlog_common', 'parties/add', 1, 1),
(43, 1, '1', '2015-02-09 17:06:42', 2, 'destination_workcentres', 3, '', 'pop_wlog_common', 'parties/add', 1, 1),
(44, 1, '1', '2015-02-09 17:09:14', 2, 'parties', 3, '', 'pop_wlog_common', 'parties/add', 1, 1),
(45, 1, '1', '2015-02-09 17:09:14', 2, 'party_vehicles', 6, '', 'pop_wlog_common', 'parties/add', 1, 1),
(46, 1, '1', '2015-02-09 17:09:14', 2, 'party_vehicles', 7, '', 'pop_wlog_common', 'parties/add', 1, 1),
(47, 1, '1', '2015-02-09 17:09:14', 2, 'party_vehicles', 8, '', 'pop_wlog_common', 'parties/add', 1, 1),
(48, 1, '1', '2015-02-09 17:09:14', 2, 'party_destinations', 4, '', 'pop_wlog_common', 'parties/add', 1, 1),
(49, 1, '1', '2015-02-09 17:09:14', 2, 'destination_workcentres', 4, '', 'pop_wlog_common', 'parties/add', 1, 1),
(50, 1, '1', '2015-02-09 17:09:14', 2, 'party_destinations', 5, '', 'pop_wlog_common', 'parties/add', 1, 1),
(51, 1, '1', '2015-02-09 17:09:14', 2, 'destination_workcentres', 5, '', 'pop_wlog_common', 'parties/add', 1, 1),
(52, 1, '1', '2015-02-09 17:09:14', 2, 'party_destinations', 6, '', 'pop_wlog_common', 'parties/add', 1, 1),
(53, 1, '1', '2015-02-09 17:09:14', 2, 'destination_workcentres', 6, '', 'pop_wlog_common', 'parties/add', 1, 1),
(54, 1, '1', '2015-02-09 17:10:11', 2, 'party_vehicle_rents', 1, '', 'pop_wlog_common', 'party_vehicle_rents/add', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `worklog_workcentres`
--

CREATE TABLE IF NOT EXISTS `worklog_workcentres` (
  `wlog_wc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wlog_wc_fk_worklogs` int(11) unsigned NOT NULL,
  `wlog_wc_fk_workcentres` int(11) unsigned NOT NULL,
  `wlog_wc_message` varchar(600) DEFAULT NULL,
  `wlog_wc_action` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`wlog_wc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `worklog_workcentres`
--

INSERT INTO `worklog_workcentres` (`wlog_wc_id`, `wlog_wc_fk_worklogs`, `wlog_wc_fk_workcentres`, `wlog_wc_message`, `wlog_wc_action`) VALUES
(1, 1, 0, 'A new firm <span class="wlg_name">Firm 1</span> and it''s default settings were added.', 1),
(2, 2, 0, 'A new workcentre <span class="wlg_name">Default Workcentre</span> has been added under the firm: Firm 1.', 1),
(3, 3, 0, 'A new registration details <span class="wlg_name">F1_w1 Pvt Ltd</span> added.', 1),
(4, 4, 0, 'The workcentre <span class="wlg_name">Default Workcentre</span> has been edited.', 2),
(5, 5, 0, 'A new workcentre <span class="wlg_name">F1_w2</span> under firm: Firm 1 has been added.', 1),
(6, 6, 0, 'The workcentre <span class="wlg_name">F1_w2</span> has been edited.', 2),
(7, 7, 0, 'A new firm <span class="wlg_name">Firm 2</span> and it''s default settings were added.', 1),
(8, 8, 0, 'A new workcentre <span class="wlg_name">Default Workcentre</span> has been added under the firm: Firm 2.', 1),
(9, 9, 0, 'A new Driver <span class="wlg_name">F1_w1</span> and his personal details have added.', 1),
(10, 10, 1, 'Driver <span class="wlg_name">F1_w1</span> has became as a member of the workcentre.', 1),
(11, 11, 1, 'Personal Details of Driver <span class="wlg_name">D F1_w1</span> has been edited.', 2),
(12, 12, 0, 'A new Driver <span class="wlg_name">D F1_W2</span> and his personal details have added.', 1),
(13, 13, 2, 'Driver <span class="wlg_name">D F1_W2</span> has became as a member of the workcentre.', 1),
(14, 14, 0, 'A new Driver <span class="wlg_name">D F1_W1_W2</span> and his personal details have added.', 1),
(15, 15, 1, 'Driver <span class="wlg_name">D F1_W1_W2</span> has became as a member of the workcentre.', 1),
(16, 16, 2, 'Driver <span class="wlg_name">D F1_W1_W2</span> has became as a member of the workcentre.', 1),
(17, 17, 0, 'A new Loader <span class="wlg_name">L1 F1_W1</span> and his personal details have added.', 1),
(18, 18, 1, 'Loader <span class="wlg_name">L1 F1_W1</span> has became as a member of the workcentre.', 1),
(19, 19, 0, 'A new Loader <span class="wlg_name">L2 F1_W2</span> and his personal details have added.', 1),
(20, 20, 2, 'Loader <span class="wlg_name">L2 F1_W2</span> has became as a member of the workcentre.', 1),
(21, 21, 0, 'A new Loader <span class="wlg_name">L3 F1_W1_W2</span> and his personal details have added.', 1),
(22, 22, 1, 'Loader <span class="wlg_name">L3 F1_W1_W2</span> has became as a member of the workcentre.', 1),
(23, 23, 2, 'Loader <span class="wlg_name">L3 F1_W1_W2</span> has became as a member of the workcentre.', 1),
(24, 24, 0, 'A new vehicle <span class="wlg_name">V1 F1_W1</span> has been added.', 1),
(25, 25, 1, 'Vehicle  <span class="wlg_name">V1 F1_W1</span> has been registered in the workcentre.', 1),
(26, 26, 0, 'A new vehicle <span class="wlg_name">V2 F1_W2</span> has been added.', 1),
(27, 27, 2, 'Vehicle  <span class="wlg_name">V2 F1_W2</span> has been registered in the workcentre.', 1),
(28, 28, 0, 'A new vehicle <span class="wlg_name">V3 F1_W1_W2 (OTHERS)</span> has been added.', 1),
(29, 29, 1, 'Vehicle  <span class="wlg_name">V3 F1_W1_W2 (OTHERS)</span> has been registered in the workcentre.', 1),
(30, 30, 2, 'Vehicle  <span class="wlg_name">V3 F1_W1_W2 (OTHERS)</span> has been registered in the workcentre.', 1),
(31, 31, 1, 'A new party  <span class="wlg_name">P1</span> has been added.', 1),
(32, 32, 1, 'A new vehicle  <span class="wlg_name">P1 V1</span> has been added for the party P1.', 1),
(33, 33, 1, 'A new vehicle  <span class="wlg_name">P1 V2</span> has been added for the party P1.', 1),
(34, 34, 1, 'A new vehicle  <span class="wlg_name">P1 V3</span> has been added for the party P1.', 1),
(35, 35, 1, 'A new destination  <span class="wlg_name">P1 D1 F1_w1</span> has been added for the party <span class="wlg_name">P1</span>.', 1),
(36, 36, 1, 'The destination <span class="wlg_name">P1 D1 F1_w1</span> of party <span class="wlg_name">P1</span> has been registered in the workcentre on its creation time.', 1),
(37, 37, 1, 'A new destination <span class="wlg_name">P1 D2 F1_w2</span> for party <span class="wlg_name">P1</span> has been added.', 1),
(38, 38, 2, 'The destination <span class="wlg_name">P1 D2 F1_w2</span> of party <span class="wlg_name">P1</span> has been registered in the workcentre on its creation time.', 1),
(39, 39, 1, 'A new party  <span class="wlg_name">P2</span> has been added.', 1),
(40, 40, 1, 'A new vehicle  <span class="wlg_name">P2 V1</span> has been added for the party P2.', 1),
(41, 41, 1, 'A new vehicle  <span class="wlg_name">P2 V2</span> has been added for the party P2.', 1),
(42, 42, 1, 'A new destination  <span class="wlg_name">P2 D1 F1_w1</span> has been added for the party <span class="wlg_name">P2</span>.', 1),
(43, 43, 1, 'The destination <span class="wlg_name">P2 D1 F1_w1</span> of party <span class="wlg_name">P2</span> has been registered in the workcentre on its creation time.', 1),
(44, 44, 2, 'A new party  <span class="wlg_name">P3</span> has been added.', 1),
(45, 45, 2, 'A new vehicle  <span class="wlg_name">P3 V1</span> has been added for the party P3.', 1),
(46, 46, 2, 'A new vehicle  <span class="wlg_name">P3 V2</span> has been added for the party P3.', 1),
(47, 47, 2, 'A new vehicle  <span class="wlg_name">P3 V3</span> has been added for the party P3.', 1),
(48, 48, 2, 'A new destination  <span class="wlg_name">P3 D1 F1_w2</span> has been added for the party <span class="wlg_name">P3</span>.', 1),
(49, 49, 2, 'The destination <span class="wlg_name">P3 D1 F1_w2</span> of party <span class="wlg_name">P3</span> has been registered in the workcentre on its creation time.', 1),
(50, 50, 2, 'A new destination  <span class="wlg_name">P3 D2 F1_w2</span> has been added for the party <span class="wlg_name">P3</span>.', 1),
(51, 51, 2, 'The destination <span class="wlg_name">P3 D2 F1_w2</span> of party <span class="wlg_name">P3</span> has been registered in the workcentre on its creation time.', 1),
(52, 52, 2, 'A new destination  <span class="wlg_name">P3 D3 F1_w2</span> has been added for the party <span class="wlg_name">P3</span>.', 1),
(53, 53, 2, 'The destination <span class="wlg_name">P3 D3 F1_w2</span> of party <span class="wlg_name">P3</span> has been registered in the workcentre on its creation time.', 1),
(54, 54, 2, 'Freight charge for vehicle <span class="wlg_name">P3 V1</span> of party <span class="wlg_name">P3</span>  from his destination <span class="wlg_name">P3 D1 F1_w2</span> to the workcentre has been added.', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_users_groups`
--
ALTER TABLE `auth_users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `firm_settings`
--
ALTER TABLE `firm_settings`
  ADD CONSTRAINT `FKey_firm_settings_frmset_fk_firms` FOREIGN KEY (`frmset_fk_firms`) REFERENCES `firms` (`firm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKey_firm_settings_frmset_fk_settings` FOREIGN KEY (`frmset_fk_settings`) REFERENCES `settings` (`set_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_bill_additives`
--
ALTER TABLE `purchase_bill_additives`
  ADD CONSTRAINT `FKey_purchase_bill_additives_pba_fk_purchase_bill_head` FOREIGN KEY (`pba_fk_purchase_bill_head`) REFERENCES `purchase_bill_head` (`pbh_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_bill_body`
--
ALTER TABLE `purchase_bill_body`
  ADD CONSTRAINT `FKey_purchase_bill_body_pbb_fk_purchase_bill_head` FOREIGN KEY (`pbb_fk_purchase_bill_head`) REFERENCES `purchase_bill_head` (`pbh_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_bill_deductives`
--
ALTER TABLE `purchase_bill_deductives`
  ADD CONSTRAINT `FKey_purchase_bill_deductives_pbd_fk_purchase_bill_head` FOREIGN KEY (`pbd_fk_purchase_bill_head`) REFERENCES `purchase_bill_head` (`pbh_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_bill_loaders`
--
ALTER TABLE `purchase_bill_loaders`
  ADD CONSTRAINT `FKey_purchase_bill_loaders_pbl_fk_purchase_bill_head` FOREIGN KEY (`pbl_fk_purchase_bill_head`) REFERENCES `purchase_bill_head` (`pbh_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
