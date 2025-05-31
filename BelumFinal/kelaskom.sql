-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 06:15 PM
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
-- Database: `kelaskom`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'Ahmad Subarjo', '12345'),
(2, 'Aceng', 'aceng123');

-- --------------------------------------------------------

--
-- Table structure for table `dosmhs`
--

CREATE TABLE `dosmhs` (
  `id_dosmhs` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosmhs`
--

INSERT INTO `dosmhs` (`id_dosmhs`, `username`, `prodi`, `password`) VALUES
(2, 'Winata Suryana', 'Sistem Informasi', '$2y$10$SKJFVk43FFWzwKOWf/i92./s17dQ8O3gW28wpkf/xQPRAk9Mkpurq'),
(3, 'Robert Irawan', 'Sistem Informasi', '$2y$10$5Iehkb65v.n6IRZ7hiyO/.ntv.kPQVKVT4gyc91ghQOxmT1DurKRC'),
(4, 'Mahesa Maladewa', 'Informatika', '$2y$10$oKwfvxIDT/JyLfXM1QUQWeLDPTyd3EA4sFeib.t3To95ITL6NG1X.'),
(5, 'JALALUDIN ALMAHMUD', 'Informatika', '$2y$10$tZ63Hn76zR.vBPO.LgRz/O.y8iCJdZKg4uuG51kvU8WFMHZuy/sC.'),
(6, 'BUDI SUDARSONO', 'Sistem Informasi', '$2y$10$YwPYntDYarDSGcHKYU6MpOLWLyZXofpJnOHAoMs.cghfCwFfYSRfK'),
(7, 'YUNUS KARBIT', 'Informatika', '$2y$10$O5sB/AFgC7AiyWS.34CTROElgyWnqZFJdUmnxxHBJPbbpFDf7KIC2');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_matkul`
--

CREATE TABLE `jadwal_matkul` (
  `id` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `kode_matkul` varchar(20) NOT NULL,
  `mata_kuliah` varchar(100) NOT NULL,
  `sks` int(1) NOT NULL,
  `dosen` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `ruangan` varchar(20) NOT NULL,
  `prodi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_matkul`
--

INSERT INTO `jadwal_matkul` (`id`, `hari`, `jam`, `kode_matkul`, `mata_kuliah`, `sks`, `dosen`, `kelas`, `ruangan`, `prodi`) VALUES
(2, 'SENIN', '12.30-15.00', 'USK61101', 'PENDIDIKAN AGAMA', 2, 'Dr. Khalid Ramdhani, M.Pd.I', 'II. A', 'Kelas 4.77 - 3', 'Informatika'),
(3, 'SELASA', '07.30-10.00', 'USK61303', 'PANCASILA', 2, 'R. Bagus Irawan, S.H., M.H', 'II. A', 'Kelas 4.78 - 4', 'Informatika'),
(4, 'RABU', '10.00-12.30', 'FIK61507', 'ALJABAR LINEAR', 3, 'Irfan Sriyono Putro, S.T., M.Si', 'II. A', 'Kelas 4.77 - 3', 'Informatika'),
(5, 'RABU', '15.00-17.30', 'USK61202', 'BAHASA INDONESIA', 2, 'Dewi Herlina Sugiarti, S.S, S.pd., M.Pd.', 'II. A', 'Kelas 4.78 - 4', 'Informatika'),
(6, 'KAMIS', '10.00-12.30', 'FIK61508', 'INTERAKSI MANUSIA DAN KOMPUTER', 3, 'Arip Solehudin, M.Kom.', 'II. A', 'Kelas 4.75 - 1', 'Informatika'),
(7, 'JUMAT', '07.30-10.00', 'USK61207', 'BUDAYA BANGSA', 2, 'Dr. Dede Jajang Suyaman, S.E., M.M.', 'II. A', 'Kelas 4.78 - 4', 'Informatika'),
(8, 'JUMAT', '10.00-12.30', 'FIK61509', 'ORGANISASI DAN ARSITEKTUR KOMPUTER', 3, 'Irfan Sriyono Putro, S.T., M.Si', 'II. A', 'Kelas 4.77 - 3', 'Informatika'),
(9, 'RABU', '07.30-10.00', 'FIK61506', 'STRUKTUR DATA', 3, 'Asep Jamaludin, S.Si., M.Kom.', 'II. A', 'LAB LANJUT 1', 'Informatika'),
(10, 'SENIN', '15.00-17.30', 'USK61101', 'PENDIDIKAN AGAMA', 2, 'Dr. Khalid Ramdhani, M.Pd.I', 'II. B', 'Kelas 4.76 - 2', 'Informatika'),
(11, 'RABU', '07.30-10.00', 'FIK61507', 'ALJABAR LINEAR', 3, 'Irfan Sriyono Putro, S.T., M.Si', 'II. B', 'Kelas 4.77 - 3', 'Informatika'),
(12, 'RABU', '10.00-12.30', 'USK61303', 'PANCASILA', 2, 'R. Bagus Irawan, S.H., M.H', 'II. B', 'Kelas 4.75 - 1', 'Informatika'),
(13, 'KAMIS', '15.00-17.30', 'FIK61508', 'INTERAKSI MANUSIA DAN KOMPUTER', 3, 'Rini Mayasari, M.Kom.', 'II. B', 'Kelas 4.75 - 1', 'Informatika'),
(14, 'JUMAT', '07.30-10.00', 'USK61202', 'BAHASA INDONESIA', 2, 'Dewi Herlina Sugiarti, S.S, S.pd., M.Pd.', 'II. B', 'Kelas 4.76 - 2', 'Informatika'),
(15, 'JUMAT', '13.00-15.30', 'FIK61509', 'ORGANISASI DAN ARSITEKTUR KOMPUTER', 3, 'Hannie, S.Kom., MMSI', 'II. B', 'Kelas 4.77 - 3', 'Informatika'),
(16, 'JUMAT', '15.30-18.00', 'USK61207', 'BUDAYA BANGSA', 2, 'Dr. Dede Jajang Suyaman, S.E., M.M.', 'II. B', 'Kelas 4.77 - 3', 'Informatika'),
(17, 'KAMIS', '07.30-10.00', 'FIK61506', 'STRUKTUR DATA', 3, 'Asep Jamaludin, S.Si., M.Kom.', 'II. B', 'LAB LANJUT 1', 'Informatika'),
(18, 'SENIN', '07.30-10.00', 'FIK61508', 'INTERAKSI MANUSIA DAN KOMPUTER', 3, 'Garno, M.Kom.', 'II. C', 'Kelas 4.76 - 2', 'Informatika'),
(19, 'SENIN', '15.00-17.30', 'USK61101', 'PENDIDIKAN AGAMA', 2, 'Nia Karnia, S.Pd.I., M.Pd.', 'II. C', 'Kelas 4.75 - 1', 'Informatika'),
(20, 'SELASA', '10.00-12.30', 'FIK61507', 'ALJABAR LINEAR', 3, 'Iqbal Maulana, S.Si., M.Sc.', 'II. C', 'Kelas 4.77 - 3', 'Informatika'),
(21, 'SELASA', '12.30-15.00', 'FIK61509', 'ORGANISASI DAN ARSITEKTUR KOMPUTER', 3, 'Purwantoro, M.Kom.', 'II. C', 'Kelas 4.76 - 2', 'Informatika'),
(22, 'RABU', '07.30-10.00', 'USK61303', 'PANCASILA', 2, 'R. Bagus Irawan, S.H., M.H', 'II. C', 'Kelas 4.78 - 4', 'Informatika'),
(23, 'KAMIS', '07.30-10.00', 'USK61207', 'BUDAYA BANGSA', 2, 'Praditya Putri Utami, S.S., M.Pd.', 'II. C', 'Kelas 4.76 - 2', 'Informatika'),
(24, 'JUMAT', '13.00-15.30', 'USK61202', 'BAHASA INDONESIA', 2, 'Dewi Herlina Sugiarti, S.S, S.pd., M.Pd.', 'II. C', 'Kelas 4.76 - 2', 'Informatika'),
(25, 'KAMIS', '10.00-12.30', 'FIK61506', 'STRUKTUR DATA', 3, 'Asep Jamaludin, S.Si., M.Kom.', 'II. C', 'LAB LANJUT 1', 'Informatika'),
(26, 'SENIN', '10.00-12.30', 'FIK61508', 'INTERAKSI MANUSIA DAN KOMPUTER', 3, 'Garno, M.Kom.', 'II. D', 'Kelas 4.76 - 2', 'Informatika'),
(27, 'SELASA', '07.30-10.00', 'FIK61507', 'ALJABAR LINEAR', 3, 'Tesa Nur Padilah, S.Si., M.Sc.', 'II. D', 'Kelas 4.77 - 3', 'Informatika'),
(28, 'SELASA', '15.00-17.30', 'USK61101', 'PENDIDIKAN AGAMA', 2, 'Nia Karnia, S.Pd.I., M.Pd.', 'II. D', 'Kelas 4.78 - 4', 'Informatika'),
(29, 'RABU', '12.30-15.00', 'FIK61509', 'ORGANISASI DAN ARSITEKTUR KOMPUTER', 3, 'Garno, M.Kom.', 'II. D', 'Kelas 4.75 - 1', 'Informatika'),
(30, 'KAMIS', '10.00-12.30', 'USK61207', 'BUDAYA BANGSA', 2, 'Praditya Putri Utami, S.S., M.Pd.', 'II. D', 'Kelas 4.76 - 2', 'Informatika'),
(31, 'KAMIS', '12.30-15.00', 'USK61303', 'PANCASILA', 2, 'R. Bagus Irawan, S.H., M.H', 'II. D', 'Kelas 4.76 - 2', 'Informatika'),
(32, 'JUMAT', '07.30-10.00', 'USK61202', 'BAHASA INDONESIA', 2, 'Wienike Dinar Pratiwi, M.Pd.', 'II. D', 'Kelas 4.77 - 3', 'Informatika'),
(33, 'KAMIS', '07.30-10.00', 'FIK61506', 'STRUKTUR DATA', 3, 'Betha Nurina Sari, M.Kom.', 'II. D', 'LAB LANJUT 2', 'Informatika'),
(34, 'SENIN', '07.30-10.00', 'USK61207', 'BUDAYA BANGSA', 2, 'Firda Ainun Nisah, S.Si., M.Sc.', 'II. E', 'Kelas 4.75 - 1', 'Informatika'),
(35, 'SELASA', '12.30-15.00', 'FIK61507', 'ALJABAR LINEAR', 3, 'Tesa Nur Padilah, S.Si., M.Sc.', 'II. E', 'Kelas 4.77 - 3', 'Informatika'),
(36, 'RABU', '15.00-17.30', 'FIK61508', 'INTERAKSI MANUSIA DAN KOMPUTER', 3, 'Rini Mayasari, M.Kom.', 'II. E', 'Kelas 4.76 - 2', 'Informatika'),
(37, 'JUMAT', '07.30-10.00', 'USK61101', 'PENDIDIKAN AGAMA', 2, 'Dr. Khalid Ramdhani, M.Pd.I', 'II. E', 'Kelas 4.75 - 1', 'Informatika'),
(38, 'JUMAT', '10.00-12.30', 'USK61303', 'PANCASILA', 2, 'R. Bagus Irawan, S.H., M.H', 'II. E', 'Kelas 4.75 - 1', 'Informatika'),
(39, 'JUMAT', '15.30-18.00', 'USK61202', 'BAHASA INDONESIA', 2, 'Wienike Dinar Pratiwi, M.Pd.', 'II. E', 'Kelas 4.76 - 2', 'Informatika'),
(40, 'KAMIS', '12.30-15.00', 'FIK61506', 'STRUKTUR DATA', 3, 'Betha Nurina Sari, M.Kom.', 'II. E', 'LAB LANJUT 1', 'Informatika'),
(41, 'JUMAT', '13.00-15.30', 'FIK61509', 'ORGANISASI DAN ARSITEKTUR KOMPUTER', 3, 'Arip Solehudin, M.Kom.', 'II. E', 'Kelas 4.80 - 6', 'Informatika'),
(42, 'SENIN', '10.00-12.30', 'USK61207', 'BUDAYA BANGSA', 2, 'Firda Ainun Nisah, S.Si., M.Sc.', 'II. F', 'Kelas 4.75 - 1', 'Informatika'),
(43, 'RABU', '12.30-15.00', 'FIK61508', 'INTERAKSI MANUSIA DAN KOMPUTER', 3, 'Rini Mayasari, M.Kom.', 'II. F', 'Kelas 4.77 - 3', 'Informatika'),
(44, 'RABU', '15.00-17.30', 'USK61303', 'PANCASILA', 2, 'R. Bagus Irawan, S.H., M.H', 'II. F', 'Kelas 4.77 - 3', 'Informatika'),
(45, 'KAMIS', '07.30-10.00', 'USK61202', 'BAHASA INDONESIA', 2, 'Wienike Dinar Pratiwi, M.Pd.', 'II. F', 'Kelas 4.75 - 1', 'Informatika'),
(46, 'KAMIS', '15.00-17.30', 'FIK61507', 'ALJABAR LINEAR', 3, 'Tesa Nur Padilah, S.Si., M.Sc.', 'II. F', 'Kelas 4.78 - 4', 'Informatika'),
(47, 'JUMAT', '15.30-18.00', 'USK61101', 'PENDIDIKAN AGAMA', 2, 'Dr. Yadi Fahmi Arifudin, S.S.I., M.Pd.I.', 'II. F', 'Kelas 4.75 - 1', 'Informatika'),
(48, 'RABU', '10.00-12.30', 'FIK61506', 'STRUKTUR DATA', 3, 'Betha Nurina Sari, M.Kom.', 'II. F', 'LAB LANJUT 1', 'Informatika'),
(49, 'JUMAT', '10.00-12.30', 'FIK61509', 'ORGANISASI DAN ARSITEKTUR KOMPUTER', 3, 'Arip Solehudin, M.Kom.', 'II. F', 'LAB LANJUT 1', 'Informatika'),
(50, 'SELASA', '07.30-10.00', 'FIK61519', 'REKAYASA PERANGKAT LUNAK', 3, 'Dr. Oman Komarudin, S.Si., M.Kom.', 'IV. A', 'Kelas 4.75 - 1', 'Informatika'),
(51, 'SELASA', '10.00-12.30', 'FIK61517', 'ANALISIS DESAIN ALGORITMA', 3, 'E. Haodudin Nurkifli, S.T., M.Cs., Ph.D.', 'IV. A', 'LAB DASAR 1', 'Informatika'),
(52, 'SELASA', '15.00-17.30', 'FIK61523', 'EMBEDDED INTELLIGENT SYSTEMS', 3, 'Purwantoro, M.Kom.', 'IV. A', 'Kelas 4.75 - 1', 'Informatika'),
(53, 'RABU', '07.30-10.00', 'FIK61518', 'PEMROGRAMAN BERBASIS WEB', 3, 'Dadang Yusup, M.Kom.', 'IV. A', 'LAB DASAR 1', 'Informatika'),
(54, 'RABU', '10.00-12.30', 'FIK61520', 'STATISTIKA DAN PROBABILITAS', 3, 'M. Jajuli, M.Si.', 'IV. A', 'Kelas 4.78 - 4', 'Informatika'),
(55, 'SENIN', '07.30-10.00', 'FIK61513', 'SISTEM OPERASI', 3, 'Adhi Rizal, MT.', 'IV. A', 'LAB DASAR 1', 'Informatika'),
(56, 'KAMIS', '07.30-10.00', 'FIK61522', 'KECERDASAN BUATAN', 3, 'Ratna Mufidah, M.Kom.', 'IV. A', 'LAB DASAR 2', 'Informatika'),
(57, 'SENIN', '07.30-10.00', 'FIK61519', 'REKAYASA PERANGKAT LUNAK', 3, 'Intan Purnamasari, M.kom.', 'IV. B', 'Kelas 4.77 - 3', 'Informatika'),
(58, 'SELASA', '07.30-10.00', 'FIK61520', 'STATISTIKA DAN PROBABILITAS', 3, 'Iqbal Maulana, S.Si., M.Sc.', 'IV. B', 'Kelas 4.76 - 2', 'Informatika'),
(59, 'SELASA', '10.00-12.30', 'FIK61517', 'ANALISIS DESAIN ALGORITMA', 3, 'Intan Purnamasari, M.kom.', 'IV. B', 'Kelas 4.76 - 2', 'Informatika'),
(60, 'KAMIS', '07.30-10.00', 'FIK61523', 'EMBEDDED INTELLIGENT SYSTEMS', 3, 'Purwantoro, M.Kom.', 'IV. B', 'Kelas 4.77 - 3', 'Informatika'),
(61, 'SENIN', '10.00-12.30', 'FIK61513', 'SISTEM OPERASI', 3, 'Adhi Rizal, MT.', 'IV. B', 'LAB DASAR 1', 'Informatika'),
(62, 'SELASA', '12.30-15.00', 'FIK61522', 'KECERDASAN BUATAN', 3, 'Ratna Mufidah, M.Kom.', 'IV. B', 'LAB DASAR 1', 'Informatika'),
(63, 'RABU', '15.00-17.30', 'FIK61518', 'PEMROGRAMAN BERBASIS WEB', 3, 'Dadang Yusup, M.Kom.', 'IV. B', 'LAB DASAR 1', 'Informatika'),
(64, 'SENIN', '10.00-12.30', 'FIK61519', 'REKAYASA PERANGKAT LUNAK', 3, 'Intan Purnamasari, M.kom.', 'IV. C', 'Kelas 4.77 - 3', 'Informatika'),
(65, 'SENIN', '12.30-15.00', 'FIK61517', 'ANALISIS DESAIN ALGORITMA', 3, 'E. Haodudin Nurkifli, S.T., M.Cs., Ph.D.', 'IV. C', 'Kelas 4.76 - 2', 'Informatika'),
(66, 'SELASA', '12.30-15.00', 'FIK61520', 'STATISTIKA DAN PROBABILITAS', 3, 'M. Jajuli, M.Si.', 'IV. C', 'Kelas 4.75 - 1', 'Informatika'),
(67, 'KAMIS', '12.30-15.00', 'FIK61523', 'EMBEDDED INTELLIGENT SYSTEMS', 3, 'Purwantoro, M.Kom.', 'IV. C', 'Kelas 4.80 - 6', 'Informatika'),
(68, 'RABU', '07.30-10.00', 'FIK61522', 'KECERDASAN BUATAN', 3, 'Yuyun Umaidah, M.Kom.', 'IV. D', 'LAB DASAR 2', 'Informatika'),
(69, 'KAMIS', '10.00-12.30', 'FIK61518', 'PEMROGRAMAN BERBASIS WEB', 3, 'Dadang Yusup, M.Kom.', 'IV. C', 'LAB DASAR 2', 'Informatika'),
(70, 'JUMAT', '07.30-10.00', 'FIK61513', 'SISTEM OPERASI', 3, 'Adhi Rizal, MT.', 'IV. C', 'LAB DASAR 1', 'Informatika'),
(71, 'SENIN', '10.00-12.30', 'FIK61520', 'STATISTIKA DAN PROBABILITAS', 3, 'Iqbal Maulana, S.Si., M.Sc.', 'IV. D', 'Kelas 4.78 - 4', 'Informatika'),
(72, 'SELASA', '15.00-17.30', 'FIK61519', 'REKAYASA PERANGKAT LUNAK', 3, 'Dr. Oman Komarudin, S.Si., M.Kom.', 'IV. D', 'Kelas 4.77 - 3', 'Informatika'),
(73, 'KAMIS', '07.30-10.00', 'FIK61523', 'EMBEDDED INTELLIGENT SYSTEMS', 3, 'Susilawati, M.Si.', 'IV. D', 'Kelas 4.78 - 4', 'Informatika'),
(74, 'JUMAT', '13.00-15.30', 'FIK61517', 'ANALISIS DESAIN ALGORITMA', 3, 'Ultach Enri, M.Kom.', 'IV. D', 'Kelas 4.78 - 4', 'Informatika'),
(75, 'SELASA', '10.00-12.30', 'FIK61522', 'KECERDASAN BUATAN', 3, 'Ratna Mufidah, M.Kom.', 'IV. C', 'LAB DASAR 2', 'Informatika'),
(76, 'KAMIS', '12.30-15.00', 'FIK61518', 'PEMROGRAMAN BERBASIS WEB', 3, 'Kamal Prihandani, M.Kom.', 'IV. D', 'LAB DASAR 2', 'Informatika'),
(77, 'SENIN', '15.00-17.30', 'FIK61513', 'SISTEM OPERASI', 3, 'Adhi Rizal, MT.', 'IV. D', 'LAB LANJUT 2', 'Informatika'),
(78, 'SENIN', '07.30-10.00', 'FIK61520', 'STATISTIKA DAN PROBABILITAS', 3, 'Iqbal Maulana, S.Si., M.Sc.', 'IV. E', 'Kelas 4.78 - 4', 'Informatika'),
(79, 'KAMIS', '12.30-15.00', 'FIK61519', 'REKAYASA PERANGKAT LUNAK', 3, 'Dr. Jajam Haerul Jaman, S.E., M.Kom.', 'IV. E', 'Kelas 4.78 - 4', 'Informatika'),
(80, 'KAMIS', '15.00-17.30', 'FIK61523', 'EMBEDDED INTELLIGENT SYSTEMS', 3, 'Susilawati, M.Si.', 'IV. E', 'Kelas 4.76 - 2', 'Informatika'),
(81, 'RABU', '12.30-15.00', 'FIK61522', 'KECERDASAN BUATAN', 3, 'Yuyun Umaidah, M.Kom.', 'IV. E', 'LAB LANJUT 2', 'Informatika'),
(82, 'RABU', '15.00-17.30', 'FIK61513', 'SISTEM OPERASI', 3, 'Agung Susilo Yuda Irawan, M.Kom.', 'IV. E', 'LAB DASAR 2', 'Informatika'),
(83, 'JUMAT', '13.00-15.30', 'FIK61518', 'PEMROGRAMAN BERBASIS WEB', 3, 'Kamal Prihandani, M.Kom.', 'IV. E', 'LAB DASAR 1', 'Informatika'),
(84, 'JUMAT', '15.30-18.00', 'FIK61517', 'ANALISIS DESAIN ALGORITMA', 3, 'Ultach Enri, M.Kom.', 'IV. E', 'Kelas 4.78 - 4', 'Informatika'),
(85, 'SENIN', '12.30-15.00', 'FIK61520', 'STATISTIKA DAN PROBABILITAS', 3, 'Iqbal Maulana, S.Si., M.Sc.', 'IV. F', 'Kelas 4.78 - 4', 'Informatika'),
(86, 'SENIN', '15.00-17.30', 'FIK61519', 'REKAYASA PERANGKAT LUNAK', 3, 'Dr. Oman Komarudin, S.Si., M.Kom.', 'IV. F', 'Kelas 4.78 - 4', 'Informatika'),
(87, 'SELASA', '10.00-12.30', 'FIK61523', 'EMBEDDED INTELLIGENT SYSTEMS', 3, 'Susilawati, M.Si.', 'IV. F', 'Kelas 4.75 - 1', 'Informatika'),
(88, 'JUMAT', '10.00-12.30', 'FIK61517', 'ANALISIS DESAIN ALGORITMA', 3, 'Ultach Enri, M.Kom.', 'IV. F', 'Kelas 4.78 - 4', 'Informatika'),
(89, 'SELASA', '07.30-10.00', 'FIK61513', 'SISTEM OPERASI', 3, 'Agung Susilo Yuda Irawan, M.Kom.', 'IV. F', 'LAB DASAR 2', 'Informatika'),
(90, 'RABU', '10.00-12.30', 'FIK61522', 'KECERDASAN BUATAN', 3, 'Yuyun Umaidah, M.Kom.', 'IV. F', 'LAB LANJUT 2', 'Informatika'),
(91, 'JUMAT', '15.30-18.00', 'FIK61518', 'PEMROGRAMAN BERBASIS WEB', 3, 'Kamal Prihandani, M.Kom.', 'IV. F', 'LAB DASAR 1', 'Informatika'),
(92, 'RABU', '07.30-10.00', 'FIK61545', 'METODOLOGI PENELITIAN', 3, 'Betha Nurina Sari, M.Kom.', 'VI. A', 'Kelas 4.75 - 1', 'Informatika'),
(93, 'RABU', '10.00-12.30', 'FIK61548', 'SOFTWARE DESIGN PATTERN', 3, 'Carudin, M.Kom.', 'VI. A', 'Kelas 4.76 - 2', 'Informatika'),
(94, 'SABTU', '07.30-10.00', 'FIK61543', 'MANAJEMEN PROYEK PERANGKAT LUNAK', 3, 'Rini Mayasari, M.Kom.', 'VI. A', 'Kelas 4.75 - 1', 'Informatika'),
(95, 'SELASA', '07.30-10.00', 'FIK61547', 'PENGOLAHAN CITRA DIGITAL', 3, 'Aries Suharso, S.Si., M.Kom.', 'VI. A', 'LAB DASAR 1', 'Informatika'),
(96, 'RABU', '15.30-18.00', 'FIK61546', 'MACHINE LEARNING', 3, 'Budi Arif Dermawan, M.Kom.', 'VI. A', 'LAB DASAR 2', 'Informatika'),
(97, 'SENIN', '12.30-15.00', 'FIK61546', 'MACHINE LEARNING', 3, 'Budi Arif Dermawan, M.Kom.', 'VI. B', 'Kelas 4.75 - 1', 'Informatika'),
(98, 'SELASA', '10.00-12.30', 'FIK61545', 'METODOLOGI PENELITIAN', 3, 'Didi Juardi, S.T., M.Kom.', 'VI. B', 'Kelas 4.78 - 4', 'Informatika'),
(99, 'RABU', '12.30-15.00', 'FIK61548', 'SOFTWARE DESIGN PATTERN', 3, 'Carudin, M.Kom.', 'VI. B', 'Kelas 4.76 - 2', 'Informatika'),
(100, 'RABU', '10.00-12.30', 'FIK61547', 'PENGOLAHAN CITRA DIGITAL', 3, 'Aries Suharso, S.Si., M.Kom.', 'VI. B', 'LAB DASAR 1', 'Informatika'),
(101, 'JUMAT', '15.30-18.00', 'FIK61543', 'MANAJEMEN PROYEK PERANGKAT LUNAK', 3, 'Agung Susilo Yuda Irawan, M.Kom.', 'VI. B', 'Kelas 4.80 - 6', 'Informatika'),
(102, 'SELASA', '15.00-17.30', 'FIK61545', 'METODOLOGI PENELITIAN', 3, 'Didi Juardi, S.T., M.Kom.', 'VI. C', 'Kelas 4.76 - 2', 'Informatika'),
(103, 'KAMIS', '12.30-15.00', 'FIK61548', 'SOFTWARE DESIGN PATTERN', 3, 'Carudin, M.Kom.', 'VI. C', 'Kelas 4.75 - 1', 'Informatika'),
(104, 'RABU', '12.30-15.00', 'FIK61547', 'PENGOLAHAN CITRA DIGITAL', 3, 'Aries Suharso, S.Si., M.Kom.', 'Vi. C', 'LAB DASAR 1', 'Informatika'),
(105, 'KAMIS', '10.00-12.30', 'FIK61546', 'MACHINE LEARNING', 3, 'Budi Arif Dermawan, M.Kom.', 'VI. C', 'LAB DASAR 1', 'Informatika'),
(106, 'JUMAT', '15.30-18.00', 'FIK61543', 'MANAJEMEN PROYEK PERANGKAT LUNAK', 3, 'Rini Mayasari, M.Kom.', 'VI. C', 'LAB LANJUT 1', 'Informatika'),
(107, 'KAMIS', '10.00-12.30', 'FIK61548', 'SOFTWARE DESIGN PATTERN', 3, 'Carudin, M.Kom.', 'VI. D', 'Kelas 4.77 - 3', 'Informatika'),
(108, 'KAMIS', '12.30-15.00', 'FIK61545', 'METODOLOGI PENELITIAN', 3, 'Sofi Defiyanti, M.Kom.', 'VI. D', 'Kelas 4.77 - 3', 'Informatika'),
(109, 'KAMIS', '07.30-10.00', 'FIK61546', 'MACHINE LEARNING', 3, 'Dr. Chaerur Rozikin, M.Kom', 'VI. D', 'LAB DASAR 1', 'Informatika'),
(110, 'JUMAT', '07.30-10.00', 'FIK61547', 'PENGOLAHAN CITRA DIGITAL', 3, 'Riza Ibnu Adam, M.Si.', 'VI. D', 'LAB DASAR 2', 'Informatika'),
(111, 'SELASA', '12.30-15.00', 'FIK61543', 'MANAJEMEN PROYEK PERANGKAT LUNAK', 3, 'Rini Mayasari, M.Kom.', 'VI. D', 'Kelas 4.79 - 5', 'Informatika'),
(112, 'KAMIS', '15.00-17.30', 'FIK61545', 'METODOLOGI PENELITIAN', 3, 'Sofi Defiyanti, M.Kom.', 'VI. E', 'Kelas 4.77 - 3', 'Informatika'),
(113, 'JUMAT', '13.00-15.30', 'FIK61548', 'SOFTWARE DESIGN PATTERN', 3, 'Dr. Jajam Haerul Jaman, S.E., M.Kom.', 'VI. E', 'Kelas 4.75 - 1', 'Informatika'),
(114, 'KAMIS', '12.30-15.00', 'FIK61546', 'MACHINE LEARNING', 3, 'Dr. Chaerur Rozikin, M.Kom', 'VI. E', 'LAB DASAR 1', 'Informatika'),
(115, 'JUMAT', '10.00-12.30', 'FIK61547', 'PENGOLAHAN CITRA DIGITAL', 3, 'Riza Ibnu Adam, M.Si.', 'VI. E', 'LAB DASAR 2', 'Informatika'),
(116, 'JUMAT', '15.30-18.00', 'FIK61543', 'MANAJEMEN PROYEK PERANGKAT LUNAK', 3, 'Didi Juardi, S.T., M.Kom.', 'VI. E', 'LAB DASAR 2', 'Informatika'),
(117, 'SENIN', '15.00-17.30', 'FIK61548', 'SOFTWARE DESIGN PATTERN', 3, 'Aji Primajaya, S.Si., M.Kom.', 'VI. F', 'Kelas 4.77 - 3', 'Informatika'),
(118, 'SENIN', '10.00-12.30', 'FIK61545', 'METODOLOGI PENELITIAN', 3, 'Sofi Defiyanti, M.Kom.', 'VI. F', 'Kelas 4.80 - 6', 'Informatika'),
(119, 'KAMIS', '15.00-17.30', 'FIK61546', 'MACHINE LEARNING', 3, 'Dr. Chaerur Rozikin, M.Kom', 'VI. F', 'LAB DASAR 1', 'Informatika'),
(120, 'JUMAT', '07.30-10.00', 'FIK61543', 'MANAJEMEN PROYEK PERANGKAT LUNAK', 3, 'Didi Juardi, S.T., M.Kom.', 'VI. F', 'LAB LANJUT 2', 'Informatika'),
(121, 'JUMAT', '13.00-15.30', 'FIK61547', 'PENGOLAHAN CITRA DIGITAL', 3, 'Riza Ibnu Adam, M.Si.', 'VI. F', 'LAB DASAR 2', 'Informatika'),
(122, 'RABU', '07.30-10.00', 'FIK61557', 'WIRELESS SENSOR NETWORK', 3, 'E. Haodudin Nurkifli, S.T., M.Cs., Ph.D.', 'VI. PIL A', 'Kelas 4.76 - 2', 'Informatika'),
(123, 'KAMIS', '10.00-12.30', 'FIK61544', 'SISTEM PENDUKUNG KEPUTUSAN', 3, 'Dr. Jajam Haerul Jaman, S.E., M.Kom.', 'VI. PIL A', 'Kelas 4.78 - 4', 'Informatika'),
(124, 'JUMAT', '10.00-12.30', 'FIK61563', 'PERANCANGAN USER EXPERIENCE', 3, 'Siska, M.kom.', 'VI. PIL A', 'Kelas 4.76 - 2', 'Informatika'),
(125, 'RABU', '10.00-12.30', 'FIK61554', 'BUSINESS INTELIGENCE', 3, 'Aji Primajaya, S.Si., M.Kom.', 'VI. PIL A', 'LAB DASAR 2', 'Informatika'),
(126, 'SABTU', '07.30-10.00', 'FIK61550', 'ANALISIS BIG DATA', 3, 'Ratna Mufidah, M.Kom.', 'VI. PIL A', 'Kelas 4.77 - 5', 'Informatika'),
(127, 'JUMAT', '13.00-15.30', 'FIK61559', 'NETWORK MANAGEMENT', 3, 'Purwantoro, M.Kom.', 'VI. PIL A', 'LAB LANJUT 2', 'Informatika'),
(128, 'SABTU', '07.30-10.00', 'FIK61563', 'PERANCANGAN USER EXPERIENCE', 3, 'Siska, M.kom.', 'VI. PIL B', 'Kelas 4.79 - 5', 'Informatika'),
(129, 'SABTU', '10.00-12.30', 'FIK61554', 'BUSINESS INTELIGENCE', 3, 'Aji Primajaya, S.Si., M.Kom.', 'VI. PIL B', 'Kelas 4.76 - 2', 'Informatika'),
(130, 'SABTU', '10.00-12.30', 'FIK61550', 'ANALISIS BIG DATA', 3, 'Ratna Mufidah, M.Kom.', 'VI. PIL B', 'Kelas 4.77 - 5', 'Informatika'),
(131, 'SABTU', '10.00-12.30', 'FIK61557', 'WIRELESS SENSOR NETWORK', 3, 'E. Haodudin Nurkifli, S.T., M.Cs., Ph.D.', 'VI. PIL B', 'Kelas 4.78 - 4', 'Informatika'),
(132, 'SENIN', '07.30-10.00', 'SYS61304', 'Kewarganegaraan', 2, 'Devi Siti Hamzah Marpaung, S.H., M.H.', 'II. A', 'LAB LANJUT 1', 'Sistem Informasi'),
(133, 'SENIN', '12.30-15.00', 'USK61202', 'Bahasa Indonesia', 2, 'Dr. Een Nurhasanah, S.S., M.A.', 'II. A', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(134, 'SELASA', '07.30-10.00', 'SYS61601', 'Konsep Sistem informasi', 2, 'Nina Sulistiyowati, S.T., M.Kom.', 'II. A', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(135, 'SELASA', '10.00-12.30', 'SYS61643', 'Manajemen proses bisnis', 3, 'Ade Andri Hendriadi, S.Si., M.Kom', 'II. A', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(136, 'RABU', '07.30-10.00', 'SYS61528', 'Statistika dan probabilitas', 3, 'M. Jajuli, M.Si.', 'II. A', 'LAB LANJUT 2', 'Sistem Informasi'),
(137, 'RABU', '15.00-17.30', 'SYS61641', 'Manajemen dan organisasi', 2, 'Aziz Ma\'sum, S.T., M.Kom.', 'II. A', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(138, 'KAMIS', '07.30-10.00', 'SYS61505', 'Sistem operasi', 3, 'Nono Heryana, M.Kom.', 'II. A', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(139, 'RABU', '10.00-12.30', 'SYS61603', 'Sistem Basis Data', 3, 'Ultach Enri, M.Kom.', 'II. A', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(140, 'SENIN', '07.30-10.00', 'SYS61304', 'Kewarganegaraan', 2, 'Dr. Abdul Muis, S.Sos., M.Pd', 'II. B', 'LAB LANJUT 2', 'Sistem Informasi'),
(141, 'SENIN', '15.00-17.30', 'SYS61505', 'Sistem operasi', 3, 'Nono Heryana, M.Kom.', 'II. B', 'LAB DASAR 2', 'Sistem Informasi'),
(142, 'SELASA', '15.00-17.30', 'SYS61641', 'Manajemen dan organisasi', 2, 'Ahmad Khusaeri, M.Kom.', 'II. B', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(143, 'RABU', '07.30-10.00', 'SYS61643', 'Manajemen proses bisnis', 3, 'Ade Andri Hendriadi, S.Si., M.Kom', 'II. B', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(144, 'RABU', '10.00-12.30', 'SYS61601', 'Konsep Sistem informasi', 2, 'Nina Sulistiyowati, S.T., M.Kom.', 'II. B', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(145, 'KAMIS', '07.30-10.00', 'SYS61603', 'Sistem Basis Data', 3, 'Billy Ibrahim Hasbi, M.Kom.', 'II. B', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(146, 'KAMIS', '15.00-17.30', 'USK61202', 'Bahasa Indonesia', 2, 'Dr. Een Nurhasanah, S.S., M.A.', 'II. B', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(147, 'JUMAT', '07.30-10.00', 'SYS61528', 'Statistika dan probabilitas', 3, 'Carudin, M.Kom.', 'II. B', 'LAB LANJUT 1', 'Sistem Informasi'),
(148, 'SENIN', '10.00-12.30', 'SYS61601', 'Konsep Sistem informasi', 2, 'Billy Ibrahim Hasbi, M.Kom.', 'II. C', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(149, 'SENIN', '12.30-15.00', 'SYS61603', 'Sistem Basis Data', 3, 'Yuyun Umaidah, M.Kom.', 'II. C', 'LAB DASAR 2', 'Sistem Informasi'),
(150, 'JUMAT', '10.00-12.30', 'USK61202', 'Bahasa Indonesia', 2, 'Hendra Setiawan, S.S., M.Pd.', 'II. C', 'LAB DASAR 1', 'Sistem Informasi'),
(151, 'SELASA', '15.00-17.30', 'SYS61643', 'Manajemen proses bisnis', 3, 'Ade Andri Hendriadi, S.Si., M.Kom', 'II. C', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(152, 'RABU', '15.00-17.30', 'SYS61505', 'Sistem operasi', 3, 'Adhi Rizal, MT.', 'II. C', 'Kelas 4.75 - 1', 'Sistem Informasi'),
(153, 'RABU', '12.30-15.00', 'SYS61641', 'Manajemen dan organisasi', 2, 'Ahmad Khusaeri, M.Kom.', 'II. C', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(154, 'KAMIS', '12.30-15.00', 'SYS61528', 'Statistika dan probabilitas', 3, 'Iqbal Maulana, S.Si., M.Sc.', 'II. C', 'LAB LANJUT 2', 'Sistem Informasi'),
(155, 'KAMIS', '15.00-17.30', 'SYS61304', 'Kewarganegaraan', 2, 'Devi Siti Hamzah Marpaung, S.H., M.H.', 'II. C', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(156, 'SENIN', '07.30-10.00', 'SYS61641', 'Manajemen dan organisasi', 2, 'Ahmad Khusaeri, M.Kom.', 'II. D', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(157, 'SENIN', '15.00-17.30', 'SYS61601', 'Konsep Sistem informasi', 2, 'Billy Ibrahim Hasbi, M.Kom.', 'II. D', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(158, 'SELASA', '07.30-10.00', 'SYS61528', 'Statistika dan probabilitas', 3, 'Carudin, M.Kom.', 'II. D', 'LAB LANJUT 2', 'Sistem Informasi'),
(159, 'SELASA', '12.30-15.00', 'SYS61643', 'Manajemen proses bisnis', 3, 'Ade Andri Hendriadi, S.Si., M.Kom', 'II. D', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(160, 'RABU', '15.00-17.30', 'USK61202', 'Bahasa Indonesia', 2, 'Hendra Setiawan, S.S., M.Pd.', 'II. D', 'LAB LANJUT 2', 'Sistem Informasi'),
(161, 'JUMAT', '07.30-10.00', 'SYS61304', 'Kewarganegaraan', 2, 'Dr. Abdul Muis, S.Sos., M.Pd', 'II. D', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(162, 'JUMAT', '10.00-12.30', 'SYS61603', 'Sistem Basis Data', 3, 'Sofi Defiyanti, M.Kom.', 'II. D', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(163, 'JUMAT', '13.00-15.30', 'SYS61505', 'Sistem operasi', 3, 'Adhi Rizal, MT.', 'II. D', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(164, 'SABTU', '07.30-10.00', 'SIS61515', 'Data Warehouse', 3, 'Intan Purnamasari, M.kom.', 'IV. A', 'Kelas 4.76 - 2', 'Sistem Informasi'),
(165, 'KAMIS', '10.00-12.30', 'SIS61517', 'Rekayasa Perangkat Lunak', 3, 'Nono Heryana, M.Kom.', 'IV. A', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(166, 'SELASA', '12.30-15.00', 'SIS61518', 'Pemrograman Berbasis Web', 3, 'H. Bagja Nugraha, ST., M.Kom.', 'IV. A', 'LAB LANJUT 2', 'Sistem Informasi'),
(167, 'SELASA', '15.00-17.30', 'SIS61516', 'Pemrograman Berorientasi Objek', 3, 'Taufik Ridwan, M.T.', 'IV. A', 'LAB DASAR 1', 'Sistem Informasi'),
(168, 'SENIN', '12.30-15.00', 'SIS61514', 'Manajemen Sistem Informasi t', 3, 'Aziz Ma\'sum, S.T., M.Kom.', 'IV. A', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(169, 'RABU', '07.30-10.00', 'SIS61519', 'Analisa dan Perancangan Sistem Informasi', 3, 'Siska, M.kom.', 'IV. A', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(170, 'KAMIS', '15.00-17.30', 'SIS61520', 'Jaringan dan Keamanan Sistem', 3, 'Azhari Ali Ridha, S.Kom., MMSI.', 'IV. A', 'LAB DASAR 2', 'Sistem Informasi'),
(171, 'KAMIS', '12.30-15.00', 'SIS61517', 'Rekayasa Perangkat Lunak', 3, 'Nono Heryana, M.Kom.', 'IV. B', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(172, 'SELASA', '12.30-15.00', 'SIS61516', 'Pemrograman Berorientasi Objek', 3, 'Taufik Ridwan, M.T.', 'IV. B', 'LAB LANJUT 1', 'Sistem Informasi'),
(173, 'SENIN', '12.30-15.00', 'SIS61519', 'Analisa dan Perancangan Sistem Informasi', 3, 'Siska, M.kom.', 'IV. B', 'LAB LANJUT 1', 'Sistem Informasi'),
(174, 'SELASA', '10.00-12.30', 'SIS61515', 'Data Warehouse', 3, 'Betha Nurina Sari, M.Kom.', 'IV. B', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(175, 'RABU', '15.00-17.30', 'SIS61518', 'Pemrograman Berbasis Web', 3, 'H. Bagja Nugraha, ST., M.Kom.', 'IV. B', 'LAB LANJUT 1', 'Sistem Informasi'),
(176, 'SENIN', '10.00-12.30', 'SIS61514', 'Manajemen Sistem Informasi t', 3, 'Aziz Ma\'sum, S.T., M.Kom.', 'IV. B', 'LAB DASAR 2', 'Sistem Informasi'),
(177, 'JUMAT', '10.00-12.30', 'SIS61520', 'Jaringan dan Keamanan Sistem', 3, 'E. Haodudin Nurkifli, S.T., M.Cs., Ph.D.', 'IV. B', 'LAB LANJUT 2', 'Sistem Informasi'),
(178, 'SENIN', '07.30-10.00', 'SIS61516', 'Pemrograman Berorientasi Objek', 3, 'Taufik Ridwan, M.T.', 'IV. C', 'LAB DASAR 2', 'Sistem Informasi'),
(179, 'SENIN', '12.30-15.00', 'SIS61517', 'Rekayasa Perangkat Lunak', 3, 'Ahmad Khusaeri, M.Kom.', 'IV. C', 'LAB DASAR 1', 'Sistem Informasi'),
(180, 'SENIN', '10.00-12.30', 'SIS61519', 'Analisa dan Perancangan Sistem Informasi', 3, 'Siska, M.kom.', 'IV. C', 'LAB LANJUT 1', 'Sistem Informasi'),
(181, 'SENIN', '15.00-17.30', 'SIS61514', 'Manajemen Sistem Informasi t', 3, 'Aziz Ma\'sum, S.T., M.Kom.', 'IV. C', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(182, 'SELASA', '15.00-17.30', 'SIS61518', 'Pemrograman Berbasis Web', 3, 'H. Bagja Nugraha, ST., M.Kom.', 'IV. C', 'LAB LANJUT 1', 'Sistem Informasi'),
(183, 'RABU', '12.30-15.00', 'SIS61515', 'Data Warehouse', 3, 'Ultach Enri, M.Kom.', 'IV. C', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(184, 'KAMIS', '10.00-12.30', 'SIS61520', 'Jaringan dan Keamanan Sistem', 3, 'Dr. Chaerur Rozikin, M.Kom', 'IV. C', 'LAB LANJUT 2', 'Sistem Informasi'),
(185, 'SELASA', '07.30-10.00', 'FIK61206', 'Karya Tulis Ilmiah', 3, 'Apriade Voutama, M.Kom.', 'VI. A', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(186, 'JUMAT', '15.30-18.00', 'SIS61527', 'Interaksi Manusia dan Komputer', 3, 'Azhari Ali Ridha, S.Kom., MMSI.', 'VI. A', 'LAB LANJUT 2', 'Sistem Informasi'),
(187, 'RABU', '12.30-15.00', 'FIK61205', 'Technopreneurship', 3, 'Hannie, S.Kom., MMSI', 'VI. A', 'Kelas 4.78 - 4', 'Sistem Informasi'),
(188, 'KAMIS', '15.00-17.30', 'SIS61528', 'Komputasi Awan & Blockchain', 3, 'Didi Juardi, S.T., M.Kom.', 'VI. A', 'LAB LANJUT 1', 'Sistem Informasi'),
(189, 'JUMAT', '13.00-15.30', 'SIS61526', 'Sistem Cerdas', 3, 'Taufik Ridwan, M.T.', 'VI. A', 'LAB LANJUT 1', 'Sistem Informasi'),
(190, 'SENIN', '10.00-12.30', 'FIK61205', 'Technopreneurship', 3, 'Hannie, S.Kom., MMSI', 'VI. B', 'LAB LANJUT 2', 'Sistem Informasi'),
(191, 'SELASA', '15.00-17.30', 'SIS61527', 'Interaksi Manusia dan Komputer', 3, 'Azhari Ali Ridha, S.Kom., MMSI.', 'VI. B', 'LAB LANJUT 2', 'Sistem Informasi'),
(192, 'SELASA', '10.00-12.30', 'FIK61206', 'Karya Tulis Ilmiah', 3, 'Apriade Voutama, M.Kom.', 'VI. B', 'LAB LANJUT 1', 'Sistem Informasi'),
(193, 'SELASA', '07.30-10.00', 'SIS61526', 'Sistem Cerdas', 3, 'Kamal Prihandani, M.Kom.', 'VI. B', 'LAB LANJUT 1', 'Sistem Informasi'),
(194, 'KAMIS', '15.00-17.30', 'SIS61528', 'Komputasi Awan & Blockchain', 3, 'Arip Solehudin, M.Kom.', 'VI. B', 'LAB LANJUT 2', 'Sistem Informasi'),
(195, 'SENIN', '12.30-15.00', 'SIS61527', 'Interaksi Manusia dan Komputer', 3, 'Azhari Ali Ridha, S.Kom., MMSI.', 'VI. C', 'LAB LANJUT 1', 'Sistem Informasi'),
(196, 'SENIN', '15.00-17.30', 'FIK61205', 'Technopreneurship', 3, 'Hannie, S.Kom., MMSI', 'VI. C', 'LAB DASAR 1', 'Sistem Informasi'),
(197, 'SELASA', '12.30-15.00', 'SIS61528', 'Komputasi Awan & Blockchain', 3, 'Arip Solehudin, M.Kom.', 'VI. C', 'LAB DASAR 2', 'Sistem Informasi'),
(198, 'SELASA', '15.00-17.30', 'SIS61526', 'Sistem Cerdas', 3, 'Aji Primajaya, S.Si., M.Kom.', 'VI. C', 'LAB DASAR 2', 'Sistem Informasi'),
(199, 'KAMIS', '10.00-12.30', 'FIK61206', 'Karya Tulis Ilmiah', 3, 'Apriade Voutama, M.Kom.', 'VI. C', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(200, 'SENIN', '07.30-10.00', 'SIS61531', 'Computer Assisted Audit Tools & Techniques', 3, 'Nina Sulistiyowati, S.T., M.Kom.', 'VI. PIL', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(201, 'SENIN', '15.00-17.30', 'SIS61529', 'Teknik Data Mining', 3, 'Yuyun Umaidah, M.Kom.', 'VI. PIL', 'LAB LANJUT 1', 'Sistem Informasi'),
(202, 'SELASA', '10.00-12.30', 'SIS61536', 'Web Services Applications', 3, 'H. Bagja Nugraha, ST., M.Kom.', 'VI. PIL', 'LAB LANJUT 2', 'Sistem Informasi'),
(203, 'SELASA', '12.30-15.00', 'SIS61532', 'Information System Audit', 3, 'Nina Sulistiyowati, S.T., M.Kom.', 'VI. PIL', 'LAB LANJUT 1', 'Sistem Informasi'),
(204, 'SENIN', '12.30-15.00', 'SIS61535', 'Developing Business Applications', 3, 'Dr. Oman Komarudin, S.Si., M.Kom.', 'VI. PIL', 'LAB LANJUT 2', 'Sistem Informasi'),
(205, 'RABU', '15.00-17.30', 'SIS61534', 'e-Business Design', 3, 'Hannie, S.Kom., MMSI', 'VI. PIL', 'Kelas 4.79 - 5', 'Sistem Informasi'),
(206, 'JUMAT', '07.30-10.00', 'SIS61533', 'Digital and New Media', 3, 'Apriade Voutama, M.Kom.', 'VI. PIL', 'Kelas 4.80 - 6', 'Sistem Informasi'),
(207, 'JUMAT', '10.00-12.30', 'SIS61530', 'Sistem Pendukung Keputusan', 3, 'Dr. Jajam Haerul Jaman, S.E., M.Kom.', 'VI. PIL', 'Kelas 4.80 - 6', 'Sistem Informasi');

-- --------------------------------------------------------

--
-- Table structure for table `pj_kelas`
--

CREATE TABLE `pj_kelas` (
  `id_pj` int(11) NOT NULL,
  `npm` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pj_kelas`
--

INSERT INTO `pj_kelas` (`id_pj`, `npm`, `password`) VALUES
(1, '2310631250106', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `room_status`
--

CREATE TABLE `room_status` (
  `id` int(11) NOT NULL,
  `ruangan` varchar(20) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `status` enum('offline','online') NOT NULL DEFAULT 'offline',
  `timer_end` datetime DEFAULT NULL,
  `updated_by` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_status`
--

INSERT INTO `room_status` (`id`, `ruangan`, `hari`, `jam`, `status`, `timer_end`, `updated_by`, `updated_at`) VALUES
(1, 'LAB DASAR 2', 'SENIN', '07.30-10.00', 'online', '2025-05-31 16:29:00', '2310631250106', '2025-05-31 07:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `temporary_changes`
--

CREATE TABLE `temporary_changes` (
  `id` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `ruangan` varchar(20) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `mata_kuliah` varchar(100) NOT NULL,
  `sks` int(1) NOT NULL,
  `prodi` varchar(20) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `timer_end` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `dosmhs`
--
ALTER TABLE `dosmhs`
  ADD PRIMARY KEY (`id_dosmhs`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `jadwal_matkul`
--
ALTER TABLE `jadwal_matkul`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pj_kelas`
--
ALTER TABLE `pj_kelas`
  ADD PRIMARY KEY (`id_pj`);

--
-- Indexes for table `room_status`
--
ALTER TABLE `room_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_schedule` (`ruangan`,`hari`,`jam`);

--
-- Indexes for table `temporary_changes`
--
ALTER TABLE `temporary_changes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dosmhs`
--
ALTER TABLE `dosmhs`
  MODIFY `id_dosmhs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jadwal_matkul`
--
ALTER TABLE `jadwal_matkul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `pj_kelas`
--
ALTER TABLE `pj_kelas`
  MODIFY `id_pj` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room_status`
--
ALTER TABLE `room_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temporary_changes`
--
ALTER TABLE `temporary_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
