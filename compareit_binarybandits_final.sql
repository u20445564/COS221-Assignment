-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 12:08 PM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `compareit_binarybandits`
--

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `rating` int(6) NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `response` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `retailerID` int(11) DEFAULT NULL,
  `responseDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `productID`, `userID`, `rating`, `comment`, `response`, `createdAt`, `retailerID`, `responseDate`) VALUES
(2, 4, 2, 3, 'It was good until it wasn\'t', NULL, '2025-05-21 14:22:30', 2, '2025-05-22 11:30:00'),
(4, 5, 4, 2, 'Non-Stick Pan coating started to peel after a few uses.', NULL, '2025-05-23 11:30:20', NULL, NULL),
(5, 10, 5, 4, 'Ceramic Knife Set is very sharp and lightweight.', NULL, '2025-05-24 16:45:10', 5, '2025-05-25 12:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `fk_reviews_retailers` (`retailerID`),
  ADD KEY `fk_reviews_users` (`userID`),
  ADD KEY `fk_reviews_products` (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_products` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_retailers` FOREIGN KEY (`retailerID`) REFERENCES `retailers` (`retailerID`),
  ADD CONSTRAINT `fk_reviews_users` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
