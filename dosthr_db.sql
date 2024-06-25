-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 08:59 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrdostdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `education_id` int(255) NOT NULL,
  `graduate` tinyint(1) NOT NULL,
  `course` varchar(1000) NOT NULL,
  `hea` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `eligibility`
--

CREATE TABLE `eligibility` (
  `eligibility_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `license` varchar(100) NOT NULL,
  `rating` varchar(100) NOT NULL,
  `doe/c` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `tenure_id` int(11) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `education_id` int(11) NOT NULL,
  `eligibility_id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee_training`
--

CREATE TABLE `employee_training` (
  `emptrain_id` int(100) NOT NULL,
  `employee_id` int(100) NOT NULL,
  `training_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `job_id` int(11) NOT NULL,
  `position` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `start_date` date NOT NULL,
  `department_id` int(11) NOT NULL,
  `monthlysalary` double NOT NULL,
  `dailysalary` double NOT NULL,
  `status` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movement`
--

CREATE TABLE `movement` (
  `movement_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotion_id` int(100) NOT NULL,
  `employee_id` int(100) NOT NULL,
  `date` date NOT NULL,
  `new_position` varchar(100) NOT NULL,
  `new_monthlyrate` double NOT NULL,
  `new_dailyrate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tenure`
--

CREATE TABLE `tenure` (
  `tenure_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `end_date` date NOT NULL,
  `movement_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `training_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `duration` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`education_id`);

--
-- Indexes for table `eligibility`
--
ALTER TABLE `eligibility`
  ADD PRIMARY KEY (`eligibility_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `fk_empltenure_id` (`tenure_id`),
  ADD KEY `fk_eligibility_id` (`eligibility_id`),
  ADD KEY `fk_emptoemptraining_id` (`training_id`),
  ADD KEY `fk_education_id` (`education_id`);

--
-- Indexes for table `employee_training`
--
ALTER TABLE `employee_training`
  ADD PRIMARY KEY (`emptrain_id`),
  ADD KEY `fk_training_id` (`training_id`),
  ADD KEY `fk_employeetraining_id` (`employee_id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `fk_department_id` (`department_id`);

--
-- Indexes for table `movement`
--
ALTER TABLE `movement`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `fk_promotion_id` (`promotion_id`),
  ADD KEY `fk_job_id` (`job_id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotion_id`),
  ADD KEY `fk_emppromotion_id` (`employee_id`);

--
-- Indexes for table `tenure`
--
ALTER TABLE `tenure`
  ADD PRIMARY KEY (`tenure_id`),
  ADD KEY `fk_movement_id` (`movement_id`),
  ADD KEY `fk_tenurejob_id` (`job_id`),
  ADD KEY `fk_emptenure_id` (`employee_id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`training_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `education_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eligibility`
--
ALTER TABLE `eligibility`
  MODIFY `eligibility_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_training`
--
ALTER TABLE `employee_training`
  MODIFY `emptrain_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movement`
--
ALTER TABLE `movement`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotion_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenure`
--
ALTER TABLE `tenure`
  MODIFY `tenure_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `training_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `fk_education_id` FOREIGN KEY (`education_id`) REFERENCES `education` (`education_id`),
  ADD CONSTRAINT `fk_eligibility_id` FOREIGN KEY (`eligibility_id`) REFERENCES `eligibility` (`eligibility_id`),
  ADD CONSTRAINT `fk_empltenure_id` FOREIGN KEY (`tenure_id`) REFERENCES `tenure` (`tenure_id`),
  ADD CONSTRAINT `fk_emptoemptraining_id` FOREIGN KEY (`training_id`) REFERENCES `employee_training` (`emptrain_id`);

--
-- Constraints for table `employee_training`
--
ALTER TABLE `employee_training`
  ADD CONSTRAINT `fk_employeetraining_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_training_id` FOREIGN KEY (`training_id`) REFERENCES `training` (`training_id`);

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `fk_department_id` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `movement`
--
ALTER TABLE `movement`
  ADD CONSTRAINT `fk_job_id` FOREIGN KEY (`job_id`) REFERENCES `job` (`job_id`),
  ADD CONSTRAINT `fk_promotion_id` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`promotion_id`);

--
-- Constraints for table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `fk_emppromotion_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `tenure`
--
ALTER TABLE `tenure`
  ADD CONSTRAINT `fk_emptenure_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `fk_movement_id` FOREIGN KEY (`movement_id`) REFERENCES `movement` (`movement_id`),
  ADD CONSTRAINT `fk_tenurejob_id` FOREIGN KEY (`job_id`) REFERENCES `job` (`job_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
