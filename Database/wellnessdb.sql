-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 04:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wellnessdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `exerciselogs`
--

CREATE TABLE `exerciselogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_type` varchar(100) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exerciselogs`
--

INSERT INTO `exerciselogs` (`id`, `user_id`, `exercise_type`, `duration_minutes`, `log_date`, `created_at`) VALUES
(1, 1, 'ds', 2, '2025-07-10', '2025-07-10 19:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `moodlogs`
--

CREATE TABLE `moodlogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mood` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `log_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moodlogs`
--

INSERT INTO `moodlogs` (`id`, `user_id`, `mood`, `notes`, `log_date`, `created_at`) VALUES
(1, 1, 'dsdd', 'ds', '2025-07-10', '2025-07-10 19:28:44');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reminder_type` varchar(50) NOT NULL,
  `time` time NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `user_id`, `reminder_type`, `time`, `is_active`, `created_at`) VALUES
(1, 1, 'dsfd', '20:34:00', 1, '2025-07-10 19:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `sleeplogs`
--

CREATE TABLE `sleeplogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `duration_hours` float NOT NULL,
  `log_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sleeplogs`
--

INSERT INTO `sleeplogs` (`id`, `user_id`, `start_time`, `end_time`, `duration_hours`, `log_date`, `created_at`) VALUES
(1, 1, '2025-07-10 19:24:00', '2025-07-11 19:24:00', 24, '2025-07-10', '2025-07-10 19:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `address`, `city`, `created_at`) VALUES
(1, 'NASIR ABBAS', 'nasiryt.827@gmail.com', '$2y$10$.FNQpKNR.4cwAhxsYOiGGuMmqViVz5wlYDWTt3IquWWOADroyKkni', '3176526827', 'Street jeff xxxx\r\nAp04', 'Lahore', '2025-07-10 14:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `waterlogs`
--

CREATE TABLE `waterlogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `unit` varchar(10) NOT NULL,
  `log_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waterlogs`
--

INSERT INTO `waterlogs` (`id`, `user_id`, `quantity`, `unit`, `log_date`, `created_at`) VALUES
(2, 1, 200, 'glass', '2025-07-10', '2025-07-10 19:16:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exerciselogs`
--
ALTER TABLE `exerciselogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `moodlogs`
--
ALTER TABLE `moodlogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sleeplogs`
--
ALTER TABLE `sleeplogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `waterlogs`
--
ALTER TABLE `waterlogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exerciselogs`
--
ALTER TABLE `exerciselogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `moodlogs`
--
ALTER TABLE `moodlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sleeplogs`
--
ALTER TABLE `sleeplogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `waterlogs`
--
ALTER TABLE `waterlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exerciselogs`
--
ALTER TABLE `exerciselogs`
  ADD CONSTRAINT `exerciselogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `moodlogs`
--
ALTER TABLE `moodlogs`
  ADD CONSTRAINT `moodlogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `sleeplogs`
--
ALTER TABLE `sleeplogs`
  ADD CONSTRAINT `sleeplogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `waterlogs`
--
ALTER TABLE `waterlogs`
  ADD CONSTRAINT `waterlogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
