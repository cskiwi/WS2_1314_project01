-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2014 at 03:50 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `key`) VALUES
(104, '400W'),
(54, 'Aluminium'),
(87, 'Black&amp;Decker'),
(106, 'bracket Saw'),
(110, 'chainsaw'),
(113, 'Cut'),
(38, 'Dewalt'),
(117, 'diaphragm'),
(73, 'Diesel'),
(116, 'Engines'),
(76, 'ESAB'),
(93, 'Forktrucks'),
(115, 'Gasoline'),
(58, 'Generator'),
(96, 'Grooving'),
(63, 'GX 160'),
(114, 'Heads'),
(62, 'Honda'),
(71, 'JCB tlt 25 D'),
(74, 'JCB TM 200'),
(105, 'Jig Saw'),
(60, 'Kubota'),
(107, 'lathe'),
(97, 'Machine'),
(91, 'miter'),
(67, 'myford'),
(109, 'NailGun'),
(46, 'P70'),
(101, 'Pallet'),
(90, 'POW64250'),
(89, 'Powerplus'),
(108, 'radial'),
(111, 'Radio'),
(51, 'Ridgid'),
(95, 'Roll'),
(92, 'Saw'),
(53, 'Schuifladders'),
(118, 'screw Machine'),
(119, 'sliding Ladders'),
(45, 'Spit'),
(48, 'Stihl'),
(103, 'submersible'),
(94, 'Telehandler'),
(99, 'TIG'),
(100, 'Trans Pallet'),
(84, 'Vacu&uuml;m'),
(102, 'Vacuum'),
(98, 'Welder'),
(112, 'Wire'),
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
(69, 38),
(70, 45),
(70, 46),
(71, 48),
(72, 38),
(73, 51),
(74, 54),
(76, 58),
(76, 60),
(77, 62),
(77, 63),
(79, 67),
(82, 71),
(82, 73),
(83, 74),
(84, 51),
(85, 76),
(89, 84),
(90, 87),
(90, 88),
(91, 89),
(91, 90),
(68, 91),
(68, 92),
(82, 93),
(83, 94),
(82, 94),
(84, 95),
(84, 96),
(84, 97),
(85, 98),
(85, 99),
(86, 100),
(86, 101),
(88, 92),
(89, 102),
(89, 103),
(89, 104),
(90, 105),
(80, 106),
(80, 92),
(79, 107),
(69, 108),
(69, 92),
(70, 109),
(71, 110),
(72, 111),
(73, 112),
(73, 113),
(73, 114),
(76, 115),
(76, 116),
(77, 117),
(91, 110),
(75, 118),
(74, 119);

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
(68, 17, 'Dewalt DE 705  miter saw', '<p><span style="font-family: ''Times New Roman''; font-size: medium;">Dewalt miter saw with dust bag. Good shape. Strong motor. Accurate settings in both Miter and Bevel applications. This one is a Work Horse.</span></p>', NULL, 'Available', '2014-05-25'),
(69, 17, 'Dewalt radial saw', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">dewalt radial saw dw721 with&nbsp;feet</span></span></p>', 20, 'Available', '2014-05-25'),
(70, 18, 'Nailgun', '<p><span style="font-family: ''Times New Roman''; font-size: medium;">I have here a SPit Pulsa 700 P Nail gun.</span><br style="font-family: ''Times New Roman''; font-size: medium;" /><br style="font-family: ''Times New Roman''; font-size: medium;" /><span style="font-family: ''Times New Roman''; font-size: medium;">This item is all fully boxed and comes with 1 battery and 2 gas canisters (both aren''t full)</span><br style="font-family: ''Times New Roman''; font-size: medium;" /><br style="font-family: ''Times New Roman''; font-size: medium;" /><span style="font-family: ''Times New Roman''; font-size: medium;">This is in excellent working condition, comes with instructions, battery, battery charger, charger plug.</span><br style="font-family: ''Times New Roman''; font-size: medium;" /><br style="font-family: ''Times New Roman''; font-size: medium;" /><span style="font-family: ''Times New Roman''; font-size: medium;">Any questions please ask.</span></p>', NULL, 'Available', '2014-05-25'),
(71, 19, 'Stihl chainsaw', '<p>Aangeboden:</p>\r\n<p>-chainsawStihl...</p>\r\n<p>-Type 025..</p>', 5, 'Available', '2014-05-25'),
(72, 18, 'Dewalt radio', '<p>Dewalt radio with battery</p>', NULL, 'Available', '2014-05-25'),
(73, 18, 'Wire Cut Heads  Ridgid', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">Ridgid set of 3&nbsp;cut heads&nbsp;1/2+3/4+1 inch</span></span></p>\r\n<p>With arm</p>', NULL, 'Available', '2014-05-25'),
(74, 15, 'sliding Ladders', '<p>2 piece&nbsp;sliding Ladders</p>\r\n<p>5.5m</p>', NULL, 'Available', '2014-05-25'),
(75, 15, 'screw Machine with battery', '<p>4,8V with charger</p>', 15, 'Available', '2014-05-25'),
(76, 15, 'Kubota GH Series Air Cooled Gasoline Engines', '<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;"><strong>The Kubota OHV System Provides Economical Operation</strong><br />The side valve system, used in conventional, multipurpose air-cooled gasoline engines, produces a considerable amount of heat loss with low compression ratios which result in poor combustion efficiency. The overhead valve suystem used in the Kubota Super OHV Series solves these problems, providing a high thermal efficiency and much better combustion than a side valve system. As a result, the Kubota Super OHV Series is highly economical, with 25% less fuel consumption and about half the lubricating oil consumption of a side valve engine.</p>\r\n<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;"><strong>Durability and Reliability</strong><br />Kubota engines are designed for heavy-duty perdformance with an extrmemly rigid engine construction. A variety of innovative measures have been introduced with careful attention to every detail. For example, these engines are equipped with a dust and corrosion-proof carburetor and aluminum jets, as well as chrome-plated piston rings, a trouble-free breakerless ignition and other high quality components to ensure excellent durability and reliability. As a result, even when operated continuously for extended periods of time, there is no increase in oil consumption.</p>\r\n<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;"><strong>Low Vibration</strong><br />Numerous technological improvements have been implemented to reduce vibration. The high horsepower GH400 features a twin-shaft balancer as standard equipment, and a single-shaft balancer is standard equipment on the GH340, which substantially reduces the amount of vibration.</p>\r\n<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;"><strong>Lightweight and Compact</strong><br />Kubota technology has made it possible for an engine to weigh approximately 2.8 kg per horsepower--a 14% reduction from previous engines. The size is also approximately 23% less than a comparable side-valve engine.</p>\r\n<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;"><strong>Less Operating Noise</strong><br />The OHV system produces 2 to 3 dB (A) less operating noise than a side valve engine.</p>\r\n<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;"><strong>A Decompression Mechanism that Provides Easier Starting</strong><br /><img src="http://www.frontierpower.com/images/kubotaimage/av03.gif" alt="Decompression mechanism" width="200" height="125" align="RIGHT" border="0" />The Kubota Super OHV Series Engines are equipped with a mechanical decompression mechanism. During starting, a spring in the decompression pin raises the exhaust tappet and opens the exhaust valve slightly, lowering the pressure inside the combustion chamber. While running the engine uses centrifugal force to lower the decompresion pin. This unique decompression mechanism reduces the amount for force required to pull the starter rope by 40%. An electric starter is also available on the GH400.</p>\r\n<p style="color: #000033; font-family: Verdana, Geneva, ''DejaVu Sans'', Arial, sans-serif, Helvetica; font-size: 13px;">Reduction system: direct coupling, or 1/2 camshaft reduction.</p>', 25, 'Available', '2014-05-25'),
(77, 19, 'diaphragm Honda GX160', '<p>diaphragm Honda GX160</p>\r\n<ul class="disc" style="margin: 0px 0px 10px 1.5em; padding: 0px 0px 1em; list-style-position: initial; list-style-image: initial; color: #393939; font-family: ''Droid Sans'', Arial, Helvetica, sans-serif; font-size: 12px; background-color: #ffffff;">\r\n<li style="margin: 0px; padding: 0px;">Pressure washers</li>\r\n<li style="margin: 0px; padding: 0px;">Commercial lawn and garden equipment</li>\r\n<li style="margin: 0px; padding: 0px;">Tillers / cultivators</li>\r\n<li style="margin: 0px; padding: 0px;">Generators</li>\r\n<li style="margin: 0px; padding: 0px;">Construction / industrial equipment</li>\r\n<li style="margin: 0px; padding: 0px;">Agricultural equipment</li>\r\n<li style="margin: 0px; padding: 0px;">Water pumps</li>\r\n</ul>', 30, 'Available', '2014-05-25'),
(79, 15, 'lathe MyFord', '<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">lathe&nbsp;myford</span></span></p>\r\n<p><span style="font-family: Verdana, sans-serif;"><span style="font-size: 12px;">lathe&nbsp;myford 380 volt&nbsp;also&nbsp;220 volt</span></span></p>', NULL, 'Available', '2014-05-25'),
(80, 14, 'bracket Saw', '<p>bracket Saw 380 volt</p>', 2.5, 'Available', '2014-05-25'),
(82, 19, 'JCB tlt 25 D', '<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">EX-works JCB TLT35D</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Good condition</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Comes fully serviced and with Thorough examination</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Viewing available</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Transport can be arranged for cost</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Model: JCB TLT35d</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Year: 2006</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Lift Height: 4 Meters</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Lift Weight: 3500kg</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Power: Diesel</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center"><span style="font-family: Calibri; font-size: medium;">Closed Height (top of cab): 230cm</span></p>\r\n<p class="MsoNormal" style="font-family: Calibri; font-size: large; margin: 0cm 0cm 10pt; text-align: left;" align="center">&nbsp;</p>', NULL, 'Available', '2014-05-25'),
(83, 16, 'JCB TM 200', '<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">VSB: 846</span></span></p>\r\n<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">Joystick Control</span></span></p>\r\n<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">Piped for Sheargrab</span></span></p>\r\n<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">Cone &amp; Pin Heastock</span></span></p>\r\n<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">Pallet forks</span></span></p>\r\n<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">Fully Serviced</span></span></p>\r\n<p><span style="font-family: Arial, Helvetica, sans-serif;"><span style="font-size: 12px; line-height: 14px;">3 Months Warranty</span></span></p>', 350, 'Available', '2014-05-25'),
(84, 19, 'Ridgid 918-i Roll Grooving Machine', '<p>The RIDGID Model 918-I Roll Grooving Machine is capable of grooving 1" to 8" schedule 40 and 1" to 12" schedule 10 pipe faster than any roll grooving machine in its class. Powered by a 1.2 HP universal motor and heavy duty transmission, the 918-I produces high quality grooves consistently and effortlessly. The 918-I comes standard with a rugged wheel stand built for mobility in the shop without compromising stability. The stand&rsquo;s tray is tough enough to support 200 lbs. The 918-I uses a custom-designed, two-stage hydraulic pump that minimizes pipe flare, operator fatigue and hydraulic fluid leaks.</p>', 17, 'Available', '2014-05-25'),
(85, 18, 'ESAB Buddy Arc 180 Amp Inverter Stick / TIG Welder', '<p>ESAB Buddy 0700300885 Arc 180 Amp Inverter Stick / TIG Welder</p>\r\n<p>&nbsp;</p>\r\n<p>The Buddy Arc 180 is a robust and durable 180 Amp arc welding power source for the professional welder. The machine provides state of the art welding performance and reliability through use of the latest high grade IGBT technology. &nbsp;This is a user friendly and lightweight stick (MMA) welding machine that also features a Live TIG arc mode.</p>\r\n<p>&nbsp;</p>\r\n<p>The Buddy Arc 180 is designed for durability with internal electronics that are cooled by a highly efficient fan for added reliability and monitored by a thermal protection system. The machine has been equipped with three heat sinks, which further extend the lifetime of the product. The casing has been designed to withstand harsh environments and is rated to IP23S standard. The machine incorporates both a shoulder strap and a robust carry handle.</p>\r\n<p>&nbsp;</p>\r\n<p>Features:</p>\r\n<p>Superior arc characteristics &ndash; Smooth welding conditions</p>\r\n<p>Generator compliant &ndash; Suitable for use with generators</p>\r\n<p>Easy to use &ndash; Set the welding current and weld with excellent result</p>\r\n<p>Practical design &ndash; Equipped with both a shoulder strap and a robust handle making it easy to carry.</p>\r\n<p>Robust design &ndash; withstands harsh environments</p>\r\n<p>Can be used with extended mains cable &ndash; extended working range</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>MMA Welding:</p>\r\n<p>For MMA welding the Buddy Arc 180 provides a very smooth DC welding output which allows you to weld most metals such as alloyed and non alloyed steel, stainless steel and cast iron. The arc force and hot start settings are adjusted automatically according to the set welding current giving excellent arc starts and welding performance across the amperage range of the machine. The Buddy Arc 180 can weld most electrodes from 1.6-4.0mm.</p>\r\n<p>&nbsp;</p>\r\n<p>Features of the Buddy Arc 180:</p>\r\n<p>Fan Cooled</p>\r\n<p>Thermal Protection&nbsp;</p>\r\n<p>Smooth welding conditions&nbsp;</p>\r\n<p>Hot Start &amp; Arc Force&nbsp;</p>\r\n<p>Suitable for use with Generators&nbsp;</p>\r\n<p>Robust Design&nbsp;</p>\r\n<p>Single Knob Control&nbsp;</p>\r\n<p>MMA/TIG Selection Switch&nbsp;</p>\r\n<p>Integrated High Grade IGBT&nbsp;</p>\r\n<p>IP 23S Enclosure Class</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>TIG Welding:</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>The Buddy&trade; Arc 180 can easily perform TIG-welding using a &ldquo;Live TIG&rdquo; start. Equip the power source with the optional TIG-Torch which has an in-built gas valve, a gas regulator and a bottle of gas and you are ready to weld mild steel or stainless steel with or without filler material.</p>\r\n<p>&nbsp;</p>\r\n<p>Specifications:&nbsp;</p>\r\n<p>Mains Voltage: 230V/1ph&nbsp;</p>\r\n<p>Permitted Load at 40C, MMA&nbsp;</p>\r\n<p>30% Duty Cycle, A/V &nbsp; &nbsp; &nbsp; &nbsp;180/27.2&nbsp;</p>\r\n<p>60% Duty Cycle, A/V &nbsp; &nbsp; &nbsp; &nbsp;130/25.2&nbsp;</p>\r\n<p>100% Duty Cycle, A/V &nbsp; &nbsp; &nbsp; &nbsp;100/24.0&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Permitted Load at 40C, TIG&nbsp;</p>\r\n<p>35% Duty Cycle, A/V &nbsp; &nbsp; &nbsp; &nbsp;180/17.2&nbsp;</p>\r\n<p>60% Duty Cycle, A/V &nbsp; &nbsp; &nbsp; &nbsp;130/15.2&nbsp;</p>\r\n<p>100% Duty Cycle, A/V &nbsp; &nbsp; &nbsp; &nbsp;100/14.0&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Setting Range, DC &nbsp; &nbsp; &nbsp; &nbsp;5 - 180&nbsp;</p>\r\n<p>Open Circuit Voltage &nbsp; &nbsp; &nbsp; &nbsp;59.8&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Dimensions: 310x140x230&nbsp;</p>\r\n<p>Weight: 6.0kg&nbsp;</p>\r\n<p>Application: 1.6mm - 3.25mm electrodes</p>\r\n<p>&nbsp;</p>\r\n<p>Package comprises:</p>\r\n<p>Buddy Arc 180 Inverter Power Source 240v</p>\r\n<p>3m Primary Cable</p>\r\n<p>3m Welding Lead c/w Electrode Holder</p>\r\n<p>3m Earth Lead c/w Clamp</p>', NULL, 'Available', '2014-05-25'),
(86, 18, 'Trans Pallet / Pallet', '<p>Trans Pallet / Pallet in good condition nearly used</p>', NULL, 'Available', '2014-05-25'),
(88, 14, 'Professional saw', '<p>Can be set on diffrent angles</p>', NULL, 'Available', '2014-05-25'),
(89, 17, 'Vacuum 400W submersible', '<p>&nbsp;very useful item to have in case of that unexpected flooding or pumping from pools, water tanks etc.,</p>\r\n<p>With 10m of cable make this a very versatile pump.</p>\r\n<p>This pump weights nearly 5 Kgs to help substantiate its quality and durability</p>\r\n<p>Specification</p>\r\n<p>Powerful 400watt motor&nbsp;</p>\r\n<p>Delivery capacity 7500 litres / hour,&nbsp;</p>\r\n<p>Max particle size of 5 mm&nbsp;</p>\r\n<p>Max liquid temperature of 35 degrees centigrade,&nbsp;</p>\r\n<p>Thermal overload protection,&nbsp;</p>\r\n<p>Robust plastic housing,&nbsp;</p>\r\n<p>Submergence 5 meters (max pump head),&nbsp;</p>\r\n<p>Able to pump 5m off the floor&nbsp;</p>\r\n<p>Supplied cable length 10 meters,&nbsp;</p>\r\n<p>Requires outlet hose diameter of 1" - 5/4"or 3/2" hose Adapter&nbsp;</p>\r\n<p>&nbsp;</p>', NULL, 'Available', '2014-05-25'),
(90, 16, 'Black&Decker  Jig Saw 400W', '<p><span style="font-size: large; font-family: Verdana;">Black &amp; Decker&nbsp;Jig Saw model&nbsp;KS638SE 400W</span></p>\r\n<ul>\r\n<li style="font-family: ''Times New Roman''; font-size: medium;">Variable speed for better control</li>\r\n<li style="font-family: ''Times New Roman''; font-size: medium;">Dedicated curve cutting facility makes intricate curve cutting easy</li>\r\n<li style="font-family: ''Times New Roman''; font-size: medium;">Scrolling facility for following tight curves</li>\r\n<li style="font-family: ''Times New Roman''; font-size: medium;">SuperLok tool-free system for easy and quick blade change</li>\r\n<li style="font-family: ''Times New Roman''; font-size: medium;">Bevel cut to 45 degrees for versatility</li>\r\n<li style="font-family: ''Times New Roman''; font-size: medium;">Lock on switch for continuous use</li>\r\n</ul>', NULL, 'Available', '2014-05-25'),
(91, 14, 'Chainsaw Powerplus 2000W 350mm POW64250', '<table class="varoprodpageparametertable" style="width: 710px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #999999; padding: 5px; border-spacing: 0px; border-collapse: collapse; color: #4e4a4b; font-family: Arial, Tahoma, Verdana; font-size: 12px; line-height: 20px; background-color: #f9f9f9;">\r\n<tbody>\r\n<tr><th style="height: 25px; background-color: #ebebeb;" colspan="2">\r\n<h2 style="font-size: 16px; text-align: left; padding-left: 5px;">Technical info</h2>\r\n</th></tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Oil tank capacity</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">0.28 L</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell21" style="width: 494px; padding: 5px; background-color: #fbfbfb;">Sound Power Level (LwA)</td>\r\n<td class="varoprodpageparametercell22" style="width: 206px; font-weight: bold; padding: 5px; background-color: #fbfbfb;">112 dB(A)</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Rated Power (Pn)</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">2000 W</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell21" style="width: 494px; padding: 5px; background-color: #fbfbfb;">Rated Voltage AC (Un)</td>\r\n<td class="varoprodpageparametercell22" style="width: 206px; font-weight: bold; padding: 5px; background-color: #fbfbfb;">230 V~</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Blade Size</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">355 mm</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell21" style="width: 494px; padding: 5px; background-color: #fbfbfb;">Rated Frequency (fn)</td>\r\n<td class="varoprodpageparametercell22" style="width: 206px; font-weight: bold; padding: 5px; background-color: #fbfbfb;">50 Hz</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Protection Class</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">CLASS II</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell21" style="width: 494px; padding: 5px; background-color: #fbfbfb;">Quick stop</td>\r\n<td class="varoprodpageparametercell22" style="width: 206px; font-weight: bold; padding: 5px; background-color: #fbfbfb;"><img style="border: none; vertical-align: middle;" src="http://www.varo.com/Upload/Design/GUI/siteimages/true.png" alt="True" /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table class="varoprodpageparametertable" style="width: 710px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #999999; padding: 5px; border-spacing: 0px; border-collapse: collapse; color: #4e4a4b; font-family: Arial, Tahoma, Verdana; font-size: 12px; line-height: 20px; background-color: #f9f9f9;">\r\n<tbody>\r\n<tr><th style="height: 25px; background-color: #ebebeb;" colspan="2">\r\n<h2 style="font-size: 16px; text-align: left; padding-left: 5px;">Commercial info</h2>\r\n</th></tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Additional commercial info 3</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">Auto-brake hand guard, Side chain tension adjustment</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell21" style="width: 494px; padding: 5px; background-color: #fbfbfb;">Additional commercial info 2</td>\r\n<td class="varoprodpageparametercell22" style="width: 206px; font-weight: bold; padding: 5px; background-color: #fbfbfb;">Automatic lubrication, Safety trigger</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Additional commercial info</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">chain stop &lt; 0,12 sec.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table class="varoprodpageparametertable" style="width: 710px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #999999; padding: 5px; border-spacing: 0px; border-collapse: collapse; color: #4e4a4b; font-family: Arial, Tahoma, Verdana; font-size: 12px; line-height: 20px; background-color: #f9f9f9;">\r\n<tbody>\r\n<tr><th style="height: 25px; background-color: #ebebeb;" colspan="2">\r\n<h2 style="font-size: 16px; text-align: left; padding-left: 5px;">Accessoires</h2>\r\n</th></tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Accessoires</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;">POWACG422</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell21" style="width: 494px; padding: 5px; background-color: #fbfbfb;">extra tool (key)</td>\r\n<td class="varoprodpageparametercell22" style="width: 206px; font-weight: bold; padding: 5px; background-color: #fbfbfb;">spanner</td>\r\n</tr>\r\n<tr>\r\n<td class="varoprodpageparametercell1" style="width: 494px; padding: 5px; background-color: #f7f7f7;">Set of carbon brushes</td>\r\n<td class="varoprodpageparametercell2" style="width: 206px; font-weight: bold; padding: 5px; background-color: #f7f7f7;"><img style="border: none; vertical-align: middle;" src="http://www.varo.com/Upload/Design/GUI/siteimages/true.png" alt="True" /></td>\r\n</tr>\r\n</tbody>\r\n</table>', 25, 'Available', '2014-05-25');

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
(19, 'BizareAbout', '$1$9t1.c./.$SW6gtqJVrnRlmefcviglb1', 'Bizare About', '', NULL, 'BizareAbout@mail.com', 'Gebroeders de Smetstraat 1', NULL, 'Gent', 'Oost-vlaanderen', 9000, 'BE');

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
