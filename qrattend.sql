-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 09, 2024 at 04:08 PM
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
-- Table structure for table `detail_kelas`
--

CREATE TABLE `detail_kelas` (
  `id_detail_kelas` int NOT NULL,
  `id_kelas` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_pengguna` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_kelas`
--

INSERT INTO `detail_kelas` (`id_detail_kelas`, `id_kelas`, `id_pengguna`) VALUES
(1, 'EC0-1', 7),
(2, 'BIO-1', 7),
(3, 'BIO-1', 4),
(4, 'BIO-1', 5);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `id_guru` int DEFAULT NULL,
  `pelajaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ruangan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hari` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mulai` time NOT NULL,
  `selesai` time NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `id_guru`, `pelajaran`, `ruangan`, `hari`, `mulai`, `selesai`, `tanggal`) VALUES
('BIO-1', 1, 'Biology', 'X - IPA 1', 'Sunday', '10:00:00', '23:30:00', '2024-06-09'),
('BIO-2', 1, 'Biology', 'XI - IPA 2', 'Tuesday', '10:00:00', '12:00:00', '2024-06-04'),
('BIO-3', 1, 'Biology', 'XI - IPA 3', 'Friday', '07:00:00', '09:00:00', '2024-05-10'),
('EC0-1', 1, 'Economy', 'XI - IPS 1', 'Monday', '19:00:00', '21:00:00', '2024-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('siswa','guru') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `name`, `password`, `role`) VALUES
(1, '00000000', 'Achmad Sumbaryadi M.Kom', '$2a$12$fTKLPZBzYToEtslHgzHAuuXMsi8rwVoHnLMkGbt4PuY1KNX3Vyiw.', 'guru'),
(2, '19221012', 'MIFTYA AMANOOR', '$2a$12$PE1J/TGCiX5zfEVlm0LeUe7A93Zgymt73jGayOWApdqM6cV7QwacW', 'siswa'),
(3, '19220377', 'WAHYUNI RINA ASTARI', '$2a$12$PE1J/TGCiX5zfEVlm0LeUe7A93Zgymt73jGayOWApdqM6cV7QwacW', 'siswa'),
(4, '12220401', 'HERU PURWANTO', '$2a$12$qrsTQXWaHYJBMl9QO83F2.1jHkoDU1Bivj.P2dA8NX.hGokj3whKC', 'siswa'),
(5, '12221152', 'BINTANG NURHUSNI M', '$2a$12$qrsTQXWaHYJBMl9QO83F2.1jHkoDU1Bivj.P2dA8NX.hGokj3whKC', 'siswa'),
(6, '17220023', 'DANAR WIBISONO', '$2a$12$sBdCB0180bn0F1Yz/7NU/OQTTSZBHwtj1gRxXiSheed/7F0MGExBu', 'siswa'),
(7, '17220160', 'NUR AZIS SAPUTRA', '$2a$12$sBdCB0180bn0F1Yz/7NU/OQTTSZBHwtj1gRxXiSheed/7F0MGExBu', 'siswa'),
(8, '17220338', 'MUHAMMAD RIZKY TRI S', '$2a$12$oEobv0CnrhUMouSBnlZMxeTNlyVMqF/4jCxxnWCs/I56IQkbMRLsK', 'siswa'),
(9, '17220149', 'SANDI HERMAWAN', '$2a$12$oEobv0CnrhUMouSBnlZMxeTNlyVMqF/4jCxxnWCs/I56IQkbMRLsK', 'siswa'),
(10, '19220088', 'FITHO AFIFI', '$2a$12$cqscwEhjE/UNATqCODDEgO0VK6Ukt10llggTLohI8OKidMwH7Hh46', 'siswa'),
(11, '19221476', 'NAUFALFATIHULMAJID', '$2a$12$cqscwEhjE/UNATqCODDEgO0VK6Ukt10llggTLohI8OKidMwH7Hh46', 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` int NOT NULL,
  `id_kelas` char(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_pengguna` int DEFAULT NULL,
  `id_qrcode` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id_presensi`, `id_kelas`, `id_pengguna`, `id_qrcode`, `tanggal`, `jam`) VALUES
(2, 'BIO-1', 4, 42, '2024-06-08', '11:22:00'),
(11, 'BIO-1', 7, 42, '2024-06-08', '20:22:00'),
(13, 'BIO-1', 4, 43, '2024-06-09', '21:32:00'),
(16, 'BIO-1', 7, 43, '2024-06-09', '22:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `qrcodes`
--

CREATE TABLE `qrcodes` (
  `id_qrcode` int NOT NULL,
  `id_kelas` char(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `qr_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `pertemuan` int NOT NULL,
  `tanggal_pembuatan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qrcodes`
--

INSERT INTO `qrcodes` (`id_qrcode`, `id_kelas`, `qr_code`, `pertemuan`, `tanggal_pembuatan`) VALUES
(41, 'BIO-1', 'BIO-1-1-6663cb72b5181', 1, '2024-06-07'),
(42, 'BIO-1', 'BIO-1-2-6663ccf521869', 2, '2024-06-08'),
(43, 'BIO-1', 'BIO-1-3-6665afcc58f1b', 3, '2024-06-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_kelas`
--
ALTER TABLE `detail_kelas`
  ADD PRIMARY KEY (`id_detail_kelas`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_admin` (`id_guru`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_pengguna`),
  ADD KEY `id_qrcode` (`id_qrcode`);

--
-- Indexes for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD PRIMARY KEY (`id_qrcode`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_kelas`
--
ALTER TABLE `detail_kelas`
  MODIFY `id_detail_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id_presensi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `qrcodes`
--
ALTER TABLE `qrcodes`
  MODIFY `id_qrcode` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_kelas`
--
ALTER TABLE `detail_kelas`
  ADD CONSTRAINT `detail_kelas_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `detail_kelas_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `presensi_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `presensi_ibfk_3` FOREIGN KEY (`id_qrcode`) REFERENCES `qrcodes` (`id_qrcode`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `qrcodes`
--
ALTER TABLE `qrcodes`
  ADD CONSTRAINT `qrcodes_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
