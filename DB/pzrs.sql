-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 28. bře 2016, 23:47
-- Verze serveru: 5.6.26
-- Verze PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `pzrs`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_enemy`
--

CREATE TABLE IF NOT EXISTS `pzrs_enemy` (
  `id` int(11) NOT NULL,
  `teamA` text COLLATE utf8_unicode_ci NOT NULL,
  `teamALogo` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_enemy`
--

INSERT INTO `pzrs_enemy` (`id`, `teamA`, `teamALogo`, `created_at`, `active`) VALUES
(8, 'eTuba', 'upload/enemy\\hydrangeas.jpg', '0000-00-00 00:00:00', 0),
(9, 'Penguinsasdasd', 'upload/enemy\\lighthouse.jpg', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_events`
--

CREATE TABLE IF NOT EXISTS `pzrs_events` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `teaser` text COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `img` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_gallery`
--

CREATE TABLE IF NOT EXISTS `pzrs_gallery` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `private` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_images`
--

CREATE TABLE IF NOT EXISTS `pzrs_images` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `galleryid` int(100) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_mapy`
--

CREATE TABLE IF NOT EXISTS `pzrs_mapy` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_mapy`
--

INSERT INTO `pzrs_mapy` (`id`, `name`, `info`, `img`, `active`) VALUES
(1, 'Dust2', 'Pisky pisky pisky', 'upload/maps/dust2.jpg', 1),
(2, 'Inferno', 'Pisky pisky pisky', 'upload/maps/inferno.jpg', 1),
(3, 'Train', 'Pisky pisky pisky', 'upload/maps/train.jpg', 1),
(4, 'Nuke', 'Pisky pisky pisky', 'upload/maps/nuke.jpg', 1),
(5, 'Overpass', 'Pisky pisky pisky', 'upload/maps/over.jpg', 1),
(6, 'Cache', 'Pisky pisky pisky', 'upload/maps/cache.jpg', 1),
(7, 'Mirage', 'Pisky pisky pisky', 'upload/maps/mirage.jpg', 1),
(8, 'Cobblestone', 'Pisky pisky pisky', 'upload/maps/coble.jpg', 1),
(9, 'Season', 'Pisky pisky pisky', 'upload/maps/season.jpg', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_matches`
--

CREATE TABLE IF NOT EXISTS `pzrs_matches` (
  `id` int(11) NOT NULL,
  `teamA` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `teamB` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `resultA` int(11) NOT NULL,
  `resultB` int(11) NOT NULL,
  `teamALogo` text COLLATE utf8_unicode_ci NOT NULL,
  `teamBLogo` text COLLATE utf8_unicode_ci NOT NULL,
  `map1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map2` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map3` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `resultAMap1` int(11) NOT NULL,
  `resultAMap2` int(11) NOT NULL,
  `resultAMap3` int(11) NOT NULL,
  `resultBMap1` int(11) NOT NULL,
  `resultBMap2` int(11) NOT NULL,
  `resultBMap3` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `edited_at` timestamp NULL DEFAULT NULL,
  `canBeEdited` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_matches`
--

INSERT INTO `pzrs_matches` (`id`, `teamA`, `teamB`, `resultA`, `resultB`, `teamALogo`, `teamBLogo`, `map1`, `map2`, `map3`, `resultAMap1`, `resultAMap2`, `resultAMap3`, `resultBMap1`, `resultBMap2`, `resultBMap3`, `active`, `created_at`, `edited_at`, `canBeEdited`) VALUES
(51, 'ghjghjghj', 'eTuba', 0, 0, 'upload/myTeams/penguins.jpg', 'upload/enemy\\hydrangeas.jpg', '', '', '', 0, 0, 0, 0, 0, 0, 0, '2016-03-27 16:00:51', '2016-03-27 16:51:40', 1),
(54, 'Koaly', 'eTuba', 0, 0, 'upload/myTeams/koala.jpg', 'upload/enemy\\hydrangeas.jpg', '', '', '', 0, 0, 0, 0, 0, 0, 0, '2016-03-27 16:00:59', '2016-03-27 16:51:34', 1),
(56, 'ghjghjghj', 'Penguinsasdasd', 0, 0, 'upload/myTeams/penguins.jpg', 'upload/enemy\\lighthouse.jpg', '', '', '', 0, 0, 0, 0, 0, 0, 0, '2016-03-27 16:47:03', NULL, 1),
(59, 'Koaly', 'asd', 0, 0, 'upload/myTeams/koala.jpg', 'upload/enemy/chrysanthemum.jpg', '', '', '', 0, 0, 0, 0, 0, 0, 0, '2016-03-28 16:07:29', NULL, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_posts`
--

CREATE TABLE IF NOT EXISTS `pzrs_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `teaser` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `img` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `edited_at` timestamp NULL DEFAULT NULL,
  `autor` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `section` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `slug` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `res1` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_posts`
--

INSERT INTO `pzrs_posts` (`id`, `title`, `teaser`, `body`, `img`, `created_at`, `edited_at`, `autor`, `section`, `slug`, `active`, `res1`) VALUES
(1, 'asda', 'dasd', '<p>asdas</p>', 'upload/posts\\lighthouse.jpg', '2016-03-26 19:17:05', NULL, '', '', 'asda', 1, 0),
(2, 'asd', 'asd', '<p>asdsad</p>', 'upload/posts\\tulips.jpg', '2016-03-26 21:55:20', NULL, '', '', 'asd', 1, 0),
(3, 'dasdasd', 'das', '<p>asdas</p>', 'upload/posts/chrysanthemum.jpg', '2016-03-27 13:32:51', NULL, '', '', 'dasdasd', 1, 0),
(4, 'sadasd', 'asd', '<p>asdsad</p>', 'upload/posts\\hydrangeas.jpg', '2016-03-27 13:33:01', '2016-03-27 17:00:09', '', '', '0', 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_settings`
--

CREATE TABLE IF NOT EXISTS `pzrs_settings` (
  `id` int(11) NOT NULL,
  `fb_app_secret` text COLLATE utf8_unicode_ci NOT NULL,
  `fb_access_token` longtext COLLATE utf8_unicode_ci NOT NULL,
  `fb_page_id` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `res` int(11) NOT NULL,
  `res2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_teams`
--

CREATE TABLE IF NOT EXISTS `pzrs_teams` (
  `id` int(11) NOT NULL,
  `teamA` text COLLATE utf8_unicode_ci NOT NULL,
  `teamALogo` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_teams`
--

INSERT INTO `pzrs_teams` (`id`, `teamA`, `teamALogo`, `created_at`, `active`) VALUES
(8, 'IronBranch.CSGO', 'upload/myTeams\\desert.jpg', '0000-00-00 00:00:00', 1),
(29, 'Koaly', 'upload/myTeams/koala.jpg', '2016-03-27 15:54:36', 0),
(31, 'ghjghjghj', 'upload/myTeams/penguins.jpg', '2016-03-27 15:59:40', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_users`
--

CREATE TABLE IF NOT EXISTS `pzrs_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_users`
--

INSERT INTO `pzrs_users` (`id`, `username`, `password`, `email`, `ip`, `role`, `active`) VALUES
(1, 'admin', '$2y$10$XOHA22Kqti1rD6I0rVKveu8Z25Qn4hgh5E9pfJAEGSEy8J3l02RHi', '', '', 0, 0);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `pzrs_enemy`
--
ALTER TABLE `pzrs_enemy`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_events`
--
ALTER TABLE `pzrs_events`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_gallery`
--
ALTER TABLE `pzrs_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_images`
--
ALTER TABLE `pzrs_images`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_mapy`
--
ALTER TABLE `pzrs_mapy`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_matches`
--
ALTER TABLE `pzrs_matches`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_posts`
--
ALTER TABLE `pzrs_posts`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_settings`
--
ALTER TABLE `pzrs_settings`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_teams`
--
ALTER TABLE `pzrs_teams`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pzrs_users`
--
ALTER TABLE `pzrs_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `pzrs_enemy`
--
ALTER TABLE `pzrs_enemy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT pro tabulku `pzrs_events`
--
ALTER TABLE `pzrs_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `pzrs_gallery`
--
ALTER TABLE `pzrs_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `pzrs_images`
--
ALTER TABLE `pzrs_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `pzrs_mapy`
--
ALTER TABLE `pzrs_mapy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pro tabulku `pzrs_matches`
--
ALTER TABLE `pzrs_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT pro tabulku `pzrs_posts`
--
ALTER TABLE `pzrs_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `pzrs_settings`
--
ALTER TABLE `pzrs_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `pzrs_teams`
--
ALTER TABLE `pzrs_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pro tabulku `pzrs_users`
--
ALTER TABLE `pzrs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
