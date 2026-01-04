-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 03, 2026 at 07:48 PM
-- Server version: 10.11.13-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srv9_wheels`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `beneficiary_name` varchar(255) NOT NULL,
  `account_number_encrypted` text NOT NULL,
  `ifsc_encrypted` text NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `bank_name`, `beneficiary_name`, `account_number_encrypted`, `ifsc_encrypted`, `branch`, `contact`, `status`, `created_at`, `updated_at`) VALUES
(1, 'paytm payments bank', 'kshitiz', 'eyJpdiI6IkJnTEhnSC9rL2lVQWZ1aDh2NDgvSWc9PSIsInZhbHVlIjoiUkd1Yy8zMGJOYUR2d0JmcjBhaSsyQT09IiwibWFjIjoiOWZjNWUyNTUzNTU4ZjI4ZTUwNmRmODFkMjdkZjQ4ZGZkNzNmMzdiYmZjYWU0M2I3NjEyYWUzOWJlYmM1MjM0MCIsInRhZyI6IiJ9', 'eyJpdiI6Ik04Vzdvc0huUXdLNlhvUmtNaG5QS0E9PSIsInZhbHVlIjoiRTRBYk9lWVI5OXdQQXpqSXkzSmZwUT09IiwibWFjIjoiZGJiMmI5MDU1MjE1Y2YxZjEzZjAxNzE1MGQyZDIzOWMwYWYxN2VlZjk1NTJiMjllYjYwMmFlNzIyZDI0NjM2YyIsInRhZyI6IiJ9', 'Noida', 'KSHITIZ.UMU@GMAIL.COM', 1, '2025-10-23 06:01:23', '2025-10-23 06:01:23'),
(2, 'paytm payments bank', 'kshitiz', 'eyJpdiI6IjA3T3diTVVYQm13TzBFb3pWbndod1E9PSIsInZhbHVlIjoiZWxqSnpuY0F2dTllRzJobXVVNXAxQT09IiwibWFjIjoiNzQxZTc0NjU3YjFlMzIzNTk5ZGU1NGY3YTBlZjE5ZDY0MjJhZGUyZjM3ZmRhZGJkNGQ3YWViYzk0N2QzNTg4YSIsInRhZyI6IiJ9', 'eyJpdiI6ImNzS1VpazJrKzZuKzFsWG45aVF5REE9PSIsInZhbHVlIjoiNEhWRldMeW5JRmJDWktKWEVrd1lIdz09IiwibWFjIjoiYmIxMGY0NzZiNTUwMzUzOTRjOWU0YmU0YzBhMjM5ZDU5ZmEzYzVhOTRjZjQyZWJmMmIxZjc1MDEwMjBjMzAxYyIsInRhZyI6IiJ9', 'Noida', 'KSHITIZ.UMU@GMAIL.COM', 1, '2025-10-23 06:01:47', '2025-10-23 06:01:47'),
(3, 'paytm payments bank', 'kshitiz', 'eyJpdiI6InYxbnc1dVZNUXk2RjZwa2NNL2dQeVE9PSIsInZhbHVlIjoiSGl3bXF4N292ZVVzL3BCVXhTdnlUdz09IiwibWFjIjoiYWM0NGUxNmM4YjM5MjY2ZWZkZTk2NTY4YzU5ODg1YzQyODEzMDIwMzM1ZGExMDMxYzljMDIwNjU2NGZmYjAxNCIsInRhZyI6IiJ9', 'eyJpdiI6IjV1VTFoTjlTT2JFb3l0RnhRMmRzZ3c9PSIsInZhbHVlIjoiU3BvemRmamV5ck9PbW1RNStWVHdmZz09IiwibWFjIjoiMjg1YmZhOTUzMzUyOGRmMTEwZTUyZGFmMTQxYjc2MTgzMDU2M2I1NmY1MjRhZTFmOTcxZGY4ZTI0OTA0MjIyZiIsInRhZyI6IiJ9', 'Noida', 'KSHITIZ.UMU@GMAIL.COM', 1, '2025-10-23 06:21:12', '2025-10-23 06:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `alert_test`
--

CREATE TABLE `alert_test` (
  `id` int(11) NOT NULL,
  `content` varchar(200) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alert_test`
--

INSERT INTO `alert_test` (`id`, `content`, `updated_at`, `created_at`) VALUES
(1, 'Your message text', '2025-10-13 17:30:50', '2025-10-13 17:30:50'),
(2, 'test', '2025-10-13 12:12:34', '2025-10-13 12:12:34'),
(3, 'test2', '2025-10-13 12:12:56', '2025-10-13 12:12:56'),
(4, 'hi how are you..??', '2025-10-13 12:16:27', '2025-10-13 12:16:27');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `venue` varchar(500) NOT NULL,
  `event_type` enum('Wedding','Engagement','Reception','Pre-Wedding') NOT NULL,
  `payment_status` enum('Pending','Advance Paid','Paid') DEFAULT 'Pending',
  `status` enum('Confirmed','Awaiting Payment','Completed','Cancelled') DEFAULT 'Confirmed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_name`, `car_id`, `booking_date`, `booking_time`, `venue`, `event_type`, `payment_status`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(3, 'Ballu', 10, '2025-12-31', '02:17:00', 'Harmu Chowk', 'Engagement', 'Advance Paid', 'Confirmed', NULL, '2025-12-30 17:45:05', '2025-12-30 17:50:37'),
(4, 'Manish', 10, '2025-12-31', '03:24:00', 'Dhurwa Project Bhawan', 'Reception', 'Pending', 'Confirmed', NULL, '2025-12-30 17:51:14', '2025-12-30 17:51:23');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `car_desc` varchar(200) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `view_360` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `rate_per_km` varchar(50) NOT NULL,
  `profile_pic` varchar(200) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `videos` text DEFAULT NULL,
  `mileage` varchar(50) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `seat_capacity` int(11) DEFAULT NULL,
  `engine` varchar(100) DEFAULT NULL,
  `registration_no` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `next_availability` varchar(50) DEFAULT NULL,
  `additional_details` text DEFAULT NULL,
  `duplicate_keys` varchar(10) DEFAULT NULL,
  `rc_book` varchar(100) DEFAULT NULL,
  `pollution` varchar(100) DEFAULT NULL,
  `insurance` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '\r\n\r\n1:running\r\n2:blacklist\r\n3:sold\r\n4:booked\r\n5:break-down',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `unique_id`, `name`, `car_desc`, `owner_name`, `view_360`, `brand`, `model`, `rate_per_km`, `profile_pic`, `images`, `videos`, `mileage`, `fuel_type`, `seat_capacity`, `engine`, `registration_no`, `location`, `next_availability`, `additional_details`, `duplicate_keys`, `rc_book`, `pollution`, `insurance`, `status`, `updated_at`, `created_at`) VALUES
(10, '1767112582', 'NA', 'Rolls-Royce Phantom – for royalty on wheels', 'Test owner', NULL, 'Wrangler', 'Iconic wrangler vintage car', '60', 'uploads/cars/profile/1767112747_profile.jpg', '[\"uploads\\/cars\\/images\\/1767112747_6954002ba37c6.jpg\",\"uploads\\/cars\\/images\\/1767112747_6954002ba37f7.jpg\"]', NULL, '16', 'Diesel', 4, 'Suzuki', 'jhdfdf', 'Ranchi', '2025-12-30', NULL, 'Yes', NULL, NULL, NULL, 1, '2025-12-30 16:38:32', '2025-12-30 16:36:22'),
(11, '1767189014', 'NA', NULL, NULL, NULL, 'Toyota', '2011', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-12-31 13:50:14', '2025-12-31 13:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `car_documents`
--

CREATE TABLE `car_documents` (
  `id` int(11) NOT NULL,
  `car_id` varchar(50) NOT NULL,
  `document_id` varchar(50) NOT NULL,
  `issued_date` varchar(50) NOT NULL,
  `expiry_date` varchar(50) NOT NULL,
  `reminder_within` int(11) NOT NULL COMMENT 'get alert to update it (how many days before)',
  `status` int(11) NOT NULL COMMENT '0:document expired\r\n1:document valid',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_documents`
--

INSERT INTO `car_documents` (`id`, `car_id`, `document_id`, `issued_date`, `expiry_date`, `reminder_within`, `status`, `updated_at`, `created_at`) VALUES
(2, '1760032112', '4', '2025-10-14', '2025-10-26', 5, 1, '2025-10-11 18:38:48', '2025-10-11 18:38:48'),
(3, '1760032112', '3', '2025-10-08', '2025-10-30', 0, 1, '2025-10-11 18:40:55', '2025-10-11 18:40:55'),
(4, '1760032112', '1', '2025-10-03', '2025-10-17', 8, 1, '2025-10-11 18:41:50', '2025-10-11 18:41:50'),
(5, '1760032112', '1', '2025-10-22', '2025-10-31', 56, 1, '2025-10-12 07:03:49', '2025-10-12 07:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `car_service`
--

CREATE TABLE `car_service` (
  `id` int(11) NOT NULL,
  `car_id` varchar(50) NOT NULL,
  `garage_id` varchar(50) NOT NULL,
  `service_type_id` varchar(50) NOT NULL,
  `cost` double NOT NULL,
  `bill_paid` double NOT NULL,
  `due` double NOT NULL,
  `billed_on_date` varchar(50) NOT NULL,
  `invoice` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '0:paid\r\n1:pending',
  `created_by` varchar(100) NOT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_service`
--

INSERT INTO `car_service` (`id`, `car_id`, `garage_id`, `service_type_id`, `cost`, `bill_paid`, `due`, `billed_on_date`, `invoice`, `status`, `created_by`, `updated_by`, `updated_at`, `created_at`) VALUES
(2, '1760032112', '1', '2', 400, 200, 200, '2025-10-15', '[\"invoices\\/M0aeeSjXulAnk4HwCMHsgHEcie8L1IiZjIEuIlAv.jpg\"]', 1, '1', '1', '2025-10-12 07:59:48', '2025-10-12 07:59:48'),
(3, '1760032112', '1', '1', 500, 500, 0, '2025-10-22', '[\"invoices\\/XQCvyVW9Wl8ZVOsz7cDPf76w66Fz4Aa02j8R90rr.png\"]', 0, '1', '1', '2025-10-12 17:45:34', '2025-10-12 17:45:34'),
(4, '1760470782', '1', '1', 6000, 5000, 1000, '2025-10-17', '[\"invoices\\/oXhnw5dmc03InWK6KEsZwFCyVlu1APXkvKxwFw3B.jpg\",\"invoices\\/fLClPrt4Lmd5goVHSfqkd2HHSykvvFPXytEMux0R.png\"]', 1, '1', NULL, '2025-10-22 18:31:18', '2025-10-22 18:31:18'),
(5, '1761146379', '2', '1', 500, 500, 0, '2025-10-17', '[\"invoices\\/F0cIFe8v3ue8Jr50ikQihVwLkaSeHRiNpmWsKw1g.jpg\"]', 0, '1', NULL, '2025-10-22 18:56:07', '2025-10-22 18:56:07'),
(12, '1767106220', '1', '1', 5000, 2000, 3000, '2025-12-18', '[\"uploads\\/cars\\/servicing_invoices\\/1767109656_invoice_6953f418e3bf6.pdf\"]', 0, '1', NULL, '2025-12-30 15:47:36', '2025-12-30 15:47:36'),
(13, '1767112582', '1', '1', 4000, 300, 3700, '2025-12-02', '[\"uploads\\/cars\\/servicing_invoices\\/1767113435_invoice_695402db2653e.jpg\"]', 0, '1', NULL, '2025-12-30 16:50:35', '2025-12-30 16:50:35'),
(14, '1767189014', '6', '1', 5000, 2000, 3000, '2025-12-29', '[\"uploads\\/cars\\/servicing_invoices\\/1767190150_invoice_69552e8684077.jpg\"]', 1, '1', NULL, '2025-12-31 14:09:10', '2025-12-31 14:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `client_responses`
--

CREATE TABLE `client_responses` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `selected_car_id` int(11) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_master`
--

CREATE TABLE `document_master` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `doc_name` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_master`
--

INSERT INTO `document_master` (`id`, `unique_id`, `doc_name`, `updated_at`, `created_at`) VALUES
(1, 'rcb', 'RC Book', '2025-10-10 19:30:19', '2025-10-10 19:30:19'),
(2, 'ins', 'Insurance', '2025-10-10 19:30:19', '2025-10-10 19:30:19'),
(3, 'pmt', 'Permit', '2025-10-10 19:30:39', '2025-10-10 19:30:39'),
(4, 'pol', 'Emission Certificate', '2025-10-10 19:30:39', '2025-10-10 19:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `driver_schedules`
--

CREATE TABLE `driver_schedules` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `booked_date` varchar(50) NOT NULL,
  `manager_remark` text DEFAULT NULL,
  `driver_remark` text DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '0:pending\r\n1:completed\r\n2:intrip\r\n3:terminated\r\n',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `garage_master`
--

CREATE TABLE `garage_master` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `manager` varchar(100) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `navigation` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '0:not active\r\n1:active',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `garage_master`
--

INSERT INTO `garage_master` (`id`, `unique_id`, `name`, `manager`, `location`, `mobile`, `mail`, `navigation`, `status`, `updated_at`, `created_at`) VALUES
(1, 'gg1', 'Car Zone Plus', NULL, 'Samlung, Purulia Rd, near Maulana Azad chauk, Lowadih, Ranchi, Jharkhand 834001', '094311 74786', 'carz8689@gmail.com', 'https://maps.app.goo.gl/DHTXsgvZLvro3cM39', 1, '2025-10-10 19:33:05', '2025-10-10 19:33:05'),
(2, '17605607162802', 'Auto Zone', 'auto wala', 'Opp Mangal Tower, Near Maruti Clinic, HB Road Kantatoli Chowk, Kantatoli-834001', '08904229800', 'autozone@gmail.com', 'https://www.google.com/maps/place/23%C2%B021\'59.5%22N+85%C2%B020\'46.9%22E/@23.3665158,85.3463662,17z/data=!3m1!4b1!4m4!3m3!8m2!3d23.3665158!4d85.3463662?entry=ttu&g_ep=EgoyMDI1MTAxMy4wIKXMDSoASAFQAw%3D%3D', 1, '2025-10-15 20:38:36', '2025-10-15 20:38:36'),
(6, '17671140965173', 'Lalita Motor', 'Awantika Singh', 'Dibdih Ranchi', '0123456789', 'lalitamotors@gmail.com', 'https://www.google.com/maps?sca_esv=484d733798db9795&rlz=1C1ONGR_enIN1110IN1110&sxsrf=AE3TifO53_TftLxywoZZkXdCGAd00aWisg:1767114033304&uact=5&gs_lp=Egxnd3Mtd2l6LXNlcnAiDGxhbGl0YSBtb3RvcjILEC4YgAQYxwEYrwEyBRAAGIAEMgUQABiABDIFEAAYgAQyCxAuGIAEGMcBGK8BMgYQABgWGB4yBhAAGBYYHjIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjIaEC4YgAQYxwEYrwEYlwUY3AQY3gQY4ATYAQFIjxVQAFi0E3AAeACQAQCYAcMCoAH_D6oBCDAuMTAuMS4xuAEDyAEA-AEBmAIMoALWEcICBBAjGCfCAgoQIxiABBiKBRgnwgILEAAYgAQYigUYkQLCAgsQLhiABBiKBRiRAsICCxAAGIAEGLEDGIMBwgIREC4YgAQYsQMYgwEYxwEY0QPCAgsQABiABBiKBRixA8ICDhAuGIAEGLEDGMcBGNEDwgIKEC4YgAQYigUYQ8ICChAAGIAEGIoFGEPCAgsQLhiRAhiABBiKBcICCBAuGIAEGLEDwgINEC4YgAQYigUYQxixA8ICCBAAGIAEGLEDwgIOEC4YgAQYigUYsQMYgwHCAggQLhixAxiABMICGhAuGIAEGIoFGJECGJcFGNwEGN4EGOAE2AEBwgIREC4YgAQYigUYkQIYsQMYgwHCAgsQLhiABBixAxiDAcICERAuGK8BGMcBGJECGIAEGIoFwgIFEC4YgATCAhEQLhiABBjHARivARiYBRiZBcICCRAuGIAEGAoYC8ICCRAAGIAEGAoYC8ICFBAuGIAEGJcFGNwEGN4EGN8E2AEBwgIIEAAYFhgeGAqYAwC6BgYIARABGBSSBwcwLjkuMi4xoAfA_AGyBwcwLjkuMi4xuAfWEcIHBjMtMTEuMcgHtwGACAE&um=1&ie=UTF-8&fb=1&gl=in&sa=X&geocode=KWsVhk9w4fQ5MWI_obu3JZzo&daddr=Dibdih+Overbridge,+Near+Ceragem,+SP+Colony,+Dibdih,+Ranchi,+Jharkhand+834002', 1, '2025-12-30 17:01:36', '2025-12-30 17:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"8885a3a4-4587-4657-83a3-afe54f99769d\",\"displayName\":\"App\\\\Events\\\\NewAlertAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":16:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\NewAlertAdded\\\":1:{s:5:\\\"alert\\\";O:8:\\\"stdClass\\\":4:{s:2:\\\"id\\\";i:2;s:7:\\\"content\\\";s:4:\\\"test\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-13 17:42:34\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-13 17:42:34\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1760377354,\"delay\":null}', 0, NULL, 1760377354, 1760377354),
(2, 'default', '{\"uuid\":\"b5930301-e29b-4ccf-80f7-6a7b16881446\",\"displayName\":\"App\\\\Events\\\\NewAlertAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":16:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\NewAlertAdded\\\":1:{s:5:\\\"alert\\\";O:8:\\\"stdClass\\\":4:{s:2:\\\"id\\\";i:3;s:7:\\\"content\\\";s:5:\\\"test2\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-13 17:42:56\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-13 17:42:56\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1760377376,\"delay\":null}', 0, NULL, 1760377376, 1760377376),
(3, 'default', '{\"uuid\":\"7d55e6e4-b6e6-46d1-ba58-ca9c66672951\",\"displayName\":\"App\\\\Events\\\\NewAlertAdded\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":16:{s:5:\\\"event\\\";O:24:\\\"App\\\\Events\\\\NewAlertAdded\\\":1:{s:5:\\\"alert\\\";O:8:\\\"stdClass\\\":4:{s:2:\\\"id\\\";i:4;s:7:\\\"content\\\";s:18:\\\"hi how are you..??\\\";s:10:\\\"updated_at\\\";s:19:\\\"2025-10-13 17:46:27\\\";s:10:\\\"created_at\\\";s:19:\\\"2025-10-13 17:46:27\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1760377587,\"delay\":null}', 0, NULL, 1760377587, 1760377587);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `enquiry_type` varchar(100) NOT NULL,
  `branch` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `event_type` varchar(200) NOT NULL,
  `booking_date` varchar(50) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1:need to contacted\r\n2:contacted\r\n3:closed',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `unique_id`, `client_name`, `contact`, `whatsapp`, `enquiry_type`, `branch`, `source`, `event_type`, `booking_date`, `car_id`, `manager_id`, `status`, `updated_at`, `created_at`) VALUES
(8, 'PT/WW/W/pre/20251102105846', 'Chotu', '09006042011', NULL, 'Potential', 'WW', 'WhatsApp', 'Prewedding', NULL, NULL, NULL, 1, '2025-11-02 10:58:46', '2025-11-02 10:58:46'),
(9, 'IS/021125/FW/F/pre/6', 'Surbhi', '9006042011', NULL, 'Instant', 'FW', 'Facebook', 'Prewedding', NULL, NULL, NULL, 1, '2025-11-02 11:01:41', '2025-11-02 11:01:41'),
(10, 'PT/041125/WW/G/wed/7', 'Ram', '09006042011', NULL, 'Potential', 'WW', 'Google', 'Barat Entry', NULL, NULL, 10, 1, '2025-11-04 16:12:30', '2025-11-04 16:12:30'),
(11, 'EO/041125/FW/I/pre/8', 'ravi', '09006042011', NULL, 'Event Organizers', 'FW', 'Instagram', 'Prewedding', '2025-11-14T01:21', NULL, 9, 1, '2025-11-04 16:48:01', '2025-11-04 16:48:01'),
(12, 'HL/301225/SW/I/anni/9', 'Vinay Roy', '0123456789', NULL, 'Hot Leads', 'SW', 'Instagram', 'Corporate', '2026-01-01T03:01', NULL, NULL, 1, '2025-12-30 18:26:21', '2025-12-30 18:26:21'),
(13, 'VIP/301225/FW/W/pre/10', 'Ram', '0123456987', NULL, 'VIP', 'FW', 'WhatsApp', 'Prewedding', '2025-12-28T02:59', NULL, NULL, 1, '2025-12-30 18:26:48', '2025-12-30 18:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_07_092532_create_users_table', 2),
(5, '2025_10_07_111944_create_permission_tables', 2),
(6, '2025_10_23_110715_create_accounts_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(15, 'App\\Models\\User', 1),
(16, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(10, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 6),
(12, 'App\\Models\\User', 7),
(12, 'App\\Models\\User', 8),
(13, 'App\\Models\\User', 9),
(13, 'App\\Models\\User', 10);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `visible_to` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `visible_to`, `title`, `description`, `updated_at`, `created_at`) VALUES
(1, '10,11', 'test notification', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:23:44', '2025-10-14 09:23:44'),
(2, '10,11', 'test of notitication', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:28:05', '2025-10-14 09:28:05'),
(3, '10,11', 'test of notitication', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:28:35', '2025-10-14 09:28:35'),
(4, '10,11', 'test of notitication', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:28:49', '2025-10-14 09:28:49'),
(5, '10,11', 'test of notitication', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:29:33', '2025-10-14 09:29:33'),
(6, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:30:52', '2025-10-14 09:30:52'),
(7, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:31:01', '2025-10-14 09:31:01'),
(8, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:31:19', '2025-10-14 09:31:19'),
(9, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:52:35', '2025-10-14 09:52:35'),
(10, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:53:08', '2025-10-14 09:53:08'),
(11, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:54:05', '2025-10-14 09:54:05'),
(12, '10,11', 'hi', 'hi all guys come to my office within 3 minutes', '2025-10-14 09:55:04', '2025-10-14 09:55:04'),
(13, '10,11', 'test1', 'Buy me a Cup of tea and Compact (choti advance)', '2025-10-14 10:22:10', '2025-10-14 10:22:10'),
(14, '10,11', 'test1', 'Buy me a Cup of tea and Compact (choti advance)', '2025-10-14 10:27:59', '2025-10-14 10:27:59'),
(15, '10,11', 'this is the test notification', 'this is the test notification to all the userws of the team', '2025-10-14 11:06:11', '2025-10-14 11:06:11'),
(16, '10,11', 'this is the test message to everyone', 'this is the test message to everyone', '2025-10-14 11:08:40', '2025-10-14 11:08:40'),
(17, '10,11', 'this is test', 'notification', '2025-10-14 17:53:56', '2025-10-14 17:53:56'),
(18, '10,11', 'hi', 'bro', '2025-10-14 17:54:11', '2025-10-14 17:54:11'),
(19, '10,11', 'joo', 'dsfasaf', '2025-10-14 17:56:21', '2025-10-14 17:56:21'),
(20, '10,11', 'heading', 'custom message', '2025-10-14 17:56:40', '2025-10-14 17:56:40'),
(21, '10,11', 'heading', 'message', '2025-10-14 17:57:24', '2025-10-14 17:57:24'),
(22, '10,11', 'kshitiz', 'kumar', '2025-10-14 17:57:51', '2025-10-14 17:57:51'),
(23, '10,11', 'kshitiz', 'kumar', '2025-10-14 17:59:08', '2025-10-14 17:59:08'),
(24, '10,11', 'hi', 'hii bro how are you', '2025-10-14 18:00:57', '2025-10-14 18:00:57'),
(25, '10,11', 'test', 'it bro', '2025-10-15 21:52:15', '2025-10-15 21:52:15'),
(26, '10,11', 'te', 'ydu', '2025-10-22 19:10:22', '2025-10-22 19:10:22'),
(27, '10,11', 'sdf', 'asdf', '2025-10-22 19:10:41', '2025-10-22 19:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(8, 'create cars', 'web', '2025-10-07 14:09:52', '2025-10-07 14:09:52'),
(9, 'view cars', 'web', '2025-10-07 14:10:02', '2025-10-07 14:10:02'),
(10, 'edit cars', 'web', '2025-10-07 14:10:11', '2025-10-07 14:10:11'),
(13, 'delete cars', 'web', '2025-10-07 14:12:00', '2025-10-07 14:12:00'),
(14, 'create user', 'web', '2025-10-07 14:12:10', '2025-10-07 14:12:10'),
(15, 'view user', 'web', '2025-10-07 14:12:18', '2025-10-07 14:12:18'),
(16, 'edit user', 'web', '2025-10-07 14:12:34', '2025-10-07 14:12:34'),
(17, 'delete user', 'web', '2025-10-07 14:13:14', '2025-10-07 14:13:14'),
(18, 'create driverschedule', 'web', '2025-10-07 14:13:45', '2025-10-07 14:13:45'),
(19, 'view driverschedule', 'web', '2025-10-07 14:13:54', '2025-10-07 14:13:54'),
(20, 'edit driverschedule', 'web', '2025-10-07 14:14:01', '2025-10-07 14:14:01'),
(21, 'delete driverschedule', 'web', '2025-10-07 14:14:11', '2025-10-07 14:14:11'),
(22, 'create lead', 'web', '2025-10-07 14:14:23', '2025-10-07 14:14:23'),
(23, 'view lead', 'web', '2025-10-07 14:14:30', '2025-10-07 14:14:30'),
(24, 'edit lead', 'web', '2025-10-07 14:14:40', '2025-10-07 14:14:40'),
(25, 'delete lead', 'web', '2025-10-07 14:14:47', '2025-10-07 14:14:47'),
(26, 'create inquiries', 'web', '2025-10-07 14:15:04', '2025-10-07 14:15:04'),
(27, 'view inquiries', 'web', '2025-10-07 14:15:15', '2025-10-07 14:15:15'),
(28, 'edit inquiries', 'web', '2025-10-07 14:15:22', '2025-10-07 14:15:22'),
(29, 'delete inquiries', 'web', '2025-10-07 14:15:30', '2025-10-07 14:15:30'),
(32, 'view role', 'web', '2025-10-08 17:54:39', '2025-10-08 17:54:39'),
(33, 'add role', 'web', '2025-10-08 17:54:39', '2025-10-08 17:54:39'),
(34, 'edit role', 'web', '2025-10-08 17:54:39', '2025-10-08 17:54:39'),
(35, 'delete role', 'web', '2025-10-08 17:54:39', '2025-10-08 17:54:39'),
(36, 'create garage', 'web', '2025-10-15 14:43:42', '2025-10-15 14:43:42'),
(37, 'edit garage', 'web', '2025-10-15 14:43:56', '2025-10-15 14:43:56'),
(38, 'view garage', 'web', '2025-10-15 14:44:03', '2025-10-15 14:44:03'),
(39, 'delete garage', 'web', '2025-10-15 14:44:10', '2025-10-15 14:44:10'),
(40, 'create drivertask', 'web', '2025-10-24 13:40:07', '2025-10-24 13:40:07'),
(41, 'edit drivertask', 'web', '2025-10-24 13:40:18', '2025-10-24 13:40:18'),
(42, 'view drivertask', 'web', '2025-10-24 13:40:25', '2025-10-24 13:40:25'),
(43, 'delete drivertask', 'web', '2025-10-24 13:40:32', '2025-10-24 13:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author`, `title`, `created_at`, `updated_at`) VALUES
(1, 'trst', 'sdaf', '2025-10-13 14:58:13', '2025-10-13 14:58:13'),
(2, 'asdasd', 'asdfdsf', '2025-10-13 15:02:58', '2025-10-13 15:02:58'),
(3, 'asdf', 'asdf', '2025-10-13 15:07:19', '2025-10-13 15:07:19'),
(4, 'asdf', 'asdf', '2025-10-13 15:07:58', '2025-10-13 15:07:58'),
(5, 'kshitiz', 'kumar', '2025-10-13 15:08:17', '2025-10-13 15:08:17'),
(6, 'bhai', 'thanku', '2025-10-13 15:12:28', '2025-10-13 15:12:28'),
(7, 'trst', 'kjh', '2025-10-13 15:13:18', '2025-10-13 15:13:18'),
(8, 'testetstte', 'estet', '2025-10-14 01:18:26', '2025-10-14 01:18:26'),
(9, 'trst', 'sdf', '2025-10-14 01:20:19', '2025-10-14 01:20:19'),
(10, 'asdf', 'tdfgd', '2025-10-14 01:23:25', '2025-10-14 01:23:25'),
(11, 'testetstte', 'dsfasf', '2025-10-14 01:23:35', '2025-10-14 01:23:35'),
(12, 'hii', 'i am there', '2025-10-14 02:00:35', '2025-10-14 02:00:35'),
(13, 'hii', 'i am there', '2025-10-14 02:01:11', '2025-10-14 02:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(10, 'Admin', 'web', '2025-10-08 18:02:31', '2025-10-15 14:44:26'),
(11, 'carmanager', 'web', '2025-10-09 16:15:04', '2025-10-09 16:15:04'),
(12, 'driver', 'web', '2025-10-28 09:14:04', '2025-10-28 09:14:04'),
(13, 'Lead Manager', 'web', '2025-10-31 15:21:39', '2025-10-31 15:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(8, 10),
(8, 11),
(9, 5),
(9, 10),
(9, 11),
(9, 12),
(10, 10),
(10, 11),
(13, 10),
(13, 11),
(14, 5),
(14, 10),
(15, 5),
(15, 10),
(16, 10),
(17, 10),
(18, 10),
(19, 10),
(20, 10),
(21, 10),
(22, 10),
(22, 13),
(23, 10),
(23, 13),
(24, 10),
(24, 13),
(25, 10),
(25, 13),
(26, 5),
(26, 10),
(27, 10),
(28, 10),
(29, 10),
(32, 10),
(33, 10),
(34, 10),
(35, 10),
(36, 10),
(37, 10),
(38, 10),
(38, 12),
(39, 10),
(41, 12),
(42, 12);

-- --------------------------------------------------------

--
-- Table structure for table `service_master`
--

CREATE TABLE `service_master` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_master`
--

INSERT INTO `service_master` (`id`, `unique_id`, `service_type`, `updated_at`, `created_at`) VALUES
(1, 'qqq1', 'Full Service', '2025-10-10 19:29:17', '2025-10-10 19:29:17'),
(2, 'qqq2', 'Battery Check', '2025-12-30 17:02:02', '2025-10-10 19:29:32');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6cuhdfAhRLCoQftG5dITcZorPS53OEvTQuIBNYti', 1, '223.185.62.255', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWWoxTkh5bzM3NDJlazF0U1NkMjhCaGFjcVcyTjBzOXl4RktoYmN5cCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vbWFuYWdlci53ZWRpbndoZWVscy5jb20vZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1767123886),
('7buTpL6A1Grq2tGiZaM9AxquFH7QKDdoegaeUnTi', NULL, '54.74.99.107', 'Mozilla/4.0 (compatible; Netcraft Web Server Survey)', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTklzUkFmM3ZHTGgzTHNpTHJoU2dmczZPTjdiTzg1ZmtvUzYzc05pSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1767201001),
('cORVBqXb8wELrXLqKjszAEmWLfdLyC9col4xC6Jo', NULL, '34.241.70.47', 'Mozilla/5.0 (compatible; NetcraftSurveyAgent/1.0; +info@netcraft.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXBSdjhwVEhjZ0lRRlRTUmRwS3VhVTN3TGx3blF3cHRaTTJHOWlnYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9tYW5hZ2VyLndlZGlud2hlZWxzLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1767413678),
('eVUSdZUz7LSIFyKfxXfLU9X13JpkKCQ5VmrVNTWx', 1, '160.22.253.147', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2VuV1AyWHk1bmpEelNFOUVBcDNGcnNUbk0yZmc1cmxuOFFEdTJ6YiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vbWFuYWdlci53ZWRpbndoZWVscy5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1767196180),
('OZvodQpmhPMg3aGLb52Bb7ruyjGYj7rBdEak66Uw', NULL, '74.7.242.55', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1pWVDczclZlemo3QzBwYkNMU29BaVd4VWRwd0ZpZjhFVDAzbEMyNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vbWFuYWdlci53ZWRpbndoZWVscy5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1767260423),
('zhJwKFxvFT4LDQ7VJTMt2r8GYqs8B8JDGWDsjFG9', 1, '223.185.62.130', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDdVRGltTEMwWm9SNTBXQ2s3bFpoa2JYOTA0a3RCUTFUd1J1QWI5SyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vbWFuYWdlci53ZWRpbndoZWVscy5jb20vdXNlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1767365019);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', NULL, '$2y$12$ThqO4/nOo8u0dxkafBhNveplAP7r6RqDC82q3eWh8XFk8o6lP8QHS', NULL, NULL, '2025-10-07 11:31:06', '2025-10-07 10:16:54'),
(6, 'test', 'KSHITIZ.UMU@GMAIL.COM', '2025-10-23 03:39:25', '$2y$12$VKwNiyJpiwwF1hPnrOkh.OJnKAYyGb5diusMFN0EuYUH2ah62gOuW', 'iDWCisK4kJ', 'Active', '2025-10-23 03:39:25', '2025-10-23 03:39:25'),
(7, 'Harendra kumar', 'harendra@gmail.com', '2025-10-28 03:44:43', '$2y$12$/AYTTJ4Rc2t.6PyTOHf13u5388O4u7g6.At8XLajaIPdogmn2G7LG', 'tyKo8FzIj2', 'Active', '2025-10-28 03:44:43', '2025-10-28 03:44:43'),
(8, 'Naresh', 'naresh@gmail.com', '2025-10-28 03:45:20', '$2y$12$vD1fzXdzuCup6JOrAaGA8ekWcA7rH0XQTAzwtTnPZxvs15f38gtLS', 'kDBrlVkxS5', 'Active', '2025-10-28 03:45:20', '2025-10-28 03:45:20'),
(9, 'Nisha', 'nisha@gmail.com', '2025-10-31 09:52:08', '$2y$12$piY712tuj.GR27CSKUaKu.4tohxestYrDTUMs5eo9qtwJxqbiui/C', 'GS2HSmDErr', 'Active', '2025-10-31 09:52:08', '2025-10-31 09:52:08'),
(10, 'Rifat', 'rifat@gmail.com', '2025-10-31 09:52:48', '$2y$12$rdHQrqvAjYMkIza2AyTXEuX6uRrTOLHUiEHUlMtNq99MG7XEG6ile', 'iBBjP9jmSw', 'Active', '2025-10-31 09:52:48', '2025-10-31 09:52:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alert_test`
--
ALTER TABLE `alert_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_documents`
--
ALTER TABLE `car_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_service`
--
ALTER TABLE `car_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_responses`
--
ALTER TABLE `client_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_master`
--
ALTER TABLE `document_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_schedules`
--
ALTER TABLE `driver_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `garage_master`
--
ALTER TABLE `garage_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `service_master`
--
ALTER TABLE `service_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `alert_test`
--
ALTER TABLE `alert_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `car_documents`
--
ALTER TABLE `car_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `car_service`
--
ALTER TABLE `car_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `client_responses`
--
ALTER TABLE `client_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_master`
--
ALTER TABLE `document_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `driver_schedules`
--
ALTER TABLE `driver_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `garage_master`
--
ALTER TABLE `garage_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `service_master`
--
ALTER TABLE `service_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
