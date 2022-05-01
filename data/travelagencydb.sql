-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2020 at 09:20 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelagencydb`
--
CREATE DATABASE IF NOT EXISTS `travelagencydb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `travelagencydb`;

-- --------------------------------------------------------

--
-- Table structure for table `tbljourneys`
--

DROP TABLE IF EXISTS `tbljourneys`;
CREATE TABLE `tbljourneys` (
  `journeyNum` int(11) NOT NULL COMMENT 'מספר טיול',
  `journeyName` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'שם טיול',
  `journeyDescription` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'תיאטור מסלול',
  `journeyStartDate` date NOT NULL COMMENT 'תאריך התחלה',
  `journeyDuration` int(11) NOT NULL COMMENT 'משך הטיול',
  `journeyPrice` decimal(9,2) NOT NULL COMMENT 'מחיר ליחיד',
  `journeyKosher` char(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'כשר',
  `journeyAudiancesCode` int(11) NOT NULL COMMENT 'קוד קהל יעד'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblorders`
--

DROP TABLE IF EXISTS `tblorders`;
CREATE TABLE `tblorders` (
  `orderNum` int(11) NOT NULL COMMENT 'מספר הזמנה',
  `orderUserNum` int(11) NOT NULL COMMENT 'מספר משתמש ',
  `orderJournyNum` int(11) NOT NULL COMMENT 'מספר טיול',
  `orderQuantity` int(11) NOT NULL COMMENT 'כמות מוזמנת',
  `orederDate` date NOT NULL COMMENT 'תאריך הזמנה'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

DROP TABLE IF EXISTS `tblusers`;
CREATE TABLE `tblusers` (
  `userNum` int(11) NOT NULL COMMENT 'מספר סידורי',
  `userEmail` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'מייל',
  `userPassword` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'סיסמא',
  `userType` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ל' COMMENT 'סוג משתמש',
  `userRealname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'שם אמיתי'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbljourneys`
--
ALTER TABLE `tbljourneys`
  ADD PRIMARY KEY (`journeyNum`);

--
-- Indexes for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD PRIMARY KEY (`orderNum`),
  ADD KEY `orderUserNum` (`orderUserNum`),
  ADD KEY `orderJournyNum` (`orderJournyNum`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`userNum`),
  ADD UNIQUE KEY `emlKy` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbljourneys`
--
ALTER TABLE `tbljourneys`
  MODIFY `journeyNum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'מספר טיול';

--
-- AUTO_INCREMENT for table `tblorders`
--
ALTER TABLE `tblorders`
  MODIFY `orderNum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'מספר הזמנה';

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `userNum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'מספר סידורי';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD CONSTRAINT `tblorders_ibfk_1` FOREIGN KEY (`orderUserNum`) REFERENCES `tblusers` (`userNum`),
  ADD CONSTRAINT `tblorders_ibfk_2` FOREIGN KEY (`orderJournyNum`) REFERENCES `tbljourneys` (`journeyNum`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
