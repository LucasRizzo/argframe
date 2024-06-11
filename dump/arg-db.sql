-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 19, 2021 at 02:59 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arg-db`
--

-- --------------------------------------------------------

USE `arg-db`;

--
-- Table structure for table `arguments`
--
DROP TABLE IF EXISTS `arguments`;
CREATE TABLE `arguments` (
  `id` int(11) NOT NULL,
  `argument` varchar(10000) NOT NULL,
  `conclusion` varchar(100) DEFAULT NULL,
  `x` decimal(10,0) NOT NULL,
  `y` decimal(10,0) NOT NULL,
  `label` varchar(40) NOT NULL,
  `graph` varchar(40) NOT NULL,
  `featureset` varchar(40) NOT NULL,
  `weight` decimal(15,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `arguments`
--

INSERT INTO `arguments` (`id`, `argument`, `conclusion`, `x`, `y`, `label`, `graph`, `featureset`) VALUES
(232479, '\"mediumHigh bytes\"', 'mediumHigh [0.501, 0.75]', '516', '532', 'B1', 'example1', 'trust_features'),
(232480, '\"high bytes\"', 'high [0.751, 1]', '509', '690', 'B2', 'example1', 'trust_features'),
(232481, '\"mediumHigh activityFactor\"', 'mediumHigh [0.501, 0.75]', '518', '354', 'AF2', 'example1', 'trust_features'),
(232482, '\"high activityFactor\"', 'high [0.751, 1]', '515', '220', 'AF3', 'example1', 'trust_features'),
(232483, '\"no anonymous\"', 'high [0.751, 1]', '1995', '1016', 'AN1', 'example1', 'trust_features'),
(232484, '\"yes anonymous\"', 'low [0, 0.25]', '955', '303', 'AN2', 'example1', 'trust_features'),
(232485, '\"low uniquePages\"', 'low [0, 0.25]', '1225', '1200', 'U1', 'example1', 'trust_features'),
(232486, '\"mediumHigh uniquePages\"', 'mediumHigh [0.501, 0.75]', '799', '611', 'U2', 'example1', 'trust_features'),
(232487, '\"high uniquePages\"', 'high [0.751, 1]', '934', '714', 'U3', 'example1', 'trust_features'),
(232488, '\"low comments\"', 'low [0, 0.25]', '978', '1175', 'C1', 'example1', 'trust_features'),
(232489, '\"mediumLow comments\"', 'mediumLow [0.251, 0.5]', '985', '58', 'C2', 'example1', 'trust_features'),
(232490, '\"mediumHigh comments\"', 'mediumHigh [0.501, 0.75]', '1136', '84', 'C3', 'example1', 'trust_features'),
(232491, '\"high comments\"', 'high [0.751, 1]', '1222', '174', 'C4', 'example1', 'trust_features'),
(232492, '\"low presenceFactor\"', 'low [0, 0.25]', '1266', '915', 'P1', 'example1', 'trust_features'),
(232493, '\"mediumLow presenceFactor\"', 'mediumLow [0.251, 0.5]', '1707', '1105', 'P2', 'example1', 'trust_features'),
(232494, '\"mediumHigh presenceFactor\"', 'mediumHigh [0.501, 0.75]', '1574', '776', 'P3', 'example1', 'trust_features'),
(232495, '\"high presenceFactor\"', 'high [0.751, 1]', '1780', '714', 'P4', 'example1', 'trust_features'),
(232496, '\"low frequencyFactor\"', 'low [0, 0.25]', '1945', '651', 'F1', 'example1', 'trust_features'),
(232497, '\"mediumLow frequencyFactor\"', 'mediumLow [0.251, 0.5]', '1666', '499', 'F2', 'example1', 'trust_features'),
(232498, '\"mediumHigh frequencyFactor\"', 'mediumHigh [0.501, 0.75]', '1537', '425', 'F3', 'example1', 'trust_features'),
(232499, '\"high frequencyFactor\"', 'high [0.751, 1]', '1263', '646', 'F4', 'example1', 'trust_features'),
(232500, '\"low regularityFactor\"', 'low [0, 0.25]', '2060', '831', 'R1', 'example1', 'trust_features'),
(232501, '\"mediumLow regularityFactor\"', 'mediumLow [0.251, 0.5]', '1785', '430', 'R2', 'example1', 'trust_features'),
(232502, '\"mediumHigh regularityFactor\"', 'mediumHigh [0.501, 0.75]', '1658', '342', 'R3', 'example1', 'trust_features'),
(232503, '\"high regularityFactor\"', 'high [0.751, 1]', '1169', '727', 'R4', 'example1', 'trust_features'),
(232504, '\"veryLow notMinor\"', 'low [0, 0.25]', '271', '459', 'NM1', 'example1', 'trust_features'),
(232505, '\"mediumLow notMinor\" OR \"mediumHigh notMinor\" OR \"high notMinor\"', 'high [0.751, 1]', '1126', '1024', 'NM2', 'example1', 'trust_features'),
(232506, '\"low frequencyFactor\" AND \"low regularityFactor\" AND \"low activityFactor\"', 'NULL', '1547', '1027', 'OnlyAge', 'example1', 'trust_features'),
(232507, '\"low activityFactor\"', 'low [0, 0.25]', '1411', '809', 'AF1', 'example1', 'trust_features'),
(232508, '(\"low presenceFactor\" OR \"mediumLow presenceFactor\") AND \"low regularityFactor\" AND \"low comments\" AND \"low uniquePages\"', 'NULL', '275', '676', 'Vandal', 'example1', 'trust_features'),
(232509, '\"yes anonymous\" AND \"low comments\" AND (\"mediumHigh bytes\" OR \"high bytes\") AND \"veryLow notMinor\" AND (\"high uniquePages\" OR \"mediumHigh uniquePages\")', 'NULL', '781', '865', 'Bot', 'example1', 'trust_features'),
(232510, '\"low activityFactor\"', 'low [0, 0.25]', '505', '-431', 'AF1', 'example2', 'trust_features'),
(232511, '\"mediumHigh activityFactor\"', 'mediumHigh [0.501, 0.75]', '2039', '557', 'AF2', 'example2', 'trust_features'),
(232512, '\"high activityFactor\"', 'high [0.751, 1]', '1959', '-444', 'AF3', 'example2', 'trust_features'),
(232513, '\"no anonymous\"', 'high [0.751, 1]', '2121', '-208', 'AN1', 'example2', 'trust_features'),
(232514, '\"yes anonymous\"', 'low [0, 0.25]', '713', '-427', 'AN2', 'example2', 'trust_features'),
(232515, '\"mediumHigh bytes\"', 'mediumHigh [0.501, 0.75]', '2123', '501', 'B1', 'example2', 'trust_features'),
(232516, '\"high bytes\"', 'high [0.751, 1]', '2084', '-506', 'B2', 'example2', 'trust_features'),
(232517, '\"low comments\"', 'low [0, 0.25]', '1103', '-666', 'C1', 'example2', 'trust_features'),
(232518, '\"mediumLow comments\"', 'mediumLow [0.251, 0.5]', '936', '557', 'C2', 'example2', 'trust_features'),
(232519, '\"mediumHigh comments\"', 'mediumHigh [0.501, 0.75]', '2279', '369', 'C3', 'example2', 'trust_features'),
(232520, '\"high comments\"', 'high [0.751, 1]', '2300', '-193', 'C4', 'example2', 'trust_features'),
(232521, '\"low frequencyFactor\"', 'low [0, 0.25]', '879', '-612', 'F1', 'example2', 'trust_features'),
(232522, '\"mediumLow frequencyFactor\"', 'mediumLow [0.251, 0.5]', '794', '440', 'F2', 'example2', 'trust_features'),
(232523, '\"mediumHigh frequencyFactor\"', 'mediumHigh [0.501, 0.75]', '1888', '582', 'F3', 'example2', 'trust_features'),
(232524, '\"high frequencyFactor\"', 'high [0.751, 1]', '2227', '-85', 'F4', 'example2', 'trust_features'),
(232525, '\"veryLow notMinor\"', 'low [0, 0.25]', '665', '-248', 'NM1', 'example2', 'trust_features'),
(232526, '\"mediumLow notMinor\" OR \"mediumHigh notMinor\" OR \"high notMinor\"', 'high [0.751, 1]', '2443', '-79', 'NM2', 'example2', 'trust_features'),
(232527, '\"low regularityFactor\"', 'low [0, 0.25]', '596', '-575', 'R1', 'example2', 'trust_features'),
(232528, '\"mediumLow regularityFactor\"', 'mediumLow [0.251, 0.5]', '1072', '672', 'R2', 'example2', 'trust_features'),
(232529, '\"mediumHigh regularityFactor\"', 'mediumHigh [0.501, 0.75]', '1745', '663', 'R3', 'example2', 'trust_features'),
(232530, '\"high regularityFactor\"', 'high [0.751, 1]', '2044', '-316', 'R4', 'example2', 'trust_features'),
(232531, '\"low presenceFactor\"', 'low [0, 0.25]', '679', '-42', 'P1', 'example2', 'trust_features'),
(232532, '\"mediumLow presenceFactor\"', 'mediumLow [0.251, 0.5]', '675', '327', 'P2', 'example2', 'trust_features'),
(232533, '\"mediumHigh presenceFactor\"', 'mediumHigh [0.501, 0.75]', '2089', '375', 'P3', 'example2', 'trust_features'),
(232534, '\"high presenceFactor\"', 'high [0.751, 1]', '2233', '-364', 'P4', 'example2', 'trust_features'),
(232535, '\"low absUnique\"', 'low [0, 0.25]', '456', '-64', 'U1', 'example2', 'trust_features'),
(232536, '\"mediumHigh absUnique\"', 'mediumHigh [0.501, 0.75]', '1923', '734', 'U2', 'example2', 'trust_features'),
(232537, '\"high absUnique\"', 'high [0.751, 1]', '2387', '-328', 'U3', 'example2', 'trust_features'),
(232538, '\"low bytes\"', 'low [0, 0.25]', '530', '-245', 'B3', 'example2', 'trust_features');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes` (
  `attribute` varchar(30) NOT NULL,
  `featureset` varchar(40) NOT NULL,
  `a_level` varchar(30) NOT NULL,
  `a_from` decimal(10,3) NOT NULL,
  `a_to` decimal(15,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`attribute`, `featureset`, `a_level`, `a_from`, `a_to`) VALUES
('absUnique', 'trust_features', 'high', '20.000', '10000000.000'),
('absUnique', 'trust_features', 'low', '0.000', '5.000'),
('absUnique', 'trust_features', 'mediumHigh', '10.000', '19.000'),
('activityFactor', 'trust_features', 'high', '20.000', '100000.000'),
('activityFactor', 'trust_features', 'low', '0.000', '5.000'),
('activityFactor', 'trust_features', 'mediumHigh', '10.000', '19.000'),
('activityFactor', 'trust_features', 'mediumLow', '3.081', '6.170'),
('anonymous', 'trust_features', 'no', '0.000', '0.000'),
('anonymous', 'trust_features', 'yes', '1.000', '1.000'),
('bytes', 'trust_features', 'high', '2388.000', '9999999999.999'),
('bytes', 'trust_features', 'low', '0.000', '110.000'),
('bytes', 'trust_features', 'mediumHigh', '512.000', '2387.000'),
('bytes', 'trust_features', 'mediumLow', '110.001', '511.999'),
('comments', 'trust_features', 'high', '0.751', '1.000'),
('comments', 'trust_features', 'low', '0.000', '0.250'),
('comments', 'trust_features', 'mediumHigh', '0.501', '0.750'),
('comments', 'trust_features', 'mediumLow', '0.251', '0.500'),
('frequencyFactor', 'trust_features', 'high', '0.751', '1.000'),
('frequencyFactor', 'trust_features', 'low', '0.000', '0.250'),
('frequencyFactor', 'trust_features', 'mediumHigh', '0.501', '0.750'),
('frequencyFactor', 'trust_features', 'mediumLow', '0.251', '0.500'),
('notMinor', 'trust_features', 'high', '0.751', '1.000'),
('notMinor', 'trust_features', 'low', '0.000', '0.250'),
('notMinor', 'trust_features', 'mediumHigh', '0.501', '0.750'),
('notMinor', 'trust_features', 'mediumLow', '0.251', '0.500'),
('notMinor', 'trust_features', 'veryLow', '0.000', '0.050'),
('presenceFactor', 'trust_features', 'high', '0.751', '1.000'),
('presenceFactor', 'trust_features', 'low', '0.000', '0.250'),
('presenceFactor', 'trust_features', 'mediumHigh', '0.501', '0.750'),
('presenceFactor', 'trust_features', 'mediumLow', '0.251', '0.500'),
('regularityFactor', 'trust_features', 'high', '0.751', '1.000'),
('regularityFactor', 'trust_features', 'low', '0.000', '0.250'),
('regularityFactor', 'trust_features', 'mediumHigh', '0.501', '0.750'),
('regularityFactor', 'trust_features', 'mediumLow', '0.251', '0.500'),
('uniquePages', 'trust_features', 'absHigh', '20.000', '100000.000'),
('uniquePages', 'trust_features', 'absLow', '0.000', '5.000'),
('uniquePages', 'trust_features', 'absMedHigh', '10.000', '19.000'),
('uniquePages', 'trust_features', 'high', '20.000', '10000000.000'),
('uniquePages', 'trust_features', 'low', '0.000', '5.000'),
('uniquePages', 'trust_features', 'mediumHigh', '10.000', '19.000');

-- --------------------------------------------------------

--
-- Table structure for table `computations`
--

DROP TABLE IF EXISTS `computations`;
CREATE TABLE `computations` (
  `id` bigint(11) NOT NULL,
  `extensions` longtext NOT NULL,
  `user` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conclusions`
--

DROP TABLE IF EXISTS `conclusions`;
CREATE TABLE `conclusions` (
  `featureset` varchar(40) NOT NULL,
  `conclusion` varchar(40) NOT NULL,
  `c_from` double NOT NULL,
  `c_to` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conclusions`
--

INSERT INTO `conclusions` (`featureset`, `conclusion`, `c_from`, `c_to`) VALUES
('trust_features', 'high', 0.751, 1),
('trust_features', 'low', 0, 0.25),
('trust_features', 'mediumHigh', 0.501, 0.75),
('trust_features', 'mediumLow', 0.251, 0.5);

-- --------------------------------------------------------

--
-- Table structure for table `graphs`
--

DROP TABLE IF EXISTS `graphs`;
CREATE TABLE `graphs` (
  `featureset` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `edges` longtext NOT NULL,
  `font_size` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `graphs`
--

INSERT INTO `graphs` (`featureset`, `name`, `edges`, `font_size`) VALUES
('trust_features', 'example1', '[{\"source\":\"NM1\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"OnlyAge\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"OnlyAge\",\"target\":\"P3\",\"type\":\"none\"},{\"source\":\"OnlyAge\",\"target\":\"P2\",\"type\":\"none\"},{\"source\":\"NM2\",\"target\":\"OnlyAge\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"F4\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"Vandal\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"Vandal\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"Vandal\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"Vandal\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"NM2\",\"target\":\"U1\",\"type\":\"none\"},{\"source\":\"NM2\",\"target\":\"C1\",\"type\":\"none\"},{\"source\":\"NM2\",\"target\":\"P1\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"U3\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"U2\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"C1\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"Bot\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"U3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"U2\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"C3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"C4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"F4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"F3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"R3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"P3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"NM2\",\"type\":\"none\"}]', 22),
('trust_features', 'example2', '[{\"source\":\"AN2\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"C1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"C1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"U1\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"U1\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"C3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"C4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"F4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"F3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"NM2\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"R3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"P3\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"U2\",\"type\":\"none\"},{\"source\":\"AN2\",\"target\":\"U3\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"B3\",\"target\":\"AF2\",\"type\":\"none\"},{\"source\":\"B3\",\"target\":\"AF3\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"B1\",\"type\":\"none\"},{\"source\":\"NM1\",\"target\":\"B2\",\"type\":\"none\"},{\"source\":\"R3\",\"target\":\"C1\",\"type\":\"none\"},{\"source\":\"R4\",\"target\":\"C1\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"F4\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"F3\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"R3\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"F4\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"F3\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"F1\",\"target\":\"P3\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"F4\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"F3\",\"type\":\"none\"},{\"source\":\"C4\",\"target\":\"NM1\",\"type\":\"none\"},{\"source\":\"C3\",\"target\":\"NM1\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"R3\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"R1\",\"target\":\"P3\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"R3\",\"type\":\"none\"},{\"source\":\"P1\",\"target\":\"R4\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"P4\",\"type\":\"none\"},{\"source\":\"AF1\",\"target\":\"P3\",\"type\":\"none\"},{\"source\":\"AF2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"AF1\",\"type\":\"rebuttal\"},{\"source\":\"AF1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"AF2\",\"type\":\"rebuttal\"},{\"source\":\"AF2\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"AF3\",\"type\":\"rebuttal\"},{\"source\":\"AF3\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"AN2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"AN2\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"AN1\",\"type\":\"rebuttal\"},{\"source\":\"AN1\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"AN2\",\"type\":\"rebuttal\"},{\"source\":\"AN2\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"AN2\",\"type\":\"rebuttal\"},{\"source\":\"AN2\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"AN2\",\"type\":\"rebuttal\"},{\"source\":\"AN2\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"AN2\",\"type\":\"rebuttal\"},{\"source\":\"AN2\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"B1\",\"type\":\"rebuttal\"},{\"source\":\"B1\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"B2\",\"type\":\"rebuttal\"},{\"source\":\"B2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"C1\",\"type\":\"rebuttal\"},{\"source\":\"C1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"C2\",\"type\":\"rebuttal\"},{\"source\":\"C2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"C3\",\"type\":\"rebuttal\"},{\"source\":\"C3\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"C4\",\"type\":\"rebuttal\"},{\"source\":\"C4\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"F1\",\"type\":\"rebuttal\"},{\"source\":\"F1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"F2\",\"type\":\"rebuttal\"},{\"source\":\"F2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"F3\",\"type\":\"rebuttal\"},{\"source\":\"F3\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"F4\",\"type\":\"rebuttal\"},{\"source\":\"F4\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"NM1\",\"type\":\"rebuttal\"},{\"source\":\"NM1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"NM2\",\"type\":\"rebuttal\"},{\"source\":\"NM2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"R1\",\"type\":\"rebuttal\"},{\"source\":\"R1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"R2\",\"type\":\"rebuttal\"},{\"source\":\"R2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"R3\",\"type\":\"rebuttal\"},{\"source\":\"R3\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"R4\",\"type\":\"rebuttal\"},{\"source\":\"R4\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"P1\",\"type\":\"rebuttal\"},{\"source\":\"P1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"P2\",\"type\":\"rebuttal\"},{\"source\":\"P2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"P3\",\"type\":\"rebuttal\"},{\"source\":\"P3\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"P4\",\"type\":\"rebuttal\"},{\"source\":\"P4\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"U1\",\"type\":\"rebuttal\"},{\"source\":\"U1\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"U2\",\"type\":\"rebuttal\"},{\"source\":\"U2\",\"target\":\"B3\",\"type\":\"rebuttal\"},{\"source\":\"B3\",\"target\":\"U3\",\"type\":\"rebuttal\"},{\"source\":\"U3\",\"target\":\"B3\",\"type\":\"rebuttal\"}]', 30);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `email` varchar(40) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `password`) VALUES
('Guest', 'guest');

-- --------------------------------------------------------

--
-- Table structure for table `user_featureset`
--

DROP TABLE IF EXISTS `user_featureset`;
CREATE TABLE `user_featureset` (
  `email` varchar(40) NOT NULL,
  `featureset` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_featureset`
--

INSERT INTO `user_featureset` (`email`, `featureset`) VALUES
('Guest', 'trust_features');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arguments`
--
ALTER TABLE `arguments`
  ADD PRIMARY KEY (`id`,`graph`,`featureset`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`attribute`,`featureset`,`a_level`),
  ADD KEY `dataset` (`featureset`);

--
-- Indexes for table `computations`
--
ALTER TABLE `computations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conclusions`
--
ALTER TABLE `conclusions`
  ADD PRIMARY KEY (`featureset`,`conclusion`);

--
-- Indexes for table `graphs`
--
ALTER TABLE `graphs`
  ADD PRIMARY KEY (`featureset`,`name`),
  ADD KEY `dataset` (`featureset`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `user_featureset`
--
ALTER TABLE `user_featureset`
  ADD PRIMARY KEY (`email`,`featureset`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arguments`
--
ALTER TABLE `arguments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232539;

--
-- AUTO_INCREMENT for table `computations`
--
ALTER TABLE `computations`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31778;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
