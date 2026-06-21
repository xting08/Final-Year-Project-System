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
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(30) NOT NULL,
  `contact_no` varchar(12) NOT NULL,
  `email` varchar(30) NOT NULL,
  `role` enum('Student','Supervisor','Admin') NOT NULL,
  `is_collab` tinyint(1) NOT NULL,
  `supervisor_id` varchar(10) DEFAULT NULL,
  `specialisation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`, `password`, `full_name`, `contact_no`, `email`, `role`, `is_collab`, `supervisor_id`, `specialisation`) VALUES
(1, '0111111111', 'student123', 'student_test', '0123456789', 'student_sample@gmail.com', 'Student', 0, '1111111111', ''),
(6, '0000000000', 'testing_testing', 'student_test', '0123456789', 'student_test@gmail.com', 'Student', 0, NULL, ''),
(7, '0111111112', 'stud123', 'student_full', '0123456789', '1211103996@student.mmu.edu.my', 'Student', 0, '1111111111', ''),
(8, '1211103996', 'stud123', 'Lau She Zhang', '0123456789', '1211103996@student.mmu.edu.my', 'Student', 0, NULL, '');

--
-- Triggers `student`
--
DELIMITER $$
CREATE TRIGGER `after_student_delete` AFTER DELETE ON `student` FOR EACH ROW BEGIN
    DELETE FROM users WHERE user_id = OLD.user_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_student_insert` AFTER INSERT ON `student` FOR EACH ROW BEGIN
    INSERT INTO users (user_id, full_name, password, role)
    VALUES (NEW.user_id, NEW.full_name, NEW.password, NEW.role);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_student_update` AFTER UPDATE ON `student` FOR EACH ROW BEGIN
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
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisor` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
