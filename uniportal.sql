-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2024 at 05:13 PM
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
(1, 'Fakulta sociál.vied a zdravot.'),
(2, 'Fakulta stredoeurópskych štúdií'),
(3, 'Filozofická fakulta'),
(4, 'Pedagogická fakulta'),
(5, 'Rektorát'),
(6, 'Fakulta prírodných vied');

-- --------------------------------------------------------

--
-- Table structure for table `grant_programs`
--

CREATE TABLE `grant_programs` (
  `id` int(11) NOT NULL,
  `acronym` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(1, 'FPVaI - Katedra chémie', 6),
(2, 'FPVaI - Katedra matematiky', 6),
(3, 'FPVaI - Katedra fyziky', 6),
(4, 'FPVaI - Katedra zoológie a antropológie', 6),
(5, 'FPVaI - Katedra geografie, geoinformatiky a regionálneho rozvoja', 6),
(6, 'FPVaI - Katedra informatiky', 6),
(7, 'FPVaI - Katedra ekológie a environmentalistiky', 6),
(8, 'FPVaI - Dekanát Fakulty prírodných vied a informatiky', 6),
(9, 'FPVaI - Katedra ekológie a environment.', 6),
(10, 'FPVaI - Katedra botaniky a genetiky', 6),
(11, 'FPVaI - Katedra geografie a reg. rozvoja', 6),
(12, 'FPVaI - Ústav ekonomiky a manažmentu', 6),
(13, 'FPVaI - Ústav  ekonomiky a manažmentu', 6),
(14, 'FPVaI - Dekanát Fakulty prírodných vied', 6),
(15, 'FPV - doktorandské štúdium', 6),
(16, 'PF - Katedra pedagogiky', 4),
(17, 'PF - Katedra telesnej výchovy a športu', 4),
(18, 'PF - Katedra anglického jazyka a kultúry', 4),
(19, 'PF - Katedra výtvarnej tvorby a výchovy', 4),
(20, 'PF - Katedra techniky a informačných technológií', 4),
(21, 'PF - Dekanát Pedagogickej fakulty', 4),
(22, 'PF - Katedra pedagogickej a školskej psychológie', 4),
(23, 'PF - Katedra lingvodid.a interkult.štúdi', 4),
(24, 'PF - doktorandské štúdium', 4),
(25, 'PF - Katedra techniky a inf. technológií', 4),
(26, 'PF - Katedra hudby', 4),
(27, 'FF - Dekanát Filozofickej fakulty', 3),
(28, 'FF - Katedra slovanských filológií', 3),
(29, 'FF - Katedra etiky a estetiky', 3),
(30, 'FF - Katedra histórie', 3),
(31, 'FF - Ústav manažmentu kultúry a turizmu, kulturológie a etnológie', 3),
(32, 'FF - Katedra manažmentu kult.a turizmu', 3),
(33, 'FF - Ústav pre v. k. d. Konšt. a Metoda', 3),
(34, 'FF - Katedra filozofie a politológie', 3),
(35, 'FF - Katedra romanistiky', 3),
(36, 'FF - Katedra etnológie a folkloristiky', 3),
(37, 'FF - Ústav lit. a umeleckej komunikácie', 3),
(38, 'FF - Katedra anglistiky a amerikanistiky', 3),
(39, 'FF - Katedra romanistiky a germanistiky', 3),
(40, 'FF - Katedra slovenského jazyka a literatúry', 3),
(41, 'FF - Katedra slovenského jazyka a litera', 3),
(42, 'FF - Katedra germanistiky', 3),
(43, 'FF - Jazykové centrum', 3),
(44, 'FF - Katedra  translatológie', 3),
(45, 'FF - Katedra sociológie', 3),
(46, 'FF - Katedra všeob. a aplikovanej etiky', 3),
(47, 'FF - Katedra náboženských štúdií', 3),
(48, 'FF - Katedra žurnalistiky a nových médií', 3),
(49, 'FF - Katedra kulturológie', 3),
(50, 'FF - Katedra muzeológie', 3),
(51, 'FF - Katedra archeológie', 3),
(52, 'FF - doktorandské štúdium', 3),
(53, 'FF - Katedra masmediálnej komunikácie a reklamy', 3),
(54, 'FF - Katedra masm. komunikácie a reklamy', 3),
(55, 'FF - Katedra politológie a euroáz.štúdií', 3),
(56, 'FF - Katedra manažmentu kultúry a turizmu', 3),
(57, 'FF - Katedra žurnalistiky', 3),
(58, 'FF - Katedra filozofie', 3),
(59, 'FF - Katedra politológie a euroázijských štúdií', 3),
(60, 'FF - Katedra rusistiky', 3),
(61, 'FF - Mediálne centrum', 3),
(62, 'FSVaZ - Katedra psychologických vied', 1),
(63, 'FSVaZ - Katedra ošetrovateľstva', 1),
(64, 'FSVaZ - Ústav aplikovanej psychológie', 1),
(65, 'FSVaZ - Dekanát Fakulty sociálnych vied a zdravotníctva', 1),
(66, 'FSVaZ-doktorandské štúdium', 1),
(67, 'FSVaZ - Ústav romologických štúdií', 1),
(68, 'FSVaZ - Katedra sociálnej práce a sociálnych vied', 1),
(69, 'FSVaZ - Katedra klinických disciplín a urgentnej medicíny', 1),
(70, 'FSVaZ - Katedra soc. práce a soc. vied', 1),
(71, 'FSŠ -Ústav stredoeur.jazykov a kultúr', 2),
(72, 'FSŠ - Ústav maď.jazykovedy  a lit. vedy', 2),
(73, 'FSŠ - Katedra cestovného ruchu', 2),
(74, 'FSŠ - Dekanát Fakulty stredoeurópskych štúdií', 2),
(75, 'FSŠ - ústav pre vzdelávanie pedagógov', 2),
(76, 'FSŠ-doktorandské štúdium', 2),
(77, 'R - Kancelária kvestora', 5),
(78, 'R - Kancelária rektora', 5),
(79, 'R - Oddelenie štipendií', 5),
(80, 'Oddelenie pre medzinárodné vzťahy', 5),
(81, 'Oddelenie pre vzdelávanie', 5),
(82, 'Oddelenie pre vedeckovýskumnú činnosť', 5),
(83, 'Odd. pre vonkajšie vzťahy a soc. veci', 5),
(84, 'Oddelenie projektov', 5),
(85, 'Investičné oddelenie', 5),
(86, 'Študentské centrum', 5),
(87, 'UKF - Univerzitná knižnica', 5),
(88, 'UKF - Centrum inf. a kom. technológi', 5);

-- --------------------------------------------------------

--
-- Table structure for table `pro_podiely`
--

CREATE TABLE `pro_podiely` (
  `id` int(11) NOT NULL,
  `podiel` float NOT NULL,
  `rok_od` year(4) NOT NULL,
  `rok_do` year(4) NOT NULL,
  `pro_projekt_id` int(11) NOT NULL,
  `zamestnanci_id` int(11) NOT NULL
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
  `grant_programs_id` int(11) NOT NULL
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
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `validation_key` varchar(6) NOT NULL,
  `rola_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified` int(1) NOT NULL,
  `zamestnanci_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zamestnanci`
--

CREATE TABLE `zamestnanci` (
  `id` int(11) NOT NULL,
  `meno` varchar(45) NOT NULL,
  `priezvisko` varchar(45) NOT NULL,
  `cele_meno` varchar(100) NOT NULL,
  `rodne_meno` varchar(45) NOT NULL,
  `hash_rodneho_cisla` varchar(45) NOT NULL,
  `aktivny` int(11) NOT NULL,
  `katedra_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `grant_programs`
--
ALTER TABLE `grant_programs`
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
  ADD KEY `fk_pro_podiely_pro_projekt1_idx` (`pro_projekt_id`),
  ADD KEY `fk_pro_podiely_zamestnanci1_idx` (`zamestnanci_id`);

--
-- Indexes for table `pro_projekt`
--
ALTER TABLE `pro_projekt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pro_projekt_agentura1_idx` (`agentura_id`),
  ADD KEY `fk_pro_projekt_pro_rozpocet1_idx` (`pro_rozpocet_id`),
  ADD KEY `fk_pro_projekt_roz_pro_lat_sumy1_idx` (`roz_pro_lat_sumy_id`),
  ADD KEY `fk_pro_projekt_grant_programs1_idx` (`grant_programs_id`);

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
  ADD KEY `fk_users_zamestnanci1_idx` (`zamestnanci_id`);

--
-- Indexes for table `zamestnanci`
--
ALTER TABLE `zamestnanci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_zamestnanci_katedra_idx` (`katedra_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agentura`
--
ALTER TABLE `agentura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakulta`
--
ALTER TABLE `fakulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `grant_programs`
--
ALTER TABLE `grant_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `katedra`
--
ALTER TABLE `katedra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

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
-- AUTO_INCREMENT for table `zamestnanci`
--
ALTER TABLE `zamestnanci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_pro_podiely_zamestnanci1` FOREIGN KEY (`zamestnanci_id`) REFERENCES `mydb`.`zamestnanci` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pro_projekt`
--
ALTER TABLE `pro_projekt`
  ADD CONSTRAINT `fk_pro_projekt_agentura1` FOREIGN KEY (`agentura_id`) REFERENCES `agentura` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pro_projekt_grant_programs1` FOREIGN KEY (`grant_programs_id`) REFERENCES `mydb`.`grant_programs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
  ADD CONSTRAINT `fk_users_zamestnanci1` FOREIGN KEY (`zamestnanci_id`) REFERENCES `mydb`.`zamestnanci` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_uzivatel_rola` FOREIGN KEY (`rola_id`) REFERENCES `rola` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `zamestnanci`
--
ALTER TABLE `zamestnanci`
  ADD CONSTRAINT `fk_zamestnanci_katedra` FOREIGN KEY (`katedra_id`) REFERENCES `katedra` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
