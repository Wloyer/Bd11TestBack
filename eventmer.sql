-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 17, 2024 at 07:23 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventmer`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240515080132', '2024-05-15 13:01:19', 318);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `schedule_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `event_hour` time NOT NULL,
  `booking_date` date NOT NULL,
  `event_date` date NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancel` tinyint(1) NOT NULL,
  `nb_ticket` int NOT NULL,
  `is_sold_out` tinyint(1) NOT NULL,
  `is_adult` tinyint(1) NOT NULL,
  `is_guest_adult` tinyint(1) NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sold_tickets` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7A40BC2D5` (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `schedule_id`, `name`, `price`, `event_hour`, `booking_date`, `event_date`, `type`, `description`, `cancel`, `nb_ticket`, `is_sold_out`, `is_adult`, `is_guest_adult`, `location`, `image_event`, `sold_tickets`) VALUES
(1, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0),
(2, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0),
(3, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0),
(4, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0),
(5, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0),
(6, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0),
(7, NULL, 'Concert de Rock', 30, '20:00:00', '2024-06-01', '2024-06-15', 'Concert', 'Concert de rock avec des artistes internationaux', 1, 100, 0, 1, 0, 'Paris', 'image-url.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_user`
--

DROP TABLE IF EXISTS `event_user`;
CREATE TABLE IF NOT EXISTS `event_user` (
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`),
  KEY `IDX_92589AE271F7E88B` (`event_id`),
  KEY `IDX_92589AE2A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5A3811FB79F37AE5` (`id_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `phone`, `birthdate`) VALUES
(1, 'john.doe@example.com', '[]', '$2y$13$OUTEXjcDyvRV5StgOQPuPeIXQ0SNzexH3wHJ2R.V/g579zjaO0Chi', 'John', 'Doe', '123456789', '2024-06-01'),
(2, 'jergergohn.doe@example.com', '[]', '$2y$13$augadc4RuEIJ2sSEuz8SweUvcZ0Zh6LHBX0xvcLOKWWXqft21Nin2', 'John', 'Doe', '123456789', '2024-06-01'),
(3, 'jergreegrergohn.doe@example.com', '[]', '$2y$13$igOYnzmuxywOfz4O6FOJne4KPuccgzpCU0WmLVkTC9VxD5XfZ7JEC', 'John', 'Doe', '123456789', '2024-06-01'),
(4, 'jergreegrgggergohn.doe@example.com', '[]', '$2y$13$f7Sr801FSyoZY94TpOvGS.4eUWiw0WIuhTzqQugWWi9Lo65CnEQPy', 'John', 'Doe', '123456789', '2024-06-01'),
(5, 'joggghn.doe@example.com', '[]', '$2y$13$.X0taLKkRiKpO8HXLrQ9XO4TufO45F.NTKt5WeOyqlDX2Nu2tRO3m', 'John', 'Doe', '1234567890', '2000-01-01');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA7A40BC2D5` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`);

--
-- Constraints for table `event_user`
--
ALTER TABLE `event_user`
  ADD CONSTRAINT `FK_92589AE271F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_92589AE2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `FK_5A3811FB79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
