-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 04:24 PM
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
-- Database: `web_dev_assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `todo_joint`
--

CREATE TABLE `todo_joint` (
  `id` int(11) NOT NULL,
  `todo_id` int(11) DEFAULT NULL,
  `item_todo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todo_joint`
--

INSERT INTO `todo_joint` (`id`, `todo_id`, `item_todo_id`) VALUES
(4, 2, 3),
(16, 18, 20),
(22, 23, 26),
(23, 23, 27),
(24, 23, 28),
(25, 24, 29),
(26, 24, 30),
(27, 25, 31),
(28, 26, 32),
(29, 27, 33);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `todo_joint`
--
ALTER TABLE `todo_joint`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_joint_ibfk_1` (`todo_id`),
  ADD KEY `todo_joint_ibfk_2` (`item_todo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `todo_joint`
--
ALTER TABLE `todo_joint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `todo_joint`
--
ALTER TABLE `todo_joint`
  ADD CONSTRAINT `todo_joint_ibfk_1` FOREIGN KEY (`todo_id`) REFERENCES `todo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todo_joint_ibfk_2` FOREIGN KEY (`item_todo_id`) REFERENCES `item_todo` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
