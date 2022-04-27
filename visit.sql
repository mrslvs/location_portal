-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2022 at 09:58 AM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `z7`
--

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `id` int NOT NULL,
  `user_input` varchar(256) NOT NULL,
  `latitude` float(8,6) NOT NULL,
  `longitude` float(8,6) NOT NULL,
  `country_name` varchar(128) NOT NULL,
  `county` varchar(128),
  `alpha_3` varchar(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`id`, `user_input`, `latitude`, `longitude`, `country_name`, `alpha_3`, `created`) VALUES
(4, 'Košariská, Myjava', 48.751724, 17.566727, 'Slovakia', 'SVK', '2022-04-26 17:17:01'),
(5, 'Sobotište', 48.731155, 17.404650, 'Slovakia', 'SVK', '2022-04-26 17:19:22'),
(6, 'Sobotište', 48.731155, 17.404650, 'Slovakia', 'SVK', '2022-04-26 17:42:34'),
(9, 'Bratislava', 48.165066, 17.145674, 'Slovakia', 'SVK', '2022-04-26 17:50:03'),
(10, 'Bratislava', 48.165066, 17.145674, 'Slovakia', 'SVK', '2022-04-26 17:50:34'),
(11, 'Bratislava', 48.165066, 17.145674, 'Slovakia', 'SVK', '2022-04-26 17:50:40'),
(12, 'Praha', 50.066940, 14.460249, 'Czechia', 'CZE', '2022-04-26 17:51:53'),
(13, 'Brno', 49.198532, 16.610411, 'Czechia', 'CZE', '2022-04-26 19:29:50'),
(14, 'Jolly Harbour', 17.065395, -61.883400, 'Antigua and Barbuda', 'ATG', '2022-04-26 19:54:10'),
(15, 'Rača', 58.707779, 25.630280, 'Estonia', 'EST', '2022-04-26 20:22:56'),
(16, 'Olomouc', 49.593719, 17.260292, 'Czechia', 'CZE', '2022-04-26 20:49:31'),
(17, 'Solivar', 48.977741, 21.276340, 'Slovakia', 'SVK', '2022-04-26 21:45:48'),
(18, 'Solivar', 48.977741, 21.276340, 'Slovakia', 'SVK', '2022-04-26 21:49:34'),
(19, 'Solivar', 48.977741, 21.276340, 'Slovakia', 'SVK', '2022-04-26 21:57:31'),
(20, 'Solivar', 48.977741, 21.276340, 'Slovakia', 'SVK', '2022-04-26 22:17:45'),
(21, 'Solivar', 48.977741, 21.276340, 'Slovakia', 'SVK', '2022-04-26 22:18:39'),
(22, 'bratislava', 48.165066, 17.145674, 'Slovakia', 'SVK', '2022-04-26 22:31:35'),
(23, 'račianska 143', 48.188416, 17.133305, 'Slovakia', 'SVK', '2022-04-27 09:57:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
