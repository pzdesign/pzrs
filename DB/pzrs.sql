-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 29. bře 2016, 17:49
-- Verze serveru: 10.1.9-MariaDB
-- Verze PHP: 5.6.15

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

CREATE TABLE `pzrs_enemy` (
  `id` int(11) NOT NULL,
  `teamA` text COLLATE utf8_unicode_ci NOT NULL,
  `teamALogo` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_enemy`
--

INSERT INTO `pzrs_enemy` (`id`, `teamA`, `teamALogo`, `created_at`, `active`) VALUES
(8, 'eTuba', 'upload/enemy\\1400154456053082.jpg', '0000-00-00 00:00:00', 0),
(9, 'Penguinsasdasd', 'upload/enemy\\lighthouse.jpg', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_events`
--

CREATE TABLE `pzrs_events` (
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

CREATE TABLE `pzrs_gallery` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `private` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_images`
--

CREATE TABLE `pzrs_images` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `galleryid` int(100) NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_maps`
--

CREATE TABLE `pzrs_maps` (
  `id` int(11) NOT NULL,
  `mapName` text COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `mapImg` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_maps`
--

INSERT INTO `pzrs_maps` (`id`, `mapName`, `info`, `mapImg`, `active`) VALUES
(23, 'Dust2', '', 'upload/maps/dust2.jpg', 0),
(24, 'Cache', '', 'upload/maps/cache.jpg', 0),
(25, 'Train', '', 'upload/maps/train.jpg', 0),
(26, 'Inferno', '', 'upload/maps/inferno.jpg', 0),
(27, 'Mirage', '', 'upload/maps/mirage.jpg', 0),
(28, 'Cobblestone', '', 'upload/maps/cobblestone.jpg', 0),
(29, 'Overpass', '', 'upload/maps/overpass.png', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_matches`
--

CREATE TABLE `pzrs_matches` (
  `id` int(11) NOT NULL,
  `teamA` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `teamB` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `resultA` int(11) NOT NULL,
  `resultB` int(11) NOT NULL,
  `win` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'L',
  `teamALogo` text COLLATE utf8_unicode_ci NOT NULL,
  `teamBLogo` text COLLATE utf8_unicode_ci NOT NULL,
  `map1` text COLLATE utf8_unicode_ci NOT NULL,
  `map2` text COLLATE utf8_unicode_ci NOT NULL,
  `map3` text COLLATE utf8_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_matches`
--

INSERT INTO `pzrs_matches` (`id`, `teamA`, `teamB`, `resultA`, `resultB`, `win`, `teamALogo`, `teamBLogo`, `map1`, `map2`, `map3`, `resultAMap1`, `resultAMap2`, `resultAMap3`, `resultBMap1`, `resultBMap2`, `resultBMap3`, `active`, `created_at`, `edited_at`, `canBeEdited`) VALUES
(92, 'IronBranch.CSGO', 'eTuba', 2, 1, 'W', 'upload/myTeams\\logo.png', 'upload/enemy\\1400154456053082.jpg', 'Mirage', 'Cache', 'Dust2', 16, 10, 16, 14, 16, 5, 0, '2016-03-29 15:46:20', NULL, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_posts`
--

CREATE TABLE `pzrs_posts` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `pzrs_settings` (
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

CREATE TABLE `pzrs_teams` (
  `id` int(11) NOT NULL,
  `teamA` text COLLATE utf8_unicode_ci NOT NULL,
  `teamALogo` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `pzrs_teams`
--

INSERT INTO `pzrs_teams` (`id`, `teamA`, `teamALogo`, `created_at`, `active`) VALUES
(8, 'IronBranch.CSGO', 'upload/myTeams\\logo.png', '0000-00-00 00:00:00', 1),
(29, 'Koaly', 'upload/myTeams\\logo-zahrada2.jpg', '0000-00-00 00:00:00', 0),
(31, 'ghjghjghj', 'upload/myTeams/penguins.jpg', '2016-03-27 15:59:40', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pzrs_users`
--

CREATE TABLE `pzrs_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- Klíče pro tabulku `pzrs_maps`
--
ALTER TABLE `pzrs_maps`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
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
-- AUTO_INCREMENT pro tabulku `pzrs_maps`
--
ALTER TABLE `pzrs_maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT pro tabulku `pzrs_matches`
--
ALTER TABLE `pzrs_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT pro tabulku `pzrs_posts`
--
ALTER TABLE `pzrs_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `pzrs_settings`
--
ALTER TABLE `pzrs_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `pzrs_teams`
--
ALTER TABLE `pzrs_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pro tabulku `pzrs_users`
--
ALTER TABLE `pzrs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
