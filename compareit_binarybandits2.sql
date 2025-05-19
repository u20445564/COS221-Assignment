/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: compareit_binarybandits
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `adminretailer`
--

DROP TABLE IF EXISTS `adminretailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `adminretailer` (
  `UserID` int(11) NOT NULL,
  `RetailerID` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RetailerID`),
  KEY `idx_AR_User` (`UserID`),
  KEY `idx_AR_Retailer` (`RetailerID`),
  CONSTRAINT `fk_AR_Retailer` FOREIGN KEY (`RetailerID`) REFERENCES `retailer` (`retailerID`) ON UPDATE CASCADE,
  CONSTRAINT `fk_AR_User` FOREIGN KEY (`UserID`) REFERENCES `adminuser` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adminretailer`
--

LOCK TABLES `adminretailer` WRITE;
/*!40000 ALTER TABLE `adminretailer` DISABLE KEYS */;
INSERT INTO `adminretailer` VALUES
(31,1),
(32,2),
(33,3),
(34,4),
(35,5),
(36,6),
(37,7),
(38,8),
(39,9),
(40,10);
/*!40000 ALTER TABLE `adminretailer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adminuser`
--

DROP TABLE IF EXISTS `adminuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `adminuser` (
  `userID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `admin_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  CONSTRAINT `adminuser_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adminuser`
--

LOCK TABLES `adminuser` WRITE;
/*!40000 ALTER TABLE `adminuser` DISABLE KEYS */;
INSERT INTO `adminuser` VALUES
(31,2001,'Admin1'),
(32,2002,'Admin2'),
(33,2003,'Admin3'),
(34,2004,'Admin4'),
(35,2005,'Admin5'),
(36,2006,'Admin6'),
(37,2007,'Admin7'),
(38,2008,'Admin8'),
(39,2009,'Admin9'),
(40,2010,'Admin10');
/*!40000 ALTER TABLE `adminuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `brandID` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(50) NOT NULL,
  PRIMARY KEY (`brandID`),
  UNIQUE KEY `brand_name` (`brand_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES
(2,'Albany'),
(1,'Clover'),
(3,'Coca-Cola'),
(8,'Freshmark'),
(6,'Koo'),
(7,'Rainbow Chicken'),
(4,'Simba'),
(5,'Sunlight');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES
(10,'Baby Products'),
(5,'Bakery'),
(6,'Beverages'),
(4,'Dairy'),
(2,'Fresh Produce'),
(1,'Groceries'),
(9,'Health & Beauty'),
(8,'Household Essentials'),
(3,'Meat & Poultry'),
(7,'Snacks');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comparison`
--

DROP TABLE IF EXISTS `comparison`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comparison` (
  `retailerID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`retailerID`,`productID`),
  KEY `productID` (`productID`),
  CONSTRAINT `comparison_ibfk_1` FOREIGN KEY (`retailerID`) REFERENCES `retailer` (`retailerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comparison_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comparison`
--

LOCK TABLES `comparison` WRITE;
/*!40000 ALTER TABLE `comparison` DISABLE KEYS */;
INSERT INTO `comparison` VALUES
(1,3001,10.50),
(2,3002,11.00),
(3,3003,11.50),
(4,3004,12.00),
(5,3005,12.50),
(6,3006,13.00),
(7,3007,13.50),
(8,3008,14.00);
/*!40000 ALTER TABLE `comparison` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productcategory`
--

DROP TABLE IF EXISTS `productcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `productcategory` (
  `ProductID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  PRIMARY KEY (`ProductID`,`CategoryID`),
  KEY `CategoryID` (`CategoryID`),
  CONSTRAINT `productcategory_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productcategory_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`categoryID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productcategory`
--

LOCK TABLES `productcategory` WRITE;
/*!40000 ALTER TABLE `productcategory` DISABLE KEYS */;
INSERT INTO `productcategory` VALUES
(3001,1),
(3001,2);
/*!40000 ALTER TABLE `productcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `brandID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `imageURL` varchar(100) DEFAULT NULL,
  `specifications` text NOT NULL,
  PRIMARY KEY (`productID`),
  KEY `FK_products_brand` (`brandID`),
  KEY `FK_products_category` (`categoryID`),
  CONSTRAINT `FK_products_brand` FOREIGN KEY (`brandID`) REFERENCES `brands` (`brandID`),
  CONSTRAINT `FK_products_category` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=3009 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(3001,'Clover Milk 1L','Fresh and nutritious full cream milk',1,1,NULL,''),
(3002,'Albany White Bread','Soft and fluffy white bread',2,1,NULL,''),
(3003,'Coca-Cola 500ml','Refreshing soft drink',3,2,NULL,''),
(3004,'Simba Potato Chips','Delicious crispy potato chips',4,2,NULL,''),
(3005,'Sunlight Dishwashing Liquid','Powerful dishwashing liquid',5,3,NULL,''),
(3006,'Nivea Soft Cream','Moisturizing cream for soft skin',6,4,NULL,''),
(3007,'Huggies Ultra Dry Diapers','Soft and absorbent baby diapers',7,5,NULL,''),
(3008,'Koo Baked Beans','Delicious baked beans in tomato sauce',8,6,NULL,'');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `request` (
  `requestID` int(11) NOT NULL AUTO_INCREMENT,
  `requestCode` varchar(50) NOT NULL,
  `retailerID` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productID` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `brandID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  `imageURL` varchar(512) DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`requestID`),
  UNIQUE KEY `requestCode` (`requestCode`),
  KEY `retailerID` (`retailerID`),
  KEY `productID` (`productID`),
  KEY `brandID` (`brandID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `request_ibfk_1` FOREIGN KEY (`retailerID`) REFERENCES `retailer` (`retailerID`) ON UPDATE CASCADE,
  CONSTRAINT `request_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `request_ibfk_3` FOREIGN KEY (`brandID`) REFERENCES `brands` (`brandID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `request_ibfk_4` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES
(1,'REQ001',1,'Product1',3001,'Description for request 1',4001,5001,NULL,'Dimensions: 10×5×2 cm; Weight: 250 g; Color: White',0),
(2,'REQ002',2,'Product2',3002,'Description for request 2',4002,5002,NULL,'Material: Stainless steel; Finish: Matte; Warranty: 1 year',0),
(3,'REQ003',3,'Product3',3003,'Description for request 3',4003,5003,NULL,'Capacity: 500 ml; BPA-free; Dishwasher safe',0),
(4,'REQ004',4,'Product4',3004,'Description for request 4',4004,5004,NULL,'Crunch level: Extra crispy; Flavor: Salt & vinegar',0),
(5,'REQ005',5,'Product5',3005,'Description for request 5',4005,5005,NULL,'Formula: PH-balanced; Scent: Lemon; Volume: 750 ml',0),
(6,'REQ006',6,'Product6',3006,'Description for request 6',4001,5001,NULL,'Texture: Non-greasy; SPF: 15; Fragrance: Unscented',0),
(7,'REQ007',7,'Product7',3007,'Description for request 7',4002,5002,NULL,'Size: Newborn–12 kg; Hypoallergenic; Pack of 24',0),
(8,'REQ008',8,'Product8',3008,'Description for request 8',4003,5003,NULL,'Net weight: 410 g; Tomato sauce; Preservative-free',0),
(9,'REQ009',9,'Product9',3009,'Description for request 9',4004,5004,NULL,'Paper type: Whole wheat; Loaf size: 700 g; Sliced',0),
(10,'REQ010',10,'Product10',3010,'Description for request 10',4005,5005,NULL,'Ice-cold chilled formula; Sugar-free; 330 ml can',0),
(11,'REQ011',1,'Product11',3011,'Description for request 11',4001,5001,NULL,'Fat content: 2%; Homogenized; Best before: 6 months',0),
(12,'REQ012',2,'Product12',3012,'Description for request 12',4002,5002,NULL,'Loaf count: 2-pack; Soft crust; Vegetarian friendly',0),
(13,'REQ013',3,'Product13',3013,'Description for request 13',4003,5003,NULL,'Tube length: 250 ml; Flavor: Original; Recyclable',0),
(14,'REQ014',4,'Product14',3014,'Description for request 14',4004,5004,NULL,'Bag size: 125 g; Flavour: Salt & vinegar; Halal',0),
(15,'REQ015',5,'Product15',3015,'Description for request 15',4005,5005,NULL,'Active enzyme: Protease; Phosphate-free; 1 L bottle',0),
(16,'REQ016',6,'Product16',3016,'Description for request 16',4001,5001,NULL,'Application: Face & body; Dermatologist tested; Paraben-free',0),
(17,'REQ017',7,'Product17',3017,'Description for request 17',4002,5002,NULL,'Diaper count: 30; Leak-guard system; Latex-free',0),
(18,'REQ018',8,'Product18',3018,'Description for request 18',4003,5003,NULL,'Beans: Small; Sauce: Medium spice; BPA-free can',0),
(19,'REQ019',9,'Product19',3019,'Description for request 19',4004,5004,NULL,'Weight: 1 kg; Freshly baked; No preservatives',0),
(20,'REQ020',10,'Product20',3020,'Description for request 20',4005,5005,NULL,'Can size: 330 ml; Caffeine: 32 mg/100 ml; Recyclable',0),
(21,'REQ021',1,'Product21',3021,'Description for request 21',4001,5001,NULL,'Fat: 3.5%; Calcium fortified; 1 L Tetra Pak',0),
(22,'REQ022',2,'Product22',3022,'Description for request 22',4002,5002,NULL,'Loaf weight: 600 g; Wholegrain; No added sugar',0),
(23,'REQ023',3,'Product23',3023,'Description for request 23',4003,5003,NULL,'Bottle: 500 ml; Sugar content: 10 g; Carbonated',0),
(24,'REQ024',4,'Product24',3024,'Description for request 24',4004,5004,NULL,'Crunch: Medium; Bag weight: 110 g; Gluten-free',0),
(25,'REQ025',5,'Product25',3025,'Description for request 25',4005,5005,NULL,'Scent: Citrus; PH: 7; 500 ml pump bottle',0),
(26,'REQ026',6,'Product26',3026,'Description for request 26',4001,5001,NULL,'Includes: Moisturizer & toner; Cruelty-free',0),
(27,'REQ027',7,'Product27',3027,'Description for request 27',4002,5002,NULL,'Sizes: XS–XL; Pack of 20; Hypoallergenic',0),
(28,'REQ028',8,'Product28',3028,'Description for request 28',4003,5003,NULL,'Bean type: Navy; Tomato blend; Low sodium',0),
(29,'REQ029',9,'Product29',3029,'Description for request 29',4004,5004,NULL,'Weight: 800 g; Multi‐seed; No crust',0),
(30,'REQ030',10,'Product30',3030,'Description for request 30',4005,5005,NULL,'Can: 250 ml; Original flavor; BPA-free',0),
(31,'REQ031',1,'Product31',3031,'Description for request 31',4001,5001,NULL,'Fat: 1%; Long life UHT; 500 ml carton',0),
(32,'REQ032',2,'Product32',3032,'Description for request 32',4002,5002,NULL,'Loaf count: 3; Multigrain; Individually wrapped',0),
(33,'REQ033',3,'Product33',3033,'Description for request 33',4003,5003,NULL,'Bottle: 750 ml; Diet formula; Aspartame-free',0),
(34,'REQ034',4,'Product34',3034,'Description for request 34',4004,5004,NULL,'Flavor: BBQ; Bag size: 150 g; Oven baked',0),
(35,'REQ035',5,'Product35',3035,'Description for request 35',4005,5005,NULL,'Fragrance: Floral; 250 ml tube; Dermatologically tested',0),
(36,'REQ036',6,'Product36',3036,'Description for request 36',4001,5001,NULL,'Set includes: 3 creams; SPF 30; Non-comedogenic',0),
(37,'REQ037',7,'Product37',3037,'Description for request 37',4002,5002,NULL,'Diaper size: M; Pack of 32; Leak-lock technology',0),
(38,'REQ038',8,'Product38',3038,'Description for request 38',4003,5003,NULL,'Beans: Large; Spicy sauce; Canned: 410 g',0),
(39,'REQ039',9,'Product39',3039,'Description for request 39',4004,5004,NULL,'Weight: 750 g; Sourdough; Stone-ground',0),
(40,'REQ040',10,'Product40',3040,'Description for request 40',4005,5005,NULL,'Can size: 330 ml; Zero sugar; Recyclable',0);
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retailer`
--

DROP TABLE IF EXISTS `retailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `retailer` (
  `retailerID` int(11) NOT NULL AUTO_INCREMENT,
  `retailer_name` varchar(50) NOT NULL,
  PRIMARY KEY (`retailerID`),
  UNIQUE KEY `retailer_name` (`retailer_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retailer`
--

LOCK TABLES `retailer` WRITE;
/*!40000 ALTER TABLE `retailer` DISABLE KEYS */;
INSERT INTO `retailer` VALUES
(4,'Checkers'),
(8,'Clicks'),
(9,'Dis-Chem'),
(10,'Food Lover\'s Market'),
(7,'Game'),
(6,'Makro'),
(1,'Pick n Pay'),
(2,'Shoprite'),
(5,'Spar'),
(3,'Woolworths');
/*!40000 ALTER TABLE `retailer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retailuser`
--

DROP TABLE IF EXISTS `retailuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `retailuser` (
  `userID` int(11) NOT NULL,
  `retailerID` int(11) NOT NULL,
  `retailerCode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  CONSTRAINT `retailuser_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retailuser`
--

LOCK TABLES `retailuser` WRITE;
/*!40000 ALTER TABLE `retailuser` DISABLE KEYS */;
INSERT INTO `retailuser` VALUES
(21,1,'R001'),
(22,2,'R002'),
(23,3,'R003'),
(24,4,'R004'),
(25,5,'R005'),
(26,6,'R006'),
(27,7,'R007'),
(28,8,'R008'),
(29,9,'R009'),
(30,10,'R010');
/*!40000 ALTER TABLE `retailuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `reviewID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL COMMENT '1–5 stars',
  `reviewDate` datetime NOT NULL DEFAULT current_timestamp(),
  `comment` text DEFAULT NULL,
  PRIMARY KEY (`reviewID`),
  KEY `userID` (`userID`),
  KEY `productID` (`productID`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES
(1,1,3001,2,'2025-05-02 11:30:00','Review comment 1'),
(2,2,3002,3,'2025-05-03 12:30:00','Review comment 2'),
(3,3,3003,4,'2025-05-04 13:30:00','Review comment 3'),
(4,4,3004,5,'2025-05-05 14:30:00','Review comment 4'),
(5,5,3005,1,'2025-05-06 15:30:00','Review comment 5'),
(6,6,3006,2,'2025-05-07 16:30:00','Review comment 6'),
(7,7,3007,3,'2025-05-08 17:30:00','Review comment 7'),
(8,8,3008,4,'2025-05-09 18:30:00','Review comment 8'),
(9,9,3009,5,'2025-05-10 19:30:00','Review comment 9'),
(10,10,3010,1,'2025-05-11 10:30:00','Review comment 10'),
(11,11,3011,2,'2025-05-12 11:30:00','Review comment 11'),
(12,12,3012,3,'2025-05-13 12:30:00','Review comment 12'),
(13,13,3013,4,'2025-05-14 13:30:00','Review comment 13'),
(14,14,3014,5,'2025-05-15 14:30:00','Review comment 14'),
(15,15,3015,1,'2025-05-16 15:30:00','Review comment 15'),
(16,16,3016,2,'2025-05-17 16:30:00','Review comment 16'),
(17,17,3017,3,'2025-05-18 17:30:00','Review comment 17'),
(18,18,3018,4,'2025-05-19 18:30:00','Review comment 18'),
(19,19,3019,5,'2025-05-20 19:30:00','Review comment 19'),
(20,20,3020,1,'2025-05-21 10:30:00','Review comment 20'),
(21,1,3021,2,'2025-05-22 11:30:00','Review comment 21'),
(22,2,3022,3,'2025-05-23 12:30:00','Review comment 22'),
(23,3,3023,4,'2025-05-24 13:30:00','Review comment 23'),
(24,4,3024,5,'2025-05-25 14:30:00','Review comment 24'),
(25,5,3025,1,'2025-05-26 15:30:00','Review comment 25'),
(26,6,3026,2,'2025-05-27 16:30:00','Review comment 26'),
(27,7,3027,3,'2025-05-28 17:30:00','Review comment 27'),
(28,8,3028,4,'2025-05-01 18:30:00','Review comment 28'),
(29,9,3029,5,'2025-05-02 19:30:00','Review comment 29'),
(30,10,3030,1,'2025-05-03 10:30:00','Review comment 30'),
(31,11,3031,2,'2025-05-04 11:30:00','Review comment 31'),
(32,12,3032,3,'2025-05-05 12:30:00','Review comment 32'),
(33,13,3033,4,'2025-05-06 13:30:00','Review comment 33'),
(34,14,3034,5,'2025-05-07 14:30:00','Review comment 34'),
(35,15,3035,1,'2025-05-08 15:30:00','Review comment 35'),
(36,16,3036,2,'2025-05-09 16:30:00','Review comment 36'),
(37,17,3037,3,'2025-05-10 17:30:00','Review comment 37'),
(38,18,3038,4,'2025-05-11 18:30:00','Review comment 38'),
(39,19,3039,5,'2025-05-12 19:30:00','Review comment 39'),
(40,20,3040,1,'2025-05-13 10:30:00','Review comment 40');
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `userFname` varchar(50) DEFAULT NULL,
  `userSname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userID`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'user1','First1','Last1'),
(2,'user2','First2','Last2'),
(3,'user3','First3','Last3'),
(4,'user4','First4','Last4'),
(5,'user5','First5','Last5'),
(6,'user6','First6','Last6'),
(7,'user7','First7','Last7'),
(8,'user8','First8','Last8'),
(9,'user9','First9','Last9'),
(10,'user10','First10','Last10'),
(11,'user11','First11','Last11'),
(12,'user12','First12','Last12'),
(13,'user13','First13','Last13'),
(14,'user14','First14','Last14'),
(15,'user15','First15','Last15'),
(16,'user16','First16','Last16'),
(17,'user17','First17','Last17'),
(18,'user18','First18','Last18'),
(19,'user19','First19','Last19'),
(20,'user20','First20','Last20');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userbase`
--

DROP TABLE IF EXISTS `userbase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `userbase` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `phone_number` varchar(10) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `phone_number` (`phone_number`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userbase`
--

LOCK TABLES `userbase` WRITE;
/*!40000 ALTER TABLE `userbase` DISABLE KEYS */;
INSERT INTO `userbase` VALUES
(1,'pass1','user1@example.com','010000011'),
(2,'pass2','user2@example.com','010000012'),
(3,'pass3','user3@example.com','010000013'),
(4,'pass4','user4@example.com','010000014'),
(5,'pass5','user5@example.com','010000015'),
(6,'pass6','user6@example.com','010000016'),
(7,'pass7','user7@example.com','010000017'),
(8,'pass8','user8@example.com','010000018'),
(9,'pass9','user9@example.com','010000019'),
(10,'pass10','user10@example.com','010000020'),
(11,'pass11','user11@example.com','010000021'),
(12,'pass12','user12@example.com','010000022'),
(13,'pass13','user13@example.com','010000023'),
(14,'pass14','user14@example.com','010000024'),
(15,'pass15','user15@example.com','010000025'),
(16,'pass16','user16@example.com','010000026'),
(17,'pass17','user17@example.com','010000027'),
(18,'pass18','user18@example.com','010000028'),
(19,'pass19','user19@example.com','010000029'),
(20,'pass20','user20@example.com','010000030'),
(21,'pass21','user21@example.com','010000031'),
(22,'pass22','user22@example.com','010000032'),
(23,'pass23','user23@example.com','010000033'),
(24,'pass24','user24@example.com','010000034'),
(25,'pass25','user25@example.com','010000035'),
(26,'pass26','user26@example.com','010000036'),
(27,'pass27','user27@example.com','010000037'),
(28,'pass28','user28@example.com','010000038'),
(29,'pass29','user29@example.com','010000039'),
(30,'pass30','user30@example.com','010000040'),
(31,'pass31','user31@example.com','010000041'),
(32,'pass32','user32@example.com','010000042'),
(33,'pass33','user33@example.com','010000043'),
(34,'pass34','user34@example.com','010000044'),
(35,'pass35','user35@example.com','010000045'),
(36,'pass36','user36@example.com','010000046'),
(37,'pass37','user37@example.com','010000047'),
(38,'pass38','user38@example.com','010000048'),
(39,'pass39','user39@example.com','010000049'),
(40,'pass40','user40@example.com','010000050');
/*!40000 ALTER TABLE `userbase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vw_adminretailer`
--

DROP TABLE IF EXISTS `vw_adminretailer`;
/*!50001 DROP VIEW IF EXISTS `vw_adminretailer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_adminretailer` AS SELECT
 1 AS `RetailerID`,
  1 AS `RetailerName`,
  1 AS `AdminUserID`,
  1 AS `AdminID`,
  1 AS `AdminEmail`,
  1 AS `AdminPhone`,
  1 AS `AdminName` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_productcatalog`
--

DROP TABLE IF EXISTS `vw_productcatalog`;
/*!50001 DROP VIEW IF EXISTS `vw_productcatalog`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_productcatalog` AS SELECT
 1 AS `ProductID`,
  1 AS `Product_name`,
  1 AS `brand_name`,
  1 AS `category_name`,
  1 AS `RetailerID`,
  1 AS `Price` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_productcategories`
--

DROP TABLE IF EXISTS `vw_productcategories`;
/*!50001 DROP VIEW IF EXISTS `vw_productcategories`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_productcategories` AS SELECT
 1 AS `ProductID`,
  1 AS `Product_name`,
  1 AS `CategoryID`,
  1 AS `category_name` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_requestdetails`
--

DROP TABLE IF EXISTS `vw_requestdetails`;
/*!50001 DROP VIEW IF EXISTS `vw_requestdetails`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_requestdetails` AS SELECT
 1 AS `requestID`,
  1 AS `requestCode`,
  1 AS `retailerID`,
  1 AS `retailerCode`,
  1 AS `productName`,
  1 AS `brand_name`,
  1 AS `category_name`,
  1 AS `description`,
  1 AS `specifications`,
  1 AS `resolved` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_retailerproducts`
--

DROP TABLE IF EXISTS `vw_retailerproducts`;
/*!50001 DROP VIEW IF EXISTS `vw_retailerproducts`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_retailerproducts` AS SELECT
 1 AS `RetailerID`,
  1 AS `RetailerCode`,
  1 AS `ProductID`,
  1 AS `Product_name`,
  1 AS `Price` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_retailerrequests`
--

DROP TABLE IF EXISTS `vw_retailerrequests`;
/*!50001 DROP VIEW IF EXISTS `vw_retailerrequests`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_retailerrequests` AS SELECT
 1 AS `requestID`,
  1 AS `RetailerID`,
  1 AS `RetailerCode`,
  1 AS `requestCode`,
  1 AS `productName`,
  1 AS `resolved` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_retailuser`
--

DROP TABLE IF EXISTS `vw_retailuser`;
/*!50001 DROP VIEW IF EXISTS `vw_retailuser`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_retailuser` AS SELECT
 1 AS `UserID`,
  1 AS `Email`,
  1 AS `Phone_number`,
  1 AS `RetailerID`,
  1 AS `RetailerCode` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_userproductreviews`
--

DROP TABLE IF EXISTS `vw_userproductreviews`;
/*!50001 DROP VIEW IF EXISTS `vw_userproductreviews`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_userproductreviews` AS SELECT
 1 AS `ReviewID`,
  1 AS `UserID`,
  1 AS `Email`,
  1 AS `ProductID`,
  1 AS `Product_name`,
  1 AS `Rating`,
  1 AS `Comment`,
  1 AS `ReviewDate` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'compareit_binarybandits'
--

--
-- Final view structure for view `vw_adminretailer`
--

/*!50001 DROP VIEW IF EXISTS `vw_adminretailer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_adminretailer` AS select `r`.`retailerID` AS `RetailerID`,`r`.`retailer_name` AS `RetailerName`,`ar`.`UserID` AS `AdminUserID`,`au`.`adminID` AS `AdminID`,`ub`.`email` AS `AdminEmail`,`ub`.`phone_number` AS `AdminPhone`,`au`.`admin_name` AS `AdminName` from (((`retailer` `r` join `adminretailer` `ar` on(`r`.`retailerID` = `ar`.`RetailerID`)) join `adminuser` `au` on(`ar`.`UserID` = `au`.`userID`)) join `userbase` `ub` on(`au`.`userID` = `ub`.`userID`)) order by `r`.`retailerID` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_productcatalog`
--

/*!50001 DROP VIEW IF EXISTS `vw_productcatalog`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_productcatalog` AS select `p`.`productID` AS `ProductID`,`p`.`product_name` AS `Product_name`,`b`.`brand_name` AS `brand_name`,`c`.`category_name` AS `category_name`,`cmp`.`retailerID` AS `RetailerID`,`cmp`.`price` AS `Price` from (((`products` `p` join `brands` `b` on(`p`.`brandID` = `b`.`brandID`)) join `category` `c` on(`p`.`categoryID` = `c`.`categoryID`)) join `comparison` `cmp` on(`p`.`productID` = `cmp`.`productID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_productcategories`
--

/*!50001 DROP VIEW IF EXISTS `vw_productcategories`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_productcategories` AS select `p`.`productID` AS `ProductID`,`p`.`product_name` AS `Product_name`,`pc`.`CategoryID` AS `CategoryID`,`c`.`category_name` AS `category_name` from ((`productcategory` `pc` join `products` `p` on(`pc`.`ProductID` = `p`.`productID`)) join `category` `c` on(`pc`.`CategoryID` = `c`.`categoryID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_requestdetails`
--

/*!50001 DROP VIEW IF EXISTS `vw_requestdetails`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_requestdetails` AS select `req`.`requestID` AS `requestID`,`req`.`requestCode` AS `requestCode`,`req`.`retailerID` AS `retailerID`,`ru`.`retailerCode` AS `retailerCode`,`req`.`productName` AS `productName`,`b`.`brand_name` AS `brand_name`,`cat`.`category_name` AS `category_name`,`req`.`description` AS `description`,`req`.`specifications` AS `specifications`,`req`.`resolved` AS `resolved` from ((((`request` `req` left join `retailer` `r` on(`req`.`retailerID` = `r`.`retailerID`)) left join `retailuser` `ru` on(`ru`.`retailerID` = `r`.`retailerID`)) left join `brands` `b` on(`req`.`brandID` = `b`.`brandID`)) left join `category` `cat` on(`req`.`categoryID` = `cat`.`categoryID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_retailerproducts`
--

/*!50001 DROP VIEW IF EXISTS `vw_retailerproducts`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_retailerproducts` AS select `r`.`retailerID` AS `RetailerID`,`ru`.`retailerCode` AS `RetailerCode`,`p`.`productID` AS `ProductID`,`p`.`product_name` AS `Product_name`,`cmp`.`price` AS `Price` from (((`comparison` `cmp` join `retailer` `r` on(`cmp`.`retailerID` = `r`.`retailerID`)) left join `retailuser` `ru` on(`r`.`retailerID` = `ru`.`retailerID`)) join `products` `p` on(`cmp`.`productID` = `p`.`productID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_retailerrequests`
--

/*!50001 DROP VIEW IF EXISTS `vw_retailerrequests`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_retailerrequests` AS select `req`.`requestID` AS `requestID`,`r`.`retailerID` AS `RetailerID`,`ru`.`retailerCode` AS `RetailerCode`,`req`.`requestCode` AS `requestCode`,`req`.`productName` AS `productName`,`req`.`resolved` AS `resolved` from ((`request` `req` join `retailer` `r` on(`req`.`retailerID` = `r`.`retailerID`)) join `retailuser` `ru` on(`r`.`retailerID` = `ru`.`retailerID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_retailuser`
--

/*!50001 DROP VIEW IF EXISTS `vw_retailuser`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_retailuser` AS select `r`.`userID` AS `UserID`,`ub`.`email` AS `Email`,`ub`.`phone_number` AS `Phone_number`,`r`.`retailerID` AS `RetailerID`,`r`.`retailerCode` AS `RetailerCode` from (`retailuser` `r` join `userbase` `ub` on(`r`.`userID` = `ub`.`userID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_userproductreviews`
--

/*!50001 DROP VIEW IF EXISTS `vw_userproductreviews`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_uca1400_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_userproductreviews` AS select `rv`.`reviewID` AS `ReviewID`,`ub`.`userID` AS `UserID`,`ub`.`email` AS `Email`,`p`.`productID` AS `ProductID`,`p`.`product_name` AS `Product_name`,`rv`.`rating` AS `Rating`,`rv`.`comment` AS `Comment`,`rv`.`reviewDate` AS `ReviewDate` from ((`review` `rv` join `userbase` `ub` on(`rv`.`userID` = `ub`.`userID`)) join `products` `p` on(`rv`.`productID` = `p`.`productID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-19 20:56:17
