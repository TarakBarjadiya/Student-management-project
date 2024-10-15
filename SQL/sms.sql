-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2024 at 07:11 PM
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
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modification_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_name`, `admin_email`, `password_hash`, `created_at`, `modification_date`) VALUES
(1, 'Admin', 'admin@login.com', '$2y$10$DNMxj2bERsHtDHF9.SUFeehFsrIbYE8jmwkuxh4d1EftzPWSUb8P2', '2024-10-15 17:04:42', '2024-10-15 17:04:42');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_type` enum('College','Other') NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `batch_year` year(4) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modification_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `class_fees` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_type`, `class_name`, `batch_year`, `creation_date`, `modification_date`, `class_fees`) VALUES
(1, 'College', 'BCA', '2022', '2024-10-15 17:05:12', '2024-10-15 17:05:12', 8500);

-- --------------------------------------------------------

--
-- Table structure for table `class_notices`
--

CREATE TABLE `class_notices` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `c_notice_title` varchar(255) NOT NULL,
  `c_notice_description` text NOT NULL,
  `publish_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_notices`
--

INSERT INTO `class_notices` (`id`, `class_id`, `c_notice_title`, `c_notice_description`, `publish_date`) VALUES
(1, 1, 'Testing Class Notices', 'Testing Class Notices Feature.', '2024-10-15 17:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `inq_name` text NOT NULL,
  `inq_email` text NOT NULL,
  `inq_msg` text NOT NULL,
  `inq_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `inq_name`, `inq_email`, `inq_msg`, `inq_date`) VALUES
(1, 'Harsh Makwana', 'harsh@gmail.com', 'Testing contact us / inquiries feature.', '2024-10-15 13:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `request_title` varchar(255) NOT NULL,
  `request_description` text NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `student_id`, `request_title`, `request_description`, `request_date`, `status`) VALUES
(1, 1, 'Testing Requests', 'Testing Requests Feature.\r\n', '2024-10-15 17:10:15', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `enrollment_number` varchar(15) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `stud_dob` date NOT NULL,
  `stud_address` text NOT NULL,
  `house_no` text NOT NULL,
  `address_2` text NOT NULL,
  `address_3` text NOT NULL,
  `state` text NOT NULL,
  `city` text NOT NULL,
  `postal_code` text NOT NULL,
  `stud_mobile` varchar(15) NOT NULL,
  `stud_email` varchar(50) NOT NULL,
  `father_name` varchar(50) NOT NULL,
  `father_contact` varchar(15) NOT NULL,
  `mother_name` varchar(50) NOT NULL,
  `mother_contact` varchar(15) NOT NULL,
  `fees_pending` decimal(10,2) DEFAULT 0.00,
  `fees_paid` decimal(10,2) DEFAULT 0.00,
  `paid_date` date DEFAULT NULL,
  `stud_status` enum('on roll','passout','admission canceled') NOT NULL DEFAULT 'on roll'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`id`, `class_id`, `enrollment_number`, `first_name`, `middle_name`, `last_name`, `gender`, `stud_dob`, `stud_address`, `house_no`, `address_2`, `address_3`, `state`, `city`, `postal_code`, `stud_mobile`, `stud_email`, `father_name`, `father_contact`, `mother_name`, `mother_contact`, `fees_pending`, `fees_paid`, `paid_date`, `stud_status`) VALUES
(1, 1, 'STUD0001', 'Tarak', 'Dharmendrabhai', 'Barjadiya', 'Male', '2004-12-10', 'Vasupujya, Vishwanagar Main Road, Mavdi Plot, Rajkot, Gujarat - 360004', 'Vasupujya', 'Vishwanagar Main Road', 'Mavdi Plot', 'Gujarat', 'Rajkot', '360004', '9316528407', 'tarak@gmail.com', 'Dharmendrabhai M Barjadiya', '9998463254', 'Sonalben D Barjadiya', '9428258282', 8500.00, 0.00, NULL, 'on roll');

-- --------------------------------------------------------

--
-- Table structure for table `student_login`
--

CREATE TABLE `student_login` (
  `student_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_login`
--

INSERT INTO `student_login` (`student_id`, `password`, `last_login`) VALUES
(1, '$2y$10$1l1ULYhq5KzPxrfTRlsleOx6Vajina5B.HmJ4haYVhKV3FZT2gXRa', '2024-10-15 17:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `student_notifications`
--

CREATE TABLE `student_notifications` (
  `notification_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `notification_title` varchar(255) NOT NULL,
  `notification_description` text NOT NULL,
  `notification_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_notifications`
--

INSERT INTO `student_notifications` (`notification_id`, `student_id`, `notification_title`, `notification_description`, `notification_date`) VALUES
(1, 1, 'Notification Testing', 'Testing Notification Feature.\r\n', '2024-10-15 17:07:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_notices`
--
ALTER TABLE `class_notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stud_email` (`stud_email`),
  ADD UNIQUE KEY `enrollment_number` (`enrollment_number`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_login`
--
ALTER TABLE `student_login`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `student_notifications`
--
ALTER TABLE `student_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `class_notices`
--
ALTER TABLE `class_notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_notifications`
--
ALTER TABLE `student_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_notices`
--
ALTER TABLE `class_notices`
  ADD CONSTRAINT `class_notices_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`id`);

--
-- Constraints for table `student_info`
--
ALTER TABLE `student_info`
  ADD CONSTRAINT `student_info_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Constraints for table `student_login`
--
ALTER TABLE `student_login`
  ADD CONSTRAINT `student_login_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_notifications`
--
ALTER TABLE `student_notifications`
  ADD CONSTRAINT `student_notifications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
