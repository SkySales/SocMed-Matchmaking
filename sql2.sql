-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for eventswave
CREATE DATABASE IF NOT EXISTS `eventswave` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `eventswave`;

-- Dumping structure for table eventswave.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `Admin_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_Name` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  PRIMARY KEY (`Admin_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.admin: ~0 rows (approximately)

-- Dumping structure for table eventswave.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `COMMENT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `POST_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `COMMENT` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`COMMENT_ID`),
  KEY `POST_ID` (`POST_ID`),
  KEY `USER_ID` (`USER_ID`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`POST_ID`) REFERENCES `posts` (`Post_ID`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.comments: ~0 rows (approximately)
INSERT INTO `comments` (`COMMENT_ID`, `POST_ID`, `USER_ID`, `COMMENT`, `DATE`) VALUES
	(1, 2, 7, 'wag nlng pala', '2026-04-15 14:45:20'),
	(2, 99, 18, 'asdasd', '2026-04-20 05:18:23');

-- Dumping structure for table eventswave.comments_events
CREATE TABLE IF NOT EXISTS `comments_events` (
  `COMMENT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Event_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `COMMENT` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`COMMENT_ID`),
  KEY `Event_ID` (`Event_ID`),
  KEY `USER_ID` (`USER_ID`),
  CONSTRAINT `comments_events_ibfk_1` FOREIGN KEY (`Event_ID`) REFERENCES `events` (`Event_ID`),
  CONSTRAINT `comments_events_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.comments_events: ~0 rows (approximately)
INSERT INTO `comments_events` (`COMMENT_ID`, `Event_ID`, `USER_ID`, `COMMENT`, `DATE`) VALUES
	(1, 2, 7, '', '2026-04-16 06:17:29');

-- Dumping structure for table eventswave.comments_vid
CREATE TABLE IF NOT EXISTS `comments_vid` (
  `COMMENT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `VIDEO_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `COMMENT` text NOT NULL,
  `DATE` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`COMMENT_ID`),
  KEY `VIDEO_ID` (`VIDEO_ID`),
  KEY `USER_ID` (`USER_ID`),
  CONSTRAINT `comments_vid_ibfk_1` FOREIGN KEY (`VIDEO_ID`) REFERENCES `videos` (`Video_ID`),
  CONSTRAINT `comments_vid_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.comments_vid: ~0 rows (approximately)

-- Dumping structure for table eventswave.events
CREATE TABLE IF NOT EXISTS `events` (
  `Event_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) NOT NULL,
  `Likes` int(11) NOT NULL,
  `Event_Poster` text NOT NULL,
  `Caption` varchar(250) NOT NULL,
  `Event_Time` time NOT NULL,
  `Event_Date` datetime NOT NULL,
  `Invite_Link` text NOT NULL,
  `HashTags` varchar(250) NOT NULL,
  `Date_Upload` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Event_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.events: ~2 rows (approximately)
INSERT INTO `events` (`Event_ID`, `User_ID`, `Likes`, `Event_Poster`, `Caption`, `Event_Time`, `Event_Date`, `Invite_Link`, `HashTags`, `Date_Upload`) VALUES
	(1, 7, 0, 'File_202604151263943.jfif', 'Test', '08:42:00', '2026-04-16 00:00:00', 'https://chatgpt.com/c/69db0f63-e2a0-8321-9164-7f306039afa5', '#wow', '2026-04-15 14:40:42'),
	(2, 7, 0, 'File_202604159789233.png', 'ML 5man', '08:47:00', '2026-04-17 00:00:00', 'https://chatgpt.com/c/69db0f63-e2a0-8321-9164-7f306039afa5', '#wow #Legit', '2026-04-15 14:44:45'),
	(3, 17, 0, 'File_202604228106396.', 'sadasdasd', '09:36:00', '2026-04-16 00:00:00', 'https://chatgpt.com/c/69db0f63-e2a0-8321-9164-7f306039afa5', '#wow #Legit', '2026-04-22 03:33:27');

-- Dumping structure for table eventswave.fallowing
CREATE TABLE IF NOT EXISTS `fallowing` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_Id` int(11) NOT NULL,
  `Other_user_id` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.fallowing: ~18 rows (approximately)
INSERT INTO `fallowing` (`ID`, `User_Id`, `Other_user_id`) VALUES
	(1, 7, 7),
	(7, 8, 7),
	(9, 7, 8),
	(10, 12, 7),
	(12, 15, 7),
	(13, 16, 7),
	(34, 20, 7),
	(35, 20, 18),
	(36, 20, 17),
	(42, 23, 20),
	(49, 7, 22),
	(51, 7, 23),
	(52, 7, 20),
	(54, 17, 20),
	(55, 17, 19),
	(56, 17, 21),
	(57, 17, 18),
	(58, 17, 7);

-- Dumping structure for table eventswave.likes
CREATE TABLE IF NOT EXISTS `likes` (
  `Like_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Post_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  PRIMARY KEY (`Like_ID`),
  KEY `Post_ID` (`Post_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`Post_ID`) REFERENCES `posts` (`Post_ID`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.likes: ~0 rows (approximately)
INSERT INTO `likes` (`Like_ID`, `Post_ID`, `User_ID`) VALUES
	(1, 2, 7),
	(2, 99, 18);

-- Dumping structure for table eventswave.likes_events
CREATE TABLE IF NOT EXISTS `likes_events` (
  `Like_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Event_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  PRIMARY KEY (`Like_ID`),
  KEY `Event_ID` (`Event_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `likes_events_ibfk_1` FOREIGN KEY (`Event_ID`) REFERENCES `events` (`Event_ID`),
  CONSTRAINT `likes_events_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.likes_events: ~0 rows (approximately)

-- Dumping structure for table eventswave.likes_vid
CREATE TABLE IF NOT EXISTS `likes_vid` (
  `Like_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Video_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  PRIMARY KEY (`Like_ID`),
  KEY `Video_ID` (`Video_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `likes_vid_ibfk_1` FOREIGN KEY (`Video_ID`) REFERENCES `videos` (`Video_ID`),
  CONSTRAINT `likes_vid_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.likes_vid: ~0 rows (approximately)

-- Dumping structure for table eventswave.lobbies
CREATE TABLE IF NOT EXISTS `lobbies` (
  `Lobby_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lobby_Name` varchar(100) DEFAULT NULL,
  `Host_ID` int(11) DEFAULT NULL,
  `Is_Private` tinyint(1) DEFAULT 0,
  `Lobby_Code` varchar(10) DEFAULT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Lobby_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.lobbies: ~0 rows (approximately)

-- Dumping structure for table eventswave.lobby_members
CREATE TABLE IF NOT EXISTS `lobby_members` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Lobby_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Joined_At` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.lobby_members: ~0 rows (approximately)

-- Dumping structure for table eventswave.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `Post_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) NOT NULL,
  `Likes` int(11) NOT NULL,
  `Img_Path` text NOT NULL,
  `Caption` varchar(700) NOT NULL,
  `HashTags` varchar(250) NOT NULL,
  `Date_Upload` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Post_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.posts: ~54 rows (approximately)
INSERT INTO `posts` (`Post_ID`, `User_ID`, `Likes`, `Img_Path`, `Caption`, `HashTags`, `Date_Upload`) VALUES
	(2, 7, 1, 'File_202604156451056.png', 'TARA LARO', '#wow #Legit', '2026-04-15 14:45:06'),
	(3, 7, 0, 'File_202604168412272.png', 'valorant, dota', '#dota #valo', '2026-04-16 06:13:54'),
	(4, 7, 0, 'File_202604167127139.jfif', '+3 Dota ', '#dota #dotaplayers', '2026-04-16 07:04:45'),
	(5, 7, 0, 'File_202604166631608.', 'roblox', '#wow', '2026-04-16 07:05:06'),
	(6, 7, 0, 'File_202604161569572.', 'Join the biggest music festival this summer with live bands and DJs', '#music #festival #summer #live #concert', '2026-04-16 07:05:47'),
	(7, 7, 0, 'File_202604165887146.', 'Tech conference 2026 about AI machine learning and innovation', '#tech #AI #machinelearning #conference #innovation', '2026-04-16 07:05:55'),
	(8, 7, 0, 'File_202604167867226.', 'Food fair with street food desserts and local delicacies', '#food #streetfood #dessert #foodfestival #local', '2026-04-16 07:06:11'),
	(9, 7, 0, 'File_202604162644067.', 'Local basketball tournament happening this weekend join your team now', '#basketball #sports #tournament #weekend #team', '2026-04-16 07:06:22'),
	(55, 7, 0, 'File_202604155926062.jfif', 'Live music festival with bands and DJs', '#music #festival #live #concert', '2026-04-16 13:09:20'),
	(56, 7, 0, 'File_202604155926062.jfif', 'Summer beach party with music and dance', '#summer #party #music', '2026-04-16 13:09:20'),
	(57, 7, 0, 'File_202604155926062.jfif', 'Underground hip hop concert tonight', '#hiphop #music #concert', '2026-04-16 13:09:20'),
	(58, 7, 0, 'File_202604155926062.jfif', 'AI and machine learning conference 2026', '#AI #machinelearning #tech', '2026-04-16 13:09:20'),
	(59, 7, 0, 'File_202604155926062.jfif', 'Startup innovation summit for developers', '#startup #innovation #tech', '2026-04-16 13:09:20'),
	(60, 7, 0, 'File_202604155926062.jfif', 'Coding bootcamp for beginners', '#coding #programming #tech', '2026-04-16 13:09:20'),
	(61, 7, 0, 'File_202604155926062.jfif', 'Basketball tournament open for all teams', '#basketball #sports #tournament', '2026-04-16 13:09:20'),
	(62, 7, 0, 'File_202604155926062.jfif', 'Football championship finals this weekend', '#football #sports #finals', '2026-04-16 13:09:20'),
	(63, 7, 0, 'File_202604155926062.jfif', 'Morning fitness bootcamp session', '#fitness #health #sports', '2026-04-16 13:09:20'),
	(64, 7, 0, 'File_202604155926062.jfif', 'Street food festival with local dishes', '#food #streetfood #festival', '2026-04-16 13:09:20'),
	(65, 7, 0, 'File_202604155926062.jfif', 'Dessert fair with cakes and sweets', '#dessert #food #sweets', '2026-04-16 13:09:20'),
	(66, 7, 0, 'File_202604155926062.jfif', 'Food truck gathering downtown', '#foodtrucks #food #event', '2026-04-16 13:09:20'),
	(67, 7, 0, 'File_202604155926062.jfif', 'Business seminar on marketing strategies', '#business #marketing #seminar', '2026-04-16 13:09:20'),
	(68, 7, 0, 'File_202604155926062.jfif', 'Free workshop on public speaking', '#workshop #speaking #skills', '2026-04-16 13:09:20'),
	(69, 7, 0, 'File_202604155926062.jfif', 'Career development training event', '#career #training #growth', '2026-04-16 13:09:20'),
	(70, 7, 0, 'File_202604155926062.jfif', 'Movie night under the stars', '#movie #night #entertainment', '2026-04-16 13:09:20'),
	(71, 7, 0, 'File_202604155926062.jfif', 'Comedy show featuring local comedians', '#comedy #show #fun', '2026-04-16 13:09:20'),
	(72, 7, 0, 'File_202604155926062.jfif', 'Live theater performance downtown', '#theater #performance #live', '2026-04-16 13:09:20'),
	(73, 7, 0, 'File_202604155926062.jfif', 'Outdoor summer concert with food trucks', '#concert #summer #foodtrucks', '2026-04-16 13:09:20'),
	(74, 7, 0, 'File_202604155926062.jfif', 'Community fun run event this Sunday', '#run #community #fitness', '2026-04-16 13:09:20'),
	(75, 7, 0, 'File_202604155926062.jfif', 'Charity event for local communities', '#charity #event #community', '2026-04-16 13:09:20'),
	(76, 7, 0, 'File_202604155926062.jfif', 'Gaming tournament for esports players', '#gaming #esports #tournament', '2026-04-16 13:09:20'),
	(77, 7, 0, 'File_202604155926062.jfif', 'Art exhibit showcasing modern artists', '#art #exhibit #creative', '2026-04-16 13:09:20'),
	(78, 7, 0, 'File_202604155926062.jfif', 'Photography workshop for beginners', '#photography #workshop #art', '2026-04-16 13:09:20'),
	(79, 7, 0, 'File_202604155926062.jfif', 'Yoga session for relaxation and health', '#yoga #health #wellness', '2026-04-16 13:09:20'),
	(80, 7, 0, 'File_202604155926062.jfif', 'Meditation retreat weekend getaway', '#meditation #retreat #peace', '2026-04-16 13:09:20'),
	(81, 7, 0, 'File_202604155926062.jfif', 'Wellness seminar for mental health', '#wellness #mentalhealth #health', '2026-04-16 13:09:20'),
	(82, 7, 0, 'File_202604155926062.jfif', 'Music concert live band experience', '#music #concert #live', '2026-04-16 13:09:20'),
	(83, 7, 0, 'File_202604155926062.jfif', 'Advanced AI deep learning workshop', '#AI #deeplearning #tech', '2026-04-16 13:09:20'),
	(84, 7, 0, 'File_202604155926062.jfif', 'City marathon sports event', '#marathon #sports #run', '2026-04-16 13:09:20'),
	(85, 7, 0, 'File_202604155926062.jfif', 'International food tasting event', '#food #international #taste', '2026-04-16 13:09:20'),
	(86, 7, 0, 'File_202604155926062.jfif', 'Leadership seminar for young professionals', '#leadership #seminar #career', '2026-04-16 13:09:20'),
	(87, 7, 0, 'File_202604155926062.jfif', 'Stand-up comedy night special', '#comedy #night #fun', '2026-04-16 13:09:20'),
	(88, 7, 0, 'File_202604155926062.jfif', 'Summer outdoor festival celebration', '#summer #festival #event', '2026-04-16 13:09:20'),
	(89, 7, 0, 'File_202604155926062.jfif', 'Online gaming live stream event', '#gaming #stream #live', '2026-04-16 13:09:20'),
	(90, 7, 0, 'File_202604155926062.jfif', 'Health awareness campaign', '#health #awareness #event', '2026-04-16 13:09:20'),
	(91, 7, 0, 'File_202604155926062.jfif', 'Music festival night vibes', '#music #festival', '2026-04-16 13:09:20'),
	(92, 7, 0, 'File_202604155926062.jfif', 'Tech innovation expo', '#tech #innovation', '2026-04-16 13:09:20'),
	(93, 7, 0, 'File_202604155926062.jfif', 'Basketball league match', '#basketball #sports', '2026-04-16 13:09:20'),
	(94, 7, 0, 'File_202604155926062.jfif', 'Food carnival experience', '#food #festival', '2026-04-16 13:09:20'),
	(95, 7, 0, 'File_202604155926062.jfif', 'Entrepreneurship workshop', '#business #startup', '2026-04-16 13:09:20'),
	(96, 7, 0, 'File_202604155926062.jfif', 'Cinema premiere night', '#movie #cinema', '2026-04-16 13:09:20'),
	(97, 7, 0, 'File_202604155926062.jfif', 'Beach party summer vibes', '#summer #party', '2026-04-16 13:09:20'),
	(98, 7, 0, 'File_202604155926062.jfif', 'Esports championship finals', '#gaming #esports', '2026-04-16 13:09:20'),
	(99, 7, 1, 'File_202604155926062.jfif', 'Yoga outdoor session', '#yoga #health', '2026-04-16 13:09:20');

-- Dumping structure for table eventswave.special_events
CREATE TABLE IF NOT EXISTS `special_events` (
  `Event_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Caption` varchar(250) NOT NULL,
  `Event_Time` time NOT NULL,
  `Event_Date` datetime NOT NULL,
  `Invite_Link` text NOT NULL,
  `Date_Upload` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Event_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.special_events: ~0 rows (approximately)

-- Dumping structure for table eventswave.tags
CREATE TABLE IF NOT EXISTS `tags` (
  `Tag_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tag_Name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Tag_ID`),
  UNIQUE KEY `Tag_Name` (`Tag_Name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.tags: ~10 rows (approximately)
INSERT INTO `tags` (`Tag_ID`, `Tag_Name`) VALUES
	(5, 'Basketball'),
	(4, 'Call of Duty'),
	(7, 'Coding'),
	(3, 'Dota 2'),
	(9, 'Esports'),
	(6, 'Football'),
	(1, 'Mobile Legends'),
	(8, 'Music'),
	(10, 'Streaming'),
	(2, 'Valorant');

-- Dumping structure for table eventswave.users
CREATE TABLE IF NOT EXISTS `users` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FULL_NAME` varchar(100) NOT NULL,
  `USER_NAME` varchar(50) NOT NULL,
  `USER_TYPE` varchar(2) NOT NULL,
  `PASSWORD_S` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(60) NOT NULL,
  `IMAGE` varchar(200) DEFAULT 'assets/images/profile_pics/default.png',
  `FACEBOOK` varchar(200) DEFAULT 'www.facebook.com',
  `WHATSAPP` varchar(200) DEFAULT 'www.webwhatsapp.com',
  `BIO` varchar(500) DEFAULT 'bio here',
  `FALLOWERS` int(11) DEFAULT 0,
  `FALLOWING` int(11) DEFAULT 0,
  `POSTS` int(11) DEFAULT 0,
  `is_tagged` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.users: ~8 rows (approximately)
INSERT INTO `users` (`User_ID`, `FULL_NAME`, `USER_NAME`, `USER_TYPE`, `PASSWORD_S`, `EMAIL`, `IMAGE`, `FACEBOOK`, `WHATSAPP`, `BIO`, `FALLOWERS`, `FALLOWING`, `POSTS`, `is_tagged`) VALUES
	(7, 'Marc Amaba Avendano', 'Marcoooooo21', '1', '$2y$10$Wu8kG4OcQPDI4ToQ7mt4VuyZPHFxA4oRBlWjvXRNa0mD3glSDJyDy', 'skysales0321@gmail.com', 'Profile_202604123018193.png', 'hnfgnfghjdrh', 'sdgdhdfg', '213123123', 7, 5, 10, 1),
	(17, 'mabs3271', 'user_7363', '1', '$2y$10$fDk14y2NJbwLTGAFqQHvDObaTLZngKXy/2iOEmhzbLG.W782xm3IG', 'mabs3271@gmail.com', 'default.png', 'www.facebook.com', 'www.webwhatsapp.com', '0', 1, 5, 0, 1),
	(18, 'Torbu', 'Torbu_rat', '1', '$2y$10$QF3UXudrQscLj5WxgxnnaeH0t4jaaC83QIhXQKIGm3hoP4GvcXwe2', 'Torburatikolsal@gmail.com', 'default.png', 'www.facebook.com/Torburatikolsal', 'Torburatikolsal/12346455', 'Torburatikolsal', 2, 0, 0, 1),
	(19, 'skysales1321', 'user_7627', '1', '$2y$10$TZzfUioXFdMwY3YMMW34H.8R8b3Pk1fD9LBxRAKLXv3wyiBvob55K', 'skysales1321@gmail.com', 'default.png', 'www.facebook.com', 'www.webwhatsapp.com', '0', 1, 0, 0, 1),
	(20, 'skysales2321', 'user_5860', '1', '$2y$10$AtyO7nx1XnWPxrdzaL7x7uhKIyM6S.uaOFnxXKQHcGl81kttHNI3e', 'skysales2321@gmail.com', 'default.png', 'www.facebook.com', 'www.webwhatsapp.com', '0', 3, 3, 0, 1),
	(21, 'skysales3321', 'user_5638', '1', '$2y$10$xFOi28alYbj4K1j7RxlRY.GbGnTV5xa.iipbCA81wYeF8SkjrMEhq', 'skysales3321@gmail.com', 'default.png', 'www.facebook.com', 'www.webwhatsapp.com', '0', 1, 0, 0, 1),
	(22, 'skysales4321', 'user_8693', '1', '$2y$10$gK0ynomuD9op212Md.hBweHjgzDbtZa99uY.1iFaJWXdVPfvmFj.G', 'skysales4321@gmail.com', 'default.png', 'www.facebook.com', 'www.webwhatsapp.com', '0', 1, 0, 0, 1),
	(23, 'skysales5321', 'user_3060', '1', '$2y$10$bIQ4ZINneq5N/Lu5lIZRWOUjAf3tdu7hVhmc5123DeZNpwO9lC/6u', 'skysales5321@gmail.com', 'default.png', 'www.facebook.com', 'www.webwhatsapp.com', '0', 1, 1, 0, 1);

-- Dumping structure for table eventswave.user_tags
CREATE TABLE IF NOT EXISTS `user_tags` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) DEFAULT NULL,
  `Tag_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `User_ID` (`User_ID`),
  KEY `Tag_ID` (`Tag_ID`),
  CONSTRAINT `user_tags_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  CONSTRAINT `user_tags_ibfk_2` FOREIGN KEY (`Tag_ID`) REFERENCES `tags` (`Tag_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.user_tags: ~30 rows (approximately)
INSERT INTO `user_tags` (`ID`, `User_ID`, `Tag_ID`) VALUES
	(9, 7, 5),
	(10, 7, 7),
	(11, 7, 3),
	(12, 7, 9),
	(13, 7, 8),
	(14, 7, 10),
	(21, 17, 9),
	(22, 17, 6),
	(23, 17, 8),
	(24, 17, 10),
	(25, 18, 5),
	(26, 18, 8),
	(27, 18, 10),
	(28, 19, 6),
	(29, 20, 5),
	(30, 20, 9),
	(31, 20, 6),
	(32, 21, 3),
	(33, 21, 9),
	(34, 21, 2),
	(35, 22, 5),
	(36, 22, 3),
	(37, 22, 9),
	(38, 22, 6),
	(39, 22, 2),
	(40, 23, 5),
	(41, 23, 3),
	(42, 23, 9),
	(43, 23, 6),
	(44, 23, 2);

-- Dumping structure for table eventswave.videos
CREATE TABLE IF NOT EXISTS `videos` (
  `Video_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) NOT NULL,
  `Likes` int(11) NOT NULL,
  `Video_Path` text NOT NULL,
  `Caption` varchar(250) NOT NULL,
  `HashTags` varchar(250) NOT NULL,
  `Date_Upload` datetime NOT NULL DEFAULT current_timestamp(),
  `Thumbnail_Path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Video_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table eventswave.videos: ~0 rows (approximately)
INSERT INTO `videos` (`Video_ID`, `User_ID`, `Likes`, `Video_Path`, `Caption`, `HashTags`, `Date_Upload`, `Thumbnail_Path`) VALUES
	(1, 7, 0, 'Vid_202604172388404.mp4', 'Test Vid shorts', '#wow #Legit #test', '2026-04-17 00:00:00', 'Thumb_202604172388404.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
