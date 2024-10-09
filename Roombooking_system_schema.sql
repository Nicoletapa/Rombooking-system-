-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 09. Okt, 2024 21:07 PM
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
  `BrukerID` int(11) NOT NULL,
  `Navn` varchar(255) NOT NULL,
  `Etternavn` varchar(255) NOT NULL,
  `TlfNr` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `RolleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Reservasjon`
--

CREATE TABLE `Reservasjon` (
  `ReservasjonID` int(11) NOT NULL,
  `RomID` int(11) NOT NULL,
  `BrukerID` int(11) NOT NULL,
  `Innsjekk` datetime NOT NULL,
  `Utsjekk` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Romtype`
--

CREATE TABLE `Romtype` (
  `RomtypeID` int(11) NOT NULL,
  `RomTypeNavn` varchar(50) NOT NULL,
  `RomKapsitet` int(11) NOT NULL,
  `Beskrivelse` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bruker`
--
ALTER TABLE `Bruker`
  ADD PRIMARY KEY (`BrukerID`),
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
  MODIFY `BrukerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Reservasjon`
--
ALTER TABLE `Reservasjon`
  MODIFY `ReservasjonID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Roller`
--
ALTER TABLE `Roller`
  MODIFY `RolleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `RomID_RomType`
--
ALTER TABLE `RomID_RomType`
  MODIFY `RomID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Romtype`
--
ALTER TABLE `Romtype`
  MODIFY `RomtypeID` int(11) NOT NULL AUTO_INCREMENT;

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
