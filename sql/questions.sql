-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Авг 02 2018 г., 07:58
-- Версия сервера: 8.0.11
-- Версия PHP: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `qanda`
--

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` tinyint(4) NOT NULL,
  `question` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date_added` datetime NOT NULL,
  `creator` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `is_answered` tinyint(4) NOT NULL DEFAULT '0',
  `is_up` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `question`, `category`, `date_added`, `creator`, `email`, `is_answered`, `is_up`) VALUES
(1, 'Что вы скажете насчет проклятый деньги?', 'Finance', '2018-06-28 20:10:23', '0', 'test@test.test', 1, 1),
(2, 'Life on Mars?', 'Finance', '2018-07-19 12:14:33', 'David', 'test@test.test', 1, 1),
(3, 'Главный вопрос жизни, вселенной и всего такого', 'Philosophy', '2018-07-19 06:08:33', 'Мыши', 'test@test.test', 1, 1),
(4, 'это измененный текст вопроса', 'General knowledge', '2018-07-17 09:06:20', 'автор', 'test@test.test', 1, 1),
(5, 'What\'s in the box?!', 'General knowledge', '2018-07-21 00:00:00', 'Mills', 'test@test.test', 1, 1),
(6, 'Тестовый вопрос', 'Finance', '2018-07-17 00:00:00', 'test', 'test@test.test', 0, 0),
(7, 'В чем смысл прихода бодисатвы на восток?', 'Philosophy', '2018-07-23 20:41:42', 'Учитель', 'test@test.test', 1, 1),
(8, 'Что нам делать с пьяным матросом', 'Travel', '2018-07-26 02:03:39', 'БГ', 'test@test.test', 0, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
