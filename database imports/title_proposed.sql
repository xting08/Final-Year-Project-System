-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 04:23 PM
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
-- Table structure for table `title_proposed`
--

CREATE TABLE `title_proposed` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `project_type` enum('Application','Research','Mixed') NOT NULL,
  `is_taken` tinyint(1) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `supervisor_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `title_proposed`
--

INSERT INTO `title_proposed` (`id`, `title`, `project_type`, `is_taken`, `student_id`, `supervisor_id`) VALUES
(1, 'testing proposed topic', 'Mixed', 0, NULL, '1111111111'),
(2, 'application project test', 'Application', 0, NULL, '1111111111'),
(3, 'research project test ', 'Research', 0, NULL, '1111111111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `title_proposed`
--
ALTER TABLE `title_proposed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `title_proposed`
--
ALTER TABLE `title_proposed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `title_proposed`
--
ALTER TABLE `title_proposed`
  ADD CONSTRAINT `title_proposed_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`user_id`),
  ADD CONSTRAINT `title_proposed_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisor` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
