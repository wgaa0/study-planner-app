-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2025 at 03:46 PM
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
-- Database: `study_manager`
--
CREATE DATABASE IF NOT EXISTS `study_manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `study_manager`;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `user_id`, `title`, `description`, `created_at`) VALUES
(7, 9, 'Web Design and Development I', 'HTML, CSS, JavaScript, PHP', '2025-09-29 16:28:06'),
(8, 9, 'Computer Networks', 'TCP/IP Protocol, Subnetting, IPv4/IPv6', '2025-09-29 16:28:42'),
(9, 9, 'Geographic Information Systems', 'Coordinate Systems, Spatial Analysis', '2025-09-29 16:29:21'),
(10, 9, 'Statistics and Probability', 'Measures of Central Tendency, Probability Distributions', '2025-09-29 16:30:09'),
(11, 12, 'Mathematics', '', '2025-09-29 16:39:44'),
(12, 12, 'Physics', '', '2025-09-29 16:40:02'),
(13, 12, 'English', '', '2025-09-29 16:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `user_id`, `course_id`, `task_id`, `title`, `start`, `end`, `notes`, `created_at`) VALUES
(7, 9, NULL, NULL, 'Project Presentation', '2025-10-10 09:30:00', '2025-10-11 10:30:00', 'Present final project for Web Design and Development I course', '2025-09-29 16:33:05'),
(8, 12, NULL, NULL, 'Physics Final Exam', '2025-10-06 09:00:00', '2025-10-07 12:00:00', '', '2025-09-29 16:44:01'),
(9, 12, NULL, NULL, 'Mathematics Final Exam', '2025-10-10 14:00:00', '2025-10-11 16:00:00', '', '2025-09-29 16:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `details` text DEFAULT NULL,
  `status` enum('todo','in_progress','done') DEFAULT 'todo',
  `due_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `course_id`, `title`, `details`, `status`, `due_date`, `created_at`, `updated_at`) VALUES
(11, 7, 'Finish UI/UX', 'Enhance UI/UX with animations', 'todo', '2025-10-05 23:59:00', '2025-09-29 16:31:07', '2025-09-29 16:31:07'),
(12, 10, 'Check Grades', 'Check Final Exam results and final grades', 'todo', '2025-10-02 08:30:00', '2025-09-29 16:32:01', '2025-09-29 16:32:01'),
(13, 7, 'CRUD Functionality', 'Fully implement CRUD functionality for final project', 'done', '2025-09-28 23:59:00', '2025-09-29 16:34:12', '2025-09-29 16:34:25'),
(14, 11, 'Submit Assignment', '', 'done', '2025-09-22 13:30:00', '2025-09-29 16:40:50', '2025-09-29 16:43:00'),
(15, 13, 'Presentation', '', 'done', '2025-09-25 10:30:00', '2025-09-29 16:41:16', '2025-09-29 16:42:52'),
(16, 12, 'Study for Final Exam', '', 'in_progress', '2025-10-06 09:00:00', '2025-09-29 16:41:49', '2025-09-29 16:42:38'),
(17, 11, 'Study for Final Exam', '', 'todo', '2025-10-10 14:00:00', '2025-09-29 16:42:21', '2025-09-29 16:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(9, 'Aser Alemu', 'aser@gmail.com', '$2y$10$L66StwaB5DAC4Jmp64I4J.vlfutqouNOOf6dfdftzEfEg6JY25lpC', '2025-09-29 16:25:38'),
(10, 'Yaphet Gebreyesus', 'yaphet@gmail.com', '$2y$10$.gudgT7YmSETVyeHaCflk.B2E37LGsJ1XG3HRQ8NjqU8/WpkpaNcK', '2025-09-29 16:26:21'),
(11, 'Yohannes Abebe', 'yohannes@gmail.com', '$2y$10$Yc39FV7KZT0ezYNxrQ5iau5EWjpEcowhnIlC4I0TRB35ujEzxUKj.', '2025-09-29 16:26:52'),
(12, 'Abebe Kebede', 'abebe@gmail.com', '$2y$10$EGFSVLYRdKibYoX7KTm0NOYxDySCPAZ0.C5ySdYnPwh/4yeNItB6y', '2025-09-29 16:39:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
