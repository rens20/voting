-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2024 at 03:10 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rensdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(250) NOT NULL,
  `type` varchar(259) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `type`) VALUES
(1, 'admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(259) NOT NULL,
  `type` varchar(240) NOT NULL,
  `lrn` varchar(200) NOT NULL,
  `section` varchar(255) NOT NULL,
  `grade` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `type`, `lrn`, `section`, `grade`, `name`) VALUES
(3, 'record', 'wasieacuna@gmail.com', 'record', 'admin', '12345', '', '', ''),
(28, 'wasieacun', 'wasieacuna@gmail.com', 'dddd', 'admin1', '123456789', 'edd', '', ''),
(29, 'wasieacun', 'wasieacuna@gmail.com', 'dddd', 'user', '123456789', 'edd', '', ''),
(31, '', 'test@gmail.com', 'sggs', 'user', '1234', 'gsgs', '', 'wasie'),
(32, 'wasie', 'wasie@gmail.com', 'dddd', 'user', '33333', 'ddd', '', ''),
(33, 'dgdsg', 'wasie@gmail.com', 'safas', 'user', '2215', 'safas', '', ''),
(34, 'wasie', 'waise@gmail.com', '11', 'user', '9999', '11', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `officer` varchar(50) NOT NULL,
  `grade` int(200) NOT NULL,
  `section` varchar(255) NOT NULL,
  `motto` varchar(250) NOT NULL,
  `vote_counter` int(11) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `name`, `officer`, `grade`, `section`, `motto`, `vote_counter`, `image`) VALUES
(18, 'renss', 'Vice President', 0, 'dgdsg', 'ff', 1, 'upload/images (5).jpg'),
(19, 'ggg', 'Vice President', 0, 'dgdsg', 'ff', 6, 'upload/images (5).jpg'),
(20, 'Alices', 'President', 0, 'dgdsg', 'tanginamo', 7, 'upload/images (4).jpg'),
(21, 'wasie', 'PIO', 0, 'dgdsg', 'tanginamo', 6, 'upload/3.png'),
(22, 'Alice', 'Secretary', 11, 'dgdsg', 'ff', 6, 'upload/quiz.png'),
(23, 'rens', 'Auditor', 0, 'dgdsg', 'ff', 7, 'upload/Screenshot 2024-03-05 234927.png'),
(24, 'Alice', 'Treasurer', 11, 'dgdsg', 'FSAFSAFSA', 6, 'upload/voting updated.png');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `officer` varchar(200) NOT NULL,
  `vote_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `candidate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `voters`
--
ALTER TABLE `voters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
