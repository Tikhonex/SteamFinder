-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 19 2015 г., 12:02
-- Версия сервера: 5.5.40
-- Версия PHP: 5.3.10-1ubuntu3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tikhonex`
--

-- --------------------------------------------------------

--
-- Структура таблицы `steamidfinder`
--

CREATE TABLE IF NOT EXISTS `steamidfinder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamid64` text COLLATE utf8_unicode_ci,
  `status` int(11) DEFAULT NULL COMMENT '1 - Green / 2 - WhiteList / 3 - Blacklisted',
  `message` text COLLATE utf8_unicode_ci,
  `remark` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `steamidfinder`
--

INSERT INTO `steamidfinder` (`id`, `steamid64`, `status`, `message`, `remark`) VALUES
(1, '76561197990231526', 1, 'Developer', 'i''m'),
(2, '76561197997600622', 2, '', 'pyroman'),
(4, '76561197991528416', 2, '', 'lukarika'),
(5, '76561198030929264', 2, '', 'rassl007'),
(6, '76561198009397857', 3, 'http://forum.csmania.ru/viewtopic.php?f=14&t=29158&start=4215#p1112397', 'rebound'),
(7, '76561198029291468', 2, '', 'Mentalist');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
