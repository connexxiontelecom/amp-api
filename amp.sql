-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2021 at 11:44 AM
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
  `roles` text NOT NULL,
  `since` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `firstname`, `lastname`, `username`, `password`, `status`, `roles`, `since`) VALUES
(1, 'Super', 'Admin', 'admin', '$2y$12$yyENyeIRx0J3vC9R8QfYpeSCxc/i1vpPiFVfT3u0Nb.EvS6GFdAbq', 1, 's:167:\"adm_r,com_r,aff_r,pro_r,pay_r,log_r,prd_w,adm_w,com_w,aff_w,pro_w,pay_w,log_w,prd_c,adm_c,com_c,aff_c,pro_c,pay_c,log_c,prd_d,adm_d,com_d,aff_d,pro_d,pay_d,log_d,prd_r\";', '2020-10-07 15:13:22'),
(3, 'Test', 'User', 'testuser', '$2y$10$6Xnw.24AqwMq6A8R3abWQ.dtNhQGAD.JwRYFo4CIzhoKHSllVR81W', 1, 's:108:\",prd_r,prd_w,prd_c,prd_d,adm_r,com_r,aff_r,pro_r,pay_r,log_r,adm_w,adm_c,adm_d,com_w,aff_w,pro_w,pay_w,log_w\";', '2020-10-08 15:39:24'),
(4, 'Chijoke', 'Amanambu', 'cj', '$2y$10$WeScZfbKIEwgX6K0viFZIOdVlPCxuR7D2pWYRwL/ELJRLd/wSZr7m', 0, 's:0:\"\";', '2020-10-08 16:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `admin_log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `type` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_log`
--

INSERT INTO `admin_log` (`admin_log_id`, `admin_id`, `type`, `title`, `description`, `created_at`) VALUES
(136, 1, 'login', 'Successful Login', 'admin successfully logged into the system', '2021-02-09 14:54:55');

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
  `verify_code` text NOT NULL,
  `profile` int(11) NOT NULL DEFAULT 0 COMMENT '0 = incomplete; 1 = complete',
  `upstream_affiliate_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `affiliate`
--

INSERT INTO `affiliate` (`affiliate_id`, `username`, `email`, `password`, `firstname`, `lastname`, `ref_code`, `status`, `verify_code`, `profile`, `upstream_affiliate_id`, `created_at`) VALUES
(1, 'cjamanambu', 'cjamanambu@yahoo.com', '$2y$10$QSIq35v3/Xccqy8ONdC7PeaTN.IySKElNER9OYO0ettoEwkffZ.L.', 'Onyedika', 'Amanambu', 'vTzmNpXv4F', 1, '', 0, 0, '2020-12-21 07:08:20'),
(2, 'testaffiliate', 'testaffiliate@email.com', '$2y$10$6UMxYCVY9rYfa1lLjR1Sfufm1GZzf8jlMEsM5QhiENp7pxKC7acpS', 'Test', 'Affiliate', 'JBNi2MNjgz', 1, '', 0, 3, '2020-12-22 14:11:03'),
(3, 'testaffiliate2', 'testaffiliate@test.com', '$2y$10$ZvxJW2Q3nJQ9racsUIuIBuzPrEGtZhNPAX3jHDqTMmCa02rMpxJ.y', 'Test', 'Affiliate', '71WPR0lv47', 1, '', 0, 1, '2021-01-09 11:50:16'),
(4, 'affiliate', 'affiliate@yahoo.com', '$2y$10$yuvtuZ717ijDblRTJwE7AObeiibDQu9OU1z6EsI5H94fcIkBqmJa.', 'Onyedika', 'Amanambu', 'tjgsvJsnYa', 1, '', 0, 1, '2021-01-19 14:03:34'),
(5, 'johndoe', 'johndoe@email.com', '$2y$10$9VgYojRcDjlpZduN1tqd0OgZStEkxHRx/mGdfsZoH6iBiZfnDKR6q', 'John', 'Doe', 'QMPiW08wsl', 1, '', 0, 0, '2021-01-21 16:07:01'),
(28, 'oamanambu', 'oamanambu@yahoo.com', '$2y$10$PxkFGnjQP119Wrrclpmn5OXHtp/BTuKY.RjZpYGHuRIDVk7w3J6Py', 'Onyedika', 'Amanambu', 'zMKWLBaeJ6', 1, 'tw8Ox98CoK', 0, 0, '2021-01-28 14:33:32');

-- --------------------------------------------------------

--
-- Table structure for table `affiliate_info`
--

CREATE TABLE `affiliate_info` (
  `affiliate_info_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `phone` text DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `affiliate_info`
--

INSERT INTO `affiliate_info` (`affiliate_info_id`, `affiliate_id`, `phone`, `dob`, `gender`, `address`, `country`) VALUES
(1, 2, '09096000024', '1993-12-23', 'male', 'Number 3, Misratah Street, Wuse 2 Abuja', 'Nigeria'),
(2, 1, '07068721317', '1993-12-23', 'female', 'Number 3 Misratah Street, Wuse 2, Abuja', 'Republic of Nigeria'),
(3, 3, '08037044888', '1983-06-15', 'female', '2A Iller Crescent, Off Katsina Ala, Maitama', 'Nigeria'),
(4, 3, '08037044888', '1983-06-15', 'male', '2A Iller Crescent, Off Katsina Ala, Maitama', 'Nigeria'),
(5, 3, '08037044888', '1983-06-15', 'male', '2A Iller Crescent, Off Katsina Ala, Maitama', 'Nigeria'),
(6, 5, '2045586316', '2021-01-22', 'male', '120 Dafoe Road West', 'Canada');

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

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`bank_id`, `affiliate_id`, `bank_name`, `bank_acc_name`, `bank_acc_number`) VALUES
(1, 2, 'Zenith Bank', 'Onyedika Amanambu', '2008107965'),
(2, 1, 'Zenith Bank', 'Onyedika Amanambu', '20081079656'),
(3, 3, 'Zenith Bank', 'Onyedikachukwu Amanambu', '2008107965'),
(4, 3, 'Zenith Bank', 'Onyedikachukwu Chijioke Amanambu', '2008107965');

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
(2, 2, 0, '50', '35', '15', '0', '0'),
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
  `num_plans` int(11) NOT NULL DEFAULT 0,
  `logo` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0=unpublished; 1=published\r\n',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `url`, `category`, `description`, `num_plans`, `logo`, `status`, `created_at`) VALUES
(12, 'ihumane', 'https://ihumane.net', 'Software', '', 1, 'ihumane-logo-2.png', 1, '2021-01-27 10:36:26');

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
  `plan_commission` text NOT NULL,
  `plan_slug` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_plan`
--

INSERT INTO `product_plan` (`product_plan_id`, `product_id`, `plan_name`, `plan_price`, `plan_link`, `plan_commission`, `plan_slug`) VALUES
(11, 12, 'Basic', '20000', 'https://ihumane.net/basic', '2500', 'def4245f');

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `admin_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `affiliate`
--
ALTER TABLE `affiliate`
  MODIFY `affiliate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `affiliate_info`
--
ALTER TABLE `affiliate_info`
  MODIFY `affiliate_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `agent_log`
--
ALTER TABLE `agent_log`
  MODIFY `agent_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `commission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_log`
--
ALTER TABLE `product_log`
  MODIFY `product_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_plan`
--
ALTER TABLE `product_plan`
  MODIFY `product_plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
