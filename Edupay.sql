-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 08:01 AM
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
-- Database: `edupay`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `totalfeeimposed` int(255) DEFAULT NULL,
  `totalfeecollected` int(255) DEFAULT NULL,
  `scholarshipsawarded` int(255) DEFAULT NULL,
  `totalfeedue` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`totalfeeimposed`, `totalfeecollected`, `scholarshipsawarded`, `totalfeedue`) VALUES
(109100, 10500, 0, 98600);

-- --------------------------------------------------------

--
-- Table structure for table `admincontact`
--

CREATE TABLE `admincontact` (
  `phoneno1` varchar(500) NOT NULL,
  `phoneno2` varchar(500) NOT NULL,
  `email1` varchar(500) NOT NULL,
  `email2` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincontact`
--

INSERT INTO `admincontact` (`phoneno1`, `phoneno2`, `email1`, `email2`) VALUES
('1231231231', '4564564564', 'abc@gmail.com', 'xyz@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `auth_user`
--

CREATE TABLE `auth_user` (
  `studentid` int(255) NOT NULL,
  `username` varchar(500) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_user`
--

INSERT INTO `auth_user` (`studentid`, `username`, `name`, `email`, `password`, `role`) VALUES
(100, 'ADmiRal005', 'St.Thomas Mat Hr Sec School ', 'stthomas@mail.com', '12345678', 'admin'),
(101, 'allen005', 'allen', 'allen005@gmail.com', 'welcome', 'student'),
(102, 'pavi123', 'Pavithra', 'pavi@gmail.com', '12345', 'student'),
(103, 'Dhanalakshmi002', 'Dhanalakshmi', 'dhana@123', '1234', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `bus_requests`
--

CREATE TABLE `bus_requests` (
  `studentid` int(255) NOT NULL,
  `routename` varchar(500) NOT NULL,
  `amount` int(255) NOT NULL,
  `status` varchar(500) NOT NULL,
  `via` longtext NOT NULL,
  `boarding_point` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_requests`
--

INSERT INTO `bus_requests` (`studentid`, `routename`, `amount`, `status`, `via`, `boarding_point`) VALUES
(101, 'Anna Nagar', 40000, 'accepted', 'Anna Nagar West, Thirumangalam, Vijayakanth Kalyana Mandapam, Koyambedu (CMBT), Arumbakkam, Vadapalani, Porur, Iyyappanthangal, Poonamallee, Nazarethpettai, Thandalam Koot Road, Saveetha Engineering College', 'Poonamallee'),
(102, 'Adyar', 38000, 'accepted', 'Adyar, Besant Nagar, Thiruvanmiyur, Kotturpuram, Mylapore, Raja Annamalai Puram', 'Thiruvanmiyur');

-- --------------------------------------------------------

--
-- Table structure for table `bus_routes`
--

CREATE TABLE `bus_routes` (
  `routename` varchar(500) NOT NULL,
  `type` enum('Long','Short','Medium') NOT NULL,
  `via` longtext NOT NULL,
  `amount` int(255) NOT NULL,
  `km` int(255) NOT NULL,
  `seats` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_routes`
--

INSERT INTO `bus_routes` (`routename`, `type`, `via`, `amount`, `km`, `seats`) VALUES
('Adyar', 'Short', 'Adyar, Besant Nagar, Thiruvanmiyur, Kotturpuram, Mylapore, Raja Annamalai Puram', 38000, 45, 49),
('Ambattur', 'Long', 'Ambattur, Avadi, Thirumullaivoyal, Mogappair, Koyambedu, Vadapalani, Porur, Valasaravakkam', 46000, 70, 50),
('Anna Nagar', 'Medium', 'Anna Nagar West, Thirumangalam, Vijayakanth Kalyana Mandapam, Koyambedu (CMBT), Arumbakkam, Vadapalani, Porur, Iyyappanthangal, Poonamallee, Nazarethpettai, Thandalam Koot Road, Saveetha Engineering College', 40000, 83, 47),
('Chromepet', 'Short', 'Chromepet, Pallavaram, Medavakkam, Velachery, Guindy', 36000, 40, 50),
('Guindy', 'Medium', 'Guindy, Saidapet, Alandur, St. Thomas Mount, Nanganallur, Meenambakkam, Pallavaram, Tambaram', 43000, 65, 50),
('Minjur', 'Long', 'Manali New Town, Mathur M.M.D.A., Madhavaram Milk Colony, Moolakadai, Retteri, Thirumangalam, Anna Nagar West, Vijayakanth Kalyana Mandapam, Koyambedu (CMBT), Porur, Iyyappanthangal, Poonamallee, Nazarethpettai, Thandalam Koot Road, Saveetha Engineering College', 45000, 124, 43),
('Mylapore', 'Short', 'Mylapore, Santhome, Raja Annamalai Puram, Adyar, Besant Nagar', 37000, 42, 50),
('Porur', 'Medium', 'Porur, Iyyappanthangal, Ramapuram, Valasaravakkam, Vadapalani, Kodambakkam, Nungambakkam', 41000, 55, 50),
('T Nagar', 'Short', 'T Nagar Main Road, Ranganathan Street, Pondy Bazaar, Mambalam, Kodambakkam, Saidapet, Ashok Nagar', 35000, 50, 50),
('Velachery', 'Medium', 'Velachery Main Road, Guindy, IIT Madras, Taramani, Perungudi, Pallikaranai, Medavakkam, Sholinganallur', 42000, 60, 50);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `studentid` int(255) NOT NULL,
  `username` varchar(500) NOT NULL,
  `feename` varchar(500) NOT NULL,
  `feetype` varchar(500) NOT NULL,
  `feeamt` int(255) NOT NULL,
  `duedate` date NOT NULL,
  `paydate` date NOT NULL,
  `referenceid` varchar(500) NOT NULL,
  `remarks` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`studentid`, `username`, `feename`, `feetype`, `feeamt`, `duedate`, `paydate`, `referenceid`, `remarks`) VALUES
(101, 'allen005', 'TESTING', 'Full', 100, '2025-09-03', '2025-09-07', 'REF68bdc65365ca0', 'ONLINE'),
(103, 'Dhanalakshmi002', 'Bus Fee', 'Full', 2500, '2025-11-28', '2025-09-09', 'REF68bfc4cc8c4ea', 'ONLINE'),
(102, 'pavi123', 'Bus Fee', 'Full', 2500, '2025-09-10', '2025-09-10', 'REF68c124f57b9d3', 'ONLINE'),
(101, 'allen005', 'Term 1', 'Full', 10400, '2026-01-01', '2025-09-12', 'REF68c3b9f0b60c4', 'ONLINE');

-- --------------------------------------------------------

--
-- Table structure for table `incharge`
--

CREATE TABLE `incharge` (
  `class` int(255) NOT NULL,
  `sec` varchar(2) DEFAULT NULL,
  `incharge` varchar(500) NOT NULL,
  `inchargeno` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incharge`
--

INSERT INTO `incharge` (`class`, `sec`, `incharge`, `inchargeno`) VALUES
(11, 'A', 'Mrs.Dhanalakshmi', 7825625635),
(11, 'B', 'Ms.Saliya', 10192020),
(12, 'B', 'Mr.Akash', 7825625635);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `Description` varchar(255) NOT NULL,
  `Notification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`Description`, `Notification`) VALUES
('Help', 'You can Download Your E-Receipts From the History Page  '),
('Error', 'If any Error Occurs During Payment Kindly Report to The School'),
('Help', 'For Scholarship, Kindly Approach the School Accounts Office');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `studentid` int(255) NOT NULL,
  `username` varchar(500) NOT NULL,
  `name` varchar(500) NOT NULL,
  `feeamt` int(255) NOT NULL,
  `feetype` enum('Full','Partial') NOT NULL,
  `feename` varchar(500) NOT NULL,
  `duedate` date NOT NULL,
  `status` enum('paid','due') NOT NULL,
  `schawarded` enum('yes','no') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`studentid`, `username`, `name`, `feeamt`, `feetype`, `feename`, `duedate`, `status`, `schawarded`) VALUES
(101, 'allen005', 'Kevin Allen', 10400, 'Full', 'Term 1', '2026-01-01', 'paid', 'no'),
(102, 'pavi123', 'Pavithra', 10400, 'Full', 'Term 1', '2026-01-01', 'due', 'no'),
(101, 'allen005', 'Kevin Allen', 100, 'Full', 'TESTING', '2025-09-03', 'paid', 'no'),
(103, 'Dhanalaskshmi002', 'Dhanalakshmi', 3400, 'Partial', 'Term Fee I3', '2026-01-01', 'due', 'no'),
(103, 'Dhanalaskshmi002', 'Dhanalakshmi', 3400, 'Partial', 'Term Fee I2', '2025-12-02', 'due', 'no'),
(103, 'Dhanalaskshmi002', 'Dhanalakshmi', 3400, 'Partial', 'Term Fee I1', '2025-11-02', 'due', 'no'),
(101, 'allen005', 'Kevin Allen', 40000, 'Full', 'BUS FEE', '2026-05-31', 'due', 'no'),
(102, 'pavi123', 'Pavithra', 38000, 'Full', 'BUS FEE', '2026-05-31', 'due', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `quotas`
--

CREATE TABLE `quotas` (
  `quota` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotas`
--

INSERT INTO `quotas` (`quota`, `percentage`) VALUES
('Sports Quota', 30),
('Armed forces', 25),
('Disability Quota', 40),
('Merit quota', 50);

-- --------------------------------------------------------

--
-- Table structure for table `scholarship`
--

CREATE TABLE `scholarship` (
  `studentid` int(255) NOT NULL,
  `schname` varchar(500) NOT NULL,
  `percentage` int(255) NOT NULL,
  `amountreduced` int(255) NOT NULL,
  `incharge` varchar(500) NOT NULL,
  `feename` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentprofile`
--

CREATE TABLE `studentprofile` (
  `photo` longtext NOT NULL,
  `studentid` int(255) NOT NULL,
  `username` varchar(500) NOT NULL,
  `name` varchar(500) NOT NULL,
  `class` int(255) NOT NULL,
  `sec` enum('A','B','C','D') NOT NULL,
  `fathername` varchar(500) NOT NULL,
  `fatherno` varchar(500) NOT NULL,
  `mothername` varchar(500) NOT NULL,
  `motherno` varchar(500) NOT NULL,
  `bloodgroup` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentprofile`
--

INSERT INTO `studentprofile` (`photo`, `studentid`, `username`, `name`, `class`, `sec`, `fathername`, `fatherno`, `mothername`, `motherno`, `bloodgroup`, `email`) VALUES
('uploads/1748230306_My photo.png', 101, 'allen005', 'Kevin Allen', 11, 'A', 'dinesh', '7871405219', 'theresa', '9962911379', 'o positive', 'allen005@gmail.com'),
('uploads/newimage.jpg', 102, 'pavi123', 'Pavithra', 11, 'B', 'xyz', '1234', 'abc', '1245', 'o+ve', 'pavi123@gmail.com'),
('uploads/dhana.jpg', 103, 'Dhanalaskshmi002', 'Dhanalakshmi', 12, 'B', 'abc', '123', 'xyz', '1234', 'AB positive', 'dhana@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_user`
--
ALTER TABLE `auth_user`
  ADD PRIMARY KEY (`studentid`);

--
-- Indexes for table `bus_routes`
--
ALTER TABLE `bus_routes`
  ADD PRIMARY KEY (`routename`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD KEY `auth_user to payment` (`studentid`);

--
-- Indexes for table `studentprofile`
--
ALTER TABLE `studentprofile`
  ADD PRIMARY KEY (`studentid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `auth_user to history` FOREIGN KEY (`studentid`) REFERENCES `auth_user` (`studentid`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `auth_user to payment` FOREIGN KEY (`studentid`) REFERENCES `auth_user` (`studentid`);

--
-- Constraints for table `scholarship`
--
ALTER TABLE `scholarship`
  ADD CONSTRAINT `auth_user to scholarship` FOREIGN KEY (`studentid`) REFERENCES `auth_user` (`studentid`);

--
-- Constraints for table `studentprofile`
--
ALTER TABLE `studentprofile`
  ADD CONSTRAINT `profile to user` FOREIGN KEY (`studentid`) REFERENCES `auth_user` (`studentid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
