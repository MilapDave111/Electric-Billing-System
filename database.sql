-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 08:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastReading` decimal(20,0) NOT NULL,
  `newReading` decimal(20,0) NOT NULL,
  `membersCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `lastReading`, `newReading`, `membersCount`) VALUES
(107, 'Hiteshbhai', 10198, 10297, 4),
(108, 'Davabhai', 5611, 5725, 6),
(109, 'Hareshbhai', 5162, 5260, 5),
(110, 'Angadiya', 3862, 3948, 5),
(111, 'Krishna', 426, 442, 2),
(112, 'Giriraj', 798, 823, 3),
(113, 'Jeel', 1224, 1306, 4),
(114, 'Gopal', 38, 45, 2),
(115, 'Nandish', 8469, 8576, 5);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` int(11) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `unit` decimal(10,2) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `total_without_water` decimal(10,2) NOT NULL,
  `cycle` bigint(20) NOT NULL,
  `amount_water` decimal(10,2) DEFAULT 0.00,
  `water_per_person` decimal(10,2) DEFAULT 0.00,
  `water_per_family` decimal(10,2) DEFAULT 0.00,
  `total_bill_for_each_family` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `acc_id`, `unit`, `price_per_unit`, `total_without_water`, `cycle`, `amount_water`, `water_per_person`, `water_per_family`, `total_bill_for_each_family`) VALUES
(107, 107, 99.00, 8.07, 798.60, 1747654516, 1573.00, 43.69, 174.78, 973.38),
(108, 108, 114.00, 8.07, 919.60, 1747654516, 1573.00, 43.69, 262.17, 1181.77),
(109, 109, 98.00, 8.07, 790.53, 1747654516, 1573.00, 43.69, 218.47, 1009.00),
(110, 110, 86.00, 8.07, 693.73, 1747654516, 1573.00, 43.69, 218.47, 912.20),
(111, 111, 16.00, 8.07, 129.07, 1747654516, 1573.00, 43.69, 87.39, 216.46),
(112, 112, 25.00, 8.07, 201.67, 1747654516, 1573.00, 43.69, 131.08, 332.75),
(113, 113, 82.00, 8.07, 661.47, 1747654516, 1573.00, 43.69, 174.78, 836.25),
(114, 114, 7.00, 8.07, 56.47, 1747654516, 1573.00, 43.69, 87.39, 143.86),
(115, 115, 107.00, 8.07, 863.13, 1747654516, 1573.00, 43.69, 218.47, 1081.60);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_id` (`acc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`acc_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
