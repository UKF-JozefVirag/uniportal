-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2023 at 08:02 PM
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
-- Database: `uniportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `acronym`
--

CREATE TABLE `acronym` (
  `id` int(11) NOT NULL,
  `nazov` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agentura`
--

CREATE TABLE `agentura` (
  `id` int(11) NOT NULL,
  `nazov` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fakulta`
--

CREATE TABLE `fakulta` (
  `id` int(11) NOT NULL,
  `nazov` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `fakulta`
--

INSERT INTO `fakulta` (`id`, `nazov`) VALUES
(1, 'Fakulta prírodných vied'),
(2, 'Fakulta sociál.vied a zdravot.\r\n'),
(3, 'Fakulta stredoeurópskych štúdií\r\n'),
(4, 'Filozofická fakulta\r\n'),
(5, 'Pedagogická fakulta\r\n'),
(6, 'Rektorát\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `katedra`
--

CREATE TABLE `katedra` (
  `id` int(11) NOT NULL,
  `nazov` varchar(120) NOT NULL,
  `fakulta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `katedra`
--

INSERT INTO `katedra` (`id`, `nazov`, `fakulta_id`) VALUES
(1, 'eadeda', 1),
(2, 'eafrfaf', 4);

-- --------------------------------------------------------

--
-- Table structure for table `pro_podiely`
--

CREATE TABLE `pro_podiely` (
  `id` int(11) NOT NULL,
  `podiel` float NOT NULL,
  `rok` year(4) NOT NULL,
  `uzivatel_id` int(11) NOT NULL,
  `pro_projekt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pro_projekt`
--

CREATE TABLE `pro_projekt` (
  `id` int(11) NOT NULL,
  `nazov` varchar(120) NOT NULL,
  `typ` int(11) NOT NULL,
  `agentura_id` int(11) NOT NULL,
  `pro_rozpocet_id` int(11) NOT NULL,
  `roz_pro_lat_sumy_id` int(11) NOT NULL,
  `acronym_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pro_rozpocet`
--

CREATE TABLE `pro_rozpocet` (
  `id` int(11) NOT NULL,
  `rok` year(4) NOT NULL,
  `bezne` int(11) NOT NULL,
  `kapitalove` int(11) NOT NULL,
  `spolu` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pro_vypocet`
--

CREATE TABLE `pro_vypocet` (
  `id` int(11) NOT NULL,
  `rok` year(4) NOT NULL,
  `podiel` double NOT NULL,
  `suma_rok` double NOT NULL,
  `pro_projekt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rola`
--

CREATE TABLE `rola` (
  `id` int(11) NOT NULL,
  `app` int(11) NOT NULL,
  `nazov` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `rola`
--

INSERT INTO `rola` (`id`, `app`, `nazov`) VALUES
(1, 1, 'asd'),
(2, 2, 'fds');

-- --------------------------------------------------------

--
-- Table structure for table `roz_pro_lat_sumy`
--

CREATE TABLE `roz_pro_lat_sumy` (
  `id` int(11) NOT NULL,
  `kategoria` varchar(45) NOT NULL,
  `suma` double NOT NULL,
  `rok_zac` year(4) NOT NULL,
  `rok_kon` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `meno` varchar(60) NOT NULL,
  `priezvisko` varchar(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `validation_key` varchar(6) NOT NULL,
  `rola_id` int(11) NOT NULL,
  `katedra_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `meno`, `priezvisko`, `email`, `password`, `validation_key`, `rola_id`, `katedra_id`, `updated_at`, `created_at`, `verified`) VALUES
(11, 'Michal', 'Herman', 'avengerM@gmail.com', '$2y$10$bDt.603c/WISTd3b5ANnX.ch4z/IeBj/zp7DH0JsAKR3NFYz5UT6m', '134254', 1, 1, '2023-12-12 08:51:29', '2023-12-12 08:51:29', 0),
(12, 'Michal', 'Hedman', 'avengdrM@gmail.com', '$2y$10$dJp1FdZ9RZ3O4Pq5VdqkKeROdp.rqfUztW.pnVHG1Y0Rj0JwhL8qG', '941895', 1, 1, '2023-12-12 08:52:55', '2023-12-12 08:52:55', 0),
(13, 'Michal', 'Hermaan', 'Mjaklevriž@gmail.com', '$2y$10$3EuNfwULWM.DyFHYva.reu7a0FIyzB5/i87W2LO.JeSzJke5Ba7Qa', '415077', 1, 1, '2023-12-12 11:07:44', '2023-12-12 11:07:44', 0),
(14, 'Micefefefefhal', 'Hermafeffefan', 'Mjaklevrukf.sk@gmail.com', '$2y$10$LakE.KQ4/X2sOvyZJPBi7eQvGTkI.SssWPlDF7wQwPryOGkcw06CK', '737100', 1, 1, '2023-12-12 11:37:38', '2023-12-12 11:37:38', 0),
(15, 'Alšbetka', 'Bozáňová', 'beeflamescar@gmail.com', '$2y$10$fijalqR8YY9UVks7sTtCbOM53TiM3Hp0zuBiQM7RCVQSUZvPITQcm', '828917', 1, 1, '2023-12-12 13:24:21', '2023-12-12 13:24:21', 0),
(16, 'Alšbetka', 'Bozáňová', 'beeflamescar@ukf.sk', '$2y$10$RqR1IFBttrnPUZtG.mDT6.ivvdHl2bTijsBVUHFZjcvKcyMBm6tpC', '338385', 1, 1, '2023-12-12 13:24:43', '2023-12-12 13:24:43', 0),
(17, 'Alšbetka', 'Bozáňová', 'beefererescar@ukf.sk', '$2y$10$uwLXmBOh2Eymw9cB5JV/ne6NPhbnbv2z39DnD4lKhx/kGNZsQz.tO', '293011', 1, 1, '2023-12-12 13:28:57', '2023-12-12 13:28:57', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acronym`
--
ALTER TABLE `acronym`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agentura`
--
ALTER TABLE `agentura`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fakulta`
--
ALTER TABLE `fakulta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `katedra`
--
ALTER TABLE `katedra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_katedra_fakulta1_idx` (`fakulta_id`);

--
-- Indexes for table `pro_podiely`
--
ALTER TABLE `pro_podiely`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pro_podiely_uzivatel1_idx` (`uzivatel_id`),
  ADD KEY `fk_pro_podiely_pro_projekt1_idx` (`pro_projekt_id`);

--
-- Indexes for table `pro_projekt`
--
ALTER TABLE `pro_projekt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pro_projekt_agentura1_idx` (`agentura_id`),
  ADD KEY `fk_pro_projekt_pro_rozpocet1_idx` (`pro_rozpocet_id`),
  ADD KEY `fk_pro_projekt_roz_pro_lat_sumy1_idx` (`roz_pro_lat_sumy_id`),
  ADD KEY `fk_pro_projekt_acronym1_idx` (`acronym_id`);

--
-- Indexes for table `pro_rozpocet`
--
ALTER TABLE `pro_rozpocet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pro_vypocet`
--
ALTER TABLE `pro_vypocet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pro_vypocet_pro_projekt1_idx` (`pro_projekt_id`);

--
-- Indexes for table `rola`
--
ALTER TABLE `rola`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roz_pro_lat_sumy`
--
ALTER TABLE `roz_pro_lat_sumy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uzivatel_rola_idx` (`rola_id`),
  ADD KEY `fk_uzivatel_katedra1_idx` (`katedra_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acronym`
--
ALTER TABLE `acronym`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agentura`
--
ALTER TABLE `agentura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakulta`
--
ALTER TABLE `fakulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `katedra`
--
ALTER TABLE `katedra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pro_podiely`
--
ALTER TABLE `pro_podiely`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pro_projekt`
--
ALTER TABLE `pro_projekt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pro_rozpocet`
--
ALTER TABLE `pro_rozpocet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pro_vypocet`
--
ALTER TABLE `pro_vypocet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rola`
--
ALTER TABLE `rola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roz_pro_lat_sumy`
--
ALTER TABLE `roz_pro_lat_sumy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `katedra`
--
ALTER TABLE `katedra`
  ADD CONSTRAINT `fk_katedra_fakulta1` FOREIGN KEY (`fakulta_id`) REFERENCES `fakulta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pro_podiely`
--
ALTER TABLE `pro_podiely`
  ADD CONSTRAINT `fk_pro_podiely_pro_projekt1` FOREIGN KEY (`pro_projekt_id`) REFERENCES `pro_projekt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pro_podiely_uzivatel1` FOREIGN KEY (`uzivatel_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pro_projekt`
--
ALTER TABLE `pro_projekt`
  ADD CONSTRAINT `fk_pro_projekt_acronym1` FOREIGN KEY (`acronym_id`) REFERENCES `acronym` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pro_projekt_agentura1` FOREIGN KEY (`agentura_id`) REFERENCES `agentura` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pro_projekt_pro_rozpocet1` FOREIGN KEY (`pro_rozpocet_id`) REFERENCES `pro_rozpocet` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pro_projekt_roz_pro_lat_sumy1` FOREIGN KEY (`roz_pro_lat_sumy_id`) REFERENCES `roz_pro_lat_sumy` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pro_vypocet`
--
ALTER TABLE `pro_vypocet`
  ADD CONSTRAINT `fk_pro_vypocet_pro_projekt1` FOREIGN KEY (`pro_projekt_id`) REFERENCES `pro_projekt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_uzivatel_katedra1` FOREIGN KEY (`katedra_id`) REFERENCES `katedra` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_uzivatel_rola` FOREIGN KEY (`rola_id`) REFERENCES `rola` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
