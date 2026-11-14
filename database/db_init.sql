-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 13, 2026 at 01:11 PM
-- Server version: 8.0.46
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `cycling_contest`
--
CREATE DATABASE IF NOT EXISTS `cycling_contest` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cycling_contest`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE IF NOT EXISTS `admin_accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$b/G5Qdc5h.MS40eKVAweAe40l4AOqUytqvymZoVe8seS6Rp.jAIY2');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image_name`) VALUES
(1, 'MTB', 'Mountain bikes are bicycles designed for off-road trails. While they share some features with other types of bicycles, they also possess specific characteristics that enhance their durability and performance on rugged terrain. These include wheels with wide tires, a much sturdier frame and fork, and oversized suspension and braking systems. These bicycles are used on mountain trails, forest roads, and other types of unpaved paths. Such routes often feature rocks, ditches, sand, tree roots, and other natural obstacles that increase the difficulty of the terrain.', 'MountainBike.jpg'),
(2, 'Downhill', 'Downhill is a style similar to mountain biking, but one that involves a much higher level of difficulty and danger. It is practiced on steep terrain, often featuring obstacles that are difficult to overcome without the necessary skill. Bicycles designed for this type of cycling are heavier and more robust, offering greater stability than other types of mountain bikes. The increased weight of these bikes is justified by the fact that competition courses are traversed almost entirely on descents.', 'downhill.jpg'),
(3, 'BMX', 'BMX bikes are off-road bicycles used for racing or performing stunts. The BMX bike is designed for those who are passionate about performing acrobatics and tricks. Some cities have specially designated areas where one can practice various bike maneuvers. Typically, these spaces are frequented by cyclists as well as enthusiasts of other sports, such as skateboarding or rollerblading.', 'bmx.jpg'),
(4, 'Trial', 'Trial is a sport that involves navigating a bicycle over various obstacles along a specific course, without the rider touching the obstacles or the ground with any part of their body or bike other than the tires. The routes consist of a series of diverse natural and/or artificial obstacles; the design of these routes and the selection of the obstacles are left to the discretion of the organizers. Examples of obstacles used include wooden pallets, tires, industrial spools, large rocks, and car bodies. A judge is assigned to each section to observe the competitor as they navigate the course, keep track of their time, and apply penalties when necessary.', 'trial.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `category_route`
--

CREATE TABLE IF NOT EXISTS `category_route` (
  `category_id` int NOT NULL,
  `route_id` int NOT NULL,
  PRIMARY KEY (`category_id`,`route_id`),
  KEY `fk_category_route_routes` (`route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_route`
--

INSERT INTO `category_route` (`category_id`, `route_id`) VALUES
(1, 1),
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `competitors`
--

CREATE TABLE IF NOT EXISTS `competitors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('M','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_competitors_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competitors`
--

INSERT INTO `competitors` (`id`, `first_name`, `last_name`, `date_of_birth`, `email`, `phone_number`, `gender`) VALUES
(1, 'John', 'Doe', '1998-11-19', 'john.doe@test.com', '0788100100', 'M'),
(2, 'Anna', 'Jones', '1998-08-12', 'anna.jones@test.com', '0722200200', 'F');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `competitor_id` int NOT NULL,
  `category_id` int NOT NULL,
  `finish_position` int DEFAULT NULL,
  `status` enum('REGISTERED','FINISHED','NOT_FINISHED','DISQUALIFIED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGISTERED',
  `age_category` enum('JUNIOR','INTERMEDIATE','SENIOR','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`competitor_id`,`category_id`),
  KEY `idx_results_category_position` (`category_id`,`finish_position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`competitor_id`, `category_id`, `finish_position`, `status`, `age_category`) VALUES
(1, 1, NULL, 'DISQUALIFIED', 'JUNIOR'),
(1, 2, NULL, 'FINISHED', 'JUNIOR'),
(1, 3, NULL, 'FINISHED', 'JUNIOR'),
(1, 4, NULL, 'FINISHED', 'JUNIOR'),
(2, 1, NULL, 'REGISTERED', 'INTERMEDIATE'),
(2, 2, NULL, 'FINISHED', 'INTERMEDIATE');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `center_lat` decimal(9,6) NOT NULL,
  `center_lng` decimal(9,6) NOT NULL,
  `origin_lat` decimal(9,6) NOT NULL,
  `origin_lng` decimal(9,6) NOT NULL,
  `destination_lat` decimal(9,6) NOT NULL,
  `destination_lng` decimal(9,6) NOT NULL,
  `waypoints` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `center_lat`, `center_lng`, `origin_lat`, `origin_lng`, `destination_lat`, `destination_lng`, `waypoints`) VALUES
(1, 45.442969, 25.588924, 45.442969, 25.588924, 45.442969, 25.588924, '[{\"lat\": 45.50318879, \"lng\": 25.61662495}, {\"lat\": 45.48408976, \"lng\": 25.64277647}]'),
(2, 45.442969, 25.588924, 45.442969, 25.588924, 45.442969, 25.588924, '[{\"lat\": 45.48712482, \"lng\": 25.56559861}, {\"lat\": 45.47087569, \"lng\": 25.51125705}, {\"lat\": 45.4490268, \"lng\": 25.5197494}]'),
(3, 45.428038, 25.592292, 45.428038, 25.592292, 45.442969, 25.588924, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_route`
--
ALTER TABLE `category_route`
  ADD CONSTRAINT `fk_category_route_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_category_route_routes` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `fk_results_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_results_competitors` FOREIGN KEY (`competitor_id`) REFERENCES `competitors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
