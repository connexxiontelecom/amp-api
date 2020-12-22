-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2020 at 08:31 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=active; 0=inactive',
  `since` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `firstname`, `lastname`, `username`, `password`, `status`, `since`) VALUES
(1, 'Super', 'Admin', 'admin', '$2y$12$yyENyeIRx0J3vC9R8QfYpeSCxc/i1vpPiFVfT3u0Nb.EvS6GFdAbq', 1, '2020-10-07 15:13:22'),
(3, 'Test', 'User', 'testuser', '$2y$10$6Xnw.24AqwMq6A8R3abWQ.dtNhQGAD.JwRYFo4CIzhoKHSllVR81W', 1, '2020-10-08 15:39:24'),
(4, 'Chijoke', 'Amanambu', 'cj', '$2y$10$WeScZfbKIEwgX6K0viFZIOdVlPCxuR7D2pWYRwL/ELJRLd/wSZr7m', 0, '2020-10-08 16:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `admin_log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `event` text NOT NULL,
  `detail` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_log`
--

INSERT INTO `admin_log` (`admin_log_id`, `admin_id`, `event`, `detail`, `created_at`) VALUES
(1, 1, 'logout', 'success', '2020-10-08 13:50:05'),
(2, 1, 'login', 'success', '2020-10-08 13:50:28'),
(3, 1, 'logout', 'success', '2020-10-08 13:50:33'),
(4, 1, 'login', 'success', '2020-10-08 13:50:36'),
(5, 1, 'logout', 'success', '2020-10-08 14:15:59'),
(6, 1, 'logout', 'success', '2020-10-08 14:17:28'),
(7, 1, 'login', 'success', '2020-10-08 14:17:31'),
(8, 1, 'logout', 'success', '2020-10-08 14:23:32'),
(9, 1, 'login', 'success', '2020-10-08 14:23:38'),
(10, 1, 'logout', 'success', '2020-10-08 14:24:43'),
(11, 1, 'login', 'success', '2020-10-08 14:24:46'),
(12, 1, 'logout', 'success', '2020-10-08 14:27:16'),
(13, 1, 'login', 'success', '2020-10-08 14:27:20'),
(14, 1, 'logout', 'success', '2020-10-08 14:27:35'),
(15, 1, 'login', 'success', '2020-10-08 14:27:38'),
(16, 1, 'logout', 'success', '2020-10-08 15:47:19'),
(17, 3, 'login', 'success', '2020-10-08 15:47:28'),
(18, 3, 'logout', 'success', '2020-10-08 15:47:48'),
(19, 1, 'login', 'success', '2020-10-08 15:47:53'),
(20, 1, 'add_admin', 'failed to add testuser', '2020-10-08 15:54:05'),
(21, 1, 'add_admin', 'failed to add admin', '2020-10-08 15:55:07'),
(22, 1, 'logout', 'success', '2020-10-08 15:57:22'),
(23, 1, 'login', 'success', '2020-10-08 15:57:25'),
(24, 1, 'add_admin', 'successfully added cj', '2020-10-08 16:05:34'),
(25, 1, 'logout', 'success', '2020-10-08 16:05:41'),
(26, 1, 'login', 'success', '2020-10-08 16:05:58'),
(27, 1, 'logout', 'success', '2020-10-08 16:07:02'),
(28, 1, 'login', 'success', '2020-10-12 12:16:27'),
(29, 1, 'add_admin', 'failed to add admin', '2020-10-12 12:16:42'),
(30, 1, 'update_admin', 'updated cj', '2020-10-12 13:24:07'),
(31, 1, 'update_admin', 'updated cj', '2020-10-12 13:24:25'),
(32, 1, 'logout', 'success', '2020-10-12 13:34:13'),
(33, 1, 'login', 'success', '2020-10-12 13:35:44'),
(34, 1, 'logout', 'success', '2020-10-12 13:44:12'),
(35, 1, 'login', 'success', '2020-10-12 14:00:08'),
(36, 1, 'logout', 'success', '2020-10-12 14:43:36'),
(37, 1, 'login', 'success', '2020-10-12 14:43:55'),
(38, 1, 'login', 'success', '2020-10-13 12:01:02'),
(39, 1, 'logout', 'success', '2020-10-13 12:47:09'),
(40, 1, 'login', 'success', '2020-10-13 12:47:32'),
(41, 1, 'logout', 'success', '2020-10-13 12:47:36'),
(42, 1, 'login', 'success', '2020-10-13 12:50:24'),
(43, 1, 'logout', 'success', '2020-10-13 13:03:13'),
(44, 1, 'login', 'success', '2020-10-13 13:03:30'),
(45, 1, 'add_product', 'failed to add product', '2020-10-13 15:42:43'),
(46, 1, 'add_product', 'successfully added Test Product', '2020-10-13 15:42:43'),
(47, 1, 'add_product', 'failed to add product', '2020-10-13 15:44:55'),
(48, 1, 'add_product', 'failed to add product', '2020-10-13 15:50:54'),
(49, 1, 'add_product', 'failed to add Test Product', '2020-10-13 15:50:54'),
(50, 1, 'login', 'success', '2020-10-16 13:21:23'),
(51, 1, 'login', 'success', '2020-10-19 17:10:25'),
(52, 1, 'login', 'success', '2020-10-20 06:51:24'),
(53, 1, 'add_product', 'failed to add product', '2020-10-20 11:46:36'),
(54, 1, 'add_product', 'failed to add product', '2020-10-20 12:11:22'),
(55, 1, 'add_product', 'failed to add Test Product', '2020-10-20 12:11:22'),
(56, 1, 'add_product', 'failed to add product', '2020-10-20 12:12:18'),
(57, 1, 'add_product', 'successfully added Test Product 2', '2020-10-20 12:12:18'),
(58, 1, 'add_product', 'failed to add product', '2020-10-20 12:39:03'),
(59, 1, 'add_product', 'failed to add Test Product 2', '2020-10-20 12:39:03'),
(60, 1, 'add_product', 'failed to add product', '2020-10-20 12:39:20'),
(61, 1, 'add_product', 'successfully added Test Product2', '2020-10-20 12:39:20'),
(62, 1, 'add_product', 'failed to add product', '2020-10-20 12:53:11'),
(63, 1, 'add_product', 'failed to add Test Product2', '2020-10-20 12:53:11'),
(64, 1, 'add_product', 'failed to add product', '2020-10-20 12:53:40'),
(65, 1, 'add_product', 'failed to add Test Product2', '2020-10-20 12:53:40'),
(66, 1, 'add_product', 'failed to add product', '2020-10-20 13:18:07'),
(67, 1, 'add_product', 'successfully added Test Product 3', '2020-10-20 13:18:07'),
(68, 1, 'add_product', 'failed to add product', '2020-10-20 13:19:38'),
(69, 1, 'add_product', 'successfully added Test Product 4', '2020-10-20 13:19:38'),
(70, 1, 'login', 'success', '2020-10-21 07:49:43'),
(71, 1, 'login', 'success', '2020-10-22 07:48:58'),
(72, 1, 'add_product', 'failed to add product', '2020-10-22 08:05:28'),
(73, 1, 'add_product', 'successfully added Test Product 5', '2020-10-22 08:05:28'),
(74, 1, 'login', 'success', '2020-10-23 08:14:39'),
(75, 1, 'login', 'success', '2020-10-26 07:55:15'),
(76, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:08:19'),
(77, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:08:57'),
(78, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:11:25'),
(79, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:11:58'),
(80, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:14:14'),
(81, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:26:31'),
(82, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:26:57'),
(83, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:28:05'),
(84, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:30:55'),
(85, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:31:23'),
(86, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:35:33'),
(87, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:36:08'),
(88, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:37:45'),
(89, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:39:09'),
(90, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:39:25'),
(91, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:39:48'),
(92, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:43:11'),
(93, 1, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:43:59'),
(94, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:45:30'),
(95, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:46:14'),
(96, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:53:44'),
(97, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:54:50'),
(98, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:01'),
(99, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:28'),
(100, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:35'),
(101, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:50'),
(102, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:58:15'),
(103, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:59:04'),
(104, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 11:00:53'),
(105, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 11:05:07'),
(106, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:25:04'),
(107, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:26:07'),
(108, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:27:40'),
(109, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:27:50'),
(110, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:29:58'),
(111, 1, 'update_product', 'successfully updated product plan', '2020-10-26 13:35:46'),
(112, 1, 'update_product', 'successfully updated product plan', '2020-10-26 13:35:58'),
(113, 1, 'update_product', 'successfully updated product plan', '2020-10-26 13:36:18'),
(114, 1, 'add_product', 'failed to add Test Product in use', '2020-10-26 13:47:23'),
(115, 1, 'logout', 'success', '2020-10-26 14:03:35'),
(116, 1, 'login', 'success', '2020-10-26 14:03:50'),
(117, 1, 'update_product', 'successfully updated product plan', '2020-10-26 14:05:15'),
(118, 1, 'login', 'success', '2020-10-27 08:09:58'),
(119, 1, 'logout', 'success', '2020-10-27 08:10:47'),
(120, 1, 'login', 'success', '2020-10-27 12:37:36'),
(121, 1, 'logout', 'success', '2020-10-27 12:37:40'),
(122, 1, 'login', 'success', '2020-10-27 14:17:01'),
(123, 1, 'logout', 'success', '2020-10-27 14:30:52'),
(124, 1, 'login', 'success', '2020-10-28 07:56:24'),
(125, 1, 'logout', 'success', '2020-10-28 10:00:41'),
(126, 1, 'login', 'success', '2020-10-28 13:50:55'),
(127, 1, 'logout', 'success', '2020-10-28 13:50:59'),
(128, 1, 'login', 'success', '2020-10-28 14:11:20'),
(129, 1, 'logout', 'success', '2020-10-28 14:11:58'),
(130, 1, 'login', 'success', '2020-11-02 14:14:32'),
(131, 1, 'logout', 'success', '2020-11-02 14:31:38'),
(132, 1, 'login', 'success', '2020-11-02 14:41:31'),
(133, 1, 'logout', 'success', '2020-11-02 14:50:14'),
(134, 1, 'login', 'success', '2020-11-02 15:00:56'),
(135, 1, 'logout', 'success', '2020-11-02 15:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate`
--

CREATE TABLE `affiliate` (
  `affiliate_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `ref_code` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0 = inactive; 1 = active; 2 = pending',
  `profile` int(11) NOT NULL DEFAULT 0 COMMENT '0 = incomplete; 1 = complete',
  `upstream_affiliate_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `affiliate`
--

INSERT INTO `affiliate` (`affiliate_id`, `username`, `email`, `password`, `firstname`, `lastname`, `ref_code`, `status`, `profile`, `upstream_affiliate_id`, `created_at`) VALUES
(1, 'cjamanambu', 'oamanambu@yahoo.com', '$2y$10$a1PmgPQNjx3I2gJwo9T5f.Mh3cTOB2FJbBatplsc8LhuvKQVqn25C', 'Onyedika', 'Amanambu', 'vTzmNpXv4F', 1, 0, 0, '2020-12-21 07:08:20');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_info`
--

CREATE TABLE `affiliate_info` (
  `affiliate_info_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `phone` text NOT NULL,
  `dob` date NOT NULL,
  `company` text NOT NULL,
  `website` text NOT NULL,
  `gender` text NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `country` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `agent_log`
--

CREATE TABLE `agent_log` (
  `agent_log_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `event` text NOT NULL,
  `detail` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agent_log`
--

INSERT INTO `agent_log` (`agent_log_id`, `agent_id`, `event`, `detail`, `created_at`) VALUES
(1, 1, 'register', 0, '2020-10-27 13:35:53'),
(2, 1, 'register', 0, '2020-10-27 13:37:47'),
(3, 4, 'register', 0, '2020-10-27 14:05:50'),
(4, 0, 'login', 0, '2020-10-28 12:31:33'),
(5, 5, 'register', 0, '2020-10-28 12:35:34'),
(6, 0, 'login', 0, '2020-10-28 12:36:22'),
(7, 0, 'login', 0, '2020-10-28 12:38:03'),
(8, 0, 'logout', 0, '2020-10-28 12:38:39'),
(9, 5, 'login', 0, '2020-10-28 13:00:10'),
(10, 5, 'logout', 0, '2020-10-28 13:40:48'),
(11, 5, 'login', 0, '2020-10-28 13:40:55'),
(12, 5, 'logout', 0, '2020-10-28 13:44:11'),
(13, 5, 'login', 0, '2020-10-28 13:44:20'),
(14, 5, 'logout', 0, '2020-10-28 13:50:51'),
(15, 5, 'login', 0, '2020-10-28 13:51:08'),
(16, 5, 'logout', 0, '2020-10-28 14:04:25'),
(17, 5, 'login', 0, '2020-10-28 14:05:47'),
(18, 5, 'logout', 0, '2020-10-28 14:11:14'),
(19, 5, 'login', 0, '2020-10-28 14:12:04'),
(20, 5, 'logout', 0, '2020-10-28 14:14:06'),
(21, 5, 'login', 0, '2020-10-28 14:19:32'),
(22, 5, 'login', 0, '2020-11-02 14:14:10'),
(23, 5, 'logout', 0, '2020-11-02 14:14:20'),
(24, 5, 'login', 0, '2020-11-02 14:32:08'),
(25, 5, 'logout', 0, '2020-11-02 14:41:22'),
(26, 5, 'login', 0, '2020-11-02 14:50:20'),
(27, 5, 'logout', 0, '2020-11-02 15:00:51'),
(28, 5, 'login', 0, '2020-11-02 15:11:29'),
(29, 5, 'logout', 0, '2020-11-02 15:11:39'),
(30, 5, 'login', 0, '2020-11-02 15:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `bank_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `bank_name` text NOT NULL,
  `bank_acc_name` text NOT NULL,
  `bank_acc_number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `commission_id` int(11) NOT NULL,
  `num_gens` int(11) NOT NULL,
  `current_gen` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 == true; 0 == false',
  `gen_1` text NOT NULL,
  `gen_2` text NOT NULL,
  `gen_3` text NOT NULL,
  `gen_4` text NOT NULL,
  `gen_5` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `commission`
--

INSERT INTO `commission` (`commission_id`, `num_gens`, `current_gen`, `gen_1`, `gen_2`, `gen_3`, `gen_4`, `gen_5`) VALUES
(1, 1, 1, '60', '40', '0', '0', '0'),
(2, 2, 0, '50', '30', '20', '0', '0'),
(3, 3, 0, '40', '30', '20', '10', '0'),
(4, 4, 0, '30', '20', '18', '17', '15');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `url` text DEFAULT NULL,
  `category` text NOT NULL,
  `description` text NOT NULL,
  `num_plans` int(11) NOT NULL,
  `logo` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '0=unpublished; 1=published\r\n',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `url`, `category`, `description`, `num_plans`, `logo`, `status`, `created_at`) VALUES
(1, 'ihumane', 'https://ihumane.net', 'Software', '', 3, '', 1, '2020-12-16 10:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_log`
--

CREATE TABLE `product_log` (
  `product_log_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `event` text NOT NULL,
  `detail` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_log`
--

INSERT INTO `product_log` (`product_log_id`, `product_id`, `event`, `detail`, `created_at`) VALUES
(1, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:08:19'),
(2, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:08:57'),
(3, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:11:25'),
(4, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:11:58'),
(5, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:14:14'),
(6, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:26:31'),
(7, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:26:57'),
(8, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:28:05'),
(9, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:30:55'),
(10, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:31:23'),
(11, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:35:33'),
(12, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:36:08'),
(13, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:37:45'),
(14, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:39:09'),
(15, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:39:25'),
(16, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:39:48'),
(17, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:43:11'),
(18, 2, 'update_product', 'successfully updated Test Product 2', '2020-10-26 10:43:59'),
(19, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:45:30'),
(20, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:46:14'),
(21, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:53:45'),
(22, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:54:50'),
(23, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:01'),
(24, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:28'),
(25, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:35'),
(26, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:57:50'),
(27, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:58:15'),
(28, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 10:59:04'),
(29, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 11:00:53'),
(30, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 11:05:07'),
(31, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:22:54'),
(32, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:23:36'),
(33, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:25:04'),
(34, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:25:09'),
(35, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:26:07'),
(36, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:26:12'),
(37, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:27:40'),
(38, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:27:50'),
(39, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:27:56'),
(40, 1, 'update_product', 'successfully updated Test Product', '2020-10-26 12:29:58'),
(41, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:30:04'),
(42, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:32:46'),
(43, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:33:15'),
(44, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:34:28'),
(45, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:34:55'),
(46, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 12:35:03'),
(47, 1, 'update_product', 'successfully updated product plan', '2020-10-26 13:35:46'),
(48, 1, 'update_product', 'successfully updated product plan', '2020-10-26 13:35:58'),
(49, 1, 'update_product', 'successfully updated product plan', '2020-10-26 13:36:18'),
(50, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-26 13:36:48'),
(51, 4, 'update_product', 'successfully updated product plan', '2020-10-26 14:05:15'),
(52, 1, 'delete_plan', 'successfully deleted product plan', '2020-10-27 08:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_plan`
--

CREATE TABLE `product_plan` (
  `product_plan_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `plan_name` text NOT NULL,
  `plan_price` text NOT NULL,
  `plan_link` text NOT NULL,
  `plan_commission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_plan`
--

INSERT INTO `product_plan` (`product_plan_id`, `product_id`, `plan_name`, `plan_price`, `plan_link`, `plan_commission`) VALUES
(2, 1, 'Free Trial', '0', 'http://ihumane.com/free-trial', '0'),
(3, 1, 'Free Trial', '0', 'http://ihumane.com/free-trial/2', '0'),
(4, 1, 'Basic', '20000', 'http://ihumane.com/basic', '2000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`admin_log_id`);

--
-- Indexes for table `affiliate`
--
ALTER TABLE `affiliate`
  ADD PRIMARY KEY (`affiliate_id`);

--
-- Indexes for table `affiliate_info`
--
ALTER TABLE `affiliate_info`
  ADD PRIMARY KEY (`affiliate_info_id`);

--
-- Indexes for table `agent_log`
--
ALTER TABLE `agent_log`
  ADD PRIMARY KEY (`agent_log_id`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`commission_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_log`
--
ALTER TABLE `product_log`
  ADD PRIMARY KEY (`product_log_id`);

--
-- Indexes for table `product_plan`
--
ALTER TABLE `product_plan`
  ADD PRIMARY KEY (`product_plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `admin_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `affiliate`
--
ALTER TABLE `affiliate`
  MODIFY `affiliate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `affiliate_info`
--
ALTER TABLE `affiliate_info`
  MODIFY `affiliate_info_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_log`
--
ALTER TABLE `agent_log`
  MODIFY `agent_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `commission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_log`
--
ALTER TABLE `product_log`
  MODIFY `product_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_plan`
--
ALTER TABLE `product_plan`
  MODIFY `product_plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
