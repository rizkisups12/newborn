-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 29, 2023 at 04:38 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipdmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `newborn`
--

CREATE TABLE `newborn` (
  `id` int(11) NOT NULL,
  `id_seq` int(11) NOT NULL,
  `mother_name` varchar(50) NOT NULL,
  `mother_age` tinyint(4) NOT NULL,
  `gestational_age` tinyint(4) NOT NULL,
  `infant_gender` char(1) NOT NULL,
  `birth_datetime` varchar(19) DEFAULT NULL,
  `height` int(11) NOT NULL,
  `weight` decimal(4,2) NOT NULL,
  `description` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `newborn`
--

INSERT INTO `newborn` (`id`, `id_seq`, `mother_name`, `mother_age`, `gestational_age`, `infant_gender`, `birth_datetime`, `height`, `weight`, `description`) VALUES
(1, 1, 'Z', 24, 12, 'F', '28-07-2023 16:07', 8, '6.00', '1'),
(2, 1, 'Z', 24, 38, 'F', '27-07-2023 17:13', 45, '3.22', 'Sehat'),
(3, 1, 'Z', 24, 12, 'M', '28-06-2023 16:07', 8, '6.00', '1'),
(4, 1, 'Z', 24, 38, 'F', '27-06-2023 17:13', 45, '3.00', 'Sehat'),
(5, 1, 'Z', 24, 12, 'M', '28-01-2023 16:07', 8, '6.20', '1'),
(6, 1, 'Z', 24, 38, 'M', '27-01-2023 17:13', 45, '3.20', 'Sehat'),
(7, 1, 'Z', 24, 38, 'F', '27-02-2023 17:13', 45, '3.25', 'Sehat'),
(8, 1, 'Z', 24, 38, 'F', '27-09-2023 17:13', 45, '4.10', 'Sehat'),
(10, 1, 'Zee', 27, 38, 'M', '28-07-2023 23:06', 21, '3.40', '1'),
(10, 2, 'Zee', 27, 38, 'F', '28-07-2023 23:07', 28, '3.60', '2'),
(11, 1, 'Zee', 27, 38, 'M', '28-05-2022 16:07', 31, '3.40', 'satu'),
(11, 2, 'Zee', 27, 38, 'M', '28-05-2022 23:38', 42, '7.50', 'dua'),
(12, 1, 'R', 26, 36, 'M', '29-07-2023 00:19', 45, '3.20', '1ODk0NDU1ODUxMzIwMjMtMDctMTEgMTY6MDM6MTJEMS0xMy5qc'),
(12, 2, 'R', 26, 36, 'F', '29-08-2023 11:11', 48, '3.45', '2'),
(13, 1, 'Rachel', 26, 36, 'F', '29-09-2023 11:17', 32, '3.60', 'sehat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `newborn`
--
ALTER TABLE `newborn`
  ADD PRIMARY KEY (`id`,`id_seq`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
