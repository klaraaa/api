-- Adminer 4.2.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `qlokare_grades`;
CREATE DATABASE `qlokare_grades` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `qlokare_grades`;

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `courses` (`id`, `name`) VALUES
(1,	'HTML'),
(2,	'CSS');

DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `course_id` int(10) unsigned NOT NULL,
  `grade` enum('MVG','VG','G','IG') COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `grades` (`id`, `student_id`, `course_id`, `grade`) VALUES
(35,	2,	1,	'VG'),
(36,	1,	2,	'IG'),
(50,	3,	2,	'IG'),
(52,	3,	2,	'IG');

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `students` (`id`, `firstname`, `lastname`) VALUES
(1,	'Klara',	'Köllerström'),
(2,	'Sandra',	'Bothen');

-- 2016-03-11 11:51:48
