-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Време на генериране: 
-- Версия на сървъра: 5.6.14
-- Версия на PHP: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `rps`
--

-- --------------------------------------------------------

--
-- Структура на таблица `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Схема на данните от таблица `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(2, 'Дупки'),
(3, 'Тротоари'),
(4, 'Неправилно паркиране'),
(5, 'Улични знаци'),
(6, 'Боклуци'),
(7, 'Труп на животно'),
(8, 'Улично осветление'),
(9, 'Опасни сгради'),
(10, 'Графити'),
(11, 'Други');

-- --------------------------------------------------------

--
-- Структура на таблица `confirmation`
--

CREATE TABLE IF NOT EXISTS `confirmation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `likes` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `problem_id` (`problem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Схема на данните от таблица `confirmation`
--

INSERT INTO `confirmation` (`id`, `problem_id`, `ip`, `likes`) VALUES
(30, 3, '191.100.100.100', 0),
(31, 3, '191.100.50.100', 1),
(36, 3, '::1', 0);

-- --------------------------------------------------------

--
-- Структура на таблица `problems`
--

CREATE TABLE IF NOT EXISTS `problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `gsm` varchar(13) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `imagelink` varchar(255) NOT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Схема на данните от таблица `problems`
--

INSERT INTO `problems` (`id`, `cat_id`, `description`, `author`, `email`, `gsm`, `latitude`, `longitude`, `imagelink`, `date`, `address`) VALUES
(1, 3, 'dqdqdq', '', '', '', 43.8495786, 25.955229199999962, 'img/users/contact.png', '2015-05-30 00:28:30', 'улица „Петко Д. Петков“ 5, 7000 Русе, България'),
(2, 4, 'Има коприва на мястото и пари', '', '', '', 43.8319895, 25.972848800000065, '', '2015-05-30 00:51:08', 'булевард „Васил Левски“ 15, 7015 Русе, България');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
