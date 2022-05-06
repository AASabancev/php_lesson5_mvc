-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 06 2022 г., 19:18
-- Версия сервера: 10.5.11-MariaDB
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lesson4_mvc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `text`, `image`, `created_at`, `updated_at`) VALUES
(6, 180, 'dfgdfg', NULL, '2022-04-24 11:07:42', '2022-04-24 11:07:42'),
(7, 180, '123123', NULL, '2022-04-24 11:08:30', '2022-04-24 11:08:30'),
(9, 180, 'ываыва', '/files/uploads/lIXxlMRTliNewmL.jpg', '2022-04-24 12:22:55', '2022-04-24 12:22:55'),
(10, 180, 'hello', '/files/uploads/xouxW9iQEb8QdXy.jpg', '2022-04-24 12:48:42', '2022-04-24 12:48:42'),
(11, 180, 'test', '/files/uploads/sYTTPLrnfREqWEK.jpg', '2022-04-25 10:53:11', '2022-04-25 10:53:11'),
(12, 180, 'hello', '/files/uploads/ex8pcTsW8gDz2Z6.jpg', '2022-04-25 10:55:41', '2022-04-25 10:55:41'),
(13, 180, 'sdf', '/files/uploads/5YPzWduqADyz48A.jpg', '2022-04-25 10:56:19', '2022-04-25 10:56:19'),
(14, 180, 'sdf', '/files/uploads/eTYBHqK5pFN0gtI.jpg', '2022-04-25 10:56:35', '2022-04-25 10:56:35'),
(21, 183, 'sdfsdf', NULL, '2022-05-06 13:58:17', '2022-05-06 13:58:17'),
(22, 183, 'dfg', '/files/uploads/wqjrjX5XlhEKnXn.jpg', '2022-05-06 13:59:55', '2022-05-06 13:59:55'),
(23, 183, 'ыва', NULL, '2022-05-06 14:00:51', '2022-05-06 14:00:51');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `fio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `image`, `role_id`, `fio`, `login`, `password`, `created_at`, `updated_at`) VALUES
(173, '/files/uploads/ALRsjrBLPE32WSL.jpg', 2, 'Artem', 'art1', 'c6ac7acb4cea28630efb29fd6a0ab51f31c6b32e', '2022-04-22 13:23:30', '2022-05-06 13:57:52'),
(180, '/files/uploads/5YPzWduqADyz48A.jpg', 1, 'art1', 'art2', 'b9fe41bdfaf46a7a65706a613e93df0754037667', '2022-04-22 14:22:24', '2022-04-22 14:22:24'),
(183, NULL, 1, 'artem', 'aasabancev@mail.ru', 'b9fe41bdfaf46a7a65706a613e93df0754037667', '2022-05-06 13:00:03', '2022-05-06 14:18:46'),
(184, '/files/uploads/tNfXaI5i6EPK1qB.jpg', 2, 'Тест', '12333@mail.ru', 'b9fe41bdfaf46a7a65706a613e93df0754037667', '2022-05-06 14:05:50', '2022-05-06 14:05:50');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login` (`text`(768));

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
