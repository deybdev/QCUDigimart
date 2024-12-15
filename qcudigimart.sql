-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 04:48 AM
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
(5, 'Coffee'),
(6, 'Perfume'),
(7, 'Clothes'),
(8, 'Bags'),
(9, 'Chair'),
(10, 'School/Office Supplies');

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
(108, 'Karl', 'De Leon', '../assets/user/675152bdb90e1.jpg', 'karlfrancisdeleon@gmail.com', '$2y$10$d/a/UDobaIfYR//FAmZLB.lmwado15EqqSh6Trsg7erBD6MQlokLG', '2024-12-05 15:11:34.928267', 'active', '0000-00-00', '840803', 1, NULL),
(116, 'Efren Dave', 'Cahilig', '../assets/user/675a22a6cd131.jpg', 'davecahilig19@gmail.com', '$2y$10$KngkcS9ByYulejmNmPz7FuNZkyeINqvmlttRpmJxyT49Oo7I5KcWq', '2024-12-12 07:37:45.305434', 'active', '0000-00-00', '112345', 1, NULL),
(117, 'mark ', 'calipay', '', 'marklourenzecalipay@gmail.com', '$2y$10$qLgx2suzrrtN04Jg9tx3EukKnjcxeZuvIxZXDmHT9DJyh6SK/8mMC', '2024-12-12 17:50:15.030252', 'active', '0000-00-00', '437177', 1, NULL);

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
(495, 116, 'customer', 52, 'Available po ba \'yong?', '2024-12-12 00:39:37', 1),
(496, 52, 'seller', 116, 'Alin?', '2024-12-12 00:39:56', 1),
(497, 52, 'seller', 116, 'Secret', '2024-12-12 00:40:09', 1),
(498, 116, 'customer', 52, 'Ngeks', '2024-12-12 00:40:24', 1),
(499, 52, 'seller', 116, 'HAHAHHA', '2024-12-12 00:42:59', 1),
(500, 116, 'customer', 52, 'Bakit', '2024-12-12 00:43:16', 1),
(501, 52, 'seller', 116, 'haha', '2024-12-12 00:44:36', 1),
(502, 116, 'customer', 52, 'Hey', '2024-12-12 00:48:31', 1),
(503, 52, 'seller', 116, 'HA', '2024-12-12 00:50:42', 1),
(504, 52, 'seller', 116, 'HA', '2024-12-12 00:51:08', 1),
(505, 116, 'customer', 52, 'i', '2024-12-12 00:51:15', 1),
(506, 52, 'seller', 116, 'AH', '2024-12-12 00:52:00', 1),
(507, 116, 'customer', 52, 'HA', '2024-12-12 00:52:10', 1),
(508, 116, 'customer', 52, 'A', '2024-12-12 00:52:21', 1),
(509, 52, 'seller', 116, 'UA', '2024-12-12 00:52:30', 0),
(510, 117, 'customer', 48, 'MERON PAPO?', '2024-12-12 10:51:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pending_products`
--

CREATE TABLE `pending_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `s_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_products`
--

INSERT INTO `pending_products` (`id`, `name`, `description`, `price`, `is_available`, `category_id`, `category`, `images`, `s_id`, `date_created`) VALUES
(50, 'Cortado', 'A Latin American coffee drink made with equal parts espresso and steamed milk, but without foamed milk. ', 145.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/Cortado.jpg\"]', 49, '2024-12-05 15:05:24'),
(56, 'Oakley Bag', 'Second hand tactical oakley backpack. ', 1200.00, 1, 8, 'Bags', '[\"..\\/assets\\/products\\/OakleyBag.jpg\"]', 50, '2024-12-05 15:42:32'),
(61, 'Espresso', 'Espresso as a standalone coffee is served everywhere. It contains literally the basic essence. Coffee and water. No strings attached.', 145.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/ESPRESSO.jpg\"]', 49, '2024-12-11 21:35:57'),
(62, 'Doppio', 'Doppio in Italian literally means ‘double.’ It is a double shot of Espresso coffee.', 379.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/DOPPIO.jpg\"]', 49, '2024-12-11 21:36:26'),
(63, 'Macchiato', 'Macchiato in Italian means ‘stained.’ This is because a serving of Macchiato is a normal Espresso shot with a little-foamed milk on the top.', 360.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/MACHIATTO.jpg\"]', 49, '2024-12-11 21:36:57'),
(64, 'Cappucino', 'Everyone’s favourite and the most well-known and standard coffee drink, cappuccino contains more milk-to-coffee ratio.', 267.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/Cappucino.jpg\"]', 49, '2024-12-11 21:37:25'),
(65, 'Flat White', 'A slight variation of the more generalised Cappucino, a Flat White is a no-nonsense Cappuccino with double the amount of milk as compared to coffee.', 852.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/FLAT WHITE.jpg\"]', 49, '2024-12-11 21:38:04'),
(66, 'Café au Lait', 'It is a French press coffee preparation with equal amounts coffee brew and scalded milk.', 1449.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/CAFE AU.jpg\"]', 49, '2024-12-11 21:38:38'),
(70, 'Long Black', 'In essence, Long Black and Café Americano are the same thing, but it makes a world of difference to coffee connoisseurs.', 400.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/LONK BLACK.jpg\"]', 49, '2024-12-11 21:40:23'),
(74, 'Mocha', 'The holy grail of all beverages. Nothing can go wrong with Mocha, nothing!', 1299.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/MOCHA.jpg\"]', 49, '2024-12-11 21:42:16'),
(76, 'Mazagran', 'Fancy a zingy touch to your everyday coffee? Add lemon. WHAT!? Yeah, lemon juice.', 899.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/MAZAGRAN.jpg\"]', 49, '2024-12-11 21:43:04'),
(78, 'Ballpoint Pen', '0.5mm point for drafting and drawing', 38.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/Pen.avif\"]', 52, '2024-12-11 22:02:51'),
(82, 'Scissors', 'Sharp and good for vellum boards', 27.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/scidssors.jpg\"]', 52, '2024-12-11 22:05:54'),
(83, 'White Outliner', 'For highlighting notes and documents (Brown papers only)', 89.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/highlighetr.jpg\"]', 52, '2024-12-11 22:06:36'),
(84, 'Long Folder', 'For covering documents safely (Works with A5)', 12.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/Folder.jpg\"]', 52, '2024-12-11 22:07:08'),
(85, 'Hungarian Burger', 'Experience the taste of Hungary with our juicy burger, topped with traditional Hungarian flavors like paprika and caraway seeds.', 297.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/thanos-pal-Djs02AtkOm4-unsplash.jpg\"]', 48, '2024-12-11 22:09:37'),
(92, 'Vintage Crossbody Bag', 'Vintage bag made from Germany. Adjustable strap', 699.00, 1, 8, 'Bags', '[\"..\\/assets\\/products\\/43153a49-2e75-4856-8eaf-bd99a9b6b9d3.jpg\"]', 50, '2024-12-11 22:27:12'),
(93, 'Y3 Converse', 'Y3 and Converse collaboration shoes', 900.00, 1, 4, 'Shoes', '[\"..\\/assets\\/products\\/\\u03b3 - Footwear - Ash Bloody Bbuckled Butcher\\u2026.jpg\"]', 50, '2024-12-11 22:27:48');

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
  `is_available` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `s_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `is_available`, `category_id`, `category`, `images`, `s_id`, `date_created`) VALUES
(39, 'Monoblock', 'The Monobloc chair is a lightweight stackable polypropylene chair, usually white in color, often described as the world\'s most common plastic chair.', 120.00, 0, 9, 'Chair', '[\"..\\/assets\\/products\\/mono1.jpg\",\"..\\/assets\\/products\\/mono.jpg\"]', 51, '2024-12-05 15:47:29'),
(40, 'Leather Boots', 'Leather boots. Size 37 ', 1900.00, 1, 4, 'Shoes', '[\"..\\/assets\\/products\\/Boots.jpg\"]', 50, '2024-12-05 15:47:33'),
(41, 'Custom BeltBag', 'Canvas waist bag. Adjustable waist from 26 inch to 35 inch.', 679.00, 1, 8, 'Bags', '[\"..\\/assets\\/products\\/Belthbag.jpg\"]', 50, '2024-12-05 15:47:37'),
(42, 'Leather Skirt', 'Resewed leather skirt made from cowhide leather. 26 inch waist, 30 inch length', 650.00, 1, 7, 'Clothes', '[\"..\\/assets\\/products\\/Skirt.jpg\"]', 50, '2024-12-05 15:47:42'),
(43, 'Custom Denim Shorts', 'Black Denim shorts, Margiela custom 28 waist, 36 length', 800.00, 1, 7, 'Clothes', '[\"..\\/assets\\/products\\/Shorts.jpg\"]', 50, '2024-12-05 15:47:50'),
(44, 'Bracelet', 'A bracelet is a chain or band, usually made of metal, which you wear around your wrist as jewellery.', 12.00, 0, 2, 'Jewelry', '[\"..\\/assets\\/products\\/brac1.jpg\"]', 51, '2024-12-05 15:47:54'),
(45, 'Resin Fraux', 'Get the look and feel of resin without the weight or complexity with Faux Resin. It\'s perfect for crafting lightweight jewelry and decor, offering ease of use for crafters of all skill levels.', 100.00, 1, 2, 'Jewelry', '[\"..\\/assets\\/products\\/resin1.jpg\"]', 51, '2024-12-05 15:48:54'),
(47, 'Vienna Mocha', 'Simply known as Vienna Coffee, this coffee is a fun-loving twist to your regular Espresso shot. What’s the twist, you ask? WHIPPED CREAM!', 799.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/VIENNA MOCHA.jpg\"]', 49, '2024-12-11 21:41:23'),
(48, 'Latte', 'Caffé Latte can be seen as the more mainstream brother of Café au Lait. The name literally means, yes, you guessed it right, ‘milk coffee.’', 378.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/LATTEEE.jpg\"]', 49, '2024-12-11 21:41:29'),
(49, 'Americano', 'Those who say they like their coffee black talk about Café Americano. If you might have seen in Western media, Americans like to drink their coffee straight out of the pot.', 199.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/AMERICANOO.jpg\"]', 49, '2024-12-11 21:41:34'),
(50, 'Turkish', 'The Turkish like their coffee light and sweet. Hence, a majority of this coffee is sugar water.', 760.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/TURKISH.jpg\"]', 49, '2024-12-11 21:41:38'),
(51, 'Irish', 'The Irish sure know to stir things up and making literally any dish or drink interesting. Ever heard whiskey in coffee?', 563.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/IRISH.jpg\"]', 49, '2024-12-11 21:41:52'),
(52, 'Café del Tiempo', 'Meant to enjoy in the hot summers, this is espresso served with an offering of a lemon slice and some ice.', 2459.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/CAFE CON.jpg\"]', 49, '2024-12-11 21:48:12'),
(53, 'Borgia', 'There’s really not much difference in between Mocha and Borgia. What does set them apart, is what goes in the whipped cream on the top.', 239.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/BORGIA.jpg\"]', 49, '2024-12-11 21:48:17'),
(54, 'Frappe', 'One of the fanciest coffee drinks out there, and the one that contains the least amount of coffee in comparison to other items.', 691.00, 1, 5, 'Coffee', '[\"..\\/assets\\/products\\/FRAP.jpg\"]', 49, '2024-12-11 21:48:22'),
(55, 'HBV Stapler', 'Can handle up to 45-50 pages', 49.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/stapler.jpg\"]', 52, '2024-12-11 22:05:58'),
(56, 'Stainless Ruler', 'Firm and sturdy with slide-resist pads ', 25.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/Ruler.avif\"]', 52, '2024-12-11 22:06:05'),
(57, 'Elmers Glue', '70ml long-lasting and fast drying glue', 30.00, 1, 10, 'School/Office Supplies', '[\"..\\/assets\\/products\\/glue.webp\"]', 52, '2024-12-11 22:06:09'),
(58, 'Katsu Curry Bowl', 'A comforting and flavorful dish featuring a golden-brown pork cutlet, fluffy rice, and a creamy curry sauce.', 412.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/Screenshot 2024-12-11 221903.png\"]', 48, '2024-12-11 22:20:53'),
(59, 'Blueberry Shortcake', 'A delightful summer treat with layers of buttery biscuits, juicy blueberries, and creamy whipped cream.', 356.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/Screenshot 2024-12-11 221722.png\"]', 48, '2024-12-11 22:21:25'),
(60, 'Blueberry Pancake', 'A blueberry-licious breakfast treat, perfect for any morning.', 455.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/Screenshot 2024-12-11 221510.png\"]', 48, '2024-12-11 22:21:30'),
(61, 'Classic Dimsum', 'A culinary journey through Asia, with a variety of steamed, fried, and baked dim sum.', 679.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/Screenshot 2024-12-11 221613.png\"]', 48, '2024-12-11 22:21:38'),
(62, 'French Fries', 'Crunchy, golden-brown French fries, seasoned to perfection.', 167.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/pixzolo-photography-8YBHgP0WrEo-unsplash.jpg\"]', 48, '2024-12-11 22:21:44'),
(63, 'Garlic Pasta', 'A simple yet satisfying dish of al dente pasta tossed in a rich, garlicky sauce.', 689.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/garlic pasta.png\"]', 48, '2024-12-11 22:37:58'),
(64, 'Distressed Pants', '28\" waist, 42\" length unisex tattered pants', 1499.00, 1, 7, 'Clothes', '[\"..\\/assets\\/products\\/\\u03b3 - Pants - @foreign_image distressed \\u201cVomit\\u201d\\u2026.jpg\"]', 50, '2024-12-11 22:38:03'),
(65, 'strawberry', 'efffrr', 35.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/images (1).jpg\"]', 49, '2024-12-12 18:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `rejected_products`
--

CREATE TABLE `rejected_products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `s_id` int(11) NOT NULL,
  `date_rejected` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rejected_products`
--

INSERT INTO `rejected_products` (`id`, `name`, `description`, `price`, `is_available`, `category_id`, `category`, `images`, `s_id`, `date_rejected`) VALUES
(1, '0', 'abf;aljkfbabf', 20.00, 1, 1, 'Food', '0', 7, '2024-11-13 20:33:36'),
(2, '0', 'abf;aljkfbabf', 20.00, 1, 1, 'Food', '0', 7, '2024-11-13 20:33:42'),
(3, 'Burger', 'abf;aljkfbabf', 20.00, 1, 1, 'Food', '0', 7, '2024-11-13 20:37:21'),
(4, 'nqwio1`', 'nqwlkpwquibflqwbfiqbifonq', 18.00, 1, 1, 'Food', '../assets/products/toma.jpg', 7, '2024-11-13 20:43:16'),
(5, 'Kemerut', 'kjafbakfjkasfpaskfbsaf', 10.00, 1, 3, 'Vegetable', '[\"..\\/assets\\/products\\/fl1.jpg\",\"..\\/assets\\/products\\/coop2.jpg\"]', 13, '2024-11-18 21:21:39'),
(7, 'Hipon na pagod', 'asngas[gaspgasgasa an oaisgiasn iang', 12.00, 1, 1, 'Food', '[\"..\\/assets\\/products\\/shri.jpg\"]', 46, '2024-12-03 22:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `rejected_sellers`
--

CREATE TABLE `rejected_sellers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_rejected` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rejected_sellers`
--

INSERT INTO `rejected_sellers` (`id`, `name`, `date_rejected`) VALUES
(73, 'lopey lopi', '2024-12-02 11:36:51.000000');

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
  `status` varchar(50) DEFAULT 'Pending',
  `admin_comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `target_id`, `report_type`, `reason`, `description`, `proof`, `date_reported`, `status`, `admin_comment`) VALUES
(16, 116, 50, 'seller', 'counterfeit_product', '---', '../assets/reports/coobg2.jpg', '2024-12-12 15:09:33', 'ignored', '');

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
(107, 72, 14, '2024-11-30 15:20:15'),
(111, 100, 26, '2024-12-02 09:48:01'),
(112, 101, 27, '2024-12-02 09:49:52'),
(113, 102, 25, '2024-12-02 09:53:56'),
(114, 100, 25, '2024-12-02 10:52:50'),
(116, 99, 26, '2024-12-02 11:18:15'),
(117, 99, 25, '2024-12-02 11:19:28'),
(119, 106, 27, '2024-12-04 09:37:20'),
(122, 117, 58, '2024-12-12 09:51:12');

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
(48, 'Francis', 'De Leon', 'Coup', 'market', '../assets/jacopo-maiarelli--gOUx23DNks-unsplash (1).jpg', '../assets/lauren-mancke-sil2Hx4iupI-unsplash.jpg', 'Satisfy Your Cravings, One Bite at a Time', 'deleon.482571150044@depedqc.ph', '$2y$10$fMriffRvEzlqt5TD2Y2dE.eU.D3QhZpL9KtwtABq1XXoyxKvqTyCi', '2024-12-05 14:51:01.345784', 'active', '0000-00-00', '', 0),
(49, 'Ahmad', 'Ambinoc', 'CafeHussein', 'cafeteria', '../assets/nathan-dumlao-6VhPY27jdps-unsplash.jpg', '../assets/joshua-rodriguez-f7zm5TDOi4g-unsplash.jpg', 'A cozy coffee shop with a warm ambiance, serving freshly brewed coffee and handcrafted pastries in a relaxed and inviting atmosphere.', 'kmultiexperiments@gmail.com', '$2y$10$Cx/QPveKu0cfTfoRU7dG2eU.XtDly3V/GdOZzzjCJvKOFKLEU.xvO', '2024-12-05 14:58:02.826391', 'active', '0000-00-00', '', 0),
(50, 'Efren', 'Cahilig', 'HomeHaven', 'enterprise', '../assets/Logoprof.jpg', '../assets/bgbanner.jpg', 'Second hand and custom clothes, bags, and etc.', 'efren123@gmail.com', '$2y$10$rGOgVKC6myQA0rt9vPsjD.RpaxfH9JYQTNz3CigoTgJ4imJf17/Qq', '2024-12-05 15:35:14.256342', 'active', '0000-00-00', '', 0),
(51, 'Ysa Mae', 'Simon', 'Tatsuman', 'freelance', '../assets/coo.jpg', '../assets/coobg2.jpg', '', 'simonysa@gmail.com', '$2y$10$3Y8mLEqOC9f5sA237D4S3uhBrDlDUGmUXSr1o3pvozFppE7OMW11m', '2024-12-05 15:37:26.799592', 'active', '0000-00-00', '', 0),
(52, 'Myn', 'Marasigan', 'SUPERsupplies', 'coop', '../assets/pexels-nietjuh-843226.jpg', '../assets/pexels-lum3n-44775-399161.jpg', 'Your one-stop shop for all your school essentials! We offer a wide range of quality supplies, from notebooks and pens to backpacks and calculators, to help you ace your studies.', 'littledipper@gmail.com', '$2y$10$L.Fvzxu8EkyYqm.EyWh2QOVqOA7xfuWgOJPLtqXBakS4ksKFRX8Fa', '2024-12-11 21:51:30.655594', 'active', '0000-00-00', '742363', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- AUTO_INCREMENT for table `pending_products`
--
ALTER TABLE `pending_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `pending_sellers`
--
ALTER TABLE `pending_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `rejected_products`
--
ALTER TABLE `rejected_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rejected_sellers`
--
ALTER TABLE `rejected_sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `saved_products`
--
ALTER TABLE `saved_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

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
