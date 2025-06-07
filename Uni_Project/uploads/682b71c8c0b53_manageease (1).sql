-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2025 at 12:47 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manageease`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `list_id`, `title`, `created_at`) VALUES
(1, 1, 'home pages', '2025-05-08 01:50:54'),
(2, 1, 'inner pager', '2025-05-08 01:51:03'),
(3, 2, 'database', '2025-05-08 01:51:14'),
(4, 3, 'figma', '2025-05-08 01:51:24'),
(6, 4, 'testing', '2025-05-08 02:16:35'),
(7, 6, 'test test', '2025-05-08 22:09:44'),
(8, 5, 'new postind', '2025-05-13 01:14:46'),
(17, 12, 'about us page', '2025-05-18 22:04:46'),
(16, 12, 'home page', '2025-05-18 22:04:34'),
(11, 8, 'card 2.1', '2025-05-14 05:25:54'),
(12, 8, 'card 2.2', '2025-05-14 05:26:05'),
(13, 9, 'home', '2025-05-14 11:36:33'),
(14, 10, 'using questioner', '2025-05-15 21:05:09'),
(15, 11, 'using CRUD table', '2025-05-15 21:05:33'),
(18, 15, 'Ø¯Ù‡Ø§Ù†', '2025-05-18 23:17:05'),
(19, 15, 'ÙƒÙ‡Ø±Ø¨Ø§Ø¡', '2025-05-18 23:17:11'),
(20, 17, 'print reports', '2025-05-19 00:44:47'),
(21, 18, 'ch1', '2025-05-19 00:45:05');

-- --------------------------------------------------------

--
-- Table structure for table `card_attachments`
--

DROP TABLE IF EXISTS `card_attachments`;
CREATE TABLE IF NOT EXISTS `card_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` enum('image','pdf','video','audio','file','url') NOT NULL,
  `path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `card_attachments`
--

INSERT INTO `card_attachments` (`id`, `card_id`, `title`, `type`, `path`, `created_at`) VALUES
(1, 1, '11zon_compressed.zip', 'file', '../uploads/68213003c1663_11zon_compressed.zip', '2025-05-11 23:17:23'),
(3, 1, 'Screenshot 6_9_2022 2_29_33 PM.png', 'file', '../uploads/68225cfa60171_Screenshot 6_9_2022 2_29_33 PM.png', '2025-05-12 20:41:30'),
(4, 1, 'our project', 'url', 'https://drive.google.com/drive/folders/1K9s_8sF20kCJ3dnCyJ4ZNKE4NFIzI68p?usp=sharing', '2025-05-12 20:43:04'),
(5, 1, 'Screenshot 6_9_2022 2_29_33 PM.png', 'file', '../uploads/68238eb571ab3_Screenshot 6_9_2022 2_29_33 PM.png', '2025-05-13 18:25:57'),
(6, 9, 'Screenshot 6_9_2022 2_29_28 PM.png', 'file', '../uploads/682400e5a262d_Screenshot 6_9_2022 2_29_28 PM.png', '2025-05-14 02:33:09'),
(8, 9, '11zon_compressed.zip', 'file', '../uploads/68240127128c1_11zon_compressed.zip', '2025-05-14 02:34:15'),
(9, 9, 'manageEase', 'url', 'https://drive.google.com/drive/folders/1K9s_8sF20kCJ3dnCyJ4ZNKE4NFIzI68p?usp=drive_link', '2025-05-14 02:35:26'),
(10, 13, 'mangeEase', 'url', 'https://drive.google.com/drive/folders/1K9s_8sF20kCJ3dnCyJ4ZNKE4NFIzI68p?usp=drive_link', '2025-05-14 08:46:07'),
(11, 14, 'power (2).pptx', 'file', '../uploads/68262de4c0d67_power (2).pptx', '2025-05-15 18:09:40'),
(12, 14, 'my email', 'url', 'nadeen66h@gmail.com', '2025-05-15 18:11:36'),
(13, 16, 'Secure login-rafiki.png', 'file', '../uploads/682a30c88d87c_Secure login-rafiki.png', '2025-05-18 19:11:04'),
(14, 16, 'color web', 'url', 'https://www.color-hex.com/color/a8a6a5', '2025-05-18 19:12:36'),
(15, 18, 'resetPass.jpg', 'file', '../uploads/682a412a28e72_resetPass.jpg', '2025-05-18 20:20:58'),
(16, 18, 'Ø¯Ù‡Ø§Ù†', 'url', 'https://chat.deepseek.com/a/chat/s/2ac4d88d-580d-4b25-ab60-fb4d4a4a4fc5', '2025-05-18 20:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `card_checklists`
--

DROP TABLE IF EXISTS `card_checklists`;
CREATE TABLE IF NOT EXISTS `card_checklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `progress` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `card_checklists`
--

INSERT INTO `card_checklists` (`id`, `card_id`, `title`, `progress`) VALUES
(2, 1, 'second checklist \"2\"', 67),
(3, 8, 'test checklist', 50),
(4, 9, 'checklist 1', 50),
(5, 13, 'Ø§header', 50),
(6, 14, 'meet teem', 0),
(10, 3, 'rows', 100),
(8, 3, 'colums', 0),
(14, 16, 'header', 0),
(13, 1, 'for testing', 0),
(15, 18, 'Ø§Ù„Ù…ÙˆØ§Ø¯', 0);

-- --------------------------------------------------------

--
-- Table structure for table `card_checklist_items`
--

DROP TABLE IF EXISTS `card_checklist_items`;
CREATE TABLE IF NOT EXISTS `card_checklist_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checklist_id` int(11) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `is_checked` tinyint(1) DEFAULT '0',
  `position` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `checklist_id` (`checklist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `card_checklist_items`
--

INSERT INTO `card_checklist_items` (`id`, `checklist_id`, `content`, `is_checked`, `position`) VALUES
(1, 3, 'test item', 1, 0),
(2, 3, 'test item 2', 0, 0),
(7, 4, 'item 11.1', 1, 0),
(4, 2, 'item 1.22', 1, 0),
(5, 2, 'item 1.3', 0, 0),
(6, 2, 'item.14', 1, 0),
(8, 4, 'item12.1', 0, 0),
(9, 10, 'firts', 1, 0),
(10, 5, 'links', 0, 0),
(11, 5, 'logo', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `card_comments`
--

DROP TABLE IF EXISTS `card_comments`;
CREATE TABLE IF NOT EXISTS `card_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `card_comments`
--

INSERT INTO `card_comments` (`id`, `card_id`, `user_id`, `content`, `parent_id`, `created_at`) VALUES
(1, 2, 1, 'kk', NULL, '2025-05-15 16:55:12'),
(2, 2, 1, 'hi', NULL, '2025-05-15 17:21:02'),
(4, 14, 1, 'call me when you finish', NULL, '2025-05-15 18:08:09'),
(5, 3, 1, 'hi', NULL, '2025-05-15 18:52:36'),
(6, 1, 1, 'the best', NULL, '2025-05-15 19:41:02'),
(7, 16, 1, 'check pic', NULL, '2025-05-18 19:11:38'),
(8, 18, 1, 'Ø§Ø¹Ù…Ù„ Ø²ÙŠ Ù‡Ø§ÙŠ Ø§Ù„ØµÙˆØ±Ø©', NULL, '2025-05-18 20:21:12');

-- --------------------------------------------------------

--
-- Table structure for table `card_details`
--

DROP TABLE IF EXISTS `card_details`;
CREATE TABLE IF NOT EXISTS `card_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `description` text,
  `status` enum('To Do','In Progress','Review','Done') DEFAULT 'To Do',
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `due_reminder` enum('None','10 minutes before','1 hour before','1 day before','2 days before') DEFAULT 'None',
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `card_details`
--

INSERT INTO `card_details` (`id`, `card_id`, `description`, `status`, `start_date`, `due_date`, `due_reminder`, `completed_at`) VALUES
(1, 1, 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sunt ducimus saepe neque laborum! Aut cum a corporis repellat laborum quod.', 'Done', '2025-05-11', '2025-05-22', '1 day before', '2025-05-09 01:08:50'),
(2, 6, 'for all', 'To Do', NULL, NULL, 'None', NULL),
(3, 2, NULL, 'To Do', '2025-05-12', '2025-05-13', 'None', NULL),
(4, 9, 'Description Description Description', 'Done', '2025-05-16', '2025-05-23', '1 day before', '2025-05-16 00:41:25'),
(5, 13, NULL, 'Done', '2025-05-16', '2025-06-09', '1 day before', '2025-05-17 14:25:39'),
(6, 14, 'using only questioner  method to collect all data', 'In Progress', '2025-05-16', '2025-05-31', '10 minutes before', NULL),
(7, 10, NULL, 'Done', NULL, NULL, 'None', '2025-05-16 00:41:32'),
(8, 12, NULL, 'Done', NULL, NULL, 'None', '2025-05-16 00:42:10'),
(9, 11, NULL, 'In Progress', NULL, NULL, 'None', NULL),
(10, 15, NULL, 'Done', '2025-05-28', '2025-05-31', '10 minutes before', '2025-05-18 22:53:09'),
(11, 7, NULL, 'In Progress', NULL, NULL, 'None', NULL),
(12, 16, NULL, 'Done', '2025-05-29', '2025-05-31', '1 day before', '2025-05-18 22:08:34'),
(13, 17, NULL, 'Done', NULL, NULL, 'None', NULL),
(14, 18, NULL, 'Done', '2025-05-18', '2025-05-23', '1 day before', '2025-05-18 23:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `card_members`
--

DROP TABLE IF EXISTS `card_members`;
CREATE TABLE IF NOT EXISTS `card_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `card_members`
--

INSERT INTO `card_members` (`id`, `card_id`, `username`) VALUES
(19, 4, 'majed ahmad'),
(35, 1, 'mohannad mustafa'),
(34, 1, 'majed ahmad'),
(33, 1, 'nadeen haswah'),
(20, 4, 'sameer ahmad'),
(21, 9, 'randa almansi'),
(22, 9, 'rama haswah'),
(29, 13, 'randa almansi'),
(28, 13, 'leen naser'),
(25, 14, 'leen naser'),
(26, 14, 'rama haswah'),
(30, 13, 'roro roro'),
(31, 11, 'rama haswah'),
(32, 11, 'roro roro'),
(36, 1, 'roro roro'),
(37, 6, 'nadeen haswah'),
(38, 6, 'roro roro'),
(39, 7, 'roro roro'),
(40, 16, 'randa almansi'),
(41, 16, 'roro roro'),
(43, 15, 'farah saif'),
(44, 15, 'saif jamal'),
(45, 18, 'randa almansi'),
(46, 21, 'randa almansi'),
(47, 21, 'roro roro');

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE IF NOT EXISTS `lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lists`
--

INSERT INTO `lists` (`id`, `project_id`, `name`, `created_at`) VALUES
(1, 2, 'front-end', '2025-05-08 01:50:25'),
(2, 2, 'back-end', '2025-05-08 01:50:33'),
(3, 2, 'ux-ui', '2025-05-08 01:50:40'),
(4, 2, 'QA', '2025-05-08 01:50:44'),
(5, 2, 'posting', '2025-05-08 15:49:31'),
(6, 1, 'test', '2025-05-08 22:09:36'),
(12, 9, 'front-end pages', '2025-05-18 22:03:34'),
(8, 7, 'list 2', '2025-05-14 05:25:05'),
(9, 7, 'front', '2025-05-14 11:36:18'),
(10, 8, 'collect data', '2025-05-15 21:04:17'),
(11, 8, 'analysis', '2025-05-15 21:04:39'),
(13, 9, 'back-end pages', '2025-05-18 22:03:52'),
(14, 9, 'QA', '2025-05-18 22:03:57'),
(15, 10, 'Ø§Ù„Ø·Ø§Ø¨Ù‚ Ø§Ù„Ø§ÙˆÙ„', '2025-05-18 23:16:39'),
(16, 10, 'Ø§Ù„Ø·Ø§Ø¨Ù‚ Ø§Ù„ØªØ§Ù†ÙŠ', '2025-05-18 23:16:48'),
(17, 7, 'printing', '2025-05-19 00:44:30'),
(18, 7, 'summary', '2025-05-19 00:44:59');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` text,
  `content` text,
  `color` varchar(7) DEFAULT NULL,
  `position_x` int(11) DEFAULT NULL,
  `position_y` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `triggered_by` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `type` enum('member_added','task_assigned','task_status_changed','attachment_uploaded','project_status_changed','task_comment','deadline_reminder','task_submitted') NOT NULL,
  `message` text,
  `related_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `task_id` (`task_id`),
  KEY `triggered_by` (`triggered_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `visibility` enum('Private','Public') NOT NULL DEFAULT 'Private',
  `description` text,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Not Started','In Progress','Completed') NOT NULL DEFAULT 'Not Started',
  `project_managers` text NOT NULL,
  `members` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `start_date`, `end_date`, `visibility`, `description`, `created_by`, `created_at`, `status`, `project_managers`, `members`) VALUES
(1, 'Algo', '2025-04-23', '2025-05-23', 'Public', 'test test test ', 'nadeen haswah', '2025-04-23 14:55:02', 'Not Started', 'nadeen haswah', 'mohammad nabeel,leen naser,roro roro'),
(2, 'first project', '2025-04-23', '2025-05-23', 'Private', 'test test test', 'nadeen haswah', '2025-04-23 15:01:30', 'Not Started', 'nadeen haswah', 'nadeen haswah,test test,leen naser,mohannad mustafa,yasmeena abu alziat,nadeen haswah,majed ahmad,mohannad mustafa,yasmeena abu alziat,rama haswah,sameer ahmad,roro roro'),
(3, 'project 1', '2025-04-24', '2025-05-24', 'Private', 'any thing', 'nadeen haswah', '2025-04-24 13:02:17', 'Not Started', 'nadeen haswah', 'leen naser,yasmeena abu alziat,randa almansi'),
(4, 'project 2', '2025-04-25', '2025-05-10', 'Private', ' There is no description  <br>', 'nadeen haswah', '2025-04-25 14:43:38', 'Not Started', 'nadeen haswah', 'majed ahmad,mohannad mustafa,randa almansi'),
(5, 'project 3', '2025-04-24', '2025-04-26', 'Public', 'no desc.', 'nadeen haswah', '2025-04-25 14:44:16', 'Not Started', 'randa almansi', 'nadeen haswah,leen naser,mohannad mustafa,rama haswah'),
(6, 'project 4', '2025-04-26', '2025-05-23', 'Private', ' There is no description  <br>', 'nadeen haswah', '2025-04-25 14:44:48', 'Not Started', 'nadeen haswah', 'nadeen haswah,leen naser,randa almansi'),
(7, 'new update', '2025-05-15', '2025-06-15', 'Public', 'no thing', 'nadeen haswah', '2025-05-14 02:24:19', 'In Progress', 'nadeen haswah', 'mohammad nabeel,leen naser,majed ahmad,randa almansi,rama haswah,roro roro'),
(8, 'main project', '2025-05-15', '2025-06-15', 'Private', 'this is the most important project', 'nadeen haswah', '2025-05-15 18:03:10', 'In Progress', 'nadeen haswah', 'leen naser,majed ahmad,randa almansi,rama haswah,farah saif,saif jamal'),
(9, 'company website', '2025-05-29', '2025-06-29', 'Public', 'company website', 'nadeen haswah', '2025-05-18 19:01:29', 'Completed', 'nadeen haswah', 'randa almansi,rama haswah,roro roro');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `job` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('project_manager','team_member','special_member') NOT NULL,
  `search_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `permissions` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `added_by` (`added_by`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `job`, `phone`, `password`, `profile_picture`, `role`, `search_enabled`, `status`, `added_by`, `created_at`, `permissions`) VALUES
(1, 'nadeen haswah', 'nadeen66h@gmail.com', 'programmer', '799342463', '$2y$10$8Vyf/ZQc2nBTKST3rI9/seSzbpQ2FSutWIn0lEZ2iZrZhHgzMBFlW', '../uploads/1745419774_pexels-a-darmel-8133893.jpg', 'project_manager', 1, 'active', NULL, '2025-04-23 14:48:31', NULL),
(2, 'test test', 'test@gmail.com', 'student', '793147774', '$2y$10$sgQYKd/8cV7J0GgJERoDj.XFCpOGK5S0mA/n.t8/fPniBvSBiSdOS', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-04-23 14:50:16', NULL),
(3, 'mohammad nabeel', 'mohammad_nabeel@gmail.com', 'teacher', '793140098', '$2y$10$k7Y/YuDtoFyxjU/Lp/PxT.5QwvaFU7xHvPRk55aHkY3BtJTKa1xZa', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-04-23 14:51:33', NULL),
(4, 'leen naser', 'leen_naser@gmail.com', 'no job', '793147774', '$2y$10$ydIkfY6I4Gc/f.6b5gN/VuyRXP3oGXegtNztdumSmTp4f.PdOWhTO', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-04-23 14:54:21', NULL),
(5, 'majed ahmad', 'majed_ajmad@gmail.com', 'back-end developer', '799342463', '$2y$10$mt4gqdZhZwd9bOAbaP9plOS4oqIGYXZmbQdcpB1DopARqbP88E8.W', '../asset/images/profilePic.png', 'special_member', 1, 'active', 1, '2025-04-23 14:58:15', NULL),
(6, 'mohannad mustafa', 'mohannad_mustafa@gmail.com', 'math teacher', '793147009', '$2y$10$2BP5JM5p0hDr09SVqI3pL.SHJbavYqj.o9l9T..wcLRJ6ddablZkO', '../asset/images/profilePic.png', 'special_member', 1, 'active', 1, '2025-04-23 14:59:15', 'Add Tasks'),
(7, 'yasmeena abu alziat', 'yasmeenaabuazeit@gmail.con', 'QA', '793147774', '$2y$10$GMSnXEyBMOCtRXiUA3A3seIC0Tm8TNRbhwM15J70d6KR.GBBlkaqa', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-04-23 15:00:21', NULL),
(8, 'randa almansi', 'randaalmansi@gmail.com', 'cyber security', '799342463', '$2y$10$/YI609QcVF6eKipmVRK.i.Q36J/UAYmyIT4VEKnUksx.eS9s.cOT.', '../uploads/1745434806_portrait-female-lawyer-formal-suit-with-clipboard.jpg', 'team_member', 0, 'active', 1, '2025-04-23 18:56:39', NULL),
(9, 'lojy ameen', 'lojy@gmail.com', 'java developer', '799342463', '$2y$10$WvXBvQ1HFbIJIcx/HMRzCOIF5y29Yn5yBgLfjq7hmVB1EvWLb7eiu', '../asset/images/profilePic.png', 'team_member', 0, 'active', 8, '2025-04-23 20:19:31', NULL),
(10, 'rama haswah', 'ramahaswah@gmail.com', 'student', '799342463', '$2y$10$gjdwRRoTjG1C5rPOV20tp.UFCGp.8EclrnWVzqLbZB3eK.mA/5JpO', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-04-24 13:30:55', NULL),
(11, 'ameer raed', 'ameer_r@gmail.com', 'student', '799342463', '$2y$10$yysISFJXcGqBfuse3acgveZ2tHGneIeIVHm9ivw5B7gbfCpQRm7iq', '../asset/images/profilePic.png', 'special_member', 1, 'active', 1, '2025-04-27 03:55:32', 'Add Projects,Watch Reports'),
(12, 'sameer ahmad', 'sameer@gmail.com', 'QA', '799342463', '$2y$10$pJaSlr8Q7VBRIYDKumJps.Vg0qRmICWGOf.j6qFYH5Ap2VykdhQvu', '../asset/images/profilePic.png', 'project_manager', 1, 'active', NULL, '2025-04-29 21:33:18', NULL),
(13, 'roro roro', 'roro@gmail.com', 'no job', '799342463', '$2y$10$uFwN8vbsHySOcgpAXmoW5u8BOIBfxD7GE2WJcJHdIdXhvfW66aEKC', '../asset/images/profilePic.png', 'special_member', 1, 'active', 1, '2025-05-17 11:43:26', 'Watch Reports'),
(14, 'maram mohammad', 'maram@gmail.com', 'QA', '799342463', '$2y$10$ELjfEAwjf0H1aYN7wzyw../sb9FOw8vtCQZj0LRjvGqW/RSRJgY9.', '../asset/images/profilePic.png', 'special_member', 1, 'active', 1, '2025-05-18 18:59:23', NULL),
(15, 'farah saif', 'farah_saif@gmail.com', 'student', '799342463', '$2y$10$.wRYOoHXOSgp3M2QGZ8F3eYGCU8AlMgk1ZwRYeYEsJLpWc.AoQfRC', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-05-18 19:49:30', NULL),
(16, 'saif jamal', 'saif_jamal@gmail.com', 'QA', '799342463', '$2y$10$lF5s3IkiTUPrzcrqpQBzf.BybJdWmsXLn1lAjKBoexSrGCosh7Y5u', '../asset/images/profilePic.png', 'team_member', 1, 'active', 1, '2025-05-18 20:00:03', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
