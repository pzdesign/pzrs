-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 22. bře 2016, 16:42
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
(63, 'PLAYzone aréna byla součástí GAME EXPO', 'V pořadí třetí zastávkou PLAYzone arény v roce 2016 byl bratislavský festival GAME EXPO, jehož osmý ročník se uskutečnil o víkendu 18. až 20. března v Domě kultury Ružinov. Návštěvníci si ke stánkům mohli přijít vyzkoušet špičkové technologie, zahrát si t', '<p style="text-align: center;"><strong>Praha, ČR - 22. března 2016</strong> &ndash; Mezin&aacute;rodn&iacute; v&yacute;stava a festival GAME EXPO 2016 nab&iacute;dl během tř&iacute;denn&iacute;ho programu bohat&yacute; program. Ať už festival nav&scaron;t&iacute;vil fanou&scaron;ek poč&iacute;tačov&yacute;ch, stoln&iacute;ch nebo i karetn&iacute;ch her, vždy si každ&yacute; při&scaron;el na sv&eacute;. Mimo tyto tři odvětv&iacute; byly připraveny tak&eacute; oddechov&eacute; hry jako Guitar Hero, odborn&eacute; předn&aacute;&scaron;ky, prezentace hern&iacute;ch studi&iacute; a historick&aacute; v&yacute;stava poč&iacute;tačov&yacute;ch her.</p>\n<p style="text-align: center;"><img title="PLAYzone ar&eacute;na HAL3000" src="http://i.imgur.com/SMbhUKOg.jpg" alt="PLAYzone ar&eacute;na HAL3000" width="280" height="186" />&nbsp;<img title="PLAYzone ar&eacute;na DELL Alienware" src="http://i.imgur.com/1h3Pd2pg.jpg" alt="PLAYzone ar&eacute;na DELL Alienware" width="280" height="186" /></p>\n<p style="text-align: center;">V PLAYzone ar&eacute;ně bylo od p&aacute;tku do neděle ru&scaron;no. Pro n&aacute;v&scaron;těvn&iacute;ky byl připraven st&aacute;nek společnost&iacute; DELL a Alienware, kde byly připraveny hern&iacute; notebooky, na kter&yacute;ch byla možnost odzkou&scaron;et ty nejnověj&scaron;&iacute; pecky na nejvy&scaron;&scaron;&iacute; detaily. Kdo při&scaron;el, tak nelitoval. Na st&aacute;nku bylo možn&eacute; mimo jin&eacute; potkat a zahr&aacute;t si s League of Legends hr&aacute;čkou tuzemsk&eacute; organizace eSuba. Mimo tento st&aacute;nek byla připravena tak&eacute; hern&iacute; ar&eacute;na HAL3000, kde n&aacute;v&scaron;těvn&iacute;ci Game Expa mohli otestovat &scaron;pičkov&yacute; v&yacute;kon hern&iacute;ch sestav HAL3000 MČR &scaron;est&eacute; generace. Požitek z hran&iacute; na nejvy&scaron;&scaron;&iacute; &uacute;rovni byl pos&iacute;len BenQ hern&iacute;mi monitory s frekvenc&iacute; 144 Hz a hern&iacute;mi perif&eacute;riemi Mionix.</p>\n<p style="text-align: center;">Na těchto st&aacute;nc&iacute;ch se během třech dn&iacute; uskutečnilo celkem sedm turnajů pro veřejnost, do kter&yacute;ch se mohl přihl&aacute;sit naprosto každ&yacute;. Bojovalo se v nejpopul&aacute;rněj&scaron;&iacute;ch titulech současn&eacute; sc&eacute;ny elektronick&eacute;ho sportu, konkr&eacute;tně v League of Legends a Counter-Strike: Global Offensive. Až na jeden 3v3 turnaj se soutěže hr&aacute;li formou &bdquo;jeden proti jednomu&ldquo;, ti nejlep&scaron;&iacute; si kromě velk&eacute; porce z&aacute;bavy měli &scaron;anci odn&eacute;st si ceny partnerů PLAYzone ar&eacute;ny. N&iacute;že naleznete v&yacute;pis v&scaron;ech odehran&yacute;ch turnajů i s jej&iacute;mi v&iacute;tězi.</p>\n<p style="text-align: center;">&nbsp;</p>\n<ul style="text-align: center;">\n<li>P&aacute;tek 14:00 - Dell Alienware League of Legends 1v1 #1 &ndash; v&iacute;těz: eXtatus Necro</li>\n<li>P&aacute;tek 18:00 - BenQ Counter-Strike Global Offensive 1v1 #1 &ndash; v&iacute;těz: Spe3dUP</li>\n<li>Sobota 10:00 - HAL3000 League of Legends 3v3 &ndash; v&iacute;těz: All Star</li>\n<li>Sobota 14:00 - Dell Alienware League of Legends 1v1 #2 &ndash; v&iacute;těz: byun baekhyunnie</li>\n<li>Sobota 18:00 - Mionix Counter-Strike Global Offensive 1v1 &ndash; v&iacute;těz: BELZI</li>\n<li>Neděle 10:00 - BenQ Counter-Strike Global Offensive 1v1 #2 &ndash; v&iacute;těz: alucard</li>\n<li>Neděle 13:00 - Dell Alienware League of Legends 1v1 #3 &ndash; v&iacute;těz: Black Werlas</li>\n</ul>\n<p style="text-align: center;">&nbsp;</p>\n<p style="text-align: center;"><img title="PLAYzone ar&eacute;na GAME EXPO" src="http://i.imgur.com/fMslprX.jpg" alt="PLAYzone ar&eacute;na GAME EXPO" width="480" height="319" /></p>\n<p style="text-align: center;">Během v&iacute;kendu se v Bratislavě na st&aacute;nc&iacute;ch na&scaron;&iacute; PLAYzone ar&eacute;ny propařili stovky hodiny, odměněni byli ti nejlep&scaron;&iacute; hr&aacute;či v&yacute;stavy Game Expo a &uacute;plně v&scaron;ichni měli možnost už&iacute;t si kopec z&aacute;bavy. To v&scaron;e d&iacute;ky partnerům PLAYzone ar&eacute;ny: Dell, Alienware, HAL3000, BenQ a Mionix.</p>', '', '2016-03-22 14:50:06', '2016-03-22 15:41:46', '', '', '0', 0, 0),
(64, 'Kkjkj', 'kjkj', '<p>kjkjk</p>', '', '2016-03-22 14:56:55', NULL, '', '', 'kkjkj', 0, 0),
(65, 'jkjk', 'jkjkj', '<p>kjkjk</p>', '', '2016-03-22 14:58:41', NULL, '', '', 'jkjk', 0, 0),
(66, 'asdas', 'asd', '<p>dasdasd</p>', '', '2016-03-22 15:00:48', NULL, '', '', 'asdas', 0, 0),
(67, 'asdasd', 'asdasd', '<p>asdasd</p>', '', '2016-03-22 15:01:11', NULL, '', '', 'asdasd', 0, 0),
(68, 'sdsad', 'asdas', '<p>dasd</p>', '', '2016-03-22 15:01:27', NULL, '', '', 'sdsad', 0, 0),
(69, 'asdas', 'asd', '<p>dasd</p>', '', '2016-03-22 15:03:05', NULL, '', '', 'asdas', 0, 0),
(70, 'asdasd', 'asdasd', '<p>asdasd</p>', '', '2016-03-22 15:03:38', NULL, '', '', 'asdasd', 0, 0),
(79, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:06:27', NULL, '', '', 'asd', 0, 0),
(80, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:06:29', NULL, '', '', 'asd', 0, 0),
(81, 'asd', 'dasd', '<p>asd</p>', '', '2016-03-22 15:07:17', NULL, '', '', 'asd', 1, 0),
(82, 'asd', 'dasd', '<p>asd</p>', '', '2016-03-22 15:07:17', NULL, '', '', 'asd', 1, 0),
(83, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:07:38', NULL, '', '', 'asd', 1, 0),
(84, 'asdasdasd', 'asd', '<p>asd</p>', '', '2016-03-22 15:08:33', NULL, '', '', 'asdasdasd', 1, 0),
(85, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:08:49', NULL, '', '', 'asd', 1, 0),
(86, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:09:22', NULL, '', '', 'asd', 0, 0),
(87, 'asd', 'asd', '<p>asdasd</p>', '', '2016-03-22 15:16:52', NULL, '', '', 'asd', 0, 0),
(88, 'asdasd', 'asdasd', '<p>asdasdd</p>', '', '2016-03-22 15:17:06', NULL, '', '', 'asdasd', 0, 0),
(89, 'asdasd', 'asdasd', '<p>asdasdd</p>', '', '2016-03-22 15:17:08', NULL, '', '', 'asdasd', 0, 0),
(90, 'asdasdasd', 'asdasdasd', '<p>asdasddasd</p>', '', '2016-03-22 15:17:40', '2016-03-22 15:18:32', '', '', '0', 0, 0),
(91, 'sdf', 'sdf', '<p>sdf</p>', '', '2016-03-22 15:19:37', NULL, '', '', 'sdf', 0, 0),
(92, 'asdf', 'sdfs', '<p>sdf</p>', '', '2016-03-22 15:24:18', NULL, '', '', 'asdf', 0, 0),
(93, 'sdf', 'sdfsdf', '<p>sdfsdf</p>', '', '2016-03-22 15:24:26', NULL, '', '', 'sdf', 0, 0),
(94, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:24:31', NULL, '', '', 'asd', 0, 0),
(95, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:27:17', NULL, '', '', 'asd', 0, 0),
(96, 'sdf', 'sdf', '<p>sdf</p>', '', '2016-03-22 15:28:12', NULL, '', '', 'sdf', 0, 0),
(97, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:28:33', NULL, '', '', 'asd', 0, 0),
(98, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:29:45', NULL, '', '', 'asd', 0, 0),
(99, 'asd', 'asd', 'asd', '', '2016-03-22 15:29:54', NULL, '', '', 'asd', 0, 0),
(100, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:30:09', NULL, '', '', 'asd', 0, 0),
(101, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:30:45', NULL, '', '', 'asd', 0, 0),
(102, 'asdasd', 'asdasd', '<p>asdasdasd</p>', '', '2016-03-22 15:36:15', NULL, '', '', 'asdasd', 0, 0),
(103, 'asd', 'asd', '<p>asdasd</p>', '', '2016-03-22 15:36:50', NULL, '', '', 'asd', 0, 0),
(104, 'asd', 'asd', '<p>asdasd</p>', '', '2016-03-22 15:37:25', NULL, '', '', 'asd', 0, 0),
(105, 'asd', 'asd', '<p>asd</p>', '', '2016-03-22 15:37:50', NULL, '', '', 'asd', 0, 0),
(106, 'asd', 'aasd', '<p>asdasd</p>', '', '2016-03-22 15:38:08', NULL, '', '', 'asd', 0, 0),
(107, 'asd', 'asd', '<p>asdasd</p>', '', '2016-03-22 15:40:58', '2016-03-22 15:41:19', '', '', '0', 1, 0),
(108, 'sdasd', 'da', '<p>asdas</p>', '', '2016-03-22 15:41:05', '2016-03-22 15:41:16', '', '', '0', 1, 0);

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
-- Klíče pro tabulku `pzrs_users`
--
ALTER TABLE `pzrs_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `pzrs_posts`
--
ALTER TABLE `pzrs_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT pro tabulku `pzrs_settings`
--
ALTER TABLE `pzrs_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `pzrs_users`
--
ALTER TABLE `pzrs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
