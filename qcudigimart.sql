-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 11:15 PM
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
  `suspend_until` date NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `profile_image`, `email`, `password`, `date_created`, `status`, `suspend_until`, `verification_code`, `is_verified`, `verification_expiry`) VALUES
(72, 'Raffy', 'Elmedo', '../assets/user/6749b2864ad23.jpg', 'elmedo.raffy.sareno@gmail.com', '$2y$10$D6LUN2tCbvxeW2N9FXiu9ewbIkHpqKoPf4N3wCacgheOa1.rB/yWq', '2024-11-29 20:13:08.510916', 'active', '0000-00-00', '768930', 1, NULL),
(85, 'Doraemon', 'Nobita', '../assets/user/674a87c5805ed.jpg', 'elmedo@gmail.com', '$2y$10$6I1TPnOFnoEPgetWmnVK0.NI1DFFOqgSX6iyXFIHLkfKKxYTQ/zou', '2024-11-30 11:30:27.180117', 'active', '0000-00-00', '925210', 1, NULL),
(86, 'Howard', 'Jones', '../assets/user/674abb3b799a0.jpg', 'jones@gmail.com', '$2y$10$REY8hjU96482k0otl.Fzle0AeDuEmJM.FOt7hQ8A4fiv2sO3Exc2W', '2024-11-30 15:12:50.353853', 'active', '0000-00-00', '146649', 1, NULL),
(87, 'ako', 'gwapo', '', 'jamescuritana34@gmail.com', '$2y$10$bcJS5tgL.3b3aGMD/kmMsOG6i4JlmMKmijG5vKStzKyXGKKZdrFyi', '2024-11-30 22:08:43.626105', 'active', '0000-00-00', '756133', 1, NULL),
(88, 'Hanna', 'Modelo', '', 'hannacamillegermanmodelo@gmail.com', '$2y$10$ZkB0kt8JhwjbUrFnNH7dLOtWce5PJy4vpmRiWTun9a9w4w5HmDm6C', '2024-11-30 22:21:15.222291', 'banned', '0000-00-00', '378584', 1, NULL),
(90, 'first', 'user', '', 'first@gmail.com', '$2y$10$jSWgaBz205R8Pk.ACacUcu9o0HlyWqDmGZal4ALaKQrtS/VjXJUl2', '2024-11-30 23:46:15.363856', 'active', '0000-00-00', '670620', 1, NULL),
(91, 'second', 'user', '', 'second@gmail.com', '$2y$10$f0TkAkVIM/6ZtOHkAItdBeDiVqRklWf0o3KJ62cW5.q7/EwqtskoW', '2024-11-30 23:47:25.768198', 'active', '0000-00-00', '730285', 1, NULL),
(92, 'third', 'user', '', 'third@gmail.com', '$2y$10$G93hTTozH64ptNnCojpX4e66WkKonem1W/bzYdFy51Y47trmxgDHq', '2024-11-30 23:48:34.161838', 'active', '0000-00-00', '664840', 1, NULL);

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
  `date_created` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `sender_type`, `receiver_id`, `m_content`, `date_created`, `is_read`) VALUES
(346, 85, 'customer', 15, 'Hey', '2024-11-30 15:36:23', 1),
(347, 85, 'customer', 15, 'HAHA', '2024-11-30 15:36:53', 1),
(348, 85, 'customer', 15, 'Hey', '2024-11-30 15:53:25', 1),
(349, 85, 'customer', 15, 'HAHA', '2024-11-30 15:54:51', 1),
(350, 15, 'seller', 85, 'HU', '2024-11-30 16:00:34', 1),
(351, 15, 'seller', 85, 'avail ba', '2024-11-30 16:03:58', 1),
(352, 85, 'customer', 15, 'alin', '2024-11-30 16:10:31', 1),
(353, 15, 'seller', 85, 'itu', '2024-11-30 16:11:15', 1),
(354, 15, 'seller', 85, 'puso mo', '2024-11-30 16:11:16', 1),
(355, 85, 'customer', 15, 'HAHA', '2024-11-30 16:11:27', 1),
(356, 72, 'customer', 15, 'Hello po', '2024-11-30 16:16:30', 1),
(357, 15, 'seller', 72, 'Ano na naman', '2024-11-30 16:18:10', 1),
(358, 90, 'customer', 15, 'HAHAHAHA', '2024-11-30 16:46:55', 1),
(359, 91, 'customer', 15, 'HAHAHA', '2024-11-30 16:48:01', 1),
(360, 92, 'customer', 15, 'ey', '2024-11-30 16:49:09', 1),
(361, 15, 'seller', 85, 'Available ba \'yan sha', '2024-12-01 17:33:49', 1),
(362, 85, 'customer', 15, 'SIge sabi mo e', '2024-12-01 17:34:32', 0);

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
(19, 'Random', 'ajfajfgkas,a a jasfajg faukjga bufkasjgfliauksf', 12.00, 21, 3, 'Vegetable', '[\"..\\/assets\\/products\\/burger.jpg\",\"..\\/assets\\/products\\/hotdog.jpg\",\"..\\/assets\\/products\\/dunk.jpg\"]', 16, '2024-11-20 07:33:51'),
(29, 'CHIPPSS', 'asf', 12.00, 12, 5, 'Coffee', '[\"..\\/assets\\/products\\/okik.jpg\"]', 19, '2024-11-26 16:35:22');

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
  `date_created` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `verification_code` varchar(255) NOT NULL,
  `is_verified` int(11) NOT NULL
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
(14, 'Shoes Dunk', 'asjlbakgfiuasfa', 2000.00, 16, 4, 'Shoes', '[\"..\\/assets\\/products\\/dunk.jpg\"]', 15, '2024-11-22 10:28:39'),
(21, 'Carrot', 'The carrot (Daucus carota) is a root vegetable often claimed to be the perfect health food. It is crunchy, tasty, and highly nutritious. Carrots are a particularly good source of beta-carotene, fiber, vitamin K1, potassium, and antioxidants.', 12.00, 48, 3, 'Vegetable', '[\"..\\/assets\\/products\\/car.jpg\"]', 15, '2024-11-29 13:20:51');

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
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `report_type` enum('product','customer','seller') NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL,
  `date_reported` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `target_id`, `report_type`, `reason`, `description`, `proof`, `date_reported`, `status`) VALUES
(8, 1, 15, 'seller', 'policy_violation', 'saasfa', '../assets/reports/message-image.jpg', '2024-11-27 21:40:46', 'Pending'),
(9, 1, 15, 'seller', 'policy_violation', 'saasfa', '../assets/reports/message-image.jpg', '2024-11-27 21:41:14', 'Pending'),
(10, 72, 15, 'seller', 'unauthorized_resale', 'aknsbkb;a klsab; jabkjsab absa;babga; jgsab; gsab;gjabsab;gksagsag', '../assets/reports/as.jpg', '2024-12-01 22:15:22', 'Pending'),
(11, 1, 15, 'seller', 'policy_violation', 'HAHAH pangit lasa be', '../assets/reports/bg2.jpg', '2024-12-01 22:44:41', 'Pending'),
(12, 85, 15, 'seller', 'unauthorized_resale', 'as fl;ampmas[psa', NULL, '2024-12-01 22:47:12', 'Pending');

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

--
-- Dumping data for table `saved_products`
--

INSERT INTO `saved_products` (`id`, `user_id`, `product_id`, `date_saved`) VALUES
(102, 38, 14, '2024-11-29 04:59:38'),
(107, 72, 14, '2024-11-30 15:20:15');

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
  `suspend_until` date NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `is_verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`id`, `first_name`, `last_name`, `store_name`, `org_type`, `store_profile`, `store_banner`, `description`, `email`, `password`, `date_created`, `status`, `suspend_until`, `verification_code`, `is_verified`) VALUES
(15, 'Efren Dave', 'Cahilig', 'Chips Store', 'market', '../assets/burger.jpg', '../assets/bg3.jpg', 'Welcome to The Urban Garden, where nature meets the city! Our store offers a curated selection of beautiful and sustainable plants, perfect for sprucing up your home, office, or urban space. We specialize in indoor plants, succulent arrangements, and eco-friendly gardening accessories. Whether you\'re a seasoned plant parent or a beginner, our friendly staff is here to guide you in selecting the best plants for your space and lifestyle.\r\n\r\nWhat We Offer:\r\n\r\n● Indoor plants, succulents, and flowering plants\r\n● Planters, pots, and eco-friendly gardening supplies\r\n● Expert advice on plant care and maintenance\r\n● Workshops and events on sustainable gardening and urban farming\r\n\r\nWe believe that even in a busy city, anyone can have a green oasis at home. Come visit us today and let’s grow together!', 'dave@gmail.com', '$2y$10$P.K/whLJ9Gajp0BSYiq5W.WAxMDXCPyWkKQ3bipIQ7NOzmavuh2ZO', '2024-11-18 23:04:13.062262', 'active', '0000-00-00', '', 1),
(18, 'Ahmad', 'Ambinoc', 'Random Chips', 'cafeteria', '../assets/kco-logo.jpg', '', '', 'ambinoc@gmail.com', '$2y$10$31wkMEhrWSsv1PQ/jmw38O4RE.VcvLCUFUpTD1U0BZf8uNyj7qsdu', '2024-11-24 21:57:37.201149', 'active', '0000-00-00', '', 0),
(41, 'Dave', 'Cahilig', 'Carrot Chips', 'enterprise', '../assets/bruh.png', '', '', 'davecahilig19@gmail.com', '$2y$10$0d4pbQW6zKvNzUmEO9cKae4dtlcFH/D0/qHS3NPo7b/XaejLH.zQ.', '2024-11-30 11:23:36.637462', 'active', '0000-00-00', '156733', 0);

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
  ADD KEY `fk_sender_seller` (`sender_id`),
  ADD KEY `fk_receiver_seller` (`receiver_id`);

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
-- Indexes for table `reports`
--
ALTER TABLE `reports`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `pending_products`
--
ALTER TABLE `pending_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pending_sellers`
--
ALTER TABLE `pending_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `rejected_products`
--
ALTER TABLE `rejected_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rejected_sellers`
--
ALTER TABLE `rejected_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `saved_products`
--
ALTER TABLE `saved_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
