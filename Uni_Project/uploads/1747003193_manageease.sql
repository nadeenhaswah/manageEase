-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 28, 2025 at 07:04 PM
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
  `project_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `type` enum('member_added','task_completed','project_status_changed','task_submitted','deadline_alert') DEFAULT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  KEY `task_id` (`task_id`)
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `start_date`, `end_date`, `visibility`, `description`, `created_by`, `created_at`, `status`, `project_managers`, `members`) VALUES
(1, 'Algo', '2025-04-23', '2025-05-23', 'Public', 'test test test ', 'nadeen haswah', '2025-04-23 14:55:02', 'Not Started', 'nadeen haswah', 'mohammad nabeel,leen naser'),
(2, 'first project', '2025-04-23', '2025-05-23', 'Private', 'test test test', 'nadeen haswah', '2025-04-23 15:01:30', 'Not Started', 'nadeen haswah', 'nadeen haswah,test test,leen naser,mohannad mustafa,yasmeena abu alziat'),
(3, 'project 1', '2025-04-24', '2025-05-24', 'Private', 'any thing', 'nadeen haswah', '2025-04-24 13:02:17', 'Not Started', 'nadeen haswah', 'leen naser,yasmeena abu alziat,randa almansi'),
(4, 'project 2', '2025-04-25', '2025-05-10', 'Private', ' There is no description  <br>', 'nadeen haswah', '2025-04-25 14:43:38', 'Not Started', 'nadeen haswah', 'majed ahmad,mohannad mustafa,randa almansi'),
(5, 'project 3', '2025-04-24', '2025-04-26', 'Public', 'no desc.', 'nadeen haswah', '2025-04-25 14:44:16', 'Not Started', 'randa almansi', 'nadeen haswah,leen naser,mohannad mustafa,rama haswah'),
(6, 'project 4', '2025-04-26', '2025-05-23', 'Private', ' There is no description  <br>', 'nadeen haswah', '2025-04-25 14:44:48', 'Not Started', 'nadeen haswah', 'nadeen haswah,leen naser,randa almansi');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

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
(11, 'ameer raed', 'ameer_r@gmail.com', 'student', '799342463', '$2y$10$yysISFJXcGqBfuse3acgveZ2tHGneIeIVHm9ivw5B7gbfCpQRm7iq', '../asset/images/profilePic.png', 'special_member', 1, 'active', 1, '2025-04-27 03:55:32', 'Add Projects,Watch Reports');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
