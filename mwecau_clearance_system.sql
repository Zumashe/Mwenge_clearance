-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2025 at 07:43 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mwecau_clearance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `clearance_certificates`
--

CREATE TABLE `clearance_certificates` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `clearance_offices`
--

CREATE TABLE `clearance_offices` (
  `id` int(11) NOT NULL,
  `office_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clearance_offices`
--

INSERT INTO `clearance_offices` (`id`, `office_name`) VALUES
(2, 'dean_of_student'),
(1, 'finance'),
(7, 'head_of_faculty'),
(4, 'hod'),
(5, 'hostel'),
(3, 'mwecauso'),
(6, 'registration');

-- --------------------------------------------------------

--
-- Table structure for table `clearance_requests`
--

CREATE TABLE `clearance_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `programme` varchar(100) NOT NULL,
  `status_finance` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_dean_of_student` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_mwecauso` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_hod` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_hostel` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_registration` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_head_of_faculty` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_library` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clearance_requests`
--

INSERT INTO `clearance_requests` (`id`, `user_id`, `reg_no`, `full_name`, `academic_year`, `programme`, `status_finance`, `status_dean_of_student`, `status_mwecauso`, `status_hod`, `status_hostel`, `status_registration`, `status_head_of_faculty`, `request_date`, `status_library`) VALUES
(1, 3, 't/deg/2022/1328', 'shaaban soud magembe', '2024/2025', 'bss', 'approved', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending', '2025-07-22 15:00:08', 'pending'),
(2, 13, 't/deg/2022/2020', 'zulfa idd', '2024/2025', 'bss', 'pending', 'pending', 'pending', 'pending', 'approved', 'pending', 'pending', '2025-07-22 15:09:41', 'pending'),
(3, 15, 't/deg/2022/1104', 'pius shitobelo', '2024/2025', 'bss', 'pending', 'pending', 'pending', 'pending', 'approved', 'pending', 'pending', '2025-07-24 17:07:50', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `clearance_request_status`
--

CREATE TABLE `clearance_request_status` (
  `id` int(11) NOT NULL,
  `clearance_request_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `comment` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(10, 'admin'),
(3, 'dean_of_student'),
(2, 'finance'),
(8, 'head_of_faculty'),
(5, 'hod'),
(6, 'hostel'),
(9, 'Library'),
(4, 'mwecauso'),
(7, 'registration'),
(1, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `student_requests`
--

CREATE TABLE `student_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `reg_no` varchar(100) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `academic_year` varchar(50) DEFAULT NULL,
  `clearance_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `reg_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `reg_no`, `email`, `username`, `password`, `role_id`, `created_at`) VALUES
(3, 'shaaban soud magembe', 't/deg/2022/1328', 'shaabansoud05@gmail.com', NULL, '$2y$10$6GOy60Qi0UB3U87FQ0e/cufUxtBSDE8j6FKB16vR8snJ8OsAzZKtq', 1, '2025-07-22 13:03:13'),
(4, 'Finance Officer', NULL, NULL, 'finance', '$2y$10$Il66Oc1hMwFluM698IxpiOALdprab74W/yy3sKIvX7Cr.aT2hLJp2', 2, '2025-07-22 13:27:12'),
(5, 'Dean of Students', NULL, NULL, 'dean', '$2y$10$UA7fLCkzPDUMPSHP2mN5U.UFrSw8XJevjGxXqBIkaQ.8Jyt46/GFS', 3, '2025-07-22 13:27:12'),
(6, 'Mwecauso Office', NULL, NULL, 'mwecauso', '$2y$10$JBwymD4Erw0rOe9C0ayrauqNDX3CD.tIMpo1r7zDL8kk6LL1bOusS', 4, '2025-07-22 13:27:12'),
(7, 'Head of Department', NULL, NULL, 'hod', '$2y$10$xouvHCWh6cEVj6TSQrBZc.tGw3TeCi/3NrgHiCoX.d6GcXoZiCfBW', 5, '2025-07-22 13:27:12'),
(8, 'Hostel Incharge', NULL, NULL, 'hostel', '$2y$10$uW7dNfuiw/5Lb5o2m1PvwO.XTJDSoD5dmGwh3su8EFOha1xpBTmIC', 6, '2025-07-22 13:27:12'),
(9, 'Registration Office', NULL, NULL, 'registration', '$2y$10$EneaywZhO9VUE5AnPuY/T.rHzxIlmuQF7Je2hWKNS90M4WjbF.cGq', 7, '2025-07-22 13:27:12'),
(10, 'Head of Faculty', NULL, NULL, 'faculty_dashboard', '$2y$10$Yzz7eUYlBgu7hD/vJDcooOsl7GSn6B3qibkNwR2byruB.GwH/QNdi', 8, '2025-07-22 13:27:12'),
(11, 'Library Officer', NULL, NULL, 'library', '$2y$10$9YGD.1eyBFsCWHalEMCMTOmNSiPtCl3b8i3H7qpyNkHGyUJJCneCO', 9, '2025-07-22 13:27:12'),
(12, 'halima yusufu', 'halima yusufu shaaba', 'halimayusufu@gmail.com', NULL, '$2y$10$gowalRqo4Ktf34bBHxlN7OTm12JoXCMsRl9a.l.VhREk0KBgO.KZe', 1, '2025-07-22 15:07:05'),
(13, 'zulfa idd', 't/deg/2022/2020', 'zulfaidd@gmail.com', NULL, '$2y$10$zFHexPtCeD4sp2X5.qbXn.rTvHYlCHNpYt4zP67fHOwHJOnMT/9WO', 1, '2025-07-22 15:09:05'),
(14, 'System Admin', NULL, NULL, 'admin', '$2y$10$B1D3E9lgPyuUpCwBwwNoeORmrLIts7iE9sSjQDtYZ81VuEUDuNOX2', 10, '2025-07-23 13:15:21'),
(15, 'pius shitobelo', 't/deg/2022/1104', 'piusshitobelo@gmail.com', NULL, '$2y$10$6aBy5SWFZ3bpCEklmMmjwOWCsLmnZH.cUKVzbN.PVh3f3V7ASHbVu', 1, '2025-07-24 17:06:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clearance_certificates`
--
ALTER TABLE `clearance_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `clearance_offices`
--
ALTER TABLE `clearance_offices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `office_name` (`office_name`);

--
-- Indexes for table `clearance_requests`
--
ALTER TABLE `clearance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `clearance_request_status`
--
ALTER TABLE `clearance_request_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clearance_request_id` (`clearance_request_id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `student_requests`
--
ALTER TABLE `student_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reg_no` (`reg_no`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clearance_certificates`
--
ALTER TABLE `clearance_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clearance_offices`
--
ALTER TABLE `clearance_offices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clearance_requests`
--
ALTER TABLE `clearance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `clearance_request_status`
--
ALTER TABLE `clearance_request_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_requests`
--
ALTER TABLE `student_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clearance_requests`
--
ALTER TABLE `clearance_requests`
  ADD CONSTRAINT `clearance_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clearance_request_status`
--
ALTER TABLE `clearance_request_status`
  ADD CONSTRAINT `clearance_request_status_ibfk_1` FOREIGN KEY (`clearance_request_id`) REFERENCES `clearance_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clearance_request_status_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `clearance_offices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
