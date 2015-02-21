-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 27, 2014 at 08:27 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;



--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `emp_category`, `emp_name`, `emp_date`, `emp_address`,`emp_status`) VALUES
(1, 1, 'Noushad PM','2014-10-25', 'KOTTAPPURAM PO\r\nKONDOTY VIA\r\nMALAPPURAM DT',1);



--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`set_id`, `set_title`, `set_key`, `set_default_value`) VALUES
(1, 'Theme', 'THEME', '1'),
(2, 'Automatic redirection', 'REDIRECT', '2'),
(3, 'Verifiers', 'VERIFIERS', '2,3'),
(4, 'Mark current user''s worklog as "verified" to him.', 'MY_WORKLOG', '2');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
