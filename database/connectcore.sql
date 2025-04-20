-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 09:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `connectcore`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `companyId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `industry_type` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`companyId`, `name`, `industry_type`, `email`, `phone`, `address`, `registered_at`) VALUES
(8, 'last', 'death', 'op@redit.com', '56443546322', 'dajksfdkjwcvn', '2025-04-04 14:32:07'),
(9, 'test', 'test_industry', 'test@example.com', '01010101011', 'uttor', '2025-04-04 17:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `company_subscriptions`
--

CREATE TABLE `company_subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contractId` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','completed','terminated') DEFAULT 'active',
  `hiringCompanyId` int(11) DEFAULT NULL,
  `applyingCompanyId` int(11) DEFAULT NULL,
  `jobId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_reviews`
--

CREATE TABLE `contract_reviews` (
  `review_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `reviewer_company_id` int(11) NOT NULL,
  `reviewee_company_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeeassignments`
--

CREATE TABLE `employeeassignments` (
  `recordId` int(11) NOT NULL,
  `contractId` int(11) DEFAULT NULL,
  `companyId` int(11) DEFAULT NULL,
  `employeeId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeephone`
--

CREATE TABLE `employeephone` (
  `id` int(11) NOT NULL,
  `employeeId` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employeeId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `rate` enum('1','2','3','4','5') DEFAULT NULL,
  `availability_status` enum('available','unavailable') DEFAULT NULL,
  `companyId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeeskills`
--

CREATE TABLE `employeeskills` (
  `employeeId` int(11) NOT NULL,
  `skillId` int(11) NOT NULL,
  `experience_level` enum('beginner','intermediate','expert') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobapplications`
--

CREATE TABLE `jobapplications` (
  `applicationId` int(11) NOT NULL,
  `applyingCompanyId` int(11) DEFAULT NULL,
  `jobId` int(11) DEFAULT NULL,
  `apply_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected','withdrawn') DEFAULT 'pending',
  `application_type` enum('apply','offer') NOT NULL DEFAULT 'apply',
  `offer_rate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobapplications`
--

INSERT INTO `jobapplications` (`applicationId`, `applyingCompanyId`, `jobId`, `apply_time`, `status`, `application_type`, `offer_rate`) VALUES
(12, 8, 5, '2025-04-09 09:41:40', 'accepted', 'apply', 1300.00),
(13, 9, 4, '2025-04-09 09:42:11', 'rejected', 'apply', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobpostings`
--

CREATE TABLE `jobpostings` (
  `jobId` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `required_skill` varchar(64) DEFAULT NULL,
  `job_details` text DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `posted_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `companyId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobpostings`
--

INSERT INTO `jobpostings` (`jobId`, `job_title`, `required_skill`, `job_details`, `rate`, `status`, `posted_time`, `companyId`) VALUES
(4, 'PHP dev', 'PHP', 'Need someone with PHP and SQL', 1300.00, 'open', '2025-04-09 09:04:08', 8),
(5, 'Content Writer', 'content writing', 'Need social media content writer', 1000.00, 'open', '2025-04-09 09:12:22', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `read_status` tinyint(1) DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationId`, `userId`, `message`, `read_status`, `time`) VALUES
(1, 8, 'New application received for your job posting', 0, '2025-04-09 09:18:30'),
(2, 9, 'New rate offer of $500 received for your job posting', 0, '2025-04-09 09:20:26'),
(3, 9, 'New rate offer of $750 received for your job posting', 0, '2025-04-09 09:24:07'),
(4, 8, 'Your application has been accepted for job: RMG worker', 0, '2025-04-09 09:24:46'),
(5, 8, 'New rate offer of $5000 received for your job posting', 0, '2025-04-09 09:28:47'),
(6, 9, 'Your application has been rejected for job: Strategist', 0, '2025-04-09 09:29:00'),
(7, 9, 'New rate offer of $1500 received for your job posting', 0, '2025-04-09 09:32:35'),
(8, 8, 'Your application has been accepted for job: Content Writer', 0, '2025-04-09 09:33:11'),
(9, 8, 'New application received for your job posting', 0, '2025-04-09 09:33:21'),
(10, 9, 'Your application has been rejected for job: PHP dev', 0, '2025-04-09 09:33:48'),
(11, 9, 'New rate offer of $1300 received for your job posting', 0, '2025-04-09 09:41:40'),
(12, 8, 'Your application has been accepted for job: Content Writer', 0, '2025-04-09 09:42:05'),
(13, 8, 'New application received for your job posting', 0, '2025-04-09 09:42:11'),
(14, 9, 'Your application has been rejected for job: PHP dev', 0, '2025-04-09 09:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentId` int(11) NOT NULL,
  `contractId` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `applyingCompanyShare` decimal(10,2) NOT NULL,
  `skillConnetShare` decimal(10,2) NOT NULL,
  `employee_salary` decimal(10,2) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skillId` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_num` varchar(20) DEFAULT NULL,
  `user_type` enum('admin','company') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `password`, `name`, `email`, `phone_num`, `user_type`, `created_at`) VALUES
(8, '12345678', 'last', 'op@redit.com', '56443546322', 'company', '2025-04-04 14:32:07'),
(9, 'testpass', 'test', 'test@example.com', '01010101011', 'company', '2025-04-04 17:04:31');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.user_type = 'company' THEN
        INSERT INTO companies (name, email, phone, registered_at)
        VALUES (NEW.name, NEW.email, NEW.phone_num, NOW());
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`companyId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `company_subscriptions`
--
ALTER TABLE `company_subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contractId`),
  ADD KEY `hiringCompanyId` (`hiringCompanyId`),
  ADD KEY `applyingCompanyId` (`applyingCompanyId`),
  ADD KEY `jobId` (`jobId`);

--
-- Indexes for table `contract_reviews`
--
ALTER TABLE `contract_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `reviewer_company_id` (`reviewer_company_id`),
  ADD KEY `reviewee_company_id` (`reviewee_company_id`);

--
-- Indexes for table `employeeassignments`
--
ALTER TABLE `employeeassignments`
  ADD PRIMARY KEY (`recordId`),
  ADD KEY `contractId` (`contractId`),
  ADD KEY `companyId` (`companyId`),
  ADD KEY `employeeId` (`employeeId`);

--
-- Indexes for table `employeephone`
--
ALTER TABLE `employeephone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employeeId` (`employeeId`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employeeId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `companyId` (`companyId`);

--
-- Indexes for table `employeeskills`
--
ALTER TABLE `employeeskills`
  ADD PRIMARY KEY (`employeeId`,`skillId`),
  ADD KEY `skillId` (`skillId`);

--
-- Indexes for table `jobapplications`
--
ALTER TABLE `jobapplications`
  ADD PRIMARY KEY (`applicationId`),
  ADD KEY `applyingCompanyId` (`applyingCompanyId`),
  ADD KEY `jobId` (`jobId`);

--
-- Indexes for table `jobpostings`
--
ALTER TABLE `jobpostings`
  ADD PRIMARY KEY (`jobId`),
  ADD KEY `companyId` (`companyId`),
  ADD KEY `required_skill` (`required_skill`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `contractId` (`contractId`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skillId`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `companyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `company_subscriptions`
--
ALTER TABLE `company_subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contractId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_reviews`
--
ALTER TABLE `contract_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employeeassignments`
--
ALTER TABLE `employeeassignments`
  MODIFY `recordId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employeephone`
--
ALTER TABLE `employeephone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employeeId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobapplications`
--
ALTER TABLE `jobapplications`
  MODIFY `applicationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobpostings`
--
ALTER TABLE `jobpostings`
  MODIFY `jobId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skillId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `delete_on_user_deletion` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `company_subscriptions`
--
ALTER TABLE `company_subscriptions`
  ADD CONSTRAINT `company_subscriptions_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`companyId`),
  ADD CONSTRAINT `company_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`plan_id`);

--
-- Constraints for table `contract_reviews`
--
ALTER TABLE `contract_reviews`
  ADD CONSTRAINT `contract_reviews_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`contractId`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_reviews_ibfk_2` FOREIGN KEY (`reviewer_company_id`) REFERENCES `companies` (`companyId`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_reviews_ibfk_3` FOREIGN KEY (`reviewee_company_id`) REFERENCES `companies` (`companyId`) ON DELETE CASCADE;

--
-- Constraints for table `employeephone`
--
ALTER TABLE `employeephone`
  ADD CONSTRAINT `employeephone_ibfk_1` FOREIGN KEY (`employeeId`) REFERENCES `employees` (`employeeId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
