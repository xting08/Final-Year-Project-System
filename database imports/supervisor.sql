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
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(30) NOT NULL,
  `contact_no` varchar(12) NOT NULL,
  `email` varchar(30) NOT NULL,
  `role` enum('Student','Supervisor','Admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor`
--

INSERT INTO `supervisor` (`id`, `user_id`, `password`, `full_name`, `contact_no`, `email`, `role`) VALUES
(1, '1111111111', 'supervisor123', 'supervisor_main', '0123456789', 'supervisor_sample@gmail.com', 'Supervisor'),
(2, '1000000000', 'testing_testing', 'supervisor_test', '0123456789', 'supervisor_testing@gmail.com', 'Supervisor');

--
-- Triggers `supervisor`
--
DELIMITER $$
CREATE TRIGGER `after_supervisor_delete` AFTER DELETE ON `supervisor` FOR EACH ROW BEGIN
    DELETE FROM users WHERE user_id = OLD.user_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_supervisor_insert` AFTER INSERT ON `supervisor` FOR EACH ROW BEGIN
    INSERT INTO users (user_id, full_name, password, role)
    VALUES (NEW.user_id, NEW.full_name, NEW.password, NEW.role);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_supervisor_update` AFTER UPDATE ON `supervisor` FOR EACH ROW BEGIN
    UPDATE users
    SET full_name = NEW.full_name,
        password = NEW.password
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
