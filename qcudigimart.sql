-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 06:39 AM
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
-- Database: `qcudigimart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `a_email` varchar(255) NOT NULL,
  `a_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_email`, `a_password`) VALUES
(1, 'admin@gmail.com', '$2y$10$xRRUQfETQkYyCtWJDZxsi.ZXXzcFhHtbCuym7YqIw/So5Gb8hGMCe');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Food'),
(2, 'Jewelry'),
(3, 'Vegetable'),
(4, 'Shoes'),
(5, 'Coffee');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `suspend_until` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `profile_image`, `email`, `password`, `date_created`, `status`, `suspend_until`) VALUES
(33, 'Howard', 'Jones', '', 'jones@gmail.com', '$2y$10$PfHZvlzkBUvwBryvDEUKcuvBB6Ty1FhVq.tEE49B68/nBc.0/9eqa', '2024-11-18 11:04:12.080805', 'active', '0000-00-00'),
(38, 'Raffy', 'Elmedo', '../assets/user/67432efaa8547.jpg', 'elmedo@gmail.com', '$2y$10$QBtbkf0qEtNRePIFYMKhNu8a.aykU3ayUHaPN//.r1dDPHZWMWICu', '2024-11-18 21:13:12.561591', 'active', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('customer','seller') NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `m_content` text NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `sender_type`, `receiver_id`, `m_content`, `date_created`) VALUES
(192, 38, 'customer', 18, 'Hey', '2024-11-24 15:00:27'),
(193, 18, 'seller', 38, 'Eyy', '2024-11-24 15:00:48'),
(194, 18, 'seller', 38, 'HA', '2024-11-24 15:12:01'),
(195, 38, 'customer', 18, 'Sira', '2024-11-24 15:12:09'),
(196, 38, 'customer', 19, 'Hey', '2024-11-24 15:19:53'),
(197, 38, 'customer', 15, 'Hey', '2024-11-24 15:34:41'),
(198, 15, 'seller', 38, 'Yow', '2024-11-24 15:37:57'),
(199, 15, 'seller', 38, 'Shees', '2024-11-24 15:44:08'),
(200, 38, 'customer', 19, 'Ypw', '2024-11-24 15:46:46'),
(201, 38, 'customer', 15, 'HAHAHHAHAHA', '2024-11-24 15:50:14'),
(202, 38, 'customer', 15, 'HAHAH', '2024-11-26 04:48:38'),
(203, 38, 'customer', 19, 'Oyy oy', '2024-11-26 04:51:53'),
(204, 38, 'customer', 15, 'Bakt', '2024-11-26 04:52:13'),
(205, 15, 'seller', 38, 'Ewan gagi', '2024-11-26 04:52:19'),
(206, 38, 'customer', 18, 'Ey', '2024-11-26 04:59:24'),
(207, 15, 'seller', 38, 'HAHAH', '2024-11-26 05:02:55'),
(208, 38, 'customer', 15, 'Bkait', '2024-11-26 05:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `pending_products`
--

CREATE TABLE `pending_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `s_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_products`
--

INSERT INTO `pending_products` (`id`, `name`, `description`, `price`, `quantity`, `category_id`, `category`, `images`, `s_id`, `date_created`) VALUES
(11, 'Hotdog na may Burger', 'yfakfajsgsgafgasgjhfsagagflafblbasafjbibijoofaoibfsoibjfsboifabioafsiabasfiobfsaiobfasoibfasbiufabiibjsfaibjfsaibjfsaoijbfaoijbfasiobjafsijbasfjibsafjiasfjafsjasfjiasfijbasfibjafsibjasfibjasfijafsijbsfaijbasfijb', 20.00, 12, 1, 'Food', '[\"..\\/assets\\/products\\/empanada.jpg\",\"..\\/assets\\/products\\/burger.jpg\",\"..\\/assets\\/products\\/hotdog.jpg\"]', 10, '2024-11-17 09:52:26'),
(19, 'Random', 'ajfajfgkas,a a jasfajg faukjga bufkasjgfliauksf', 12.00, 21, 3, 'Vegetable', '[\"..\\/assets\\/products\\/burger.jpg\",\"..\\/assets\\/products\\/hotdog.jpg\",\"..\\/assets\\/products\\/dunk.jpg\"]', 16, '2024-11-20 07:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `pending_sellers`
--

CREATE TABLE `pending_sellers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `org_type` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `s_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `quantity`, `category_id`, `category`, `images`, `s_id`, `date_created`) VALUES
(12, 'Burger', 'agfljavsfjasbfamfiakf', 12.00, 19, 1, 'Food', '[\"..\\/assets\\/products\\/burger.jpg\"]', 15, '2024-11-18 23:05:57'),
(13, 'Carrot', 'asfasaglsakfa', 21.00, 3, 1, 'Food', '[\"..\\/assets\\/products\\/car.jpg\"]', 15, '2024-11-22 10:28:34'),
(14, 'Shoes Dunk', 'asjlbakgfiuasfa', 2000.00, 1, 4, 'Shoes', '[\"..\\/assets\\/products\\/dunk.jpg\"]', 15, '2024-11-22 10:28:39'),
(15, 'Sample', 'askfajsafba  ags,ua aga, jga', 12.00, 2, 2, 'Jewelry', '[\"..\\/assets\\/products\\/logo.png\"]', 15, '2024-11-22 10:28:44'),
(17, 'Chiririrog', 'absf as balab fakbs abasab asobfalfabfskajfba iawhthis asjiks a\r\n', 20.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/hotdog.jpg\"]', 18, '2024-11-24 21:59:18'),
(18, 'empanada', 'aksnfklasnglas laisnalkn asl asn a', 20.00, 12, 1, 'Food', '[\"..\\/assets\\/products\\/empanada.jpg\"]', 19, '2024-11-24 22:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `rejected_products`
--

CREATE TABLE `rejected_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `s_id` int(11) NOT NULL,
  `date_rejected` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rejected_products`
--

INSERT INTO `rejected_products` (`id`, `name`, `description`, `price`, `quantity`, `category_id`, `category`, `images`, `s_id`, `date_rejected`) VALUES
(1, '0', 'abf;aljkfbabf', 20.00, 20, 1, 'Food', '0', 7, '2024-11-13 20:33:36'),
(2, '0', 'abf;aljkfbabf', 20.00, 20, 1, 'Food', '0', 7, '2024-11-13 20:33:42'),
(3, 'Burger', 'abf;aljkfbabf', 20.00, 20, 1, 'Food', '0', 7, '2024-11-13 20:37:21'),
(4, 'nqwio1`', 'nqwlkpwquibflqwbfiqbifonq', 18.00, 1, 1, 'Food', '../assets/products/toma.jpg', 7, '2024-11-13 20:43:16'),
(5, 'Kemerut', 'kjafbakfjkasfpaskfbsaf', 10.00, 2, 3, 'Vegetable', '[\"..\\/assets\\/products\\/fl1.jpg\",\"..\\/assets\\/products\\/coop2.jpg\"]', 13, '2024-11-18 21:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `rejected_sellers`
--

CREATE TABLE `rejected_sellers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_rejected` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `complainant` varchar(255) NOT NULL,
  `reported` varchar(255) NOT NULL,
  `report_type` varchar(255) NOT NULL,
  `submited_at` varchar(255) NOT NULL,
  `proof` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_products`
--

CREATE TABLE `saved_products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `date_saved` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `org_type` varchar(30) NOT NULL,
  `store_profile` varchar(255) NOT NULL,
  `store_banner` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` datetime(6) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `suspend_until` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`id`, `first_name`, `last_name`, `store_name`, `org_type`, `store_profile`, `store_banner`, `description`, `email`, `password`, `date_created`, `status`, `suspend_until`) VALUES
(15, 'Efren Dave', 'Cahilig', 'Chips Store', 'market', '../assets/bro.jpg', '../assets/bg3.jpg', 'This is an example of description', 'davecahilig19@gmail.com', '$2y$10$P.K/whLJ9Gajp0BSYiq5W.WAxMDXCPyWkKQ3bipIQ7NOzmavuh2ZO', '2024-11-18 23:04:13.062262', 'active', '0000-00-00'),
(18, 'Ahmad', 'Ambinoc', 'Random Chips', 'cafeteria', '../assets/kco-logo.jpg', '', '', 'ambinoc@gmail.com', '$2y$10$31wkMEhrWSsv1PQ/jmw38O4RE.VcvLCUFUpTD1U0BZf8uNyj7qsdu', '2024-11-24 21:57:37.201149', 'active', '0000-00-00'),
(19, 'Doraemon', 'Nobita', 'Pancakeee', 'coop', '../assets/toma.jpg', '', '', 'nobita@gmail.com', '$2y$10$koGxY/2X/NxR43RuKVIogO4eyiIfY0m55O7/PKVWL838v3zZkmPOe', '2024-11-24 22:16:36.215871', 'active', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`),
  ADD UNIQUE KEY `a_email` (`a_email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `c_email` (`email`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `pending_products`
--
ALTER TABLE `pending_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_sellers`
--
ALTER TABLE `pending_sellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pending_email` (`email`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `rejected_products`
--
ALTER TABLE `rejected_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rejected_sellers`
--
ALTER TABLE `rejected_sellers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_products`
--
ALTER TABLE `saved_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `s_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `pending_products`
--
ALTER TABLE `pending_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `pending_sellers`
--
ALTER TABLE `pending_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rejected_products`
--
ALTER TABLE `rejected_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rejected_sellers`
--
ALTER TABLE `rejected_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_products`
--
ALTER TABLE `saved_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`s_id`) REFERENCES `seller` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
