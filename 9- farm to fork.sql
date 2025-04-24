-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2025 at 01:18 PM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `seller_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `seller_id`) VALUES
(24, 32, 'Om Devani', '08128728287', 'devaniom11@gmail.com', 'cash on delivery', 'flat no. 26,Ayodhya-row-house, Nr.Brillent School, Dabholi Road, Katargaram m Suart Gujarat India - 395004', 'carrot (1)', 90, '11-Apr-2025', 'completed', 33),
(25, 32, 'Om Devani', '08128728287', 'devaniom11@gmail.com', 'cash on delivery', 'flat no. 26,Ayodhya-row-house, Nr.Brillent School, Dabholi Road, Katargaram n  Suart Gujarat India - 395004', 'mango (1)', 23, '11-Apr-2025', 'completed', 33),
(27, 32, 'Om Devani', '08128728287', 'devaniom11@gmail.com', 'cash on delivery', 'flat no. 26,Ayodhya-row-house, Nr.Brillent School, Dabholi Road, Katargaram kml Suart Gujarat India - 395004', 'mandarins (1)', 34, '12-Apr-2025', 'pending', 34);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `seller_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `details`, `price`, `image`, `seller_id`) VALUES
(39, 'Carrot', 'Vegitables', 'Gajar', 90, 'طعام.jpeg', 33),
(40, 'Mango', 'Fruits', 'Kerii', 23, '4df8583b-83b5-4f78-9833-e78b342e7203.jpeg', 33),
(41, 'chilli', 'vegitables', 'marcha', 12, '00810b9b-ae4a-4725-8e1a-d738d2d5517e.jpeg', 34),
(42, 'mandarins', 'fruits', 'santra', 34, 'Mandarins.jpeg', 34),
(49, 'Om Devani', 'Vegitables', 'hui', 67, '10 Health Benefits Of Coconut Water, Nutrition, & Side Effects.jpeg', 33);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `image` varchar(100) NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `image`, `is_approved`) VALUES
(31, 'Admin', 'admin@example.com', '$2y$10$IZN8CDWAMvr2skHHfz05cOfFXS2w7jt5rUqSi8ip/QADTSDjT5uDu', 'admin', '', 0),
(32, 'Buyer', 'buyer@example.com', '$2y$10$W7ttLwJqcClP662H4Wv3FOW.p2AAVblGWBrmJdVCbLStQ3KbDLmBO', 'buyer', 'krishna.jpg', 0),
(33, 'Seller', 'seller@example.com', '$2y$10$iqFszxSpkDhja3lt9AnXlOvF03oNbv3Bw6E/WPFS4NA/Jk2JvNlLa', 'seller', 'tax.jpg', 1),
(34, 'seller2', 'seller-1@example.com', '$2y$10$nslvgRqSuGUnQRwl.x6ETO6Sc65UTf3yiANzd1vhqHAGVJ2PzHkX2', 'seller', 'IMG_20231231_190045_253.jpg', 1),
(42, 'Om Devani', 'devaniom11@gmail.com', '$2y$10$On1q/lnFxmARgAimub9mnOvWlE1lhM4okhGY2Te9XAeu0ej0zJtlS', 'seller', 'IMG_20231231_190045_253.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_seller_user` (`seller_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_seller_user` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
