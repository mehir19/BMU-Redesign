-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 08:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `journal_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` enum('Submitted','Under Review','Accepted','Rejected','Published') DEFAULT 'Submitted',
  `formatted_file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `author_id`, `title`, `abstract`, `file_path`, `status`, `formatted_file_path`, `created_at`) VALUES
(1, 1, 'Article on Pain Management', 'Content goes here to review', 'uploads/manuscripts/1780931565_BMU All Courses Portfolio.pdf', 'Published', 'uploads/published/1781026974_final_Mihir_Mondal_WordPress_Support.pdf', '2026-06-08 15:12:45'),
(2, 1, 'Hereditary Angioedema: A Narrative Review', 'Hereditary angioedema (HAE) is mostly undiagnosed in most part of the world specially in developing countries like Bangladesh due to lack of awareness and diagnostic facilities. Swelling of face, eyes, lips, hands, feet, and genitals, abdominal pain, and life-threatening laryngeal oedema are the presenting features. HAE should be suspected in all patients who present with angioedema without wheals and who do not respond to antihistamines and/or steroids. C1 levels, C1-INH', 'uploads/manuscripts/1780940708_ESRA Approved Training centre certificate Bangladesh Medical University.pdf', 'Published', 'uploads/published/1781029367_final_holiday-rules.pdf', '2026-06-08 17:45:08'),
(3, 1, 'New article submission', 'Testing the article submission agin', 'uploads/manuscripts/1781026788_BMU-2026-4620.pdf', 'Published', 'uploads/published/1781026940_final_LabResult.pdf', '2026-06-09 17:39:48');

-- --------------------------------------------------------

--
-- Table structure for table `processed_documents`
--

CREATE TABLE `processed_documents` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `copyright_file` varchar(255) NOT NULL,
  `payment_file` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `processed_documents`
--

INSERT INTO `processed_documents` (`id`, `article_id`, `copyright_file`, `payment_file`, `is_verified`, `uploaded_at`) VALUES
(1, 1, 'uploads/requirements/1781026710_copy_BMU-2026-4620.pdf', 'uploads/requirements/1781026710_pay_01-Tyro-bangla.png', 1, '2026-06-08 15:45:30'),
(5, 3, 'uploads/requirements/1781026905_copy_BMU-2026-3381.pdf', 'uploads/requirements/1781026905_pay_01-Tyro-bangla.png', 1, '2026-06-09 17:41:45'),
(6, 2, 'uploads/requirements/1781029296_copy_BMU-Font-selection.pdf', 'uploads/requirements/1781029296_pay_website.png', 1, '2026-06-09 18:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `admin_review_status` enum('Pending','Accept','Reject') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `article_id`, `reviewer_id`, `review_text`, `admin_review_status`, `created_at`) VALUES
(1, 1, 2, 'Reviewed by Reviewer', 'Accept', '2026-06-08 15:40:10'),
(2, 1, 2, 'Reviewed by Reviewer', 'Reject', '2026-06-08 15:40:30'),
(3, 1, 2, 'It publishable', 'Accept', '2026-06-08 15:43:13'),
(4, 2, 4, 'ESRA Approved', 'Accept', '2026-06-08 17:47:30'),
(5, 2, 4, 'ESRA Approved', 'Accept', '2026-06-08 17:47:38'),
(6, 2, 2, 'Certified content', 'Accept', '2026-06-08 17:48:14'),
(7, 3, 2, 'Reviewd', 'Accept', '2026-06-09 17:40:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Author','Reviewer','Admin') NOT NULL DEFAULT 'Author',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'John Author', 'author@test.com', '$2y$10$nPzvK8pjXTdbWPfxsY5efO/RINEA7DnXYOhrdDptCai8jCQcgPXRC', 'Author', '2026-06-08 14:36:08'),
(2, 'Dr Smith', 'reviewer@test.com', '$2y$10$7hGanB1C/uQy33aFyUKRbufzbQ64DOztqt1Vs8.ppAol3xhBCbpL6', 'Reviewer', '2026-06-08 14:36:08'),
(3, 'Editor In Chief', 'admin@test.com', '$2y$10$YBQo1aPYKH8b781JtA1Wdeuy07KvVDxx7DGWsBAf.KntWSUIqEyy2', 'Admin', '2026-06-08 14:36:08'),
(4, 'Dr. Sarah Jenkins', 'sarah@university.edu', '$2y$10$R255t3etaMa4czHNnGJjUuMEzvbZnT.wrXoAKBw5HbM4le/nSzx5y', 'Reviewer', '2026-06-08 17:42:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `processed_documents`
--
ALTER TABLE `processed_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_id` (`article_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `processed_documents`
--
ALTER TABLE `processed_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `processed_documents`
--
ALTER TABLE `processed_documents`
  ADD CONSTRAINT `processed_documents_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
