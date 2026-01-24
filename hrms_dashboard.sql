-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2026 at 02:30 PM
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
-- Database: `hrms_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'sickk'),
(3, 'leaves'),
(4, 'xyz');

-- --------------------------------------------------------

--
-- Table structure for table `department_designation`
--

CREATE TABLE `department_designation` (
  `id` int(255) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `designation_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_designation`
--

INSERT INTO `department_designation` (`id`, `department_name`, `designation_name`) VALUES
(2, 'Maths', 'senior manager'),
(4, 'hnd', 'senior manager'),
(5, 'hnd', 'employee'),
(6, 'physiology', 'employee'),
(10, 'finance', 'manager'),
(11, 'finance', 'employee'),
(12, 'finance', 'senior manager'),
(14, 'finance', 'senior manager'),
(15, 'finance', 'manager'),
(19, 'zoology', 'student'),
(20, 'zoology', 'kjk'),
(21, 'computer', 'developer');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(100) DEFAULT NULL,
  `f_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `marital_status` varchar(20) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `passport_no` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact_nationality` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `offer_letter` varchar(255) DEFAULT NULL,
  `joining_letter` varchar(255) DEFAULT NULL,
  `contract_paper` varchar(255) DEFAULT NULL,
  `id_proof` varchar(255) DEFAULT NULL,
  `other_document` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `status` varchar(10) DEFAULT 'Active',
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `emp_id`, `f_name`, `l_name`, `dob`, `gender`, `marital_status`, `father_name`, `nationality`, `passport_no`, `photo`, `bank_name`, `branch_name`, `account_name`, `account_number`, `address`, `city`, `contact_nationality`, `mobile`, `phone`, `email`, `resume`, `offer_letter`, `joining_letter`, `contract_paper`, `id_proof`, `other_document`, `designation`, `join_date`, `status`, `department`) VALUES
(7, '90', 'fara', 'xyz', '2022-02-13', 'Male', 'Widowed', 'jkjk', 'Algeria', '78', '4.png', 'alfalah', 'main', 'kdf', '456', 'house42', 'jhdsj', 'Afghanistan', '2222222222', '4444444444', 'xyz@email', '1.png.png', '13.png', '01.jpg', '13.png', '16.png', '18.png', 'developer', '2022-10-13', 'Active', 'computer science'),
(8, '90', 'rubina', 'asghar', '2022-02-13', 'Male', 'Widowed', 'jkjk', 'Algeria', '78', '4.png', 'alfalah', 'main', 'kdf', '456', 'house42', 'jhdsj', 'Afghanistan', '2222222222', '4444444444', 'xyz@email', '1.png.png', '13.png', '01.jpg', '13.png', '16.png', '18.png', 'employee', '2022-10-13', 'Active', 'physiology'),
(9, '90', 'farahn', 'xyz', '2022-02-13', 'Male', 'Widowed', 'jkjk', 'Algeria', '78', '4.png', 'alfalah', 'main', 'kdf', '456', 'house42', 'jhdsj', 'Afghanistan', '2222222222', '4444444444', 'xyz@email', '1.png.png', '13.png', '01.jpg', '13.png', '16.png', '18.png', 'employee', '2022-10-13', 'Active', 'physiology'),
(10, '90', 'farahn', 'xyz', '2022-02-13', 'Male', 'Widowed', 'jkjk', 'Algeria', '78', '4.png', 'alfalah', 'main', 'kdf', '456', 'house42', 'jhdsj', 'Afghanistan', '2222222222', '4444444444', 'xyz@email', '1.png.png', '13.png', '01.jpg', '13.png', '16.png', '18.png', 'employee', '2022-10-13', 'Active', 'physiology'),
(11, '65', 'afaqq', 'waseem', '2020-07-13', 'Male', 'Unmarried', 'zeeshan ahmad', 'Pakistan', '654', '6.png', 'jubliee', 'main', 'sef', '56678', 'house28', 'karachi', 'Pakistan', '343435353', '6567575675', 'afaq@email', '2.png', '2.jpeg', '13.png', '1.png.png', '15.png', '24.png', 'senior manager', '2018-02-13', 'Active', 'Maths'),
(12, '65', 'farzana', 'ghori', '2003-06-11', 'Female', 'Unmarried', 'm.ghori', 'Pakistan', '109', '3.png', 'alfalah', 'main', 'jod', '676', 'house10', 'bahawalpur', 'Pakistan', '5657686899', '2736287619', 'farzana@email', '1.png.png', '3.jpeg', '17.png', '21.png', 'quaideazam.jpeg', '4.png', 'employee', '2010-10-20', 'Active', 'hnd'),
(13, '90', 'dora', 'den', '2025-07-08', 'Female', 'Married', 'nhk', 'Pakistan', '109', '5.png', 'alfalah', 'main', 'sef', '56678', 'house34', 'karachi', 'Pakistan', '333423423423', '53242423', 'den@gmail.com', '15.png', '2.png', '2.jpg', '9.jpg', '4.jpg', '3.jpg', 'manager', '2025-07-24', 'Active', 'finance'),
(14, '90', 'fahad', 'farooq', '2025-07-15', 'Male', 'Unmarried', 'frooq', 'Algeria', '654', 'Screenshot_2024.09.15_05.08.25.858.png', 'jubliee', 'main', 'sef', '56678', 'hjdhajsd', 'jwdj', 'Albania', '3648723', '3454232', 'faha@email', 'affidavit 1.jpg', '3.jpg', '5.jpg', '8.jpg', '4.jpg', '3.jpg', 'senior manager', '2025-07-21', 'Active', 'finance');

-- --------------------------------------------------------

--
-- Table structure for table `employee_awards`
--

CREATE TABLE `employee_awards` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `award_name` varchar(255) DEFAULT NULL,
  `gift_item` varchar(255) DEFAULT NULL,
  `award_amount` decimal(10,2) DEFAULT NULL,
  `award_month` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_awards`
--

INSERT INTO `employee_awards` (`id`, `employee_id`, `award_name`, `gift_item`, `award_amount`, `award_month`) VALUES
(2, 8, 'employee of the year', 'watch', 100000.00, 'Nov 24, 2027'),
(3, 13, 'xyz', 'jug', 2000.00, 'Jun 16, 2025'),
(4, 7, 'developer of the year', 'watch', 20000.00, 'Jul 20, 2025');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `start_date`, `end_date`) VALUES
(2, 'rrrr', '2025-07-10', '2025-07-10'),
(3, 'xyz', '2025-01-28', '2025-07-31'),
(4, 'kkk', '2025-07-02', '2025-07-30'),
(6, 'eid', '2025-07-10', '2025-07-10'),
(7, 'jacob', '2025-07-10', '2025-07-10'),
(9, 'bbbb', '2025-02-24', '2025-07-24');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `purchased_from` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `amount_spent` decimal(10,2) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `bill_copy` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `item_name`, `purchased_from`, `purchase_date`, `amount_spent`, `employee_id`, `bill_copy`) VALUES
(7, 'jar', 'chase', '0000-00-00', 8000.00, 13, '1753521101_2.jpg'),
(8, 'electri cooler', 'chase', '0000-00-00', 600.00, 11, '1753521339_3.jpg'),
(9, 'xyz', 'jksjk', '2025-06-26', 80.00, 11, '1753522396_8.jpg'),
(10, 'gun', 'opd', '2025-07-26', 70.00, 14, '1753522567_8.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `event_name`, `description`, `start_date`, `end_date`) VALUES
(3, 'eid', 'jjjjj', '2025-07-14', '2025-07-03'),
(4, 'rrrr', 'nnnn', '2025-01-08', '2025-02-08'),
(5, 'jacob', 'bhbhbhn', '2025-04-09', '2025-04-23'),
(6, 'independance day', 'bnbnbnb', '2025-03-04', '2025-04-01'),
(7, 'independence day', 'jjjj', '2023-01-21', '2023-11-07'),
(8, 'xyz', 'uuu', '2025-05-05', '2025-08-12'),
(9, 'jacob', 'nnn', '2025-05-06', '2025-07-11'),
(10, 'eid', 'njnjn', '2025-12-24', '2025-09-16'),
(11, 'xyz', 'hhhhh', '2025-09-15', '2025-04-23'),
(12, 'eid', 'gfgfhg', '1992-07-06', '2025-07-24'),
(13, 'mnmnbf', 'ggfgf', '2025-07-24', '2025-07-25');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_desc` text NOT NULL,
  `long_desc` longtext NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `short_desc`, `long_desc`, `status`, `created_at`) VALUES
(3, 'bhssh', 'sh', '<p><span class=\"marker\">uuuuuuuuuuu</span></p>\r\n', 'Published', '2025-07-11 02:13:56'),
(4, 'jjj', 'ddjdd', '<p>uuuuuuuuuuu</p>\r\n', 'Published', '2025-07-11 02:39:01'),
(5, 'xyz', 'hi', '<p>he lives in karachi</p>\r\n', 'Published', '2025-07-11 23:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `salary_details`
--

CREATE TABLE `salary_details` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `enrollment_type` varchar(50) DEFAULT NULL,
  `basic_salary` decimal(10,2) DEFAULT NULL,
  `house_allowance` decimal(10,2) DEFAULT NULL,
  `medical_allowance` decimal(10,2) DEFAULT NULL,
  `special_allowance` decimal(10,2) DEFAULT NULL,
  `fuel_allowance` decimal(10,2) DEFAULT NULL,
  `phone_allowance` decimal(10,2) DEFAULT NULL,
  `other_allowance` decimal(10,2) DEFAULT NULL,
  `provident_fund` decimal(10,2) DEFAULT NULL,
  `tax_deduction` decimal(10,2) DEFAULT NULL,
  `other_deduction` decimal(10,2) DEFAULT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `total_deduction` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_details`
--

INSERT INTO `salary_details` (`id`, `emp_id`, `department`, `designation`, `enrollment_type`, `basic_salary`, `house_allowance`, `medical_allowance`, `special_allowance`, `fuel_allowance`, `phone_allowance`, `other_allowance`, `provident_fund`, `tax_deduction`, `other_deduction`, `gross_salary`, `total_deduction`, `net_salary`, `created_at`) VALUES
(1, 8, 'physiology', 'employee', 'Permanent', 200.00, 40.00, 2.00, 4.00, 6.00, 3.00, 5.00, 128.00, 50.00, 3.00, 260.00, 181.00, 79.00, '2025-07-15 13:37:41'),
(2, 7, 'computer science', 'developer', 'Permanent', 80000.00, 3.00, 3.00, 1.00, 3.00, 2.00, 1.00, 8.00, 88.00, 8.00, 80013.00, 104.00, 79909.00, '2025-07-15 13:57:11'),
(3, 2, 'Maths', 'senior manager', 'Permanent', 23.00, 54.00, 1.00, 5.00, 1.00, 2.00, 5.00, 1.00, 11.00, 1.00, 91.00, 13.00, 78.00, '2025-07-15 14:43:36'),
(4, 12, 'hnd', 'employee', 'Provision', 5.00, 3.00, 5.00, 5.00, 1.00, 2.00, 3.00, 4.00, 3.00, 2.00, 24.00, 9.00, 15.00, '2025-07-15 16:22:33'),
(5, 11, 'hnd', 'senior manager', 'Permanent', 23.00, 0.00, 20.00, 78.00, 10.00, 89.00, 89.00, 67.00, 89.00, 46.00, 309.00, 202.00, 107.00, '2025-07-15 18:00:01'),
(6, 10, 'physiology', 'employee', 'Permanent', 900.00, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00, 70.00, 30.00, 12.00, 960.00, 112.00, 848.00, '2025-07-15 18:16:23'),
(7, 13, 'finance', 'manager', 'Provision', 100000.00, 34.00, 56.00, 23.00, 24.00, 54.00, 80.00, 53.00, 3.00, 5.00, 100271.00, 61.00, 100210.00, '2025-07-15 18:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `salary_payments`
--

CREATE TABLE `salary_payments` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `payment_month` varchar(20) NOT NULL,
  `payment_date` date NOT NULL,
  `gross_salary` decimal(10,2) NOT NULL,
  `total_deduction` decimal(10,2) NOT NULL,
  `net_salary` decimal(10,2) NOT NULL,
  `fine_deduction` decimal(10,2) DEFAULT 0.00,
  `payment_amount` decimal(10,2) NOT NULL,
  `enrollment_type` enum('cash','cheque','bank') NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_payments`
--

INSERT INTO `salary_payments` (`id`, `emp_id`, `payment_month`, `payment_date`, `gross_salary`, `total_deduction`, `net_salary`, `fine_deduction`, `payment_amount`, `enrollment_type`, `comments`, `created_at`) VALUES
(1, '13', 'Jul 21, 2025', '2025-07-23', 100271.00, 61.00, 100210.00, 600.00, 99610.00, 'cash', 'good', '2025-07-21 09:27:51'),
(3, '7', 'Jul 21, 2025', '2025-07-21', 80013.00, 104.00, 79909.00, 300.00, 79609.00, 'cheque', 'good', '2025-07-21 15:23:54'),
(4, '11', 'Jul 21, 2024', '2025-07-21', 309.00, 202.00, 107.00, 80.00, 27.00, 'cash', 'good work', '2025-07-21 17:51:16'),
(5, '8', 'Jul 21, 2024', '2025-07-21', 260.00, 181.00, 79.00, 500.00, -421.00, 'bank', 'hard working', '2025-07-21 19:15:22'),
(6, '2', 'Jul 23, 2025', '2025-07-23', 91.00, 13.00, 78.00, 4.00, 74.00, 'bank', 'xyz', '2025-07-23 17:05:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_designation`
--
ALTER TABLE `department_designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_awards`
--
ALTER TABLE `employee_awards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee_expense` (`employee_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_details`
--
ALTER TABLE `salary_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `payment_month` (`payment_month`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department_designation`
--
ALTER TABLE `department_designation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employee_awards`
--
ALTER TABLE `employee_awards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salary_details`
--
ALTER TABLE `salary_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `salary_payments`
--
ALTER TABLE `salary_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fk_employee_expense` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
