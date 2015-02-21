-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 25, 2014 at 07:52 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `the_quarry`
--
CREATE DATABASE IF NOT EXISTS `the_quarry` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `the_quarry`;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, NULL, 1268889823, 1419493843, 1, 'Admin', 'istrator', 'ADMIN', '0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `auth_users_groups`
--

INSERT INTO `auth_users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `emp_category`, `emp_name`, `emp_date`, `emp_address`, `emp_status`) VALUES
(1, 1, 'Admin', '2014-10-25', 'KOTTAPPURAM PO\r\nKONDOTY VIA\r\nMALAPPURAM DT', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `employee_work_centre`
--

INSERT INTO `employee_work_centre` (`ewp_id`, `ewp_date`, `ewp_fk_auth_users`, `ewp_fk_workcentres`, `ewp_ob`, `ewp_ob_mode`, `ewp_day_wage`, `ewp_day_hourly_wage`, `ewp_day_ot_wage`, `ewp_night_wage`, `ewp_night_hourly_wage`, `ewp_night_ot_wage`, `ewp_salary_wage`, `ewp_status`) VALUES
(1, '2014-12-25', 1, 1, '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `firms`
--

INSERT INTO `firms` (`firm_id`, `firm_date`, `firm_name`, `firm_status`) VALUES
(1, '2014-12-25', 'Elite', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `firm_settings`
--

INSERT INTO `firm_settings` (`frmset_id`, `frmset_fk_settings`, `frmset_fk_firms`, `frmset_value`) VALUES
(1, 4, 1, '2'),
(2, 2, 1, '2'),
(3, 1, 1, '1'),
(4, 3, 1, '2,3');

-- --------------------------------------------------------

--
-- Table structure for table `form_inputs`
--

CREATE TABLE IF NOT EXISTS `form_inputs` (
  `fip_clsfunc` varchar(100) DEFAULT NULL,
  `fip_values` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`pvr_id`)
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
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `set_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `set_title` varchar(100) DEFAULT NULL,
  `set_key` varchar(20) DEFAULT NULL,
  `set_default_value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`set_id`, `set_title`, `set_key`, `set_default_value`) VALUES
(1, 'Theme', 'THEME', '1'),
(2, 'Automatic redirection', 'REDIRECT', '2'),
(3, 'Verifiers', 'VERIFIERS', '2,3'),
(4, 'Mark current user''s worklog as "verified" to him.', 'MY_WORKLOG', '2');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

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
(47, 'Edit', 'Edit Parties', 'parties/edit', 9, 12, 2, 2);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

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
(46, 1, 47);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `verify`
--

INSERT INTO `verify` (`verify_id`, `verify_fk_worklog_workcentres`, `verify_fk_auth_users`, `verify_datime`, `verify_status`) VALUES
(1, 1, 1, '2014-12-25 13:20:56', 2),
(2, 2, 1, '2014-12-25 13:20:56', 2);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `workcentres`
--

INSERT INTO `workcentres` (`wcntr_id`, `wcntr_fk_firms`, `wcntr_date`, `wcntr_ownership`, `wcntr_capital`, `wcntr_name`, `wcntr_fk_workcentre_registration_details`, `wcntr_status`) VALUES
(1, 1, '2014-12-25', 1, '0.00', 'Default Workcentre', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `workcentre_registration_details`
--

CREATE TABLE IF NOT EXISTS `workcentre_registration_details` (
  `wrd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `worklogs`
--

INSERT INTO `worklogs` (`wlog_id`, `wlog_fk_auth_users`, `wlog_firms`, `wlog_created`, `wlog_warnings`, `wlog_ref_table`, `wlog_ref_id`, `wlog_ref_url`, `wlog_popup_id`, `wlog_clsfunc`, `wlog_ref_action`, `wlog_status`) VALUES
(1, 1, '1', '2014-12-25 13:20:56', 2, 'firms', 1, '', '', 'firms/add', 1, 1),
(2, 1, '1', '2014-12-25 13:20:56', 2, 'workcentres', 1, 'workcentres/index', 'pop_wlog_common', 'firms/add', 1, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `worklog_workcentres`
--

INSERT INTO `worklog_workcentres` (`wlog_wc_id`, `wlog_wc_fk_worklogs`, `wlog_wc_fk_workcentres`, `wlog_wc_message`, `wlog_wc_action`) VALUES
(1, 1, 0, 'A new firm <span class="wlg_name">Elite</span> and it''s default settings were added.', 1),
(2, 2, 0, 'A new workcentre <span class="wlg_name">Default Workcentre</span> has been added under the firm: Elite.', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_users_groups`
--
ALTER TABLE `auth_users_groups`
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `firm_settings`
--
ALTER TABLE `firm_settings`
  ADD CONSTRAINT `FKey_firm_settings_frmset_fk_firms` FOREIGN KEY (`frmset_fk_firms`) REFERENCES `firms` (`firm_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FKey_firm_settings_frmset_fk_settings` FOREIGN KEY (`frmset_fk_settings`) REFERENCES `settings` (`set_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
