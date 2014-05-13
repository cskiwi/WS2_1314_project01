-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2014 at 09:39 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `r0359502_pw`
--

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_2` (`key`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `key`) VALUES
(4, 'sfdsf');

-- --------------------------------------------------------

--
-- Table structure for table `key_for_tools`
--

CREATE TABLE IF NOT EXISTS `key_for_tools` (
  `tools_id` int(11) NOT NULL,
  `key_id` int(11) NOT NULL,
  KEY `tools_id` (`tools_id`),
  KEY `key_id` (`key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `key_for_tools`
--

INSERT INTO `key_for_tools` (`tools_id`, `key_id`) VALUES
(14, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE IF NOT EXISTS `tools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `Price` double DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Availible',
  PRIMARY KEY (`id`),
  KEY `tools_ibfk_1` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `user_id`, `title`, `content`, `Price`, `image`, `status`) VALUES
(14, 1, 'Adding test', 'lksng jksdngjdsn', NULL, '1400008477-Asse-20140430-00078.jpg', 'Availible');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `roles` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `cityTown` varchar(255) DEFAULT NULL,
  `stateProvinceRegion` varchar(255) DEFAULT NULL,
  `zipPostal` int(11) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `roles`, `website`, `email`, `address1`, `address2`, `cityTown`, `stateProvinceRegion`, `zipPostal`, `country`) VALUES
(1, 'cskiwi', '$1$oN1.RS1.$LUgyp.euLnd5ZODFo0Qll1', NULL, '', NULL, 'glenn.latomme@gmail.com', 'Zuidstraat 74', NULL, 'Kaprijke', 'Oost-vlaanderen', 9970, 'Be');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `key_for_tools`
--
ALTER TABLE `key_for_tools`
  ADD CONSTRAINT `key_for_tools_ibfk_2` FOREIGN KEY (`key_id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key_for_tools_ibfk_1` FOREIGN KEY (`tools_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
