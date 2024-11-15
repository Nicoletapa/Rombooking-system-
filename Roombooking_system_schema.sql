-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 24. Okt, 2024 18:18 PM
-- Tjener-versjon: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Gang`
--
CREATE DATABASE IF NOT EXISTS `Gang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gang`;
-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Bruker`
--

CREATE TABLE `Bruker` (
  `BrukerID` int(11) NOT NULL AUTO_INCREMENT,
  `Navn` varchar(255) NOT NULL,
  `Etternavn` varchar(255) NOT NULL,
  `TlfNr` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserName` varchar(255) UNIQUE NOT NULL,
  `RolleID` int(11) NOT NULL,
  `FailedLoginAttempts` int(11) NOT NULL DEFAULT 0,
  `LastFailedLogin` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`BrukerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dataark for tabell `Bruker`
--

INSERT INTO `Bruker` (`BrukerID`, `Navn`, `Etternavn`, `TlfNr`, `Email`, `Password`, `UserName`, `RolleID`, `FailedLoginAttempts`, `LastFailedLogin`) VALUES
(8, 'thevi', 'thach', '12341234', 'thevi@example.com', '$2y$10$U1cxgUYWc2D4ojbcVbu5f.SW1Tr9kazsxqnt/JWpS0YEWyrJ1vwB6', 'Admin', 2, 0, NULL),
(9, 'nicoleta', 'nicoleta', '91121222', 'nicoleta@example.com', '$2y$10$nf7VVykU5E/nBIeqbKOPqO.9j/wCP9M9cSbzFAJZSKB4B6gSaK9Fu', 'nicoleta', 2, 0, NULL);


-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Reservasjon`
--

CREATE TABLE `Reservasjon` (
  `ReservasjonID` int(11) NOT NULL,
  `RomID` int(11) NOT NULL,
  `BrukerID` int(11) NOT NULL,
  `Innsjekk` datetime NOT NULL,
  `Utsjekk` datetime NOT NULL,
  `AntallPersoner` int(11) NOT NULL,
  `Bestillingsdato` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Data for the table `Reservasjon`
--

INSERT INTO `Reservasjon` (`ReservasjonID`, `RomID`, `BrukerID`, `Innsjekk`, `Utsjekk`, `AntallPersoner`, `Bestillingsdato`) VALUES
(1, 3, 8, '2024-10-15 07:30:00', '2024-10-17 12:00:00', 2, '2024-10-10 14:00:00'),
(2, 3, 8, '2024-10-15 07:30:00', '2024-10-17 12:00:00', 3, '2024-10-10 14:05:00'),
(3, 2, 8, '2024-10-13 17:00:00', '2024-10-16 14:00:00', 1, '2024-10-08 09:30:00'),
(4, 1, 8, '2024-10-27 00:00:00', '2024-10-30 00:00:00', 2, '2024-10-20 12:15:00'),
(5, 1, 8, '2024-10-23 00:00:00', '2024-10-26 00:00:00', 4, '2024-10-18 11:00:00'),
(6, 3, 8, '2024-10-30 00:00:00', '2024-10-31 00:00:00', 2, '2024-10-25 15:30:00'),
(7, 2, 8, '2024-10-28 00:00:00', '2024-10-31 00:00:00', 3, '2024-10-22 10:45:00'),
(8, 1, 9, '2024-10-18 00:00:00', '2024-10-20 00:00:00', 1, '2024-10-14 08:00:00'),
(9, 3, 9, '2024-10-19 00:00:00', '2024-10-23 00:00:00', 2, '2024-10-15 09:15:00'),
(10, 5, 9, '2024-10-22 00:00:00', '2024-10-25 00:00:00', 4, '2024-10-16 16:45:00');



-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Roller`
--

CREATE TABLE `Roller` (
  `RolleID` int(11) NOT NULL,
  `RolleNavn` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `Roller`
--

INSERT INTO `Roller` (`RolleID`, `RolleNavn`) VALUES
(1, 'Customer'),
(2, 'Admin');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `RomID_RomType`
--

CREATE TABLE `RomID_RomType` (
  `RomID` int(11) NOT NULL,
  `RomTypeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `RomID_RomType`
--

INSERT INTO `RomID_RomType` (`RomID`, `RomTypeID`) VALUES
(1, 1),
(4, 1),
(7, 1),
(10, 1),
(13, 1),
(16, 1),
(19, 1),
(22, 1),
(25, 1),
(2, 2),
(5, 2),
(8, 2),
(11, 2),
(14, 2),
(17, 2),
(20, 2),
(23, 2),
(3, 3),
(6, 3),
(9, 3),
(12, 3),
(15, 3),
(18, 3),
(21, 3),
(24, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Romtype`
--

CREATE TABLE `Romtype` (
  `RomtypeID` int(11) NOT NULL,
  `RomTypeNavn` varchar(50) NOT NULL,
  `RomKapsitet` int(11) NOT NULL,
  `Beskrivelse` varchar(1000) NOT NULL,
  `RoomTypeImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dataark for tabell `Romtype`
--

INSERT INTO `Romtype` (`RomtypeID`, `RomTypeNavn`, `RomKapsitet`, `Beskrivelse`, `RoomTypeImage`) VALUES
(1, 'Enkeltrom', 1, 'Et komfortabelt rom for en person', '/RomBooking-System-/Public/Images/Enkeltrom.avif'),
(2, 'Dobbeltrom', 2, 'Et rom for to personer med dobbeltseng', '/RomBooking-System-/Public/Images/Dobbeltrom.avif'),
(3, 'Junior Suite', 3, 'En luksuriøs suite med plass til tre personer', '/RomBooking-System-/Public/Images/JuniorSuite.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bruker`
--
ALTER TABLE `Bruker`
  ADD KEY `RolleID` (`RolleID`);

--
-- Indexes for table `Reservasjon`
--
ALTER TABLE `Reservasjon`
  ADD PRIMARY KEY (`ReservasjonID`),
  ADD KEY `RomID` (`RomID`),
  ADD KEY `BrukerID` (`BrukerID`);

--
-- Indexes for table `Roller`
--
ALTER TABLE `Roller`
  ADD PRIMARY KEY (`RolleID`);

--
-- Indexes for table `RomID_RomType`
--
ALTER TABLE `RomID_RomType`
  ADD PRIMARY KEY (`RomID`),
  ADD KEY `RomTypeID` (`RomTypeID`);

--
-- Indexes for table `Romtype`
--
ALTER TABLE `Romtype`
  ADD PRIMARY KEY (`RomtypeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Bruker`
--
ALTER TABLE `Bruker`
  MODIFY `BrukerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Reservasjon`
--
ALTER TABLE `Reservasjon`
  MODIFY `ReservasjonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Roller`
--
ALTER TABLE `Roller`
  MODIFY `RolleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `RomID_RomType`
--
ALTER TABLE `RomID_RomType`
  MODIFY `RomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `Romtype`
--
ALTER TABLE `Romtype`
  MODIFY `RomtypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `Bruker`
--
ALTER TABLE `Bruker`
  ADD CONSTRAINT `bruker_ibfk_1` FOREIGN KEY (`RolleID`) REFERENCES `Roller` (`RolleID`);

--
-- Begrensninger for tabell `Reservasjon`
--
ALTER TABLE `Reservasjon`
  ADD CONSTRAINT `reservasjon_ibfk_1` FOREIGN KEY (`RomID`) REFERENCES `RomID_RomType` (`RomID`),
  ADD CONSTRAINT `reservasjon_ibfk_2` FOREIGN KEY (`BrukerID`) REFERENCES `Bruker` (`BrukerID`);

--
-- Begrensninger for tabell `RomID_RomType`
--
ALTER TABLE `RomID_RomType`
  ADD CONSTRAINT `romid_romtype_ibfk_1` FOREIGN KEY (`RomTypeID`) REFERENCES `RomType` (`RomtypeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
