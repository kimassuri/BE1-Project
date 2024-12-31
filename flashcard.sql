-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 29, 2024 at 02:58 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flashcard`
--
CREATE DATABASE IF NOT EXISTS `flashcard` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `flashcard`;
-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `deck_id` int NOT NULL,
  `term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `definition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deckID` (`deck_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `deck_id`, `term`, `definition`) VALUES
(19, 6, 'Chicken　', '鶏肉'),
(20, 6, 'Pork', '豚肉'),
(21, 7, 'Chicken　', '鶏肉'),
(22, 7, 'Pork', '豚肉'),
(23, 8, 'Chicken', 'Con gà'),
(24, 8, 'Pig', 'Con heo'),
(25, 8, 'Query ', 'Truy vấn'),
(28, 11, 'Màu xanh', 'Aoi'),
(29, 11, 'Màu đỏ', 'Akai'),
(30, 12, 'God', 'Customer'),
(31, 12, 'bed', 'Train'),
(32, 13, 'Xin chào ', 'ជំរាបសួរ'),
(33, 13, 'Chào buổi sáng', 'លាហើយ'),
(34, 14, '朝:あさ', 'buổi sáng'),
(35, 14, '朝ご飯', 'bữa ăn sáng'),
(36, 14, '足', 'chân');

-- --------------------------------------------------------

--
-- Table structure for table `decks`
--

DROP TABLE IF EXISTS `decks`;
CREATE TABLE IF NOT EXISTS `decks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `favorites` int NOT NULL DEFAULT '0',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `decks`
--

INSERT INTO `decks` (`id`, `user_id`, `name`, `size`, `time`, `favorites`, `is_deleted`) VALUES
(6, 1, 'English - Japanese', 2, '2024-12-29 13:00:51', 0, 0),
(7, 3, 'English - Japanese', 2, '2024-12-29 13:17:20', 5, 0),
(8, 3, 'English IT', 3, '2024-12-29 13:17:30', 0, 0),
(11, 5, 'Tiếng nhật', 2, '2024-12-29 14:47:12', 0, 0),
(12, 5, 'Tiếng anh - Nhật', 2, '2024-12-29 14:47:59', 1, 0),
(13, 5, 'Tiếng Khmer', 2, '2024-12-29 14:49:33', 0, 0),
(14, 1, 'Tiếng nhật ', 3, '2024-12-29 14:52:46', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `deck_folder`
--

DROP TABLE IF EXISTS `deck_folder`;
CREATE TABLE IF NOT EXISTS `deck_folder` (
  `deck_id` int NOT NULL,
  `folder_id` int NOT NULL,
  PRIMARY KEY (`deck_id`,`folder_id`),
  KEY `fk_deck_folder_folder_id` (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE IF NOT EXISTS `folders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nameFolder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `user_id`, `nameFolder`, `is_deleted`) VALUES
(1, 1, 'Nihongo - Wakarimasen', 0),
(2, 1, 'English - I don\'t know', 0),
(6, 1, 'French - Je ne sais pas', 0),
(15, 5, 'Asura', 0),
(16, 5, 'Ausura 1.0', 0),
(17, 5, 'Asura 2.0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gmail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `gmail`) VALUES
(1, 'Fugue', '$2y$10$z0LNIHh2550hHtpo7rclmulgaImWkSQv.NIwKkUdgDyLj/MpDzQA6', 'fugue@gmail.com'),
(2, 'jing sama', '$2y$10$3ZT6/0hdNhHpv2/SWxsS3umj48b7wiNm4YkPNKbDPTBKrIlbV8s1C', 'jing@gmail.com'),
(3, 'Marcus', '$2y$10$rTvy9Z96KfGNY76DFJh6F.xcamAzn1JpGS7.2ZAZqeDNP901Pi2Qu', 'marcus@gmail.com'),
(4, 'Yan', '$2y$10$Ia1G7ZkaiWqkZxvEvbCgmeH3Ukqn7dwjhmROcHIqK72RGZA/SXgNy', 'yan@gmail.com'),
(5, 'Asura', '$2y$10$lM766RJ1gXaV5wfDhJi6duutJq7WLtpHe6ncRUpr5x7dNuJfywjpa', 'asura@gmail.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `deckID` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deck_folder`
--
ALTER TABLE `deck_folder`
  ADD CONSTRAINT `fk_deck_folder_deck_id` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_deck_folder_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
