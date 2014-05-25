-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2014 at 11:41 AM
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_2` (`key`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `key`) VALUES
(47, '15 tot 70 mm'),
(56, 'Accu'),
(39, 'Afkort'),
(59, 'agregaat'),
(86, 'Alleszagen'),
(54, 'Aluminium'),
(70, 'beton'),
(68, 'beugelzaagmachine'),
(87, 'Black&amp;Decker'),
(64, 'Bouwdroger'),
(50, 'bouwradio'),
(38, 'Dewalt'),
(73, 'Diesel'),
(85, 'dompelpomp'),
(52, 'Draadsnijdkoppen'),
(66, 'Draaibank'),
(65, 'ELBAC'),
(76, 'ESAB'),
(58, 'Generator'),
(63, 'GX 160'),
(78, 'Halfautomatisch'),
(62, 'Honda'),
(71, 'JCB tlt 25 D'),
(74, 'JCB TM 200'),
(49, 'kettingzaag'),
(82, 'kipwagen'),
(83, 'kruiwagen'),
(60, 'Kubota'),
(57, 'lader'),
(77, 'lasapparaat'),
(67, 'myford'),
(44, 'Nageltoestel'),
(46, 'P70'),
(80, 'Palletwagen'),
(90, 'POW64250'),
(89, 'Powerplus'),
(42, 'radiaalzaag'),
(51, 'Ridgid'),
(75, 'Rollgroefmachine'),
(81, 'Rupsdumper'),
(55, 'Schroefmachine'),
(53, 'Schuifladders'),
(45, 'Spit'),
(48, 'Stihl'),
(79, 'Transpallet'),
(69, 'trilnaald'),
(61, 'Trilplaat'),
(84, 'Vacu&uuml;m'),
(72, 'Verreiker'),
(40, 'Verstek'),
(41, 'Zaag'),
(88, 'zaagmachine');

-- --------------------------------------------------------

--
-- Table structure for table `key_for_tools`
--

CREATE TABLE IF NOT EXISTS `key_for_tools` (
  `tools_id` int(10) unsigned NOT NULL,
  `key_id` int(10) unsigned NOT NULL,
  KEY `tools_id` (`tools_id`),
  KEY `key_id` (`key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `key_for_tools`
--

INSERT INTO `key_for_tools` (`tools_id`, `key_id`) VALUES
(68, 38),
(68, 39),
(68, 40),
(68, 41),
(69, 38),
(69, 42),
(70, 44),
(70, 45),
(70, 46),
(70, 47),
(71, 48),
(71, 49),
(72, 38),
(72, 50),
(73, 51),
(73, 52),
(74, 53),
(74, 54),
(75, 55),
(75, 56),
(75, 57),
(76, 58),
(76, 59),
(76, 60),
(77, 61),
(77, 62),
(77, 63),
(78, 64),
(78, 65),
(79, 66),
(79, 67),
(80, 68),
(81, 69),
(81, 70),
(82, 71),
(82, 72),
(82, 73),
(83, 74),
(83, 72),
(84, 51),
(84, 75),
(85, 76),
(85, 77),
(85, 78),
(86, 79),
(86, 80),
(87, 62),
(87, 81),
(87, 82),
(87, 83),
(88, 41),
(89, 84),
(89, 85),
(90, 86),
(90, 87),
(90, 88),
(91, 49),
(91, 89),
(91, 90);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` int(10) unsigned NOT NULL,
  `to_user` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date_send` date NOT NULL,
  `message_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_user` (`from_user`,`to_user`),
  KEY `to_user` (`to_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tool_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'waiting',
  PRIMARY KEY (`id`),
  KEY `toool_id` (`tool_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `toolcountperuser`
--
CREATE TABLE IF NOT EXISTS `toolcountperuser` (
`parent` int(10) unsigned
,`count_child` bigint(21)
);
-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE IF NOT EXISTS `tools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `price` double DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tools_ibfk_1` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=92 ;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `user_id`, `title`, `content`, `price`, `status`, `date`) VALUES
(68, 20, 'Dewalt DE 705 zware afkort/verstekzaag', '<p>Professionele Dewalt DE 705 zware afkort/verstekzaag 1500W</p>\r\n<p>100% staat,deze zaagt diepte 11cm en 21cm breed, door zijn groot&nbsp;</p>\r\n<p>D305 mm zaagblad,1500W met zeer goed zaagblad,Op niets geen speling</p>\r\n<p>niet zoveel gebruikt</p>', NULL, 'Available', '2014-05-25'),
(69, 20, 'Dewalt radiaalzaag', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">dewalt radiaalzaag dw721 met voet</span></span></p>', 20, 'Available', '2014-05-25'),
(70, 20, 'Nagelpistool', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">Nageltoestel Spit P70</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">&nbsp;</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">In zeer goede staat.</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">Geschikt om te nagelen in staal / steen en beton.</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">Voor nagels van 15 tot 70 mm.</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">Werkt met patronen.</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">10 x nagelen met 1 schijfje .</span></span></p>', NULL, 'Available', '2014-05-25'),
(71, 20, 'Stihl kettingzaag', '<p>Aangeboden:</p>\r\n<p>-Kettingzaag Stihl...</p>\r\n<p>-Type 025...</p>\r\n<p>-In goede staat met goede ketting...</p>', 5, 'Available', '2014-05-25'),
(72, 20, 'Dewalt bouwradio', '<p>Aangeboden:</p>\r\n<p>-Dewalt Bouwradio...</p>\r\n<p>-In goede staat met batterij...</p>', NULL, 'Available', '2014-05-25'),
(73, 20, 'Draadsnijdkoppen-Ridgid', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">Ridgid set van drie koppen 1/2+3/4+1 duim,</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">met bijhorende arm</span></span></p>', NULL, 'Available', '2014-05-25'),
(74, 20, 'Schuifladders', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">alu schuifladder 2 stuk 5.50 m lang met koord in goede staat</span></span></p>', NULL, 'Available', '2014-05-25'),
(75, 20, 'Schroefmachine op accu', '<p>Nieuwe schroefmachine op accu</p>\r\n<p>4,8V met lader</p>\r\n<p>Nieuw in doos</p>', 15, 'Available', '2014-05-25'),
(76, 20, 'Generator/agregaat 6 KVA Kubota', '<p>-Generator KUBOTA GH400*</p>\r\n<p>Benzine</p>\r\n<p>6 KVA</p>\r\n<p>-Honda 4 kvA&nbsp;</p>\r\n<p>Ook kleine Honda generator 800watt is verkrijgbaar tegen 15euro/dag enz...</p>\r\n<p>Waarborg: 100euro</p>\r\n<p>kan afgehaald worden met een personenwagen</p>\r\n<p>leveren mogelijk</p>', 25, 'Available', '2014-05-25'),
(77, 20, 'Trilplaat Honda GX 160', '<p>Trilplaat Honda</p>\r\n<p>Motor GX 160</p>\r\n<p>-voorzien van extra watervultank&nbsp;</p>\r\n<p>-rubberen ondermat&nbsp;</p>\r\n<p>gewicht : 60kg</p>', 30, 'Available', '2014-05-25'),
(78, 20, 'Bouwdroger ELBAC', '<p>EBAC MK 11</p>\r\n<p>trekt tot 82liter water uit uw ruimtes op 24uur</p>\r\n<p>Hoogte : 90,5cm</p>\r\n<p>Breedte : 61 cm</p>\r\n<p>diepte : 56cm</p>\r\n<p>gewicht : 75kg</p>\r\n<p>minder dan 70 dBa geluid</p>\r\n<p>220 volt</p>\r\n<p>geschikt voor alle ruimte</p>', 4, 'Available', '2014-05-25'),
(79, 20, 'Draaibank myford', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">draaibank myford</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">draaibank myford 380 volt kan ook motor op 220 volt</span></span></p>', NULL, 'Available', '2014-05-25'),
(80, 20, 'Beugelzaagmachine', '<p>beugelzaagmachine 380 volt</p>', 2.5, 'Available', '2014-05-25'),
(81, 20, 'Trilnaald beton met dieselmotor', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">trilnaald beton met dieselmotor</span></span></p>', 200, 'Available', '2014-05-25'),
(82, 20, 'JCB tlt 25 D', '<p>Type: Verreiker</p>\r\n<p>Bouwjaar: 1998</p>\r\n<p>Brandstofsoort: Diesel</p>\r\n<p>Vermogen: 34 kW (46 PK)</p>\r\n<p>Bedrijfsuren: 4.410</p>\r\n<p>Kleur: Geel</p>\r\n<p>Transmissie: Automaat</p>\r\n<p>Boekjes: Aanwezig (dealer onderhouden)</p>\r\n<p>Let op: de vermelde prijs is inclusief BTW</p>\r\n<p>Opties</p>\r\n<p>Achteruitrijalarm</p>\r\n<p>Noodstop</p>\r\n<p>Originele kleur</p>\r\n<p>Servicebeurten uitgevoerd</p>\r\n<p>uitzonderlijk nette staat&nbsp;</p>\r\n<p>dealer onderhouden</p>\r\n<p>Verkoper kan garanderen dat de urenstand juist is</p>\r\n<p>Vol rubber banden</p>\r\n<p>Werklampen</p>\r\n<p>&nbsp;</p>\r\n<p>JCB Teletruk&nbsp;</p>\r\n<p>type ; TLT 25 D&nbsp;</p>\r\n<p>bouwjaar ; 1998&nbsp;</p>\r\n<p>urenstand ; 4410 uur&nbsp;</p>\r\n<p>4 cil 46,5 pk Perkins dieselmotor&nbsp;</p>\r\n<p>hefhoogte ; 4 meter&nbsp;</p>\r\n<p>hefvermogen ; 2,5 ton&nbsp;</p>\r\n<p>snelwissel/ side-shift&nbsp;</p>\r\n<p>extra DW hydro ventiel&nbsp;</p>\r\n<p>prijs ; 8950 ,- ex</p>\r\n<p>Staat van het interieur: Goed</p>\r\n<p>Staat van het elektrische systeem: Goed</p>\r\n<p>Staat van de lampen: Goed</p>\r\n<p>Staat van de aandrijflijn: Goed</p>\r\n<p>Staat van het hydraulische systeem: Goed</p>\r\n<p>Staat van de stuurinrichting: Goed</p>\r\n<p>Staat van de constructie/chassis/lassen: Goed</p>\r\n<p>Staat van het uitlaatsysteem: Goed</p>\r\n<p>Banden voor: 80%</p>\r\n<p>Banden achter: 80%</p>\r\n<p>Palletvorken + afmetingen: Goed</p>\r\n<p>Max. hefvermogen: 2 Ton per meter</p>', NULL, 'Available', '2014-05-25'),
(83, 20, 'JCB TM 200', '<p>JCB TM 200</p>\r\n<p>Type: Verreiker</p>\r\n<p>Bouwjaar: 2002</p>\r\n<p>Bedrijfsuren: 2.960</p>\r\n<p>Let op: de vermelde prijs is inclusief BTW</p>\r\n<p>Opties</p>\r\n<p>Gesloten cabine</p>\r\n<p>&nbsp;</p>\r\n<p>Resterend profiel voorbanden: 50</p>\r\n<p>Resterend profiel achterbanden: 50</p>\r\n<p>incl. vorken</p>\r\n<p>Powershift</p>', 350, 'Available', '2014-05-25'),
(84, 20, 'Ridgid 918-i Rollgroefmachine 2&quot;-12&quot;', '<p>Slechts op 1 werf gebruikt.</p>\r\n<p>Rollgroefmachine voor het walsen van groeven in stalen buizen voor leidingbouw (sprinkler).</p>', 17, 'Available', '2014-05-25'),
(85, 20, 'ESAB lasapparaat', '<p>ESAB LKA 180 Halfautomatisch lasapparaat, weinig gebruikt en in zeer goede staat, met ontspanner en gasfles.</p>', NULL, 'Available', '2014-05-25'),
(86, 20, 'Transpallet/Palletwagen', '<p>Palletwagen in zeer goede staat, weinig gebruikt.</p>', NULL, 'Available', '2014-05-25'),
(87, 20, 'Rupsdumper kipwagen dumper kruiwagen', '<p>Honda Gx 120 motor</p>\r\n<p>Laadcapaciteit 450 kg&nbsp;</p>\r\n<p>3 versnellingen vooruit + 1 achteruit met automatisch remsysteem</p>\r\n<p>Afm. laadbak (L x B x H) 92 x 64 x 24 cm</p>\r\n<p>Uitschuifbare laadvloer tot (L x B) 106 x 102 cm</p>\r\n<p>Manuele kipinstallatie</p>\r\n<p>Werkbreedte 65 cm</p>\r\n<p>Gewicht 180 kg</p>\r\n<p>&nbsp;</p>', 50, 'Available', '2014-05-25'),
(88, 20, 'Professionele zaag', '<p>Kan op verschillende graden gezet worden.</p>\r\n<p>Perfecte kwaliteit.&nbsp;</p>\r\n<p>Zeer goede conditie.</p>', NULL, 'Available', '2014-05-25'),
(89, 20, 'Vacu&uuml;m dompelpomp 400W', '<p>acu&uuml;m dompelpomp 400W te koop.</p>\r\n<p>&nbsp;</p>\r\n<p>Vacu&uuml;m dompelpomp voor het legen van tuinvijvers, ondergelopen kelders en bouwputten.</p>\r\n<p>&nbsp;</p>\r\n<p>400W</p>\r\n<p>9000l/h</p>\r\n<p>Max h: 6m</p>\r\n<p>Stof max: 30mm</p>\r\n<p>&nbsp;</p>\r\n<p>Perfecte conditie.</p>\r\n<p>Werkt uitstekend.</p>', NULL, 'Available', '2014-05-25'),
(90, 20, 'Alleszagen zaagmachine Black&amp;Decker 400W', '<p>Alleszagen zaagmachine Black&amp;Decker 400W te koop.</p>\r\n<p>&nbsp;</p>\r\n<p>400W</p>\r\n<p>Rotatie: 0-5500r/min</p>\r\n<p>&nbsp;</p>\r\n<p>Perfecte conditie.</p>\r\n<p>Werkt uitstekend.</p>', NULL, 'Available', '2014-05-25'),
(91, 20, 'Kettingzaag Powerplus 2000W 350mm POW64250', '<p>Kettingzaag Powerplus 2000W 350mm POW64250 te koop.</p>\r\n<p>&nbsp;</p>\r\n<p>Technische Specificaties&nbsp;</p>\r\n<p>Nominale spanning AC (Un) 230 V~&nbsp;</p>\r\n<p>Frequentie (fn) 50 Hz&nbsp;</p>\r\n<p>Geluidsprestatieniveau (LwA) 112 dB(A)&nbsp;</p>\r\n<p>Beschermingsklasse CLASS II&nbsp;</p>\r\n<p>Inhoud olietank 0.28 L&nbsp;</p>\r\n<p>Snelstop True&nbsp;</p>\r\n<p>Set koolstofborstels True&nbsp;</p>\r\n<p>Accessoires POWACG422&nbsp;</p>\r\n<p>CE markering True&nbsp;</p>\r\n<p>Chemical safety logo True&nbsp;</p>\r\n<p>GS markering True&nbsp;</p>\r\n<p>Chain stop &lt; 0,12 sec.&nbsp;</p>\r\n<p>Automatic lubrication, Safety trigger&nbsp;</p>\r\n<p>Auto-brake hand guard</p>\r\n<p>Side chain tension adjustment&nbsp;</p>\r\n<p>Nominaal vermogen (Pn) 2000 W&nbsp;</p>\r\n<p>Grootte van het blad 355 mm&nbsp;</p>\r\n<p>Extra gereedschap (sleutel) spanner&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Verpakking product&nbsp;</p>\r\n<p>Lengte 50,50cm&nbsp;</p>\r\n<p>Hoogte 27,50cm&nbsp;</p>\r\n<p>Breedte 21,50cm&nbsp;</p>\r\n<p>Gewicht 6,50kg</p>\r\n<p>&nbsp;</p>\r\n<p>Snijdt houd zoals boter.&nbsp;</p>\r\n<p>Perfecte conditie.</p>\r\n<p>Werkt uitstekend.</p>\r\n<p>Was alleen maar &eacute;&eacute;n keer in gebruik.</p>', 25, 'Available', '2014-05-25');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `roles`, `website`, `email`, `address1`, `address2`, `cityTown`, `stateProvinceRegion`, `zipPostal`, `country`) VALUES
(14, 'Glatomme', '$1$Bc0.0r..$poudYceCDiaRXpz9ud/bn/', NULL, '', NULL, 'glenn.latomme@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'NoteBreaking', '$1$HT0.Ew4.$5dIXqe2uOBVaOobyhZx40/', NULL, '', NULL, 'NoteBreaking@mail.com', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'GeniusMedia', '$1$7c3.CE2.$2XAwiLzN5AF3tpavLSjXo0', NULL, '', NULL, 'GeniusMedia@mail.com', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'Flapeuro', '$1$js4.wk4.$DXsvOudH9PGjhsWxHnWVb1', NULL, '', NULL, 'Flapeuro@mail.com', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Certeralee', '$1$3r..OA/.$/G38YI/CMAJIxMx6wwXxq/', NULL, '', NULL, 'Certeralee@mail.com', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'BizareAbout', '$1$9t1.c./.$SW6gtqJVrnRlmefcviglb1', 'Bizare About', '', NULL, 'BizareAbout@mail.com', 'Gebroeders de Smetstraat 1', NULL, 'Gent', 'Oost-vlaanderen', 9000, 'BE'),
(20, 'admin', '$1$kb5.dh1.$B1xh5qB2HPeffmnpJb6HI1', NULL, '', NULL, 'admin@mail.com', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `toolcountperuser`
--
DROP TABLE IF EXISTS `toolcountperuser`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `toolcountperuser` AS select `a`.`id` AS `parent`,count(`b`.`id`) AS `count_child` from (`users` `a` left join `tools` `b` on((`a`.`id` = `b`.`user_id`))) group by `a`.`id` order by `a`.`id`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `key_for_tools`
--
ALTER TABLE `key_for_tools`
  ADD CONSTRAINT `key_for_tools_ibfk_1` FOREIGN KEY (`tools_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key_for_tools_ibfk_2` FOREIGN KEY (`key_id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`to_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`from_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
