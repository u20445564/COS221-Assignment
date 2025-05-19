-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: compareit_binarybandits
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adminretailer`
--

DROP TABLE IF EXISTS `adminretailer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
INSERT INTO `adminretailer` VALUES (31,1),(32,2),(33,3),(34,4),(35,5),(36,6),(37,7),(38,8),(39,9),(40,10);
/*!40000 ALTER TABLE `adminretailer` ENABLE KEYS */;
UNLOCK TABLES;
