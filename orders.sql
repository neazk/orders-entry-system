-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2021 at 05:24 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orders`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `cname` varchar(255) NOT NULL,
  `caddress` varchar(255) NOT NULL,
  `providence` varchar(32) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `phone2` varchar(20) NOT NULL,
  `cdate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `odate` date NOT NULL DEFAULT current_timestamp(),
  `oprint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `delivery_cost` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `pdisable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `delivery_cost`, `quantity`, `pdisable`) VALUES
(1, 'دريل لقمه', 145000, 5000, 0, 1),
(2, 'قاصة هامبورك', 40000, 5000, 0, 1),
(3, 'ماطور باركسايد', 80000, 5000, 0, 0),
(4, 'دريل ديوالت ص', 35000, 5000, 0, 0),
(5, 'دريل دي والت كبير', 55000, 5000, 0, 1),
(6, 'دريل ديوالت اصلي', 70000, 5000, 0, 1),
(7, 'ثلاجة ق', 30000, 5000, 0, 0),
(8, 'مكينة لحيم هامبورغ', 80000, 5000, 0, 1),
(9, 'مسدس صبغ هامبورك', 30000, 5000, 0, 0),
(10, 'دريل ماكيتا كبير', 55000, 5000, 0, 1),
(11, 'مكينة لحيم بريمن', 75000, 5000, 0, 0),
(12, 'عدة راجز', 49000, 6000, 0, 1),
(13, 'قاصة بريمن', 35000, 5000, 0, 0),
(14, 'دريل صغير ماكيتا', 40000, 5000, 0, 1),
(15, 'كوسرة سنفرة', 15000, 5000, 0, 0),
(16, 'كوسرة ثابتة', 39000, 6000, 0, 1),
(17, 'ماطور بريمن', 75000, 5000, 0, 0),
(18, 'كوسرة ثابتة+فرطونة', 60000, 5000, 0, 1),
(19, 'مكنسة كهربائية بريمن', 80000, 5000, 0, 0),
(20, 'دريل كوسرة', 35000, 5000, 0, 1),
(21, 'دريل بريمن كهربائي', 25000, 5000, 0, 0),
(22, 'دريل وكوسرة بريمن', 45000, 5000, 0, 0),
(23, 'ثلاجة', 35000, 5000, 0, 1),
(24, 'مضخة ماء', 150000, 5000, 0, 1),
(25, 'قاصة كبيرة', 70000, 5000, 0, 1),
(26, 'مسدس صبغ', 35000, 0, 0, 1),
(27, 'مكنسة كهربائية بريمن.', 95000, 5000, 0, 1),
(28, 'كوسرة شحن ديوالت', 145000, 5000, 0, 1),
(29, 'مكينة لحيم كبيرة', 95000, 5000, 0, 1),
(30, 'معصارة فواكه', 35000, 5000, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_ibfk_1` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_ibfk_1` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
