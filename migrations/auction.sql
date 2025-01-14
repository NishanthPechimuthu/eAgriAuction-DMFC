-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 02:04 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auction28`
--

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `auctionId` int(11) NOT NULL,
  `auctionTitle` varchar(200) NOT NULL,
  `auctionStartPrice` decimal(10,2) NOT NULL,
  `auctionStartDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `auctionEndDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `auctionProductImg` varchar(300) NOT NULL,
  `auctionAddress` varchar(300) NOT NULL,
  `auctionDescription` longtext NOT NULL,
  `auctionCategoryId` int(11) NOT NULL,
  `auctionCreatedBy` int(11) NOT NULL,
  `auctionStatus` enum('activate','deactivate','suspend') DEFAULT 'activate',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`auctionId`, `auctionTitle`, `auctionStartPrice`, `auctionStartDate`, `auctionEndDate`, `auctionProductImg`, `auctionAddress`, `auctionDescription`, `auctionCategoryId`, `auctionCreatedBy`, `auctionStatus`, `createdAt`) VALUES
(5, 'Black wash the winner of the day of the day of the', 10000.00, '2024-12-07 09:01:01', '2024-11-25 06:09:00', 'prod_67457b1968c7f.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'This is the winner of the day of the day of the day of the day of the day of the day of the day of the day of the winner of BD ', 2, 1, 'activate', '2024-11-23 06:10:38'),
(6, 'He the hell bro', 1258.00, '2024-12-09 05:13:55', '2024-11-24 12:12:00', 'prod_67457b3317c9d.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'The data from the winner of the day of the day of the day to all city of with categoryname the winner is not upload any one of flood ', 2, 1, 'activate', '2024-11-25 12:13:30'),
(8, 'Black wash', 100.00, '2024-11-29 11:45:00', '2024-12-05 11:45:00', 'prod_6749ab799b9ac.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'The bootstrap message was automatically generated email from github ', 3, 2, 'activate', '2024-11-29 11:45:38'),
(10, 'He', 258.00, '2024-12-02 09:11:00', '2024-12-07 16:11:00', 'prod_674d79bcea4d8.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'categoryDropdownButton.textContent', 2, 1, 'activate', '2024-12-02 09:11:24'),
(11, 'No Buddy', 569.00, '2024-12-09 05:14:14', '2024-12-09 10:05:00', 'prod_6750297bb1d4e.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'Lorem Ipsum was the first time zone ', 3, 1, 'activate', '2024-12-04 10:05:47'),
(12, 'Gg', 99.00, '2024-12-09 05:13:55', '2024-12-06 08:51:00', 'prod_6752bb2be32d5.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'Yy', 2, 1, 'activate', '2024-12-06 08:51:55'),
(13, 'J', 25.00, '2024-12-09 05:13:55', '2024-12-06 08:52:00', 'prod_6752bb6579559.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'The ', 2, 1, 'activate', '2024-12-06 08:52:53'),
(14, 'Jlk', 235.00, '2024-12-07 10:18:00', '2024-12-11 10:18:00', 'prod_675421119460c.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'Hi', 3, 1, 'activate', '2024-12-07 10:18:57'),
(15, 'Niswant', 800.00, '2024-12-08 06:33:00', '2024-12-08 06:33:00', 'prod_67553dfdd69cd.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'Hi lets come and grab niswa', 2, 1, 'activate', '2024-12-08 06:34:37'),
(16, 'Ragu', 559.00, '2024-12-10 09:34:00', '2029-12-20 15:34:00', 'prod_675fc8d2c1c82.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'The hell ', 2, 1, 'activate', '2024-12-10 09:34:56'),
(17, '123', 1256.00, '2024-12-15 05:49:00', '2025-06-22 05:49:00', 'prod_675e6df7403e8.webp', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'Rhhd', 2, 2, 'activate', '2024-12-15 05:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bidId` int(11) NOT NULL,
  `bidAuctionId` int(11) NOT NULL,
  `bidUserId` int(11) NOT NULL,
  `bidAmount` decimal(10,2) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bidId`, `bidAuctionId`, `bidUserId`, `bidAmount`, `createdAt`) VALUES
(6, 5, 2, 12000.00, '2024-11-23 13:09:01'),
(7, 6, 2, 1259.00, '2024-11-26 07:42:24'),
(10, 8, 1, 101.00, '2024-12-02 09:11:39'),
(11, 10, 2, 5568.00, '2024-12-06 09:25:39'),
(12, 14, 2, 300.00, '2024-12-08 06:37:10'),
(13, 11, 8, 700.00, '2024-12-09 09:27:07'),
(14, 14, 8, 500.00, '2024-12-09 09:27:48'),
(15, 11, 2, 10000.00, '2024-12-09 09:28:24'),
(16, 16, 8, 600.00, '2024-12-10 16:05:31'),
(17, 16, 2, 700.00, '2024-12-10 16:05:51'),
(18, 16, 8, 710.00, '2024-12-10 16:06:00'),
(19, 14, 2, 1000.00, '2024-12-10 16:06:19'),
(20, 17, 1, 2500.00, '2024-12-15 11:59:25'),
(21, 16, 2, 1000.00, '2024-12-15 11:59:50'),
(22, 16, 2, 1100.00, '2024-12-15 12:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  `categoryImg` varchar(300) NOT NULL,
  `categoryStatus` enum('activate','deactivate','suspend') NOT NULL DEFAULT 'activate',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `categoryName`, `categoryImg`, `categoryStatus`, `createdAt`) VALUES
(1, 'Electronic', 'cat_6740450b783ce.webp', 'activate', '2024-11-22 08:47:07'),
(2, 'Food', 'cat_67415d694fa49.webp', 'activate', '2024-11-23 04:43:21'),
(3, 'Crypto', 'cat_67489931afe15.webp', 'activate', '2024-11-28 16:24:17'),
(4, 'Vegetables', 'cat_675eb2e84581c.webp', 'activate', '2024-12-15 10:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `passResets`
--

CREATE TABLE `passResets` (
  `passResetId` int(11) NOT NULL,
  `passResetUserId` int(11) NOT NULL,
  `passResetToken` varchar(64) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `passResets`
--

INSERT INTO `passResets` (`passResetId`, `passResetUserId`, `passResetToken`, `createdAt`) VALUES
(1, 3, '7fd8fdf4ccc2810e35a976983b9c0c2a32957422cebdad6c25f9998106d789d7', '2024-11-28 14:12:21'),
(2, 3, '544eb97a94096fb11e5843ff102d109e6315d1974050119ec9babd04e2402a29', '2024-11-28 14:13:07'),
(3, 3, 'd62db4feea32f2a0ea88839322f9255d1ba0ad1b5c85525b9417484b049462e1', '2024-11-28 14:14:45'),
(4, 3, '6d5c8a6d1f8605b7fc94aee6499c4a984f55a99612b678e4d61709edb7207022', '2024-11-28 14:18:33'),
(5, 3, '7ed83bcc9e8178cf8d97b7f7e1d87c6a', '2024-11-30 15:50:21'),
(6, 3, '7d28bc7dc216849f5ed9033247051a9e', '2024-11-30 15:51:48'),
(7, 3, 'EXPIRED', '2024-11-30 16:00:45'),
(8, 1, '90b166991f4c9555f5f502c112c919b6', '2024-11-30 16:19:05'),
(9, 1, 'e71ff286310aff75cfdc930b46fb5362', '2024-11-30 16:19:55'),
(10, 1, 'f4265b723e8b579901c27670ef57a011', '2024-11-30 16:20:40'),
(11, 3, '00a1c1c0f31014ce9a9765d0b5f2c08f', '2024-12-04 12:35:42'),
(12, 3, '85c4f405bae9a4c3c94eac4f338ca9c9', '2024-12-04 12:36:20'),
(13, 3, '3858c99617ce9a769f60902d3b51cbfe', '2024-12-04 12:36:31'),
(14, 3, '3708954544e71f4cfe91f6ba735017ea', '2024-12-04 12:36:54'),
(15, 3, '2e54824c891f7bfd19430ffe26192ecf', '2024-12-04 13:03:19'),
(16, 3, 'EXPIRED', '2024-12-04 13:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `trans`
--

CREATE TABLE `trans` (
  `transId` int(11) NOT NULL,
  `transTrackingId` varchar(30) NOT NULL,
  `transCardNo` varchar(30) NOT NULL,
  `transAccountNo` varchar(30) NOT NULL,
  `transUserId` int(11) NOT NULL,
  `transAmount` decimal(10,2) NOT NULL,
  `transAuctionId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trans`
--

INSERT INTO `trans` (`transId`, `transTrackingId`, `transCardNo`, `transAccountNo`, `transUserId`, `transAmount`, `transAuctionId`, `createdAt`) VALUES
(1, 'txn_67604609b088d4.61128365', '9876543210', '11130100007375', 1, 101.00, 8, '2024-12-16 15:23:53'),
(2, 'txn_67604663ded797.18816217', '9876543210', '11130100007375', 1, 101.00, 8, '2024-12-16 15:25:23'),
(3, 'txn_67604708e03180.86987544', '9876543210', '11130100007375', 1, 101.00, 8, '2024-12-16 15:28:08'),
(4, 'txn_67604776420188.17093409', '9876543210', '11130100007375', 1, 101.00, 8, '2024-12-16 15:29:58'),
(5, 'txn_6761c00e5184c0.87469131', '9876543210', '11130100005354', 2, 12000.00, 5, '2024-12-17 18:16:46'),
(6, 'txn_676299f0736110.76144269', '679045634', '11130100005354', 2, 1259.00, 6, '2024-12-18 09:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `userFirstName` varchar(100) DEFAULT NULL,
  `userLastName` varchar(100) DEFAULT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userPhone` varchar(100) DEFAULT NULL,
  `userAddress` varchar(250) DEFAULT NULL,
  `userProfileImg` varchar(300) DEFAULT 'profile.webp',
  `userAccountNo` varchar(30) DEFAULT NULL,
  `userRole` enum('user','admin') NOT NULL DEFAULT 'user',
  `userStatus` enum('activate','deactivate','suspend') DEFAULT 'activate',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `userFirstName`, `userLastName`, `userEmail`, `userPassword`, `userPhone`, `userAddress`, `userProfileImg`, `userAccountNo`, `userRole`, `userStatus`, `createdAt`) VALUES
(1, 'blk', 'Nishanth', 'Pechimuthu', 'black@black.in', '$2y$10$HWW4lymluIu7nK.4RyprBumuf7b6i8MeI5pM4OBJe5F94FQm53Fye', '+91 8015864344', 'Udumalipettai, Tiruppur,Tamil Nadu', 'img_674561fd68014.webp', '11130100005354', 'admin', 'activate', '2024-12-11 08:46:29'),
(2, 'root', 'Nishanth', 'Root', 'root@root.com', '$2y$10$hdJIVulgWHZj.RL7UyCqm.yigqyBsZP6Qkk3YceNQ43/xCloDWffC', '+91 9500814344', 'Udumalipettai, Tiruppur,Tamil Nadu,Indian-642205', 'img_674b2bcaafe57.webp', '11130100007375', 'user', 'activate', '2024-11-23 05:15:40'),
(3, 'black', NULL, NULL, 'EXP:nishanthpechimuthu@gmail.com', '$2y$10$QiirJV1S8KSjbT59BPI1geJRP/mcyoxya0X2KXndcXbVJAlVVF8ga', 'NULL', 'NULL', 'profile.webp', NULL, 'user', 'suspend', '2024-10-16 07:54:13'),
(4, '22ct19', NULL, NULL, '22ct19nishanth@gmail.com', '$2y$10$izGcmfVhW29bJYIjNnOAOutb8FcEiwciE1NLdRDvoBos1F3jHEMtO', 'NULL', 'NULL', 'profile.webp', NULL, 'user', 'activate', '2024-11-30 09:48:24'),
(8, 'yellow', NULL, NULL, 'yellow@gmail.com', '$2y$10$YHXU9AbUcpNYjfV9tawDtOqnLgUIsqH75T6s4FWSAvKKYFQPzYYFG', 'NULL', 'NULL', 'profile.webp', NULL, 'user', 'activate', '2024-10-16 15:15:50'),
(9, 'gray', NULL, NULL, 'EXP:EXP:gray@gmail.com', '$2y$10$naTRT6QM0npWIQudxP8wXe0GbYKEJyiWUDJG82f3/6qCDBHit.ytW', NULL, NULL, 'profile.webp', NULL, 'user', 'suspend', '2024-09-11 07:32:34'),
(11, 'gray2', NULL, NULL, 'gray@gmail.com', '$2y$10$90srGWeR80x9YJc8fct0TuBAGG2xuKiQigr3o03wnBphlA3FLuAT2', NULL, NULL, 'profile.webp', NULL, 'user', 'deactivate', '2024-12-07 11:35:36'),
(12, '1', NULL, NULL, '1@gmail.com', '$2y$10$EkDIISfH4hF4J1B0WcM8oewRhBhFN5hvp/mmNKy.ORvX0zmdXMRNe', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-12-09 10:41:35'),
(13, '2', NULL, NULL, '2@gmail.com', '$2y$10$C67Zjmuqe9b40.Oqh8qmheoep.ICJsai91CPkH8Z3ijRS7nMFlL8q', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-09-17 10:41:53'),
(14, '3', NULL, NULL, '3@gmail.com', '$2y$10$KgfkSYJlVbM4vGoS0fIIe.ACmWp0q6cL6bYqv6yUMqW.qx8RSgQbm', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-11-13 10:43:06'),
(15, '4', NULL, NULL, '4@gmail.com', '$2y$10$nXVyBwakWyyOMpB5BEPNPe7/LkuJi/DPeNnAPZPemIS6HRpb3hsIq', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-12-09 10:43:51'),
(16, '5', NULL, NULL, '5@gmail.com', '$2y$10$GNX/MytCLguewkaJ77t8sOYXdcxpwPqpL8UvUzS/BQbvcylPG7KLq', NULL, NULL, 'profile.webp', NULL, 'user', 'deactivate', '2024-12-09 10:46:08'),
(17, '6', NULL, NULL, '6@gmail.com', '$2y$10$9cRrSGIRd4UnkGzOBGxJ0eim1Isl7A9HOgfG1SyAQ/lShrGw5B.6a', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-10-15 10:46:25'),
(18, '7', NULL, NULL, '7@gmail.com', '$2y$10$M8mTpVq5latigiP7fb9N9Oo4b5h3qkTsuzxMlWmOms7crF4Uk1V8G', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-11-09 10:47:45'),
(19, 'john_doe', NULL, NULL, 'john_doe@example.com', '$2y$10$Qj4rWjPZJqER5sA1vQ8WbJQdxpsXE1y7h6taKp9djU3hY7pblpHS6', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-04-12 08:52:18'),
(20, 'alice_smith', NULL, NULL, 'alice_smith@example.com', '$2y$10$yL91Pbxd3XzgyVRQnJMjMErIVobggQ1x.Nlj5ZX9WvHIm58gQyCq2', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-05-03 04:00:42'),
(21, 'bob_johnson', NULL, NULL, 'bob_johnson@example.com', '$2y$10$OHwT1Q7Wi8zPbls5Gf2H6.fYnbV0BIC6yO0YoGXY3b45yRtozmUeG', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-06-25 06:15:56'),
(22, 'carol_williams', NULL, NULL, 'carol_williams@example.com', '$2y$10$9.Zj4f0X9bETK9Dwp7jm5aePxwMzYBmdzt.mWQT9uwrdJclpYpRmG', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-03-18 11:40:23'),
(23, 'david_brown', NULL, NULL, 'david_brown@example.com', '$2y$10$ghuVpwFZBy2mvhtAfgh0aeHo1wTrHNjf6T.S6iEDN28T5tqdoHGii', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-02-14 06:30:01'),
(24, 'emma_davis', NULL, NULL, 'emma_davis@example.com', '$2y$10$6hUJ0vld8cmR6AWYn44O8ux5e5MYIbMPkIuZKvcQ.Owph5TBiNU4K', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-04-10 10:50:37'),
(25, 'fred_miller', NULL, NULL, 'fred_miller@example.com', '$2y$10$Y47AZyXTj5.EyIQQ2uz5GqosRQW30uVjzCOxMlAPXeZfTq5oVdyAm', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-05-29 01:48:50'),
(26, 'grace_garcia', NULL, NULL, 'grace_garcia@example.com', '$2y$10$hsMKiaQw0MVs37NSsRry/ft7Uu5ECv3ZphZ93rU3nH6XTzp6u/Ju2', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-07-02 08:25:41'),
(27, 'hank_martinez', NULL, NULL, 'hank_martinez@example.com', '$2y$10$wDQOZg9od9AQzGs8tM5GfFjtWbwRPWfBcBdFwQ2MiBzm7UG0uAoTi', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-01-19 02:35:02'),
(28, 'irene_hernandez', NULL, NULL, 'irene_hernandez@example.com', '$2y$10$Wr6k3kaQHo.Bs8Q0h7d6xuA3q24t5BCf.Z7d0J49tk2kcs.AxC6b2', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-06-14 10:02:10'),
(29, 'jack_moore', NULL, NULL, 'jack_moore@example.com', '$2y$10$OTsmk0r0XzTY33s2fw/eU24eLRllhwA06Jg3ALQDb3HJXOUhXFEa6', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-08-09 04:44:47'),
(30, 'kate_jackson', NULL, NULL, 'kate_jackson@example.com', '$2y$10$CTTY0kXr1K8uhz0fYrFq5LBYlzN52zFoyRh5A7hBzQQMkFsqCO6t.', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-04-30 11:24:55'),
(31, 'leo_thomas', NULL, NULL, 'leo_thomas@example.com', '$2y$10$L5JqBB2IGKiL7rT1RaW.V3Wld7HgAP4nMK7fzAwMLHn8VhbTZSt8W', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-02-06 12:32:16'),
(32, 'mia_white', NULL, NULL, 'mia_white@example.com', '$2y$10$5vvZIQiJ1Ybz.kdYh5HnYenPzx06Y3WmrQwFPgFUOeqj4pLnbgLwG', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-07-22 07:14:03'),
(33, 'nina_smith', NULL, NULL, 'nina_smith@example.com', '$2y$10$Jwfh3mQH3lMmw96fRA.XnH2r1LzWeioQhDsbgR8LIM.LtkZt6JlbW', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-06-05 09:05:01'),
(34, 'oscar_davis', NULL, NULL, 'oscar_davis@example.com', '$2y$10$2smE3JHcUmsLUwD5Rp4zwPSROTDJgqaTYWsdbI2tB7u0xBzYwX2u6', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-05-19 04:32:47'),
(35, 'paul_jones', NULL, NULL, 'paul_jones@example.com', '$2y$10$AMQs5yAKQ25G8fpRVHR4hqsFb7Xuhhgw0EkQ2MwDNBwxAlblpCtvG', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-03-25 05:48:56'),
(36, 'quinn_clark', NULL, NULL, 'quinn_clark@example.com', '$2y$10$ZToh9eAB0IYblq7Di43DbXG9u9UxnMZT7gt2FPURZkCqymuoV9a1u', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-04-02 13:57:05'),
(37, 'rachel_lee', NULL, NULL, 'rachel_lee@example.com', '$2y$10$gM2a2eHD5A5Ta6wzR9FOSyDaedkTPntRPqTI0QJ34uO9KhV5Qf/rm', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-07-14 08:26:21'),
(38, 'samuel_wilson', NULL, NULL, 'samuel_wilson@example.com', '$2y$10$kEehmmhxRs.GVpXFEqtFfPOz9neFdp6.wRjlUQXtZz9BeCpHspmdW', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-02-23 14:41:36'),
(39, 'tina_moore', NULL, NULL, 'tina_moore@example.com', '$2y$10$FM5jj1VfS54L4HJvLCZtHuMe3ObzZq2m5JhM2MIHGGShYdBFCeT4q', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-06-20 04:18:08'),
(40, 'dg', NULL, NULL, 'ct@dg.com', '$2$5ghhggghghddrhu6k', NULL, NULL, 'profile.webp', NULL, 'user', 'activate', '2024-05-23 06:50:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auctionId`),
  ADD KEY `auctionCategoryId` (`auctionCategoryId`),
  ADD KEY `auctionCreatedBy` (`auctionCreatedBy`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bidId`),
  ADD KEY `bidAuctionId` (`bidAuctionId`),
  ADD KEY `bidUserId` (`bidUserId`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `categoryName` (`categoryName`);

--
-- Indexes for table `passResets`
--
ALTER TABLE `passResets`
  ADD PRIMARY KEY (`passResetId`),
  ADD KEY `passResets_passRestUserId_users_userId` (`passResetUserId`);

--
-- Indexes for table `trans`
--
ALTER TABLE `trans`
  ADD PRIMARY KEY (`transId`),
  ADD KEY `trans_transAccountNo_users_userAccountNo` (`transAccountNo`),
  ADD KEY `trans_transUserId_users_userId` (`transUserId`),
  ADD KEY `trans_transAuctionId_auctions_auctionId` (`transAuctionId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userEmail` (`userEmail`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD UNIQUE KEY `userAccountNo` (`userAccountNo`),
  ADD UNIQUE KEY `userAccountNo_2` (`userAccountNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auctionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bidId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `passResets`
--
ALTER TABLE `passResets`
  MODIFY `passResetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `trans`
--
ALTER TABLE `trans`
  MODIFY `transId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`auctionCategoryId`) REFERENCES `categories` (`categoryId`),
  ADD CONSTRAINT `auctions_ibfk_2` FOREIGN KEY (`auctionCreatedBy`) REFERENCES `users` (`userId`);

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`bidAuctionId`) REFERENCES `auctions` (`auctionId`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`bidUserId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `passResets`
--
ALTER TABLE `passResets`
  ADD CONSTRAINT `passResets_passRestUserId_users_userId` FOREIGN KEY (`passResetUserId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `trans`
--
ALTER TABLE `trans`
  ADD CONSTRAINT `trans_transAccountNo_users_userAccountNo` FOREIGN KEY (`transAccountNo`) REFERENCES `users` (`userAccountNo`),
  ADD CONSTRAINT `trans_transAuctionId_auctions_auctionId` FOREIGN KEY (`transAuctionId`) REFERENCES `auctions` (`auctionId`),
  ADD CONSTRAINT `trans_transUserId_users_userId` FOREIGN KEY (`transUserId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
