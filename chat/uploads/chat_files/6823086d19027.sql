-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 03:44 PM
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
-- Database: `medcrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_file` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `chat_id`, `sender_id`, `message`, `is_file`, `is_read`, `created_at`) VALUES
(38, 16, 44, 'Hello Admin, I hope you\'re doing well. I am currently having a problem on the portal. Are you available to assist me on this? Thanks', 0, 0, '2025-05-10 22:41:59'),
(39, 16, 3, 'Hello Sales, I am doing great. Thank you for asking. I hope everything is well on your side too. I\'d love to help you. Please let me know about your problem. Thank you', 0, 0, '2025-05-10 22:45:07'),
(40, 16, 3, '?', 0, 0, '2025-05-10 22:46:40'),
(41, 16, 3, '?', 0, 0, '2025-05-10 22:47:02'),
(42, 16, 44, 'Yes, Please allow me some moments, I am creating a video', 0, 0, '2025-05-10 22:48:39'),
(43, 16, 3, 'Sure, pleas take your time', 0, 0, '2025-05-10 22:50:08'),
(44, 16, 44, 'Thank you for your patient, I\'ll get back to you soon', 0, 0, '2025-05-10 22:51:26'),
(45, 16, 3, 'Still waiting', 0, 0, '2025-05-11 00:27:42'),
(46, 16, 44, 'Gimme few min', 0, 0, '2025-05-11 00:29:33'),
(47, 16, 3, 'Sure, take your time please', 0, 0, '2025-05-11 00:53:40'),
(48, 16, 3, 'any updates sir?', 0, 0, '2025-05-11 01:00:34'),
(49, 16, 44, 'yes sending you in a bit', 0, 0, '2025-05-11 01:07:13'),
(50, 16, 3, 'Thanks, waiting', 0, 0, '2025-05-11 01:08:44'),
(51, 16, 44, 'Emailed u video', 0, 0, '2025-05-11 01:13:49'),
(52, 17, 44, 'Hello Addie', 0, 0, '2025-05-11 01:32:20'),
(53, 17, 44, 'Hey?', 0, 0, '2025-05-11 01:36:49'),
(58, 16, 3, '6820973bbb221_1746966331.sql', 1, 0, '2025-05-11 17:25:31'),
(59, 16, 3, '682097a862121_1746966440.sql', 1, 0, '2025-05-11 17:27:20'),
(60, 16, 3, '682097f0de37c.sql', 1, 0, '2025-05-11 17:28:32'),
(61, 16, 3, '68209835ce1ac.sql', 1, 0, '2025-05-11 17:29:41'),
(62, 16, 3, '6820984fbbcc8.sql', 1, 0, '2025-05-11 17:30:07'),
(63, 16, 3, '6820987029480.sql', 1, 0, '2025-05-11 17:30:40'),
(64, 16, 3, '6820991772325.sql', 1, 0, '2025-05-11 17:33:27'),
(65, 16, 3, '68209a605c1d2.sql', 1, 0, '2025-05-11 17:38:56'),
(66, 16, 3, 'Hello', 0, 0, '2025-05-11 17:39:09'),
(67, 16, 3, 'Did you find my video?', 0, 0, '2025-05-11 17:45:30'),
(68, 16, 44, 'Yes I did, and I am giving you a response video', 0, 0, '2025-05-11 17:45:45'),
(69, 16, 44, '68209c0209f67.php', 1, 0, '2025-05-11 17:45:54'),
(70, 16, 3, 'Can you share one more please?', 0, 0, '2025-05-11 17:47:33'),
(71, 16, 44, '68209c69c6bcb.php', 1, 0, '2025-05-11 17:47:37'),
(72, 16, 3, '6820a84f46103.sql', 1, 0, '2025-05-11 18:38:23'),
(73, 17, 44, '?', 0, 0, '2025-05-11 18:38:50'),
(74, 16, 3, '6820a88e05d88.sql', 1, 0, '2025-05-11 18:39:26'),
(75, 16, 3, '6820a93e8519f.sql', 1, 0, '2025-05-11 18:42:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `inv_qne_users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
