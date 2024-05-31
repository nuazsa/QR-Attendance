-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 31, 2024 at 06:35 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qrattend`
--

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `pelajaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ruangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hari` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mulai` time NOT NULL,
  `selesai` time NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `id_admin`, `pelajaran`, `ruangan`, `hari`, `mulai`, `selesai`, `tanggal`) VALUES
(1, 1, 'Economy', 'XI - IPS 1', 'Monday', '19:00:00', '21:00:00', '2024-06-03'),
(2, 1, 'Biology', 'X - IPA 1', 'Tuesday', '13:00:00', '13:30:00', '2024-05-31'),
(3, 1, 'Biology', 'XI - IPA 2', 'Tuesday', '10:00:00', '12:00:00', '2024-06-04'),
(4, 1, 'Biology', 'XI - IPA 3', 'Friday', '07:00:00', '09:00:00', '2024-05-10');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `name`, `password`, `role`) VALUES
(1, '00000000', 'Achmad Sumbaryadi M.Kom', '$2a$12$fTKLPZBzYToEtslHgzHAuuXMsi8rwVoHnLMkGbt4PuY1KNX3Vyiw.', 'admin'),
(2, '19221012', 'MIFTYA AMANOOR', '$2a$12$PE1J/TGCiX5zfEVlm0LeUe7A93Zgymt73jGayOWApdqM6cV7QwacW', 'user'),
(3, '19220377', 'WAHYUNI RINA ASTARI', '$2a$12$PE1J/TGCiX5zfEVlm0LeUe7A93Zgymt73jGayOWApdqM6cV7QwacW', 'user'),
(4, '12220401', 'HERU PURWANTO', '$2a$12$qrsTQXWaHYJBMl9QO83F2.1jHkoDU1Bivj.P2dA8NX.hGokj3whKC', 'user'),
(5, '12221152', 'BINTANG NURHUSNI M', '$2a$12$qrsTQXWaHYJBMl9QO83F2.1jHkoDU1Bivj.P2dA8NX.hGokj3whKC', 'user'),
(6, '17220023', 'DANAR WIBISONO', '$2a$12$sBdCB0180bn0F1Yz/7NU/OQTTSZBHwtj1gRxXiSheed/7F0MGExBu', 'user'),
(7, '17220160', 'NUR AZIS SAPUTRA', '$2a$12$sBdCB0180bn0F1Yz/7NU/OQTTSZBHwtj1gRxXiSheed/7F0MGExBu', 'user'),
(8, '17220338', 'MUHAMMAD RIZKY TRI S', '$2a$12$oEobv0CnrhUMouSBnlZMxeTNlyVMqF/4jCxxnWCs/I56IQkbMRLsK', 'user'),
(9, '17220149', 'SANDI HERMAWAN', '$2a$12$oEobv0CnrhUMouSBnlZMxeTNlyVMqF/4jCxxnWCs/I56IQkbMRLsK', 'user'),
(10, '19220088', 'FITHO AFIFI', '$2a$12$cqscwEhjE/UNATqCODDEgO0VK6Ukt10llggTLohI8OKidMwH7Hh46', 'user'),
(11, '19221476', 'NAUFALFATIHULMAJID', '$2a$12$cqscwEhjE/UNATqCODDEgO0VK6Ukt10llggTLohI8OKidMwH7Hh46', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `pertemuan` int NOT NULL,
  `uniq_qr` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qrcodes`
--

CREATE TABLE `qrcodes` (
  `id` int NOT NULL,
  `id_kelas` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `qr_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('active','success') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qrcodes`
--

INSERT INTO `qrcodes` (`id`, `id_kelas`, `id_user`, `qr_code`, `status`) VALUES
(3, 1, 7, '66596851662ca', 'active'),
(4, 2, 7, '66596ebe5dd1f', 'active'),
(5, 1, 4, '6659685168ea2', 'active'),
(6, 1, 2, '6659685169e1c', 'active'),
(7, 1, 3, '665968516b7b0', 'active'),
(8, 1, 5, '665968516c711', 'active'),
(9, 1, 6, '665968516d791', 'active'),
(10, 1, 8, '665968516e4b7', 'active'),
(11, 1, 9, '6659685171193', 'active'),
(12, 1, 10, '665968517230b', 'active'),
(13, 1, 11, '6659685173af7', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `qrcodes`
--
ALTER TABLE `qrcodes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `pengguna` (`id`);

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `pengguna` (`id`);

--
-- Constraints for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD CONSTRAINT `qrcodes_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `qrcodes_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `pengguna` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
