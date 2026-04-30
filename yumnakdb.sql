-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 30, 2026 at 04:49 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yumnakdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DOB` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `UserName`, `Email`, `Phone`, `Password`, `DOB`) VALUES
(1, 'khalid_abdullah', 'khalid.admin@yumnak.com', '0555123456', '$2a$12$9xBAvl0GP0Ni9hBxcwwy7OO32gQ56Y1opaTP32pGZ8M0BGaBqcTOS', '1988-02-20');

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

CREATE TABLE `airport` (
  `AirportID` int(11) NOT NULL,
  `AirportName` varchar(150) NOT NULL,
  `City` varchar(100) NOT NULL,
  `ImagePath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `airport`
--

INSERT INTO `airport` (`AirportID`, `AirportName`, `City`, `ImagePath`) VALUES
(1, 'King Khalid International Airport', 'Riyadh', 'Image/Riyadh.jpg'),
(2, 'King Abdulaziz International Airport', 'Jeddah', 'Image/Jeddah.jpg'),
(3, 'King Fahd International Airport', 'Dammam', 'Image/Dammam.jpg'),
(4, 'Prince Mohammad bin Abdulaziz International Airport', 'Medina', 'Image/Medina.jpg'),
(5, 'Taif International Airport', 'Taif', 'Image/Taif.jpg'),
(6, 'Abha International Airport', 'Abha', 'Image/Abha.jpg'),
(7, 'King Abdullah bin Abdulaziz International Airport', 'Jizan', 'Image/Jizan.jpg'),
(8, 'Prince Nayef bin Abdulaziz International Airport', 'Al Qassim', 'Image/Al Qassim.jpg'),
(9, 'Hail International Airport', 'Hail', 'Image/Hail.jpg'),
(10, 'Al-Ahsa International Airport', 'Al-Ahsa', 'Image/Al-Ahsa.jpg'),
(11, 'Al-Jouf International Airport', 'Al-Jouf', 'Image/Al-Jouf.jpg'),
(12, 'Prince Abdulmohsin bin Abdulaziz International Airport', 'Yanbu', 'Image/Yanbu.jpg'),
(13, 'Al-Ula International Airport', 'Al-Ula', 'Image/Al-Ula.jpg'),
(14, 'Neom Bay Airport', 'Neom', 'Image/Neom.jpg'),
(15, 'Red Sea International Airport', 'Red Sea Project', 'Image/Red Sea Project.jpg'),
(16, 'Prince Sultan bin Abdulaziz Airport', 'Tabuk', 'Image/Tabuk.jpg'),
(17, 'King Saud Local Airport', 'Al Bahah', 'Image/Al Bahah.jpg'),
(18, 'Najran Domestic Airport', 'Najran', 'Image/Najran.jpg'),
(19, 'Bisha Domestic Airport', 'Bisha', 'Image/Bisha.jpg'),
(20, 'Arar Domestic Airport', 'Arar', 'Image/Arar.jpg'),
(21, 'Gurayat Domestic Airport', 'Gurayat', 'Image/Gurayat.jpg'),
(22, 'Turaif Domestic Airport', 'Turaif', 'Image/Turaif.jpg'),
(23, 'Rafha Domestic Airport', 'Rafha', 'Image/Rafha.jpg'),
(24, 'Al Qaisumah/Hafr Al Batin Airport', 'Hafar Al-Batin', 'Image/Hafar Al-Batin.jpg'),
(25, 'Wadi al-Dawasir Domestic Airport', 'Wadi al-Dawasir', 'Image/Wadi al-Dawasir.jpg'),
(26, 'Dawadmi Domestic Airport', 'Dawadmi', 'Image/Dawadmi.jpg'),
(27, 'Sharurah Domestic Airport', 'Sharurah', 'Image/Sharurah.jpg'),
(28, 'Al Wajh Domestic Airport', 'Al Wajh', 'Image/Al Wajh.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `assistance_request`
--

CREATE TABLE `assistance_request` (
  `RequestID` int(11) NOT NULL,
  `PreferredTime` datetime NOT NULL,
  `Date` datetime DEFAULT CURRENT_TIMESTAMP,
  `ExtraNote` varchar(255) DEFAULT NULL,
  `Status` enum('Pending','Accepted','Rejected','Cancelled','Completed') DEFAULT 'Pending',
  `IsPaid` tinyint(1) DEFAULT '0',
  `TravelerID` int(11) NOT NULL,
  `AdminID` int(11) DEFAULT NULL,
  `AssistantID` int(11) DEFAULT NULL,
  `GateID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assistance_request`
--

INSERT INTO `assistance_request` (`RequestID`, `PreferredTime`, `Date`, `ExtraNote`, `Status`, `IsPaid`, `TravelerID`, `AdminID`, `AssistantID`, `GateID`) VALUES
(1, '2026-05-20 14:00:00', '2026-04-30 19:10:01', 'Requires assistance from check-in to boarding gate.', 'Completed', 1, 1, 1, 4, 'RU12'),
(2, '2026-05-22 10:30:00', '2026-04-30 19:10:01', 'Passenger arriving early.', 'Accepted', 1, 2, 1, 1, 'JE05'),
(3, '2026-04-15 08:00:00', '2026-04-30 19:10:01', 'Needs help with 2 heavy bags.', 'Completed', 1, 3, 1, 3, 'DM08'),
(4, '2026-06-01 18:45:00', '2026-04-30 19:10:01', 'Flight was changed.', 'Cancelled', 1, 1, 1, NULL, 'RU12'),
(5, '2026-05-25 23:00:00', '2026-04-30 19:10:01', 'Last minute request.', 'Rejected', 1, 2, 1, NULL, 'JE05'),
(6, '2026-05-01 19:12:00', '2026-04-30 19:13:11', '', 'Completed', 1, 1, 1, 1, 'RU12'),
(7, '2026-05-05 20:01:00', '2026-04-30 19:14:39', '', 'Completed', 1, 1, 1, 1, 'TA01'),
(8, '2026-05-30 07:01:00', '2026-04-30 19:19:07', '', 'Pending', 1, 2, 1, NULL, 'DM08'),
(9, '2026-05-06 14:00:00', '2026-04-30 19:23:03', '', 'Completed', 1, 3, 1, 2, 'ME05'),
(10, '2026-10-05 16:00:00', '2026-04-30 19:25:09', '', 'Pending', 1, 3, 1, NULL, 'AB01'),
(11, '2026-07-05 07:00:00', '2026-04-30 19:28:27', 'I want help, this is an important trip to me', 'Pending', 1, 4, 1, NULL, 'JE28'),
(12, '2026-07-06 09:00:00', '2026-04-30 19:30:23', '', 'Pending', 1, 4, 1, NULL, 'RU15');

-- --------------------------------------------------------

--
-- Table structure for table `assistance_type`
--

CREATE TABLE `assistance_type` (
  `AssistanceTypeID` int(11) NOT NULL,
  `AssistanceName` varchar(100) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assistance_type`
--

INSERT INTO `assistance_type` (`AssistanceTypeID`, `AssistanceName`, `Description`, `Price`) VALUES
(1, 'Wheelchair Assistance', 'Full wheelchair support from the entrance gate directly to the aircraft seat.', '50.00'),
(2, 'Mobility Assistance', 'Walking support and luggage handling for individuals who cannot stand for long periods.', '40.00'),
(3, 'Visual Impairment Assistance', 'Dedicated spatial guidance and navigation support through the airport.', '60.00'),
(4, 'Hearing Impairment Assistance', 'Visual cue navigation and sign language communication support.', '55.00'),
(5, 'Cognitive Assistance', 'Specialized, patient accompaniment for travelers with cognitive disabilities or autism.', '65.00');

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE `assistant` (
  `AssistantID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Specialization` enum('Wheelchair Assistance','Mobility Assistance','Visual Impairment Assistance','Hearing Impairment Assistance','Cognitive Assistance') NOT NULL,
  `AdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`AssistantID`, `Name`, `Phone`, `Email`, `Specialization`, `AdminID`) VALUES
(1, 'Abdullah Yasser', '0544111222', 'abdullah@yumnak.com', 'Wheelchair Assistance', 1),
(2, 'Fatima Saad', '0544333444', 'fatima@yumnak.com', 'Visual Impairment Assistance', 1),
(3, 'Yazeed Ali', '0544555666', 'yazeed@yumnak.com', 'Hearing Impairment Assistance', 1),
(4, 'Maha Sultan', '0544777888', 'maha@yumnak.com', 'Mobility Assistance', 1),
(5, 'Saud Ibrahim', '0544999000', 'saud@yumnak.com', 'Cognitive Assistance', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gate`
--

CREATE TABLE `gate` (
  `GateID` varchar(50) NOT NULL,
  `AirportID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gate`
--

INSERT INTO `gate` (`GateID`, `AirportID`) VALUES
('RU12', 1),
('RU15', 1),
('RU22', 1),
('JE05', 2),
('JE14', 2),
('JE28', 2),
('DM08', 3),
('DM11', 3),
('ME02', 4),
('ME05', 4),
('TA01', 5),
('AB01', 6),
('JI01', 7),
('QA01', 8),
('HA01', 9),
('AH01', 10),
('JO01', 11),
('YA01', 12),
('UL01', 13),
('NE01', 14),
('RS01', 15),
('TB01', 16),
('BH01', 17),
('NJ01', 18),
('BI01', 19),
('AR01', 20),
('GU01', 21),
('TU01', 22),
('RA01', 23),
('HB01', 24),
('WD01', 25),
('DA01', 26),
('SH01', 27),
('WJ01', 28);

-- --------------------------------------------------------

--
-- Table structure for table `request_type`
--

CREATE TABLE `request_type` (
  `AssistanceTypeID` int(11) NOT NULL,
  `RequestID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `request_type`
--

INSERT INTO `request_type` (`AssistanceTypeID`, `RequestID`) VALUES
(1, 1),
(2, 1),
(3, 2),
(3, 3),
(2, 4),
(3, 5),
(1, 6),
(1, 7),
(3, 8),
(3, 9),
(3, 10),
(4, 11),
(4, 12);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `Stars` int(11) DEFAULT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  `Date` datetime DEFAULT CURRENT_TIMESTAMP,
  `RequestID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`ReviewID`, `Stars`, `Comment`, `Date`, `RequestID`) VALUES
(1, 5, 'Fatima was an excellent guide, very respectful and clear. Made my airport experience completely stress-free!', '2026-04-21 14:30:00', 3),
(2, 5, 'Won\'t be the last time using Yumnak!', '2026-04-30 19:43:31', 1),
(3, 5, 'Thank you Abdullah', '2026-04-30 19:44:21', 6),
(4, 5, 'As always the best treatment', '2026-04-30 19:44:59', 7),
(5, 4, 'Good', '2026-04-30 19:46:25', 9);

-- --------------------------------------------------------

--
-- Table structure for table `traveler`
--

CREATE TABLE `traveler` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `DOB` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `traveler`
--

INSERT INTO `traveler` (`UserID`, `UserName`, `Email`, `Phone`, `Password`, `DOB`) VALUES
(1, 'tariq_alotaibi', 'tariq@gmail.com', '0501112222', '$2a$12$GzbsYcvFMRrlKnwwmQsx/OOMX.U0z4d7ccA5Ouzr.P0B7.H5DQ.J2', '1980-05-10'),
(2, 'sara_alsaud', 'sara@gmail.com', '0503334444', '$2a$12$upKRTcM3PjHGcYplcJ5hR.58z3tm2anGi.jhRdx.5A.2jotJ3EQX6', '1992-08-15'),
(3, 'majed_alharbi', 'majed@gmail.com', '0505556666', '$2a$12$WU65E2Rh4Bb7KbrQAyYsBexJcuaYyC8JkRqFYxu2rGp2opnr1jVMu', '1975-12-01'),
(4, 'reem_alqahtani', 'reem@gmail.com', '0507778888', '$2a$12$C0ruSsEHe.TzAzK9.adhA.AhWoiIPoi2eOhj1VdVAD1zLPCaFrEjy', '2000-03-22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `airport`
--
ALTER TABLE `airport`
  ADD PRIMARY KEY (`AirportID`);

--
-- Indexes for table `assistance_request`
--
ALTER TABLE `assistance_request`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `TravelerID` (`TravelerID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `AssistantID` (`AssistantID`),
  ADD KEY `GateID` (`GateID`);

--
-- Indexes for table `assistance_type`
--
ALTER TABLE `assistance_type`
  ADD PRIMARY KEY (`AssistanceTypeID`);

--
-- Indexes for table `assistant`
--
ALTER TABLE `assistant`
  ADD PRIMARY KEY (`AssistantID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `AdminID` (`AdminID`);

--
-- Indexes for table `gate`
--
ALTER TABLE `gate`
  ADD PRIMARY KEY (`GateID`),
  ADD KEY `AirportID` (`AirportID`);

--
-- Indexes for table `request_type`
--
ALTER TABLE `request_type`
  ADD PRIMARY KEY (`AssistanceTypeID`,`RequestID`),
  ADD KEY `RequestID` (`RequestID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `RequestID` (`RequestID`);

--
-- Indexes for table `traveler`
--
ALTER TABLE `traveler`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `airport`
--
ALTER TABLE `airport`
  MODIFY `AirportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `assistance_request`
--
ALTER TABLE `assistance_request`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `assistance_type`
--
ALTER TABLE `assistance_type`
  MODIFY `AssistanceTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
  MODIFY `AssistantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `traveler`
--
ALTER TABLE `traveler`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assistance_request`
--
ALTER TABLE `assistance_request`
  ADD CONSTRAINT `assistance_request_ibfk_1` FOREIGN KEY (`TravelerID`) REFERENCES `traveler` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `assistance_request_ibfk_2` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assistance_request_ibfk_3` FOREIGN KEY (`AssistantID`) REFERENCES `assistant` (`AssistantID`) ON DELETE SET NULL,
  ADD CONSTRAINT `assistance_request_ibfk_4` FOREIGN KEY (`GateID`) REFERENCES `gate` (`GateID`);

--
-- Constraints for table `assistant`
--
ALTER TABLE `assistant`
  ADD CONSTRAINT `assistant_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL;

--
-- Constraints for table `gate`
--
ALTER TABLE `gate`
  ADD CONSTRAINT `gate_ibfk_1` FOREIGN KEY (`AirportID`) REFERENCES `airport` (`AirportID`) ON DELETE CASCADE;

--
-- Constraints for table `request_type`
--
ALTER TABLE `request_type`
  ADD CONSTRAINT `request_type_ibfk_1` FOREIGN KEY (`AssistanceTypeID`) REFERENCES `assistance_type` (`AssistanceTypeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_type_ibfk_2` FOREIGN KEY (`RequestID`) REFERENCES `assistance_request` (`RequestID`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`RequestID`) REFERENCES `assistance_request` (`RequestID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
