-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2024 at 02:39 AM
-- Server version: 8.0.39
-- PHP Version: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `Audit_Logs`
--

CREATE TABLE `Audit_Logs` (
  `audit_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `event_type` enum('login','registration','booking_approval','wallet_fill') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `event_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `event_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Audit_Logs`
--

INSERT INTO `Audit_Logs` (`audit_id`, `user_id`, `event_type`, `event_description`, `event_timestamp`) VALUES
(1, 1, 'login', 'User logged in', '2024-08-15 01:22:58'),
(2, 2, 'registration', 'New user registered', '2024-08-15 01:22:58'),
(3, 3, 'booking_approval', 'Booking approved by admin', '2024-08-15 01:22:58'),
(4, 4, 'wallet_fill', 'User filled wallet', '2024-08-15 01:22:58'),
(5, 5, 'login', 'User logged in', '2024-08-15 01:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `Bookings`
--

CREATE TABLE `Bookings` (
  `booking_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Bookings`
--

INSERT INTO `Bookings` (`booking_id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 3, '2024-08-10', '2024-08-12', 600.00, 'pending', '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(3, 3, 2, '2024-08-15', '2024-08-20', 600.00, 'cancelled', '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(4, 4, 5, '2024-09-01', '2024-09-03', 260.00, 'approved', '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(5, 5, 4, '2024-08-20', '2024-08-25', 400.00, 'approved', '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(6, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-21 09:23:22', '2024-08-21 09:23:22'),
(7, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-21 09:59:48', '2024-08-21 09:59:48'),
(8, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-21 10:04:54', '2024-08-21 10:04:54'),
(9, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-21 10:05:26', '2024-08-21 10:05:26'),
(10, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-21 10:06:36', '2024-08-21 10:06:36'),
(11, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-21 10:06:54', '2024-08-21 10:06:54'),
(12, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-22 17:54:32', '2024-08-22 17:54:32'),
(13, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-22 18:20:19', '2024-08-22 18:20:19'),
(14, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 07:57:27', '2024-08-25 07:57:27'),
(15, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 07:58:03', '2024-08-25 07:58:03'),
(16, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 07:59:09', '2024-08-25 07:59:09'),
(17, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 08:17:42', '2024-08-25 08:17:42'),
(18, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 08:19:43', '2024-08-25 08:19:43'),
(19, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 08:22:51', '2024-08-25 08:22:51'),
(20, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 08:41:05', '2024-08-25 08:41:05'),
(21, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-25 08:41:40', '2024-08-25 08:41:40'),
(22, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-26 07:18:27', '2024-08-26 07:18:27'),
(23, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-26 07:21:03', '2024-08-26 07:21:03'),
(24, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-27 07:00:32', '2024-08-27 07:00:32'),
(25, 1, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:35:51', '2024-08-29 01:35:51'),
(26, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:48:31', '2024-08-29 01:48:31'),
(27, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:48:35', '2024-08-29 01:48:35'),
(28, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:49:18', '2024-08-29 01:49:18'),
(29, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:49:38', '2024-08-29 01:49:38'),
(30, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:49:39', '2024-08-29 01:49:39'),
(31, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:49:56', '2024-08-29 01:49:56'),
(32, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:51:09', '2024-08-29 01:51:09'),
(33, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:52:37', '2024-08-29 01:52:37'),
(34, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:56:51', '2024-08-29 01:56:51'),
(35, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 01:58:40', '2024-08-29 01:58:40'),
(36, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 02:00:30', '2024-08-29 02:00:30'),
(37, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 02:03:44', '2024-08-29 02:03:44'),
(38, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 02:04:03', '2024-08-29 02:04:03'),
(39, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 02:04:04', '2024-08-29 02:04:04'),
(40, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 02:04:05', '2024-08-29 02:04:05'),
(41, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 02:04:05', '2024-08-29 02:04:05'),
(42, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 05:25:02', '2024-08-29 05:25:02'),
(43, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 05:25:51', '2024-08-29 05:25:51'),
(44, NULL, 1, '2024-12-23', '2024-12-25', 255.00, 'pending', '2024-08-29 05:28:48', '2024-08-29 05:28:48'),
(45, 77, 4, '2024-09-01', '2024-09-04', 240.00, 'approved', '2024-08-29 07:22:57', '2024-08-29 07:28:29'),
(46, 77, 4, '2024-09-01', '2024-09-04', 240.00, 'approved', '2024-08-29 07:23:07', '2024-08-29 07:28:26'),
(47, 79, 3, '2024-09-01', '2024-09-03', 712.00, 'pending', '2024-08-29 19:24:52', '2024-08-29 19:24:52'),
(48, 79, 3, '2024-09-01', '2024-09-03', 712.00, 'pending', '2024-08-29 19:25:54', '2024-08-29 19:25:54'),
(49, 79, 5, '2024-08-02', '2024-08-09', 910.00, 'pending', '2024-08-29 19:28:55', '2024-08-29 19:28:55'),
(50, 79, 4, '2024-09-06', '2024-09-09', 240.00, 'pending', '2024-08-29 19:35:12', '2024-08-29 19:35:12'),
(51, 80, 4, '2024-08-01', '2024-08-17', 1280.00, 'pending', '2024-08-29 19:39:44', '2024-08-29 19:39:44'),
(52, 80, 5, '2024-08-16', '2024-08-17', 130.00, 'pending', '2024-08-29 19:41:38', '2024-08-29 19:41:38'),
(53, 80, 3, '2024-08-03', '2024-08-05', 600.00, 'pending', '2024-08-29 19:42:43', '2024-08-29 19:42:43'),
(54, 80, 4, '2024-09-25', '2024-09-27', 160.00, 'pending', '2024-08-29 19:45:33', '2024-08-29 19:45:33'),
(55, 80, 3, '2024-10-24', '2024-10-25', 300.00, 'pending', '2024-08-29 19:47:26', '2024-08-29 19:47:26'),
(56, 80, 4, '2024-10-24', '2024-10-25', 80.00, 'pending', '2024-08-29 19:48:49', '2024-08-29 19:48:49'),
(57, 80, 3, '2024-11-01', '2024-11-03', 600.00, 'pending', '2024-08-29 19:52:03', '2024-08-29 19:52:03'),
(58, 80, 4, '2024-11-13', '2024-11-14', 80.00, 'pending', '2024-08-29 19:54:06', '2024-08-29 19:54:06'),
(59, 80, 4, '2024-11-13', '2024-11-14', 80.00, 'pending', '2024-08-29 19:54:20', '2024-08-29 19:54:20'),
(60, 80, 3, '2024-08-02', '2024-08-03', 300.00, 'pending', '2024-08-29 19:56:04', '2024-08-29 19:56:04'),
(61, 81, 4, '2024-08-17', '2024-08-18', 80.00, 'pending', '2024-08-29 20:06:38', '2024-08-29 20:06:38'),
(62, 81, 5, '2024-11-29', '2024-11-29', 0.00, 'pending', '2024-08-29 20:10:24', '2024-08-29 20:10:24'),
(63, 84, 3, '2024-09-07', '2024-09-08', 300.00, 'pending', '2024-08-29 20:46:14', '2024-08-29 20:46:14'),
(64, 84, 5, '2024-09-26', '2024-09-27', 282.00, 'pending', '2024-08-29 20:49:49', '2024-08-29 20:49:49'),
(65, 84, 5, '2024-09-26', '2024-09-27', 282.00, 'pending', '2024-08-29 20:50:50', '2024-08-29 20:50:50'),
(66, 84, 3, '2024-08-16', '2024-08-17', 335.00, 'pending', '2024-08-29 20:51:14', '2024-08-29 20:51:14'),
(67, 84, 3, '2024-08-16', '2024-08-17', 335.00, 'pending', '2024-08-29 20:51:57', '2024-08-29 20:51:57'),
(68, 84, 3, '2024-08-09', '2024-08-10', 335.00, 'pending', '2024-08-29 20:53:07', '2024-08-29 20:53:07'),
(69, 84, 3, '2024-10-01', '2024-10-02', 300.00, 'pending', '2024-08-29 20:54:06', '2024-08-29 20:54:06'),
(70, 84, 5, '2024-08-24', '2024-08-31', 910.00, 'pending', '2024-08-29 20:55:56', '2024-08-29 20:55:56'),
(71, 84, 3, '2024-08-23', '2024-08-24', 300.00, 'pending', '2024-08-29 20:57:24', '2024-08-29 20:57:24'),
(72, 84, 3, '2024-08-23', '2024-08-24', 300.00, 'pending', '2024-08-29 20:57:49', '2024-08-29 20:57:49'),
(73, 84, 3, '2024-08-23', '2024-08-24', 300.00, 'pending', '2024-08-29 20:58:11', '2024-08-29 20:58:11'),
(74, 84, 4, '2024-09-12', '2024-09-13', 140.00, 'pending', '2024-08-29 20:58:57', '2024-08-29 20:58:57'),
(75, 84, 4, '2024-12-13', '2024-12-14', 182.00, 'pending', '2024-08-29 21:20:53', '2024-08-29 21:20:53'),
(76, 84, 4, '2024-12-13', '2024-12-14', 182.00, 'pending', '2024-08-29 21:21:25', '2024-08-29 21:21:25'),
(77, 82, 3, '2024-10-26', '2024-10-27', 300.00, 'pending', '2024-08-29 21:23:28', '2024-08-29 21:23:28'),
(78, 82, 3, '2024-10-26', '2024-10-27', 402.00, 'pending', '2024-08-29 21:23:34', '2024-08-29 21:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `Booking_Services`
--

CREATE TABLE `Booking_Services` (
  `booking_service_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Booking_Services`
--

INSERT INTO `Booking_Services` (`booking_service_id`, `booking_id`, `service_id`, `quantity`, `total_price`) VALUES
(2, 2, 2, 2, 50.00),
(3, 3, 4, 1, 10.00),
(4, 4, 5, 1, 100.00),
(5, 5, 3, 3, 150.00),
(6, 11, 1, 2, 30.00),
(7, 11, 2, 1, 25.00),
(8, 11, 3, 1, 50.00),
(9, 12, 1, 2, 30.00),
(10, 12, 2, 1, 25.00),
(11, 12, 3, 1, 50.00),
(12, 13, 1, 2, 30.00),
(13, 13, 2, 1, 25.00),
(14, 13, 3, 1, 50.00),
(15, 14, 1, 2, 30.00),
(16, 14, 2, 1, 25.00),
(17, 14, 3, 1, 50.00),
(18, 15, 1, 2, 30.00),
(19, 15, 2, 1, 25.00),
(20, 15, 3, 1, 50.00),
(21, 16, 1, 2, 30.00),
(22, 16, 2, 1, 25.00),
(23, 16, 3, 1, 50.00),
(24, 17, 1, 2, 30.00),
(25, 17, 2, 1, 25.00),
(26, 17, 3, 1, 50.00),
(27, 18, 1, 2, 30.00),
(28, 18, 2, 1, 25.00),
(29, 18, 3, 1, 50.00),
(30, 19, 1, 2, 30.00),
(31, 19, 2, 1, 25.00),
(32, 19, 3, 1, 50.00),
(33, 20, 1, 2, 30.00),
(34, 20, 2, 1, 25.00),
(35, 20, 3, 1, 50.00),
(36, 21, 1, 2, 30.00),
(37, 21, 2, 1, 25.00),
(38, 21, 3, 1, 50.00),
(39, 22, 1, 2, 30.00),
(40, 22, 2, 1, 25.00),
(41, 22, 3, 1, 50.00),
(42, 23, 1, 2, 30.00),
(43, 23, 2, 1, 25.00),
(44, 23, 3, 1, 50.00),
(45, 26, 1, 2, 30.00),
(46, 26, 2, 1, 25.00),
(47, 26, 3, 1, 50.00),
(48, 27, 1, 2, 30.00),
(49, 27, 2, 1, 25.00),
(50, 27, 3, 1, 50.00),
(51, 28, 1, 2, 30.00),
(52, 28, 2, 1, 25.00),
(53, 28, 3, 1, 50.00),
(54, 29, 1, 2, 30.00),
(55, 29, 2, 1, 25.00),
(56, 29, 3, 1, 50.00),
(57, 30, 1, 2, 30.00),
(58, 30, 2, 1, 25.00),
(59, 30, 3, 1, 50.00),
(60, 31, 1, 2, 30.00),
(61, 31, 2, 1, 25.00),
(62, 31, 3, 1, 50.00),
(63, 32, 1, 2, 30.00),
(64, 32, 2, 1, 25.00),
(65, 32, 3, 1, 50.00),
(66, 33, 1, 2, 30.00),
(67, 33, 2, 1, 25.00),
(68, 33, 3, 1, 50.00),
(69, 34, 1, 2, 30.00),
(70, 34, 2, 1, 25.00),
(71, 34, 3, 1, 50.00),
(72, 35, 1, 2, 30.00),
(73, 35, 2, 1, 25.00),
(74, 35, 3, 1, 50.00),
(75, 36, 1, 2, 30.00),
(76, 36, 2, 1, 25.00),
(77, 36, 3, 1, 50.00),
(78, 37, 1, 2, 30.00),
(79, 37, 2, 1, 25.00),
(80, 37, 3, 1, 50.00),
(81, 38, 1, 2, 30.00),
(82, 38, 2, 1, 25.00),
(83, 38, 3, 1, 50.00),
(84, 39, 1, 2, 30.00),
(85, 39, 2, 1, 25.00),
(86, 39, 3, 1, 50.00),
(87, 40, 1, 2, 30.00),
(88, 40, 2, 1, 25.00),
(89, 40, 3, 1, 50.00),
(90, 41, 1, 2, 30.00),
(91, 41, 2, 1, 25.00),
(92, 41, 3, 1, 50.00),
(93, 42, 1, 2, 30.00),
(94, 42, 2, 1, 25.00),
(95, 42, 3, 1, 50.00),
(96, 43, 1, 2, 30.00),
(97, 43, 2, 1, 25.00),
(98, 43, 3, 1, 50.00),
(99, 44, 1, 2, 30.00),
(100, 44, 2, 1, 25.00),
(101, 44, 3, 1, 50.00),
(102, 47, 4, 1, 10.00),
(103, 47, 5, 1, 102.00),
(104, 48, 4, 1, 10.00),
(105, 48, 5, 1, 102.00),
(106, 64, 3, 1, 50.00),
(107, 64, 5, 1, 102.00),
(108, 65, 3, 1, 50.00),
(109, 65, 5, 1, 102.00),
(110, 66, 4, 1, 10.00),
(111, 66, 2, 1, 25.00),
(112, 67, 4, 1, 10.00),
(113, 67, 2, 1, 25.00),
(114, 68, 4, 1, 10.00),
(115, 68, 2, 1, 25.00),
(116, 74, 4, 1, 10.00),
(117, 74, 2, 2, 50.00),
(118, 75, 5, 1, 102.00),
(119, 76, 5, 1, 102.00),
(120, 78, 5, 1, 102.00);

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `comment_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `rating` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Comments`
--

INSERT INTO `Comments` (`comment_id`, `user_id`, `room_id`, `comment_text`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Great stay! Highly recommend.', 5, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(2, 2, 3, 'Luxury at its best. Will come again.', 5, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(3, 3, 2, 'Decent room, but could be cleaner.', 3, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(5, 5, 4, 'Maintenance issues, not happy.', 2, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(6, 4, 3, 'it was really nice!', 5, '2024-08-20 08:26:13', '2024-08-20 08:26:13'),
(9, 77, 4, 'hello', 5, '2024-08-29 07:25:20', '2024-08-29 07:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `Rooms`
--

CREATE TABLE `Rooms` (
  `room_id` int NOT NULL,
  `room_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `room_type` enum('single','double','suite') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('available','occupied','maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Rooms`
--

INSERT INTO `Rooms` (`room_id`, `room_number`, `room_type`, `price_per_night`, `description`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(1, '101', 'single', 75.00, 'Single bed room with sea view', 'image_url_1', 'occupied', '2024-08-15 01:22:58', '2024-08-29 19:15:07'),
(2, '102', 'double', 120.00, 'Double bed room with balcony', 'image_url_2', 'maintenance', '2024-08-15 01:22:58', '2024-08-21 11:25:59'),
(3, '201', 'suite', 300.00, 'Luxury suite with private pool', 'image_url_3', 'available', '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(4, '202', 'single', 80.00, 'Single bed room with garden view', 'image_url_4', 'available', '2024-08-15 01:22:58', '2024-08-21 11:25:48'),
(5, '203', 'double', 130.00, 'Double bed room with mountain view', 'image_url_5', 'available', '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(8, '204', 'single', 204.00, '123', '8254cfdfde832a32f198a0cc22a26e62.png', 'occupied', '2024-08-29 17:44:24', '2024-08-29 17:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `Services`
--

CREATE TABLE `Services` (
  `service_id` int NOT NULL,
  `service_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `service_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Services`
--

INSERT INTO `Services` (`service_id`, `service_name`, `service_description`, `price`, `created_at`, `updated_at`) VALUES
(1, 'breakfast', 'Buffet breakfast', 15.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(2, 'lunch', 'Three-course lunch', 25.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(3, 'dinner', 'Five-course dinner', 50.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(4, 'parking', 'Secure parking', 10.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(5, 'spa', 'Full day spa access', 102.00, '2024-08-15 01:22:58', '2024-08-29 21:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_title` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `session_value` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`session_title`, `session_value`) VALUES
('session-time', 3600);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','staff','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_locked` tinyint(1) DEFAULT '0',
  `locked_expire` datetime DEFAULT NULL,
  `failed_login_attempts` int DEFAULT '0',
  `wallet_balance` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password_hash`, `email`, `role`, `is_locked`, `locked_expire`, `failed_login_attempts`, `wallet_balance`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', 'hashed_password_1', 'john@example.com', 'customer', 0, NULL, 0, 42150.00, '2024-08-15 01:22:58', '2024-08-28 14:31:15'),
(2, 'janesmith', 'hashed_password_2', 'jane@example.com', 'admin', 0, NULL, 0, 2000.00, '2024-08-15 01:22:58', '2024-08-28 14:31:20'),
(3, 'mike_ross', 'hashed_password_3', 'mike@example.com', 'staff', 0, NULL, 1, 500.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(4, 'alice_wonder', 'hashed_password_4', 'alice@example.com', 'customer', 1, NULL, 3, 0.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(5, 'bob_marley', 'hashed_password_5', 'bob@example.com', 'customer', 0, NULL, 0, 100.00, '2024-08-15 01:22:58', '2024-08-15 01:22:58'),
(6, 'jisun', 'hashed-password', 'test@naver.com', 'customer', 0, '2024-08-25 23:48:15', 0, 0.00, '2024-08-20 07:27:04', '2024-08-29 09:17:43'),
(22, 'jisun', 'hashd', 'test123@gmail.com', 'admin', 0, NULL, 0, 0.00, '2024-08-20 09:36:34', '2024-08-22 17:11:25'),
(42, 'jisun123', '$2y$10$RNhNnY287QrZj32TDWkrb.gRGe/H.QQnVgRGMNQxVh7obK6G1GPni', 'test123457@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-20 10:03:13', '2024-08-20 10:03:13'),
(43, 'jisun123', '$2y$10$L.ElVP5adELOrA2V3vwNH.XGMw.aClUsiaEyIQ.8bRBN4t3DKyNha', 'test1234517@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-20 10:03:34', '2024-08-20 10:03:34'),
(44, 'sugat', '$2y$12$7FIgNv6de3JYCHOd0NUdu.L7N88KqQ/JJ8WrO.6BmSbTtPgNgkeR2', 'sugat@gmail.com', 'customer', 1, '2024-08-21 03:19:34', 5, 0.00, '2024-08-20 10:43:34', '2024-08-21 00:19:34'),
(45, 'sugarcoat', '$2y$12$quaKSPpZqHSkrG1AL/TcIO/S.gRo2hSFbbP4yXlyCmREhbapdzPlO', 'sugat@gmail.comd', 'customer', 0, NULL, 0, 0.00, '2024-08-20 10:46:04', '2024-08-20 10:46:04'),
(46, 'sugarcoat', '$2y$10$j2toON3vKr9oTq3ThsuQFOhw3wnN4XcafRN2ciZhUHgggitgkXHoW', 'suga1244t@gmail.comd', 'customer', 1, '2024-08-21 03:40:04', 5, 0.00, '2024-08-20 19:43:38', '2024-08-21 00:40:04'),
(47, 'sugarcoat', '$2y$10$ZSELn2XvBSvXM4F3627zVOTvHbS2vQqt.b1Ar1vQsgHlWVXqSPM0K', 'tamwood123@gmail.comd', 'customer', 1, NULL, 5, 0.00, '2024-08-20 19:49:48', '2024-08-21 11:57:54'),
(48, 'dam', '$2y$10$IE0oILhz0r3tDdDgXfWeq.XiwCi8k5grPNHRiIc.M3h.gZD9rZakK', 'sugat96@gmail.com', 'customer', 1, '2024-08-21 03:37:19', 5, 0.00, '2024-08-21 00:27:03', '2024-08-21 00:37:19'),
(49, 'dam12', '$2y$10$iucSh8md4GydbdCmk.8bFeFYE3WH8iyYVehBu57Sq3iuI98dD70JO', 'suga123t@gmail.comd', 'customer', 0, NULL, 0, 0.00, '2024-08-21 04:00:01', '2024-08-21 04:00:01'),
(50, 'test', '$2y$10$7TpkSgH2rbbM6P83AbDtvOhL3H5xhW7yiV67E.YbiCbzmeOoHUdPm', 'test@mail.com', 'admin', 0, NULL, 0, 26320.00, '2024-08-21 06:11:16', '2024-08-29 18:30:26'),
(51, 'test', '$2y$10$PyPUjJTdcsnhh3j.NNql0OLrVvsxRCXcXTCASjQ9EvXNtvgtXfIfu', 'testtest@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-21 06:20:27', '2024-08-21 06:20:27'),
(53, 'test', '$2y$10$xl9rAtTx.x.gy983z7Rq/eo8qGJKOGshXk2WIEiHEtU8fL5VEsWn.', 'test8@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-22 07:25:45', '2024-08-22 07:25:45'),
(54, 'test', '$2y$10$1sPm7mOw8mVfiO3poNrFkubTjhj2zs8/rh.okzbDQes9SzMktmdGu', 'test9@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-22 07:25:53', '2024-08-22 07:25:53'),
(55, 'test', '$2y$10$TgbuzfmPp2mujtDsQu.fwu/4ekcP2WVEj/8SK8y4OdHWOflRychoa', 'test10@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-22 07:25:59', '2024-08-22 07:25:59'),
(56, 'test', '$2y$10$D33GDTlQzrProWw04y1eteOn.7maK7.S0oHVZ9oXfgaCqak5nHQWy', 'test11@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-22 07:26:06', '2024-08-22 07:26:06'),
(57, 'test', '$2y$10$Sbra2zCPGRkFFrmOeS3lwuledtBt4NwG9KAw6XXBbO1Ba4zim21N2', 'testtestqw@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-22 16:57:31', '2024-08-22 16:57:31'),
(58, 'test', '$2y$10$DEqYdTjhZu5IMFhYVRoNJO/qH97f/WNUaEwQZBVZI0CgQYlke8N46', 'testtestqw1@mail.com', 'staff', 0, NULL, 0, 0.00, '2024-08-22 16:58:31', '2024-08-22 17:42:24'),
(59, 'test', '$2y$10$khDfQbB3AnFd1bGXKditeupoA4lxeHIcVjDpglaPHFKPj0E052uMO', 'testtestqw11@mail.com', 'staff', 0, NULL, 0, 0.00, '2024-08-22 17:24:12', '2024-08-22 17:24:12'),
(60, 'test', '$2y$10$nQ3M8GjjEEGHtcODiG2FaOsEn2PpJfP9FJtY468iqOAcMTGcgjYrC', 'testtestqw112@mail.com', 'staff', 1, NULL, 5, 0.00, '2024-08-23 01:35:37', '2024-08-29 05:15:48'),
(61, 'test', '$2y$10$wFn.apXC4qFIXawds1uKc.Bi9BP5v7BiHokhws2515/sXJ4Hyu2T.', 'testtestqw1122@mail.com', 'staff', 0, NULL, 0, 0.00, '2024-08-23 02:33:11', '2024-08-23 02:33:11'),
(62, 'test', '$2y$10$CQhjobEoIb5m5VAn9.HYG.9MrO5ZthVEnpEeNEnUhp02tLwiB6QxS', 'testtestqw1123@mail.com', 'staff', 0, NULL, 0, 0.00, '2024-08-23 05:37:05', '2024-08-23 05:37:05'),
(63, 'test', '$2y$10$1Fh9Voc5fGe54ENNWVgSLuqFwLq32jbaYM6utKLL37/oa6OdBaREm', 'testtestqw11232@mail.com', 'admin', 0, NULL, 0, 0.00, '2024-08-23 06:24:56', '2024-08-27 06:09:57'),
(64, 'jisun_lee', '$2y$10$Pq9.XV2Jk1diMgYk/6T3B.QHxnEdmsO5e2WDivReib1ehK4JjSDKO', '1223jsun@gmail.com', 'customer', 0, NULL, 1, 0.00, '2024-08-24 06:12:08', '2024-08-29 19:39:13'),
(65, 'test', '$2y$10$5IMg5N.MPov4LXtRj.Pmce.T.y1Vb/0pyit1qJmxE1NveMgidYszm', 'testtestqw112332@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-25 07:47:28', '2024-08-25 07:47:28'),
(66, 'test', '$2y$10$O6uv3PDhSOie2PN18Ps.qO//zzwV8opSDPMRfWpxuvB7bxg0i4zgu', 'testtestqw1`12332@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-25 08:22:20', '2024-08-26 06:24:05'),
(67, 'test', '$2y$10$oQowklqMAt1eai1oOX.47eJWCtI4OXrX5PkX.KGIeM8D1jjj2ABR2', 'testtestqw2212332@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-26 07:17:03', '2024-08-26 07:17:03'),
(68, 'test', '$2y$10$fcVJ7XkZWplni7GVKzwJqO2QfsqcndRooXTc4wUwObRNZrMYL7wiO', 'testtestqw2212332@mail.com1', 'customer', 0, NULL, 0, 0.00, '2024-08-26 07:21:10', '2024-08-26 07:21:10'),
(69, 'test', '$2y$10$L3NhZINNQrqABdpn.Dot3uZ1xbF4iIloBh8PNmZVH.zjf0i.tBwi6', 'test11qwe@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-26 07:25:58', '2024-08-26 07:25:58'),
(70, 'test', '$2y$10$Qhpf/6vqWlydeY3e16WMOuJd5oFU4L3l.uUB2hJksfjF4H/HEhqti', 'test11qwe5@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-26 07:43:29', '2024-08-26 07:43:29'),
(71, 'test__DELETED', '$2y$10$0Hgsm4HqiQuAehfK5C19OuKheFoUA2c5XAiXj2nev/1eMZcPTMrRG', 'testtestqw22123332@mail.com1', 'customer', 0, NULL, 0, 0.00, '2024-08-27 03:09:52', '2024-08-29 06:45:10'),
(72, 'test__DELETED', '$2y$10$QalMbNLR38oRSV.ZKrwKuuyg.z6wduvzayxe/FSXGYvJJxwoL4/x2', 'testtestqwd22123332@mail.com1', 'customer', 0, NULL, 0, 0.00, '2024-08-27 03:20:20', '2024-08-29 06:45:06'),
(73, 'jisun lee__DELETED', '$2y$10$dgJB766luZjLq.JeBTxZ9eCbJ2gm2smTBrowM3O5gjtbQokLjBhPq', 'js@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-27 05:58:03', '2024-08-28 17:55:16'),
(74, 'jisun lee__DELETED', '$2y$10$J7ItFiCfnAf2lHEEhfmEBu2jJVHc3iVDkHYBVMjumV075D0eQrFM6', '12jsun@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-27 05:59:44', '2024-08-28 14:40:04'),
(75, 'jisun lee__DELETED', '$2y$10$kI9br3cPOMBLYjE0MfVzDuu1sL0cBIUtUfJbHS1GtevRp78PPqU8a', '13jsun@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-27 06:34:51', '2024-08-28 17:55:51'),
(76, 'hi__DELETED', '$2y$10$T99peEf35IF5vtdqESwajOchescqzlFRjdhaNCmaifQnbDLmTfWfq', 'hi@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-28 17:27:57', '2024-08-29 07:06:59'),
(77, 'jisun', '$2y$10$tFWAtDinRWhy.zN6S1FRMegh.oJjCESBQtF.eNhIdduNsFjT5UQxm', 'js@mail.com', 'customer', 0, NULL, 0, 1000.00, '2024-08-29 07:21:13', '2024-08-29 07:21:23'),
(78, 'test', '$2y$10$s39Cf5oQe1DaaK5gkbQas.ypSPFGcFzFcZaMoS.7BPzU3vtD0Kuia', 'testte1stqwd22123332@mail.com1', 'staff', 0, NULL, 0, 0.00, '2024-08-29 19:10:55', '2024-08-29 19:10:55'),
(79, 'jsunny', '$2y$10$GvzZLqZk0HvqSskE3wmdL.OLAHUJ5XswnyITJwaFAwjGh5UUGR4M2', 'test50@mail.com', 'customer', 0, NULL, 2, 0.00, '2024-08-29 19:22:49', '2024-08-29 20:45:25'),
(80, 'lee', '$2y$10$r5Zppm1Cp4yeU6/QVHDp8eHuCkHY6Nhb5rMuHvnNO7j89S9Qr0soa', 'test@mail.xo', 'customer', 0, NULL, 1, 0.00, '2024-08-29 19:39:24', '2024-08-29 19:59:21'),
(81, 'jsunny__DELETED', '$2y$10$Yd.UwXS4qHfZ5QLsptg4hOWVaRBi6HHz3OUid2sNNL6h42ggGLAHW', 'test@dd.co', 'customer', 0, NULL, 2, 100.00, '2024-08-29 19:59:42', '2024-08-29 20:43:00'),
(82, '1223jsun@gmail.com', '$2y$10$9tiIFYcjDG6fmbHnnUlahuU7hhDun0OIckKjCddoKIaWrfmYnUZv6', 'test40@mail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-29 20:43:21', '2024-08-29 20:43:21'),
(83, 'tess', '$2y$10$sMxgwae04QfAo.rx.fyomeEuSaMwSFfd9SojNLV7IAfT0Xx/eXu9u', 'test56@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-29 20:44:46', '2024-08-29 20:44:46'),
(84, 'test', '$2y$10$QOxRztl8Hf6MQjop7N80q.3AxA7o3HSUjEbmCu7KtacfyNZ6IVcIe', 'test55@gmail.com', 'customer', 0, NULL, 0, 0.00, '2024-08-29 20:45:38', '2024-08-29 20:45:38');

-- --------------------------------------------------------

--
-- Table structure for table `Wallet_Transactions`
--

CREATE TABLE `Wallet_Transactions` (
  `transaction_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `transaction_type` enum('deposit','payment') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Wallet_Transactions`
--

INSERT INTO `Wallet_Transactions` (`transaction_id`, `user_id`, `transaction_type`, `amount`, `transaction_date`, `description`) VALUES
(1, 1, 'deposit', 150.50, '2024-08-15 01:22:58', 'Initial deposit'),
(2, 2, 'deposit', 2000.00, '2024-08-15 01:22:58', 'Salary deposit'),
(3, 3, 'payment', 50.00, '2024-08-15 01:22:58', 'Room booking payment'),
(4, 4, 'deposit', 100.00, '2024-08-15 01:22:58', 'Gift card'),
(5, 5, 'payment', 100.00, '2024-08-15 01:22:58', 'Service payment'),
(6, 1, 'deposit', 2000.00, '2024-08-22 06:50:33', 'charging'),
(7, 1, 'payment', 2000.00, '2024-08-22 07:06:57', 'charging'),
(8, 1, 'deposit', 2000.00, '2024-08-22 07:08:52', 'charging'),
(9, 1, 'deposit', 2000.00, '2024-08-22 07:09:42', 'charging'),
(10, 1, 'deposit', 2000.00, '2024-08-22 07:10:19', 'charging'),
(11, 1, 'deposit', 2000.00, '2024-08-22 07:11:40', 'charging'),
(12, 1, 'deposit', 2000.00, '2024-08-22 07:11:45', 'charging'),
(13, 1, 'deposit', 2000.00, '2024-08-22 07:11:51', 'charging'),
(14, 1, 'deposit', 2000.00, '2024-08-22 07:14:20', 'charging'),
(15, 1, 'deposit', 2000.00, '2024-08-22 07:14:48', 'charging'),
(16, 1, 'payment', 2000.00, '2024-08-22 07:15:21', 'charging'),
(17, 1, 'payment', 2000.00, '2024-08-22 07:15:43', 'charging'),
(18, 1, 'deposit', 2000.00, '2024-08-22 07:16:22', 'charging'),
(19, 1, 'deposit', 2000.00, '2024-08-22 17:54:50', 'charging'),
(20, 1, 'deposit', 2000.00, '2024-08-22 17:58:40', 'charging'),
(21, 1, 'deposit', 2000.00, '2024-08-25 08:19:55', 'charging'),
(22, 1, 'deposit', 2000.00, '2024-08-25 08:22:45', 'charging'),
(23, 1, 'deposit', 2000.00, '2024-08-25 08:22:59', 'charging'),
(24, 1, 'deposit', 2000.00, '2024-08-25 08:40:29', 'charging'),
(25, 1, 'deposit', 2000.00, '2024-08-25 08:41:02', 'charging'),
(26, 1, 'deposit', 2000.00, '2024-08-25 08:41:43', 'charging'),
(27, 1, 'deposit', 2000.00, '2024-08-25 08:44:16', 'charging'),
(28, 1, 'deposit', 2000.00, '2024-08-26 03:45:17', 'charging'),
(29, 1, 'deposit', 2000.00, '2024-08-27 08:12:57', 'charging'),
(30, 1, 'deposit', 2000.00, '2024-08-27 08:13:00', 'charging'),
(31, 1, 'deposit', 2000.00, '2024-08-27 08:14:37', 'charging'),
(32, 1, 'deposit', 2000.00, '2024-08-27 08:22:36', 'charging'),
(33, 1, 'deposit', 2000.00, '2024-08-27 08:29:09', 'charging'),
(34, 1, 'deposit', 2000.00, '2024-08-27 08:31:24', 'charging'),
(35, 1, 'deposit', 2000.00, '2024-08-27 08:31:42', 'charging'),
(36, 1, 'deposit', 2000.00, '2024-08-27 08:31:46', 'charging'),
(37, 50, 'deposit', 1000.00, '2024-08-27 08:32:30', 'charging'),
(38, 50, 'deposit', 110.00, '2024-08-27 08:32:44', 'charging'),
(39, 50, 'deposit', 1000.00, '2024-08-27 08:34:18', 'charging'),
(40, 1, 'deposit', 2000.00, '2024-08-27 09:01:13', 'charging'),
(41, 1, 'deposit', 2000.00, '2024-08-27 09:09:42', 'charging'),
(42, 50, 'deposit', 1000.00, '2024-08-27 09:16:35', 'test'),
(43, 50, 'deposit', 1000.00, '2024-08-27 09:19:33', 'test'),
(44, 50, 'deposit', 1000.00, '2024-08-27 09:21:09', 'test'),
(45, 50, 'deposit', 1000.00, '2024-08-27 09:21:18', 'test'),
(46, 50, 'deposit', 1000.00, '2024-08-27 09:21:44', 'test'),
(47, 50, 'deposit', 100.00, '2024-08-27 09:22:33', 'test'),
(48, 50, 'deposit', 1000.00, '2024-08-27 09:23:02', 'test'),
(49, 50, 'deposit', 1000.00, '2024-08-27 09:24:49', 'test'),
(50, 50, 'deposit', 1000.00, '2024-08-27 09:25:20', 'test'),
(51, 50, 'deposit', 1110.00, '2024-08-27 09:25:28', 'test'),
(52, 50, 'deposit', 2000.00, '2024-08-27 09:26:10', 'test'),
(53, 50, 'deposit', 1000.00, '2024-08-27 09:31:39', 'test'),
(54, 50, 'deposit', 1000.00, '2024-08-27 09:31:46', 'test'),
(55, 50, 'deposit', 1000.00, '2024-08-27 09:31:47', 'test'),
(56, 50, 'deposit', 1000.00, '2024-08-27 09:31:58', 'test'),
(57, 50, 'deposit', 1000.00, '2024-08-27 09:31:59', 'test'),
(58, 50, 'deposit', 1000.00, '2024-08-27 09:31:59', 'test'),
(59, 50, 'deposit', 1000.00, '2024-08-27 09:31:59', 'test'),
(60, 50, 'deposit', 1000.00, '2024-08-27 09:31:59', 'test'),
(61, 50, 'deposit', 1000.00, '2024-08-27 09:31:59', 'test'),
(62, 50, 'deposit', 1000.00, '2024-08-27 09:32:46', 'test'),
(63, 50, 'deposit', 1000.00, '2024-08-27 09:33:37', 'test'),
(64, 50, 'deposit', 1000.00, '2024-08-27 09:45:26', 'test'),
(65, 50, 'deposit', 1000.00, '2024-08-27 09:50:41', 'test'),
(66, 77, 'deposit', 1000.00, '2024-08-29 07:21:23', 'test'),
(67, 81, 'deposit', 100.00, '2024-08-29 20:09:08', 'test'),
(68, 81, 'deposit', 0.00, '2024-08-29 20:09:41', 'test'),
(69, 81, 'deposit', 0.00, '2024-08-29 20:42:08', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Audit_Logs`
--
ALTER TABLE `Audit_Logs`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Bookings`
--
ALTER TABLE `Bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `Booking_Services`
--
ALTER TABLE `Booking_Services`
  ADD PRIMARY KEY (`booking_service_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `Rooms`
--
ALTER TABLE `Rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `Services`
--
ALTER TABLE `Services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Wallet_Transactions`
--
ALTER TABLE `Wallet_Transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Audit_Logs`
--
ALTER TABLE `Audit_Logs`
  MODIFY `audit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Bookings`
--
ALTER TABLE `Bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `Booking_Services`
--
ALTER TABLE `Booking_Services`
  MODIFY `booking_service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Rooms`
--
ALTER TABLE `Rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Services`
--
ALTER TABLE `Services`
  MODIFY `service_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `Wallet_Transactions`
--
ALTER TABLE `Wallet_Transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Audit_Logs`
--
ALTER TABLE `Audit_Logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Bookings`
--
ALTER TABLE `Bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `Rooms` (`room_id`);

--
-- Constraints for table `Booking_Services`
--
ALTER TABLE `Booking_Services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `Bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `Services` (`service_id`);

--
-- Constraints for table `Comments`
--
ALTER TABLE `Comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `Rooms` (`room_id`);

--
-- Constraints for table `Wallet_Transactions`
--
ALTER TABLE `Wallet_Transactions`
  ADD CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
