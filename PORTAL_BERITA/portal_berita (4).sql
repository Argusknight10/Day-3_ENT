-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2024 at 07:15 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portal_berita`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(10) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(11, 'Olahraga', '2024-08-31 05:09:26', '2024-08-31 05:09:26'),
(12, 'hola', '2024-08-31 05:09:29', '2024-08-31 05:09:29'),
(13, 'wkwk', '2024-08-31 05:10:59', '2024-08-31 05:10:59'),
(14, 'rafif', '2024-08-31 05:11:04', '2024-08-31 05:11:04'),
(15, 'Kesehatan', '2024-08-31 05:11:51', '2024-08-31 05:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `id_kategori` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `image`, `content`, `id_kategori`, `created_at`, `updated_at`) VALUES
(23, 'juara', 'assets/chrisputra.jpg', 'juara', 11, '2024-08-31 05:10:36', '2024-08-31 05:10:36'),
(24, 'covid', 'assets/5fedd0b455cb7093049541.jpeg', 'covid', 15, '2024-08-31 05:12:13', '2024-08-31 05:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `profile_image`, `role`) VALUES
(1, 'user', '$2y$10$0jA1T4/OGMMtXzfA2GKN6O5RaUzgNMWNHpoWQ/1fD/ucHvvYj7z2y', 'assets/WhatsApp Image 2024-06-24 at 19.46.25.jpeg', 'user'),
(7, 'admin', '$2y$10$5haeAG2xXcCsqKoDUPff.OlQCV/jcDjfR79XZAEFF9gSg7bg80K9m', 'assets/WhatsApp Image 2024-06-24 at 19.46.25.jpeg', 'admin'),
(8, 'adi ', '$2y$10$.GUzE/IWXyKdJlphUASo.uQace0WVyGYTRMHbZ7zB0pq6PCSWEeIa', '', 'user'),
(9, 'rafif', '$2y$10$YokJWPblY21f/eNGC9ZaJ.cXCg9dj63EtTWrwRKzZIQOfdQ/FGBu.', '', 'user'),
(10, 'bagus', '$2y$10$C4g5v7PgdBzmlQXu7PMp/eBvr9qlNdlOxRjmJF0bqGIEjwx5Q7CWe', '', 'user'),
(12, 'diki', '$2y$10$NaonvWNUp8goKA5rlqZXHeNSIf9vljHOlqnGv.X9U.1s68/aptVb2', '', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`) USING BTREE,
  ADD KEY `id_kategori_2` (`id_kategori`);

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
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
