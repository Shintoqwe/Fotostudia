-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 21 2024 г., 21:12
-- Версия сервера: 10.8.4-MariaDB
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `foto_gal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bron`
--

CREATE TABLE `bron` (
  `id` int(11) NOT NULL,
  `name_room` text NOT NULL,
  `seans_1` varchar(255) NOT NULL,
  `seans_2` varchar(255) NOT NULL,
  `seans_3` varchar(255) NOT NULL,
  `seans_4` varchar(255) NOT NULL,
  `seans_5` varchar(255) NOT NULL,
  `seans_6` varchar(255) NOT NULL,
  `seans_7` varchar(255) NOT NULL,
  `seans_8` varchar(255) NOT NULL,
  `seans_9` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `bron`
--

INSERT INTO `bron` (`id`, `name_room`, `seans_1`, `seans_2`, `seans_3`, `seans_4`, `seans_5`, `seans_6`, `seans_7`, `seans_8`, `seans_9`) VALUES
(1, 'Зал с циклорамой', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'),
(2, 'loft', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'),
(3, 'Детский', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00');

-- --------------------------------------------------------

--
-- Структура таблицы `bron_time`
--

CREATE TABLE `bron_time` (
  `id` int(255) NOT NULL,
  `id_room` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `seans_1` int(11) NOT NULL DEFAULT 0,
  `seans_2` int(11) NOT NULL DEFAULT 0,
  `seans_3` int(11) NOT NULL DEFAULT 0,
  `seans_4` int(11) NOT NULL DEFAULT 0,
  `seans_5` int(11) NOT NULL DEFAULT 0,
  `seans_6` int(11) NOT NULL DEFAULT 0,
  `seans_7` int(11) NOT NULL DEFAULT 0,
  `seans_8` int(11) NOT NULL DEFAULT 0,
  `seans_9` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `bron_time`
--

INSERT INTO `bron_time` (`id`, `id_room`, `date`, `seans_1`, `seans_2`, `seans_3`, `seans_4`, `seans_5`, `seans_6`, `seans_7`, `seans_8`, `seans_9`) VALUES
(1, 1, '16.05.2024', 0, 0, 0, 1, 0, 0, 0, 0, 0),
(2, 2, '16.05.2024', 0, 0, 0, 1, 0, 0, 0, 0, 0),
(3, 3, '16.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 1, '20.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 2, '20.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 3, '20.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 1, '19.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 2, '19.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 3, '19.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 1, '21.05.2024', 0, 1, 0, 1, 0, 0, 0, 0, 0),
(11, 2, '21.05.2024', 0, 1, 0, 1, 0, 0, 0, 0, 0),
(12, 3, '21.05.2024', 0, 1, 0, 0, 0, 1, 0, 0, 0),
(13, 1, '22.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 2, '22.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 3, '22.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 1, '23.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 2, '23.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, 3, '23.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, 1, '17.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, 2, '17.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(21, 3, '17.05.2024', 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `like`
--

CREATE TABLE `like` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `img` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `photo`
--

INSERT INTO `photo` (`id`, `users_id`, `img`) VALUES
(37, 11, '6645fb76b04c7.jpeg'),
(38, 11, '66460331025dc.jpeg'),
(39, 11, '664603349fbb6.jpeg'),
(40, 11, '66460338a7372.jpeg'),
(41, 11, '6646033c35c05.avif'),
(42, 11, '66460372b91c5.jpeg'),
(43, 11, '66460383b2fe2.jpeg'),
(44, 11, '664603ceaa761.png'),
(45, 11, '664603e122e71.jpeg'),
(46, 11, '664603e474f08.jpeg'),
(47, 11, '664603e792650.jpeg'),
(48, 11, '664603eb9ace0.jpeg'),
(49, 11, '664603ef66c02.jpeg'),
(50, 11, '664603f47e2de.jpeg'),
(51, 11, '6646042243990.png'),
(77, 11, '66464e60ba816.avif'),
(78, 11, '66464e63d4418.jpeg'),
(79, 11, '66464e66df7e1.jpeg'),
(80, 11, '66464e69f33ba.jpeg'),
(81, 11, '66464e6dda3da.jpeg'),
(82, 11, '66464e71e3145.jpeg'),
(83, 11, '66464ebae1a74.jpeg'),
(84, 11, '66464ebde52b1.jpeg'),
(85, 11, '66464ec144341.jpeg'),
(86, 11, '66464ec57f6a6.jpeg'),
(87, 11, '664b590c3d03f.jpeg');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `status`) VALUES
(11, 'admin', 'qwe@mail.ru', 'c0d57e03d603d202bab2d56f7eb921d2', 'admin'),
(16, '1', '1@1', 'f72c8ba62d93eabd8a90f8fc27455eb5', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `bron`
--
ALTER TABLE `bron`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bron_time`
--
ALTER TABLE `bron_time`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_id` (`photo_id`),
  ADD KEY `users_id` (`users_id`);

--
-- Индексы таблицы `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `bron`
--
ALTER TABLE `bron`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `bron_time`
--
ALTER TABLE `bron_time`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT для таблицы `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
