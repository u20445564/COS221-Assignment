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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `userID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `adminID` (`adminID`),
  CONSTRAINT `fk_admin_userbase` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,1,'Site Administrator'),
(2,2,'Content Manager');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `brandID` int(11) NOT NULL AUTO_INCREMENT,
  `brandName` varchar(255) NOT NULL,
  PRIMARY KEY (`brandID`),
  UNIQUE KEY `brandName` (`brandName`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES
(1,'Acme Tech'),
(4,'BuildRight'),
(2,'FashionCo'),
(5,'HomeEssentials'),
(3,'ReadMore');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `categoryName` (`categoryName`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(3,'Books'),
(2,'Clothing'),
(1,'Electronics'),
(5,'Kitchenware'),
(4,'Tools');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prices` (
  `retailerID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `prices` decimal(65,2) NOT NULL,
  KEY `fk_prices_retailers` (`retailerID`),
  KEY `fk_prices_products` (`productID`),
  CONSTRAINT `fk_prices_products` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  CONSTRAINT `fk_prices_retailers` FOREIGN KEY (`retailerID`) REFERENCES `retailers` (`retailerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` VALUES
(1,1,699.99),
(1,6,149.50),
(2,1,689.00),
(2,2,29.99),
(3,6,159.00),
(3,8,119.95),
(4,2,25.00),
(4,3,12.50),
(5,3,11.99),
(5,5,49.99),
(6,4,89.99),
(6,9,39.95),
(7,4,85.50),
(7,9,42.00),
(8,7,59.99),
(8,10,79.99),
(9,6,155.00),
(9,8,115.00),
(10,5,47.50),
(10,10,75.00);
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `productName` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `brandID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `imageURL` varchar(255) NOT NULL,
  `specifications` text NOT NULL,
  PRIMARY KEY (`productID`),
  KEY `fk_products_brand` (`brandID`),
  KEY `fk_products_categories` (`categoryID`),
  CONSTRAINT `fk_products_brand` FOREIGN KEY (`brandID`) REFERENCES `brands` (`brandID`),
  CONSTRAINT `fk_products_categories` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(1,'Smartphone X1','Latest X1 model with 6.5″ display.',1,1,'/img/x1.png','{\"battery\":\"4000mAh\",\"ram\":\"8GB\"}'),
(2,'Designer T-Shirt','100% cotton, unisex fit.',2,2,'/img/tee.png','{\"size\":\"M\",\"color\":\"navy\"}'),
(3,'Hardcover Journal','200 pages, ruled.',3,3,'/img/journal.png','{\"pages\":200,\"paper\":\"acid-free\"}'),
(4,'Cordless Drill','18V Li-Ion drill with LED light.',4,4,'/img/drill.png','{\"voltage\":\"18V\",\"speed\":\"1500rpm\"}'),
(5,'Non-Stick Pan','28cm pan, PTFE coating.',5,5,'/img/pan.png','{\"diameter\":\"28cm\",\"material\":\"aluminum\"}'),
(6,'Noise-Cancel Headset','Over-ear, wireless.',1,1,'/img/headset.png','{\"battery\":\"20h\",\"range\":\"10m\"}'),
(7,'Jeans Slim Fit','Dark wash, stretch fabric.',2,2,'/img/jeans.png','{\"waist\":\"32\",\"length\":\"34\"}'),
(8,'E-Reader Pro','8″ e-ink display, 16GB storage.',3,1,'/img/ereader.png','{\"storage\":\"16GB\",\"screen\":\"8in\"}'),
(9,'Multi-Tool Kit','15-in-1 stainless steel.',4,4,'/img/multitool.png','{\"tools\":15,\"material\":\"SS\"}'),
(10,'Ceramic Knife Set','5-piece kitchen set.',5,5,'/img/knives.png','{\"pieces\":5,\"blade\":\"ceramic\"}');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `requestID` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(55) DEFAULT 'pending',
  `requestCode` varchar(25) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `modifiedAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`requestID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
INSERT INTO `requests` VALUES
(1,'pending','ADD001','{\"retailerID\": 1, \"product_name\": \"Gaming Mouse Pro\", \"description\": \"High-precision wireless gaming mouse with RGB lighting.\", \"brandID\": 1, \"categoryID\": 1, \"price\": 499.99, \"imageURL\": \"/img/gaming_mouse.png\"}','2025-05-26 22:52:07','2025-05-26 22:52:07'),
(2,'pending','ADD002','{\"retailerID\": 5, \"product_name\": \"Travel Journal Hardcover\", \"description\": \"Leather-bound travel journal, 250 pages ruled.\", \"brandID\": 3, \"categoryID\": 3, \"price\": 159.95, \"imageURL\": \"/img/travel_journal.png\"}','2025-05-26 22:52:07','2025-05-26 22:52:07'),
(3,'completed','UPD003','{\"retailerID\": 3, \"product_name\": \"Noise-Cancel Headset\", \"description\": \"Over-ear wireless headset with active noise canceling.\", \"brandID\": 1, \"categoryID\": 1, \"price\": 129.50, \"imageURL\": \"/img/headset_v2.png\"}','2025-05-26 22:52:07','2025-05-26 22:52:07'),
(4,'pending','DEL004','{\"retailerID\": 4, \"product_name\": \"Designer T-Shirt\", \"description\": \"100% cotton upscale tee, unisex fit.\", \"brandID\": 2, \"categoryID\": 2, \"price\": 39.99, \"imageURL\": \"/img/tee_del.png\"}','2025-05-26 22:52:07','2025-05-26 22:52:07'),
(5,'completed','UPD005','{\"retailerID\": 2, \"product_name\": \"Smartphone X1\", \"description\": \"Latest X1 model — update to 128GB storage variant.\", \"brandID\": 1, \"categoryID\": 1, \"price\": 799.99, \"imageURL\": \"/img/x1_128gb.png\"}','2025-05-26 22:52:07','2025-05-26 22:52:07'),
(6,'rejected','DEL006','{\"retailerID\": 7, \"product_name\": \"Cordless Drill\", \"description\": \"18V Li-Ion drill with LED light.\", \"brandID\": 4, \"categoryID\": 4, \"price\": 89.99, \"imageURL\": \"/img/drill_reject.png\"}','2025-05-26 22:52:22','2025-05-26 22:52:22');
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retailers`
--

DROP TABLE IF EXISTS `retailers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `retailers` (
  `retailerID` int(11) NOT NULL AUTO_INCREMENT,
  `retailerName` varchar(100) NOT NULL,
  PRIMARY KEY (`retailerID`),
  UNIQUE KEY `retailerName` (`retailerName`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retailers`
--

LOCK TABLES `retailers` WRITE;
/*!40000 ALTER TABLE `retailers` DISABLE KEYS */;
INSERT INTO `retailers` VALUES
(7,'Anderson Auto'),
(3,'Gonzalez Gadgets'),
(1,'Hernandez Supplies'),
(9,'Jackson Jewelry'),
(10,'King Kitchenware'),
(2,'Lopez Electronics'),
(5,'Moore Books'),
(6,'Taylor Tools'),
(8,'Thomas Toys'),
(4,'Wilson Clothing');
/*!40000 ALTER TABLE `retailers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retailerusers`
--

DROP TABLE IF EXISTS `retailerusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `retailerusers` (
  `userID` int(11) NOT NULL,
  `retailerID` int(11) NOT NULL,
  `retailerName` varchar(100) NOT NULL,
  `retailerCode` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `retailerName` (`retailerName`),
  UNIQUE KEY `retailerCode` (`retailerCode`),
  KEY `fk_retailerusers_retailers` (`retailerID`),
  CONSTRAINT `fk_retailerUsers_userbase` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`) ON DELETE CASCADE,
  CONSTRAINT `fk_retailerusers_retailers` FOREIGN KEY (`retailerID`) REFERENCES `retailers` (`retailerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retailerusers`
--

LOCK TABLES `retailerusers` WRITE;
/*!40000 ALTER TABLE `retailerusers` DISABLE KEYS */;
INSERT INTO `retailerusers` VALUES
(11,1,'Hernandez Supplies','HSUP1'),
(12,2,'Lopez Electronics','LEC02'),
(13,3,'Gonzalez Gadgets','GGA03'),
(14,4,'Wilson Clothing','WCL04'),
(15,5,'Moore Books','MBK05'),
(16,6,'Taylor Tools','TTL06'),
(17,7,'Anderson Auto','AAT07'),
(18,8,'Thomas Toys','TTO08'),
(19,9,'Jackson Jewelry','JJE09'),
(20,10,'King Kitchenware','KKW10');
/*!40000 ALTER TABLE `retailerusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `reviewDate` datetime NOT NULL DEFAULT current_timestamp(),
  `comment` varchar(255) NOT NULL,
  `retailerID` int(11) NOT NULL,
  `retailerResponse` varchar(255) DEFAULT NULL,
  `retailerRDate` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`reviewID`),
  KEY `fk_reviews_users` (`userID`),
  KEY `fk_reviews_products` (`productID`),
  KEY `fk_reviews_retailer` (`retailerID`),
  CONSTRAINT `fk_reviews_products` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  CONSTRAINT `fk_reviews_retailer` FOREIGN KEY (`retailerID`) REFERENCES `retailers` (`retailerID`),
  CONSTRAINT `fk_reviews_users` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES
(1,1,1,'2025-05-26 22:49:22','Fantastic phone, battery lasts all day.',2,'Thanks for your review!','2025-05-26 22:49:22'),
(2,2,3,'2025-05-26 22:49:22','Journal paper quality is excellent.',5,NULL,'2025-05-26 22:49:22'),
(3,3,6,'2025-05-26 22:49:22','Headset comfort could be better.',1,'We appreciate the feedback.','2025-05-26 22:49:22'),
(4,4,10,'2025-05-26 22:49:22','Knife set is sharp and easy to clean.',10,'Glad you like it!','2025-05-26 22:49:22'),
(5,5,4,'2025-05-26 22:49:22','Drill stopped working after 2 months.',7,'Please contact support.','2025-05-26 22:49:22');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `userFName` varchar(255) NOT NULL,
  `userSName` varchar(10) NOT NULL,
  PRIMARY KEY (`userID`),
  CONSTRAINT `fk_user_userbase` FOREIGN KEY (`userID`) REFERENCES `userbase` (`userID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'johnsmith','John','Smith'),
(2,'maryj','Mary','Johnson'),
(3,'pwilliams','Peter','Williams'),
(4,'lindab','Linda','Brown'),
(5,'rjones','Robert','Jones'),
(6,'patriciam','Patricia','Miller'),
(7,'mdavis','Michael','Davis'),
(8,'bgarcia','Barbara','Garcia'),
(9,'wrodriguez','William','Rodriguez'),
(10,'emartinez','Elizabeth','Martinez');
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
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phoneNumber` varchar(10) NOT NULL,
  `apiKey` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `phoneNumber` (`phoneNumber`),
  UNIQUE KEY `apiKey` (`apiKey`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userbase`
--

LOCK TABLES `userbase` WRITE;
/*!40000 ALTER TABLE `userbase` DISABLE KEYS */;
INSERT INTO `userbase` VALUES
(1,'Password123!','john.smith@example.com','0821234567','3f2504e0-4f89-11d3-9a0c-0305e82c3301'),
(2,'Sunshine2025','mary.johnson@mail.com','0832345678','9c858901-8a57-4791-81fe-4c455b099bc9'),
(3,'BlueSky!89','peter.williams@test.org','0843456789','2e1f6bda-1c3e-4d42-8b4f-7f90c45e9a72'),
(4,'CoffeeLover#1','linda.brown@sample.co.za','0724567890','5a8d7c63-4a1b-4f2c-bcde-1234567890ab'),
(5,'Tiger2023$','robert.jones@example.net','0735678901','7b6e2a99-2e1d-4b2c-8f1a-0b9c8d7e6f5d'),
(6,'OceanView77','patricia.miller@mail.co','0746789012','8f14e45f-ea8b-4f3a-aa3d-2a4f1b6c7d8e'),
(7,'GuitarHero9','michael.davis@domain.com','0757890123','1c4e6a5d-3b2f-4c1a-b7d8-9e0f1a2b3c4d'),
(8,'StarWars!73','barbara.garcia@test.org','0768901234','4d5e6f7a-8b9c-4d0e-a1b2-3c4d5e6f7a8b'),
(9,'DragonFly22','william.rodriguez@sample.org','0779012345','6a7b8c9d-0e1f-4a2b-c3d4-5e6f7a8b9c0d'),
(10,'MapleLeaf#8','elizabeth.martinez@example.com','0780123456','0f1e2d3c-4b5a-6d7e-8f9a-0b1c2d3e4f5g'),
(11,'Sunset_456','james.hernandez@mail.com','0791234567','abcdef12-3456-7890-abcd-ef1234567890'),
(12,'CoffeeBean09','jennifer.lopez@test.net','0812345678','12345678-9abc-def0-1234-56789abcdef0'),
(13,'Rock&Roll77','charles.gonzalez@sample.co','0823456789','fedcba98-7654-3210-fedc-ba9876543210'),
(14,'HappyDays2025','susan.wilson@example.org','0834567890','0a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d'),
(15,'Mountain#1','joseph.moore@mail.co.za','0845678901','9d8c7b6a-5e4f-3d2c-1b0a-9e8d7c6b5a4f'),
(16,'Bookworm123','lisa.taylor@test.com','0726789012','3a2b1c0d-4e5f-6a7b-8c9d-0e1f2a3b4c5f'),
(17,'Galaxy9!X','thomas.anderson@domain.net','0737890123','5f4e3d2c-1b0a-9e8d-7c6b-5a4f3e2d1c0b'),
(18,'CoffeeBreak7','karen.thomas@sample.org','0748901234','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d'),
(19,'RainyDay#4','daniel.jackson@example.co','0759012345','6c5b4a3d-2e1f-0a9b-8c7d-6e5f4a3b2c1d'),
(20,'Sunflower_21',NULL,'0760123456','7e8f9a0b-1c2d-3e4f-5a6b-7c8d9e0f1a2b');
/*!40000 ALTER TABLE `userbase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'compareit_binarybandits'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-26 23:22:41
