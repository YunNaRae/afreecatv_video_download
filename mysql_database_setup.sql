-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: db2.philgookang.com:50000
-- Generation Time: Mar 29, 2018 at 11:49 AM
-- Server version: 5.7.21-log
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `afreecatv`
--
CREATE DATABASE IF NOT EXISTS `afreecatv` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `afreecatv`;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
    `idx` int(11) NOT NULL,
    `video_id` int(11) NOT NULL,
    `title` varchar(128) NOT NULL,
    `preview` longblob NOT NULL,
    `url` varchar(256) NOT NULL,
    `url_type` tinyint(1) NOT NULL,
    `processed` int(11) NOT NULL,
    `filename` varchar(22) NOT NULL,
    `filesize` double NOT NULL,
    `created_date_time` datetime NOT NULL,
    `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`idx`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `video_id_2` (`video_id`,`status`),
  ADD KEY `status` (`status`),
  ADD KEY `processed` (`processed`,`filesize`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
