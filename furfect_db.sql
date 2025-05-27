-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 04:37 PM
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
-- Database: `furfect_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `cart_json` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `delivery_email` varchar(255) DEFAULT NULL,
  `delivery_info` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `delivery_name` varchar(255) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `delivery_contact` varchar(50) DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `cart_json`, `total_price`, `order_date`, `total_amount`, `payment_mode`, `delivery_email`, `delivery_info`, `email`, `delivery_name`, `delivery_address`, `delivery_contact`, `delivery_notes`) VALUES
(1, '', 0.00, '2025-05-24 07:41:15', 434.00, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:27:09', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:28:03', 0.00, 'COD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:28:33', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:30:16', 0.00, 'COD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:38:43', 0.00, 'COD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:46:43', 0.00, 'COD', NULL, '{\"name\":\"Jesie Andrei Pungtod\",\"address\":\"Cantipay, Carmen, Cebu\",\"contact\":\"09123456789\"}', NULL, NULL, NULL, NULL, NULL),
(8, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:49:03', 0.00, 'COD', NULL, '{\"name\":\"jesie\",\"address\":\"carmen\",\"contact\":\"09123456789\"}', NULL, NULL, NULL, NULL, NULL),
(9, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:53:52', 0.00, 'COD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 03:54:59', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:06:39', 0.00, 'COD', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:10:44', 0.00, 'COD', NULL, NULL, 'liza@gmail.com', NULL, NULL, NULL, NULL),
(13, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:14:12', 0.00, 'COD', NULL, NULL, 'lar@gmail.com', NULL, NULL, NULL, NULL),
(14, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:16:26', 0.00, 'COD', NULL, NULL, 'josh@gmail.com', NULL, NULL, NULL, NULL),
(15, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:18:59', 0.00, 'COD', NULL, NULL, 'lizz@gmail.com', NULL, NULL, NULL, NULL),
(16, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:33:27', 0.00, 'Counter', NULL, NULL, 'lizz@gmail.com', NULL, NULL, NULL, NULL),
(17, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:34:08', 0.00, 'COD', NULL, NULL, 'jesie@gmail.com', NULL, NULL, NULL, NULL),
(18, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:42:27', 0.00, 'COD', NULL, NULL, 'wang@gmail.com', NULL, NULL, NULL, NULL),
(19, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:44:12', 0.00, 'COD', NULL, NULL, 'yong@gmail.com', NULL, NULL, NULL, NULL),
(20, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:47:12', 0.00, 'COD', NULL, NULL, 'kian@gmail.co', NULL, NULL, NULL, NULL),
(21, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:48:27', 0.00, 'COD', NULL, NULL, 'lar@gmail.com', NULL, NULL, NULL, NULL),
(22, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:55:20', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 04:55:40', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '{\"4\":{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"image\":\"485044481_1696829240903155_721548532528580268_n.jpg\",\"quantity\":1},\"5\":{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"image\":\"483756228_1874388033332023_6248468650258463575_n.jpg\",\"quantity\":1},\"6\":{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"image\":\"479712090_1012957744227113_3752223398184242681_n.jpg\",\"quantity\":1}}', 458.00, '2025-05-25 05:34:25', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"image\":\"img_6832ca1b820a0.jpg\",\"quantity\":1}}', 2929.00, '2025-05-27 14:14:27', 0.00, 'COD', NULL, NULL, 'sesdsdss@mail.com', NULL, NULL, NULL, NULL),
(26, '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"image\":\"img_6832ca1b820a0.jpg\",\"quantity\":1}}', 2929.00, '2025-05-27 14:17:07', 0.00, 'COD', NULL, NULL, 'sesdsdss@mail.com', NULL, NULL, NULL, NULL),
(27, '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"image\":\"img_6832ca1b820a0.jpg\",\"quantity\":1}}', 2929.00, '2025-05-27 14:28:26', 0.00, 'COD', NULL, NULL, 'jshdhjsdjh@mail.com', NULL, NULL, NULL, NULL),
(28, '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"image\":\"img_6832ca1b820a0.jpg\",\"quantity\":1}}', 2929.00, '2025-05-27 14:28:46', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"image\":\"img_6832ca1b820a0.jpg\",\"quantity\":1}}', 2929.00, '2025-05-27 14:31:08', 0.00, 'Counter', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 'Cat & Dogs Multivitamins Lite 30 chews (40g)', 1, 89.00),
(2, 1, 'Cat Multivitamins 160 chews (210g)', 1, 345.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `supplier` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `ecom_category` varchar(100) DEFAULT NULL,
  `category` varchar(255) DEFAULT 'General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `barcode`, `category_id`, `price`, `stock`, `supplier`, `image`, `ecom_category`, `category`) VALUES
(11, 'ambot nimoo', '99292992', NULL, 2929.00, 56, 'polooo', 'img_6832ca1b820a0.jpg', NULL, 'Drooly Delights'),
(12, 'ambot nimoo', '12345', NULL, 92929.00, 63, 'kuor', 'img_6832ca436cdd4.jpg', NULL, 'Litter Lodge'),
(13, 'CLIR', '09992', NULL, 89292.00, 987, 'KAKAKA', 'img_6832ca9594ef8.jpg', NULL, 'Feline Feast'),
(15, 'monaros', '6767676', NULL, 72727.00, 727, 'harddware', 'img_6832cdc0d7e7f.jpg', NULL, 'Litter Lodge'),
(16, 'jhweuwueu', '9999999999999', NULL, 877887.00, 6767, 'ndnsm', 'img_6832ce16ca451.jpg', NULL, 'Fur & Shine'),
(17, 'goodest Adult', '1234567891234', NULL, 45.00, 42, 'waw', 'img_6832ddbabbf25.jpg', NULL, 'Feline Feast'),
(18, 'jes', '12334343434', NULL, 22.00, 7, 'ctu', 'img_6833faacc4ade.jpg', NULL, 'Snug Paws Haven'),
(19, 'reeeee', '412341234', NULL, 111.00, 11, 'dasd', 'img_683572d6939c6.jpg', NULL, 'Snug Paws Haven'),
(20, 'paldo tayo', '98383883', NULL, 77737.00, 7783, 'bmeg', 'img_6835c47b3a53e.jpg', NULL, 'Vital Paws');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `receipt_number` varchar(50) NOT NULL,
  `items` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `cash` decimal(10,2) NOT NULL,
  `change_due` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `receipt_number`, `items`, `total`, `cash`, `change_due`, `created_at`) VALUES
(1, 'RCPT-683269BDCD323', '[{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"quantity\":1,\"subtotal\":89},{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"quantity\":1,\"subtotal\":345},{\"name\":\"Dog & Cat Appetite Booster Beef Liver (75g)\",\"price\":\"24.00\",\"quantity\":1,\"subtotal\":24}]', 458.00, 0.00, 0.00, '2025-05-25 00:52:13'),
(2, 'RCPT-68326A0263FEB', '[{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"quantity\":1,\"subtotal\":89},{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"quantity\":1,\"subtotal\":345}]', 434.00, 0.00, 0.00, '2025-05-25 00:53:22'),
(3, 'RCPT-68326B1E91B34', '[{\"name\":\"Cat Multivitamins 160 chews (210g)\",\"price\":\"345.00\",\"quantity\":1,\"subtotal\":345},{\"name\":\"Cat & Dogs Multivitamins Lite 30 chews (40g)\",\"price\":\"89.00\",\"quantity\":1,\"subtotal\":89}]', 434.00, 0.00, 0.00, '2025-05-25 00:58:06'),
(4, 'REC_6832bba1b1957', '{\"7\":{\"name\":\"AXE BOANG\",\"price\":\"89.00\",\"quantity\":3,\"barcode\":\"4800888141125\"}}', 267.00, 300.00, 33.00, '2025-05-25 06:41:37'),
(5, 'REC_6832bbaee8a6a', '[]', 0.00, 300.00, 300.00, '2025-05-25 06:41:50'),
(6, 'REC_6832cb4e6e031', '{\"14\":{\"name\":\"LZA\",\"price\":\"92299.00\",\"quantity\":5,\"barcode\":\"877626767267267\"},\"13\":{\"name\":\"CLIR\",\"price\":\"89292.00\",\"quantity\":3,\"barcode\":\"09992\"},\"12\":{\"name\":\"ambot nimoo\",\"price\":\"92929.00\",\"quantity\":3,\"barcode\":\"12345\"},\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":4,\"barcode\":\"99292992\"}}', 1019874.00, 2000000.00, 980126.00, '2025-05-25 07:48:30'),
(7, 'REC_6832cb5978fef', '[]', 0.00, 2000000.00, 2000000.00, '2025-05-25 07:48:41'),
(8, 'REC_6832cb61bc35c', '[]', 0.00, 2000000.00, 2000000.00, '2025-05-25 07:48:49'),
(9, 'REC_6833faf840278', '{\"18\":{\"name\":\"jes\",\"price\":\"22.00\",\"quantity\":5,\"barcode\":\"12334343434\"},\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":5,\"barcode\":\"99292992\"}}', 14755.00, 15000.00, 245.00, '2025-05-26 05:24:08'),
(10, 'REC_6833fafd8fb7f', '[]', 0.00, 15000.00, 15000.00, '2025-05-26 05:24:13'),
(11, 'REC_68340b2e94f6c', '{\"17\":{\"name\":\"goodest Adult\",\"price\":\"45.00\",\"quantity\":11,\"barcode\":\"1234567891234\"},\"12\":{\"name\":\"ambot nimoo\",\"price\":\"92929.00\",\"quantity\":5,\"barcode\":\"12345\"}}', 465140.00, 500000.00, 34860.00, '2025-05-26 06:33:18'),
(12, 'REC_68340caacf45b', '[]', 0.00, 500000.00, 500000.00, '2025-05-26 06:39:38'),
(13, 'REC_68340cc3de042', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 5000.00, 2071.00, '2025-05-26 06:40:03'),
(14, 'REC_68340d6ab39c2', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 5000.00, 2071.00, '2025-05-26 06:42:50'),
(15, 'REC_68340e53a78bc', '{\"17\":{\"name\":\"goodest Adult\",\"price\":\"45.00\",\"quantity\":1,\"barcode\":\"1234567891234\"}}', 45.00, 50.00, 5.00, '2025-05-26 06:46:43'),
(16, 'REC_683410f97d6e0', '{\"12\":{\"name\":\"ambot nimoo\",\"price\":\"92929.00\",\"quantity\":1,\"barcode\":\"12345\"}}', 92929.00, 100000.00, 7071.00, '2025-05-26 06:58:01'),
(17, 'REC_6834127473543', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:04:20'),
(18, 'REC_683412d9484f4', '{\"13\":{\"name\":\"CLIR\",\"price\":\"89292.00\",\"quantity\":1,\"barcode\":\"09992\"}}', 89292.00, 100000.00, 10708.00, '2025-05-26 07:06:01'),
(19, 'REC_6834137317bac', '{\"13\":{\"name\":\"CLIR\",\"price\":\"89292.00\",\"quantity\":1,\"barcode\":\"09992\"}}', 89292.00, 100000.00, 10708.00, '2025-05-26 07:08:35'),
(20, 'REC_683414ff37eca', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:15:11'),
(21, 'REC_68341532cacf7', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:16:02'),
(22, 'REC_6834171f8e39e', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:24:15'),
(23, 'REC_68341756c28c0', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:25:10'),
(24, 'REC_683417dc8ba48', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:27:24'),
(25, 'REC_6834186c599e3', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:29:48'),
(26, 'REC_68341b6fdb65b', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:42:39'),
(27, 'REC_68341c23b4cb6', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:45:39'),
(28, 'REC_68341c9c305ab', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:47:40'),
(29, 'REC_68341d430a143', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:50:27'),
(30, 'REC_68341e4b695ec', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 07:54:51'),
(31, 'REC_6834210ae4ea2', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 08:06:34'),
(32, 'REC_6834227c2695b', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 08:12:44'),
(33, 'REC_683425599362e', '{\"11\":{\"name\":\"ambot nimoo\",\"price\":\"2929.00\",\"quantity\":1,\"barcode\":\"99292992\"}}', 2929.00, 3000.00, 71.00, '2025-05-26 08:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `total`, `payment_amount`, `sale_date`, `created_at`) VALUES
(1, 4, 267.00, 300.00, '2025-05-25 06:41:37', '2025-05-25 14:41:37'),
(2, 4, 0.00, 300.00, '2025-05-25 06:41:50', '2025-05-25 14:41:50'),
(3, 4, 1019874.00, 2000000.00, '2025-05-25 07:48:30', '2025-05-25 15:48:30'),
(4, 4, 0.00, 2000000.00, '2025-05-25 07:48:41', '2025-05-25 15:48:41'),
(5, 4, 0.00, 2000000.00, '2025-05-25 07:48:49', '2025-05-25 15:48:49'),
(6, 4, 14755.00, 15000.00, '2025-05-26 05:24:08', '2025-05-26 13:24:08'),
(7, 4, 0.00, 15000.00, '2025-05-26 05:24:13', '2025-05-26 13:24:13'),
(8, 4, 465140.00, 500000.00, '2025-05-26 06:33:18', '2025-05-26 14:33:18'),
(9, 4, 0.00, 500000.00, '2025-05-26 06:39:38', '2025-05-26 14:39:38'),
(10, 4, 2929.00, 5000.00, '2025-05-26 06:40:03', '2025-05-26 14:40:03'),
(11, 4, 2929.00, 5000.00, '2025-05-26 06:42:50', '2025-05-26 14:42:50'),
(12, 4, 45.00, 50.00, '2025-05-26 06:46:43', '2025-05-26 14:46:43'),
(13, 4, 92929.00, 100000.00, '2025-05-26 06:58:01', '2025-05-26 14:58:01'),
(14, 4, 2929.00, 3000.00, '2025-05-26 07:04:20', '2025-05-26 15:04:20'),
(15, 4, 89292.00, 100000.00, '2025-05-26 07:06:01', '2025-05-26 15:06:01'),
(16, 4, 89292.00, 100000.00, '2025-05-26 07:08:35', '2025-05-26 15:08:35'),
(17, 4, 2929.00, 3000.00, '2025-05-26 07:15:11', '2025-05-26 15:15:11'),
(18, 4, 2929.00, 3000.00, '2025-05-26 07:16:02', '2025-05-26 15:16:02'),
(19, 4, 2929.00, 3000.00, '2025-05-26 07:24:15', '2025-05-26 15:24:15'),
(20, 4, 2929.00, 3000.00, '2025-05-26 07:25:10', '2025-05-26 15:25:10'),
(21, 4, 2929.00, 3000.00, '2025-05-26 07:27:24', '2025-05-26 15:27:24'),
(22, 4, 2929.00, 3000.00, '2025-05-26 07:29:48', '2025-05-26 15:29:48'),
(23, 4, 2929.00, 3000.00, '2025-05-26 07:42:39', '2025-05-26 15:42:39'),
(24, 4, 2929.00, 3000.00, '2025-05-26 07:45:39', '2025-05-26 15:45:39'),
(25, 4, 2929.00, 3000.00, '2025-05-26 07:47:40', '2025-05-26 15:47:40'),
(26, 4, 2929.00, 3000.00, '2025-05-26 07:50:27', '2025-05-26 15:50:27'),
(27, 4, 2929.00, 3000.00, '2025-05-26 07:54:51', '2025-05-26 15:54:51'),
(28, 4, 2929.00, 3000.00, '2025-05-26 08:06:34', '2025-05-26 16:06:34'),
(29, 4, 2929.00, 3000.00, '2025-05-26 08:12:44', '2025-05-26 16:12:44'),
(30, 4, 2929.00, 3000.00, '2025-05-26 08:24:57', '2025-05-26 16:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`id`, `sale_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, NULL, 3, 89.00),
(2, 3, NULL, 5, 92299.00),
(3, 3, 13, 3, 89292.00),
(4, 3, 12, 3, 92929.00),
(5, 3, 11, 4, 2929.00),
(6, 6, 18, 5, 22.00),
(7, 6, 11, 5, 2929.00),
(8, 8, 17, 11, 45.00),
(9, 8, 12, 5, 92929.00),
(10, 10, 11, 1, 2929.00),
(11, 11, 11, 1, 2929.00),
(12, 12, 17, 1, 45.00),
(13, 13, 12, 1, 92929.00),
(14, 14, 11, 1, 2929.00),
(15, 15, 13, 1, 89292.00),
(16, 16, 13, 1, 89292.00),
(17, 17, 11, 1, 2929.00),
(18, 18, 11, 1, 2929.00),
(19, 19, 11, 1, 2929.00),
(20, 20, 11, 1, 2929.00),
(21, 21, 11, 1, 2929.00),
(22, 22, 11, 1, 2929.00),
(23, 23, 11, 1, 2929.00),
(24, 24, 11, 1, 2929.00),
(25, 25, 11, 1, 2929.00),
(26, 26, 11, 1, 2929.00),
(27, 27, 11, 1, 2929.00),
(28, 28, 11, 1, 2929.00),
(29, 29, 11, 1, 2929.00),
(30, 30, 11, 1, 2929.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier') DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `user_type` enum('admin','cashier','customer') NOT NULL DEFAULT 'customer',
  `profile_image` varchar(255) NOT NULL DEFAULT 'default.png',
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `fullname`, `user_type`, `profile_image`, `name`) VALUES
(1, 'admin', '$2y$10$SgEqEkkFrnZ77FBM3XvBLeDjaJLLyWruIt/8r1o3/6go6Dk9C5UDW', 'admin', NULL, 'customer', 'default.png', NULL),
(2, 'cashier', '$2y$10$qTg8b6oB6gsspvEmEiwIeuV8XwubbGKr6ietuJKPAp5t82SBKHYVy', 'cashier', 'jesie andrei', 'customer', 'default.png', NULL),
(3, 'palit', '$2y$10$ppiTJ.Qex.e7C/GKwn6hKO6kzS8BPmhsS5lDwt8ZNNW0DyHv65H.W', NULL, NULL, 'customer', 'default.png', NULL),
(4, 'liza', '$2y$10$AoIY3jtJLKQL4VRlPZ4.cOH3XlZI1W10fu6eLIKO30I0LZLS0Z7q6', 'cashier', 'liza alin', 'customer', 'default.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_number` (`receipt_number`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `fk_sale_id` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
