-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-11-19 07:15:18
-- 伺服器版本： 10.11.16-MariaDB
-- PHP 版本： 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `submission_system`
--

-- --------------------------------------------------------

--
-- 資料表結構 `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `submissions`
--

INSERT INTO `submissions` (`id`, `author_id`, `category`, `title`, `content`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, '研究論文', '試試', '123', 'approved', '2026-11-18 02:03:53', '2026-11-18 10:29:52'),
(3, 1, '研究論文', '試', '1234', 'draft', '2026-11-18 10:31:50', '2026-11-18 10:34:31'),
(5, 4, '研究論文', '123', '123456', 'draft', '2026-11-18 18:04:35', '2026-11-18 18:11:52'),
(6, 6, '技術報告', '456', '456', 'approved', '2026-11-18 22:19:34', '2026-11-18 22:20:15'),
(7, 7, '研究論文', '123456', '123', 'approved', '2026-11-19 10:32:57', '2026-11-19 10:34:34');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'author1', '$2y$12$Z.Dd0EK4mZswbtfmYwPbHuoUSeqjPtk.WPNkbglj3ECMZF/HoVhKm', 'author'),
(2, 'editor1', '$2y$12$voTxtr0d/MPYrlR/JEN80O/WEsoLNoeBTcZcV1Ac/oUkUTQg5q/ui', 'editor'),
(4, '111', '$2y$12$rgtDjazCybMW17ikdOd.kerZWl31006t3UXPadaqlhxRhuDODUYmO', 'author'),
(5, '1111', '$2y$12$Z2b//I35ZzA9XFO6u/T4RO5HPtXCj3pno40frVBnm/2Kz6JZXyn.q', 'editor'),
(6, '222', '$2y$12$sZ8J8UG1ajRuwYI2uFH8..LIBQmjABUO3xi41wAGtGx4H/kTHvFdi', 'author'),
(7, '333', '$2y$12$bgp.mTXHXwvjLHgQlXFuZ.xl3btBOb3t7FDBmLyMACeSarkCEuOpC', 'author');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_submissions_author` (`author_id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `fk_submissions_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
