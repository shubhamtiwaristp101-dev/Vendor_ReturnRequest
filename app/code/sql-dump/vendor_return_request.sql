-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 20, 2025 at 02:20 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m_commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `vendor_return_request`
--

CREATE TABLE `vendor_return_request` (
  `return_id` int UNSIGNED NOT NULL COMMENT 'Return ID',
  `order_id` varchar(255) NOT NULL COMMENT 'Order ID',
  `customer_id` int UNSIGNED NOT NULL COMMENT 'Customer ID',
  `reason` varchar(255) NOT NULL COMMENT 'Return Reason',
  `description` text COMMENT 'Description',
  `image` varchar(255) DEFAULT NULL COMMENT 'Image Path',
  `status` varchar(32) NOT NULL DEFAULT 'new' COMMENT 'Status',
  `date_of_request` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date of request',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Time',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Modification Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Return Request Table';

--
-- Dumping data for table `vendor_return_request`
--

INSERT INTO `vendor_return_request` (`return_id`, `order_id`, `customer_id`, `reason`, `description`, `image`, `status`, `date_of_request`, `creation_time`, `update_time`) VALUES
(2, '000000001', 1, 'damaged', 'product damaged', 'adobe-commerce.png', 'approved', '2025-07-20 10:09:12', '2025-07-20 10:09:12', '2025-07-20 10:09:12'),
(3, '000000006', 1, 'wrong_item', 'wrong item delivered', 'adobe-commerce_1.png', 'new', '2025-07-20 13:44:16', '2025-07-20 13:44:16', '2025-07-20 13:44:16'),
(4, '000000007', 1, 'not_satisfied', 'not satisfied', 'adobe-commerce_2.png', 'rejected', '2025-07-20 13:58:23', '2025-07-20 13:58:23', '2025-07-20 13:58:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vendor_return_request`
--
ALTER TABLE `vendor_return_request`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `VENDOR_RETURN_REQUEST_ORDER_ID` (`order_id`),
  ADD KEY `VENDOR_RETURN_REQUEST_CUSTOMER_ID` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vendor_return_request`
--
ALTER TABLE `vendor_return_request`
  MODIFY `return_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Return ID', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
