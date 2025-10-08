-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 04:05 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pemasukan`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi_teknisi`
--

CREATE TABLE `absensi_teknisi` (
  `id` int(11) NOT NULL,
  `karyawan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` enum('hadir','terlambat','izin','alpha') DEFAULT 'hadir',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absensi_teknisi`
--

INSERT INTO `absensi_teknisi` (`id`, `karyawan_id`, `tanggal`, `jam_masuk`, `jam_pulang`, `status`, `created_at`, `updated_at`) VALUES
(2, 11, '2025-10-08', '04:29:12', '06:38:03', 'hadir', '2025-10-07 21:29:12', '2025-10-07 23:38:03');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_in_lat` decimal(10,8) DEFAULT NULL,
  `check_in_lng` decimal(11,8) DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `check_out_lat` decimal(10,8) DEFAULT NULL,
  `check_out_lng` decimal(11,8) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `schedule_id`, `check_in`, `check_in_lat`, `check_in_lng`, `check_out`, `check_out_lat`, `check_out_lng`, `reason`, `photo_url`, `created_at`, `update_at`) VALUES
(4, 1, 1, '2025-10-08 03:33:36', '-6.73025000', '106.77974000', NULL, NULL, NULL, NULL, NULL, '2025-10-08 10:33:36', '0000-00-00 00:00:00'),
(5, 1, 1, '2025-10-08 03:48:07', '-6.73025000', '106.77974000', NULL, NULL, NULL, NULL, NULL, '2025-10-08 10:48:07', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(4, 'Pembayaran BW', 'income', '2025-05-28 06:08:35', '2025-05-28 06:08:35'),
(6, 'Beli Router', 'expense', '2025-05-28 06:10:21', '2025-05-28 06:10:21'),
(7, 'TF Bank', 'income', '2025-05-28 08:01:13', '2025-05-28 08:01:13');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id` int(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `gaji_pokok` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tunjangan` decimal(12,2) DEFAULT 0.00,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `user_id`, `name`, `nik`, `jabatan`, `tanggal_masuk`, `gaji_pokok`, `tunjangan`, `status`, `created_at`, `updated_at`, `foto`) VALUES
(2, 8, 'Epul', '32010192919291123123', 'HR 1', '2025-09-04', '30000000.00', '20000.00', 'nonaktif', '2025-09-18 01:17:15', '2025-09-18 06:39:23', NULL),
(3, 9, 'alan', '3214234234', 'HR', '2025-09-10', '231123.00', '123123.00', 'aktif', '2025-09-18 01:38:01', '2025-09-18 01:38:01', NULL),
(4, 10, 'masdons', '320129192912', 'Teknisi', '2025-09-01', '213213.00', '123123321.00', 'aktif', '2025-09-18 01:51:44', '2025-09-18 01:51:44', NULL),
(5, 11, 'alan', '32912390812398123', 'Teknisi', '2025-09-17', '213213.00', '231123.00', 'aktif', '2025-09-18 01:52:43', '2025-09-18 01:52:43', NULL),
(7, 13, 'Mas Dons', '213123123123213', 'Teknisi', '2025-09-04', '231213.00', '123123.00', 'aktif', '2025-09-18 07:23:09', '2025-09-18 07:23:09', NULL),
(8, 14, 'akak', '1234324234', 'Teknsii', '2025-09-10', '213123123.00', '123123123.00', 'aktif', '2025-09-18 07:37:04', '2025-09-18 07:37:04', NULL),
(9, 15, 'dons', '2424334434343', 'HR', '2025-09-01', '12212121.00', '1221212121.00', 'aktif', '2025-09-18 07:40:59', '2025-09-18 07:40:59', NULL),
(10, 16, 'kaka doins', '98675563445345', 'HR', '2025-09-03', '123123123.00', '123123123.00', 'aktif', '2025-09-18 07:51:28', '2025-09-18 08:02:28', '1758207087.jpg'),
(11, 17, 'masalan', '3201332039209102', 'TEKNISI', '2025-09-19', '20000000.00', '2000000.00', 'aktif', '2025-09-19 00:11:04', '2025-09-19 00:11:04', '1758265864.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `office_locations`
--

CREATE TABLE `office_locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `radius` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `office_locations`
--

INSERT INTO `office_locations` (`id`, `name`, `latitude`, `longitude`, `radius`, `created_at`) VALUES
(1, 'Kantor Abiraya', '-6.73025890', '106.77974170', 200, '2025-10-08 08:06:22'),
(2, 'Kantor Abiraya', '-6.73025890', '106.77974170', 200, '2025-10-08 08:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `start_time`, `end_time`, `created_at`) VALUES
(1, '1', '08:00:00', '18:00:00', '2025-10-08 10:31:17');

-- --------------------------------------------------------

--
-- Table structure for table `shift_schedules`
--

CREATE TABLE `shift_schedules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shift_schedules`
--

INSERT INTO `shift_schedules` (`id`, `user_id`, `shift_id`, `date`, `created_at`) VALUES
(1, 17, 1, '2025-10-07', '2025-10-08 10:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_type` enum('income','expense') NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `category_id`, `description`, `amount`, `transaction_type`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, 4, 'Pulsa Update', '1200000.00', 'income', '2025-05-08', '2025-05-28 07:50:10', '2025-05-28 19:50:52'),
(2, 4, 'TF bank', '1200000.00', 'income', '2025-05-13', '2025-05-28 08:00:32', '2025-05-28 08:00:32'),
(3, 7, 'TF bank', '800000.00', 'income', '2025-05-06', '2025-05-28 19:23:37', '2025-05-28 19:53:22'),
(5, 6, 'Router Update', '700000.00', 'expense', '2025-05-02', '2025-05-28 20:30:37', '2025-05-28 20:34:30'),
(7, 6, 'Beli PC', '2340000.00', 'expense', '2025-05-02', '2025-05-28 21:03:57', '2025-05-28 21:03:57'),
(8, 4, 'bw dons', '1000000.00', 'income', '2025-05-26', '2025-05-28 21:53:02', '2025-05-28 21:53:02'),
(9, 6, 'Router', '700000.00', 'expense', '2025-05-26', '2025-05-28 21:53:36', '2025-05-28 21:53:36'),
(10, 4, 'TF bank', '1000000.00', 'income', '2025-04-01', '2025-05-29 00:23:20', '2025-05-29 00:23:20'),
(11, 6, 'Dons', '900000.00', 'expense', '2025-03-01', '2025-05-29 01:03:38', '2025-05-29 01:03:38'),
(12, 4, 'TF bank', '1300000.00', 'income', '2025-05-21', '2025-05-29 01:47:00', '2025-05-29 01:47:00'),
(14, 4, 'TF bank', '120000.00', 'income', '2025-06-30', '2025-06-30 05:27:41', '2025-06-30 05:27:41'),
(15, 6, 'Beli PC', '900000.00', 'expense', '2025-07-01', '2025-06-30 05:28:52', '2025-06-30 05:28:52'),
(16, 7, 'transfer 1 - 30 sep', '200000000.00', 'income', '2025-09-22', '2025-09-21 23:54:53', '2025-09-21 23:54:53'),
(17, 6, 'Beli router omjhon 50 pcs', '50000000.00', 'expense', '2025-09-22', '2025-09-21 23:56:43', '2025-09-21 23:56:43'),
(18, 6, 'Router', '2300000.00', 'expense', '2025-09-11', '2025-09-21 23:57:02', '2025-09-21 23:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','teknisi') NOT NULL DEFAULT 'teknisi',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Satu', 'admin@example.com', '$2y$12$dpmE6oIX9SDXQxjuTiT1COuD1vrfz2.5gyOW3akQx/3SyHVTAvwpO', 'admin', '2025-05-20 23:54:41', '2025-05-20 23:54:41'),
(2, 'dons', 'dons@gmail.com', '$2y$12$BmSJZSKXVX0D8M/qtQK.tuvJCB/0m3yNCiov.sDvM.bK/DUCu7gPm', 'admin', '2025-05-21 20:22:05', '2025-05-21 20:22:05'),
(3, 'alan', 'alan@gmail.com', '$2y$12$b4ugHbE5v2iuA61DET6ZSuOEkGHsOPa9ctrycsq/B.44BZOmliKAq', 'superadmin', '2025-05-21 20:23:00', '2025-05-21 20:23:00'),
(4, 'alan', 'alansolihin60@gmail.com', '$2y$12$370PpE.dUJLiMePNE/3Ou.ErRa632knDlOQJ/Kqzou5T9kHdDppf2', 'teknisi', '2025-05-22 07:17:07', '2025-05-22 07:17:07'),
(5, 'superadmin', 'superadmin@gmail.com', '$2y$12$R6s4BLhRhw2xbNl1HMYfNO4VeHn0aDYIjYGA2.8aCWjahkA.8zHfK', 'superadmin', '2025-05-22 07:20:23', '2025-05-22 07:20:23'),
(8, 'Epul', 'Epul@gmail.com', '$2y$12$QwSwkocguK41eu.HB0/OBOT55VgQkZCQt.nPbFt28DsEsA.70He5S', 'teknisi', '2025-09-18 01:17:15', '2025-09-18 01:17:15'),
(9, 'alan', 'masdons@gmail.com', '$2y$12$kD0eFrv9fIK33FZ0HPML9O0JE0g6q4fJUFQHWJqWPDG5aXbQM7LyG', 'teknisi', '2025-09-18 01:38:01', '2025-09-18 01:38:01'),
(10, 'masdons', 'massdons@gmail.com', '$2y$12$uD2Ycy47jHgf/ygjsL3zveWYS4Erljr4HTBwOxThoawzZjQZDRzNi', 'teknisi', '2025-09-18 01:51:44', '2025-09-18 01:51:44'),
(11, 'alan', 'aslan@gmail.com', '$2y$12$oCHypeEFiBMEw0p4QDqknOgB03DJJUDJa9PRuJj.6nWEBIiyYZC3G', 'teknisi', '2025-09-18 01:52:43', '2025-09-18 01:52:43'),
(13, 'Mas Dons', 'masdosss20@gmail.com', '$2y$12$IYpNyByXxBiaJjqIkvHfleUG2y2LUM7JRIkTqEhPX6fGBC1MTUEoe', 'teknisi', '2025-09-18 07:23:09', '2025-09-18 07:23:09'),
(14, 'akak', 'kaka@gmail.com', '$2y$12$4BMUdNwA2AVL1NN4Go6lzeCSR/SM/SvM693HYi9NCx.sq7n7RU5ua', 'teknisi', '2025-09-18 07:37:04', '2025-09-18 07:37:04'),
(15, 'dons', 'donstews@gmail.com', '$2y$12$JHzwtLuPL8SqbZ2w8uA9q.Kx3zt7FSYgCoe17gkXvI16WeysLAPhy', 'teknisi', '2025-09-18 07:40:59', '2025-09-18 07:40:59'),
(16, 'kaka doins', 'kakadosn@gmail.com', '$2y$12$RJtWCbTgPkkLEMDDgOUhHOJatwchADQ5XncAi/NJ.ETKHWRIG4hMi', 'teknisi', '2025-09-18 07:51:28', '2025-09-18 07:51:28'),
(17, 'masalan', 'masalan20@gmail.com', '$2y$12$HKUtQEK.OHn2JDeuiYN.HOxvzPSnhX2XjdUhneFXAYTvc9Uw1PfAu', 'teknisi', '2025-09-19 00:11:04', '2025-09-19 00:11:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi_teknisi`
--
ALTER TABLE `absensi_teknisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `karyawan_id` (`karyawan_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_locations`
--
ALTER TABLE `office_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_schedules`
--
ALTER TABLE `shift_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi_teknisi`
--
ALTER TABLE `absensi_teknisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `office_locations`
--
ALTER TABLE `office_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shift_schedules`
--
ALTER TABLE `shift_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi_teknisi`
--
ALTER TABLE `absensi_teknisi`
  ADD CONSTRAINT `absensi_teknisi_ibfk_1` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `shift_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shift_schedules`
--
ALTER TABLE `shift_schedules`
  ADD CONSTRAINT `shift_schedules_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shift_schedules_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
