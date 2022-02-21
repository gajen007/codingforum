-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2022 at 11:44 AM
-- Server version: 5.7.36-cll-lve
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thushyho_tamilcoders`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answerid` int(11) NOT NULL,
  `answerText` longtext NOT NULL,
  `questionid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ACTIVE',
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `answersFollowers`
--

CREATE TABLE `answersFollowers` (
  `relationid` int(11) NOT NULL,
  `answerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `badwords`
--

CREATE TABLE `badwords` (
  `badwordid` int(11) NOT NULL,
  `badWord` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentid` int(11) NOT NULL,
  `answerid` longtext NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `relationid` int(11) NOT NULL,
  `answerid` longtext NOT NULL,
  `userid` int(11) NOT NULL,
  `feedback` varchar(255) NOT NULL,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `headings`
--

CREATE TABLE `headings` (
  `headingid` int(11) NOT NULL,
  `headingText` longtext NOT NULL,
  `addedUserID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `headingsFollowers`
--

CREATE TABLE `headingsFollowers` (
  `relationid` int(11) NOT NULL,
  `headingid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationid` int(11) NOT NULL,
  `notificationtext` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `targettype` varchar(255) DEFAULT NULL,
  `targetid` int(11) DEFAULT '0',
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) NOT NULL DEFAULT 'notseen'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notificationSetting`
--

CREATE TABLE `notificationSetting` (
  `settingid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `sendemail` bit(1) NOT NULL DEFAULT b'0',
  `type1` bit(1) NOT NULL DEFAULT b'0',
  `type2` bit(1) NOT NULL DEFAULT b'0',
  `type3` bit(1) NOT NULL DEFAULT b'0',
  `type4` bit(1) NOT NULL DEFAULT b'0',
  `type5` bit(1) NOT NULL DEFAULT b'0',
  `type6` bit(1) NOT NULL DEFAULT b'0',
  `type7` bit(1) NOT NULL DEFAULT b'0',
  `type8` bit(1) NOT NULL DEFAULT b'0',
  `type9` bit(1) NOT NULL DEFAULT b'0',
  `type10` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questionid` int(11) NOT NULL,
  `questionText` longtext NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ACTIVE',
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `questionsFollowers`
--

CREATE TABLE `questionsFollowers` (
  `relationid` int(11) NOT NULL,
  `questionid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `questionsHeadings`
--

CREATE TABLE `questionsHeadings` (
  `relationid` int(11) NOT NULL,
  `questionid` int(11) NOT NULL,
  `headingid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` longtext NOT NULL,
  `password` longtext NOT NULL,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `firstname` varchar(255) NOT NULL DEFAULT 'Anonymous',
  `lastname` varchar(255) NOT NULL DEFAULT 'User',
  `avatarURL` varchar(64) DEFAULT 'zero.png',
  `aboutme` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usersFollowers`
--

CREATE TABLE `usersFollowers` (
  `relationid` int(11) NOT NULL,
  `starUserid` int(11) NOT NULL,
  `fanUserid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answerid`);

--
-- Indexes for table `answersFollowers`
--
ALTER TABLE `answersFollowers`
  ADD PRIMARY KEY (`relationid`);

--
-- Indexes for table `badwords`
--
ALTER TABLE `badwords`
  ADD PRIMARY KEY (`badwordid`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentid`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`relationid`);

--
-- Indexes for table `headings`
--
ALTER TABLE `headings`
  ADD PRIMARY KEY (`headingid`);

--
-- Indexes for table `headingsFollowers`
--
ALTER TABLE `headingsFollowers`
  ADD PRIMARY KEY (`relationid`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationid`);

--
-- Indexes for table `notificationSetting`
--
ALTER TABLE `notificationSetting`
  ADD PRIMARY KEY (`settingid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionid`);

--
-- Indexes for table `questionsFollowers`
--
ALTER TABLE `questionsFollowers`
  ADD PRIMARY KEY (`relationid`);

--
-- Indexes for table `questionsHeadings`
--
ALTER TABLE `questionsHeadings`
  ADD PRIMARY KEY (`relationid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usersFollowers`
--
ALTER TABLE `usersFollowers`
  ADD PRIMARY KEY (`relationid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answerid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answersFollowers`
--
ALTER TABLE `answersFollowers`
  MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badwords`
--
ALTER TABLE `badwords`
  MODIFY `badwordid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `headings`
--
ALTER TABLE `headings`
  MODIFY `headingid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `headingsFollowers`
--
ALTER TABLE `headingsFollowers`
  MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notificationSetting`
--
ALTER TABLE `notificationSetting`
  MODIFY `settingid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `questionid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questionsFollowers`
--
ALTER TABLE `questionsFollowers`
  MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questionsHeadings`
--
ALTER TABLE `questionsHeadings`
  MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usersFollowers`
--
ALTER TABLE `usersFollowers`
  MODIFY `relationid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
