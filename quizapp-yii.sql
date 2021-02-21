-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 21 feb 2021 om 15:35
-- Serverversie: 10.1.37-MariaDB
-- PHP-versie: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizapp-yii`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text,
  `company` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `firstname`, `lastname`, `name`, `slug`, `image`, `bio`, `company`) VALUES
(3, 1, 'Admin', 'Admin', 'AdminAdmin', 'adminadmin', 'uploads/profile/expert_groot-576x486site-title.jpg', 'Test qtest', 'Test'),
(4, NULL, 'Test', 'Profile', 'TestProfile', 'testprofile', 'uploads/profile/blog-3site-title.jpg', 'qdsf', 'qdfs');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `question`
--

CREATE TABLE `question` (
  `id` int(11) UNSIGNED NOT NULL,
  `round_id` int(11) UNSIGNED NOT NULL,
  `order_index` int(11) NOT NULL,
  `inquiry` mediumtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `answer` varchar(555) NOT NULL,
  `wrong_options` varchar(555) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `question`
--

INSERT INTO `question` (`id`, `round_id`, `order_index`, `inquiry`, `image`, `answer`, `wrong_options`) VALUES
(5, 3, 3, 'Wat was de naam van de band die John Lennon had opgericht, voor hij Paul McCartney ontmoette en ze The Beatles werden?', 'test', 'The Quarrymen', ''),
(6, 3, 2, 'Wie luistert naar de naam Paul David Hewson?', 'test', 'Bono', 'Sting, Prince'),
(7, 3, 1, 'Hoe heette de zangeres van The Cranberries?', 'test', 'O\'riordan', ''),
(8, 4, 2, 'Welke acteur heeft de meeste Oscars voor Beste Mannelijke Hoofdrol gewonnen?', 'oscars.jpg', 'Daniel-Day Lewis', ''),
(10, 4, 1, 'Hoe heet de hoofdacteur in Die Hard?', 'die-hard.jpg', 'Bruce Willis', ''),
(11, 4, 3, 'Hoe heet de talkshow van Conan O\'Brien?', 'uploads/questions/4-oscars-quizapp.jpg', 'Late Night Show with Conan O\'Brien', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `quiz`
--

CREATE TABLE `quiz` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `quiz`
--

INSERT INTO `quiz` (`id`, `name`, `slug`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Testquiz', 'testquiz', 'qdsfjodf', 3, 0, 1613906561),
(2, 'Een quiz!', 'een-quiz', 'qdsf', 3, 1613906753, 1613906760);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `quiz_event`
--

CREATE TABLE `quiz_event` (
  `id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(55) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `quiz_event`
--

INSERT INTO `quiz_event` (`id`, `quiz_id`, `uuid`, `created_at`, `updated_at`) VALUES
(1, 1, 'ODIJE484V8', 0, 1613916526),
(2, 1, '5UE0MFHqOr', 1613914345, 1613914345);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `record`
--

CREATE TABLE `record` (
  `id` int(11) UNSIGNED NOT NULL,
  `team_id` int(11) UNSIGNED NOT NULL,
  `round_id` int(11) UNSIGNED NOT NULL,
  `order_index` int(11) NOT NULL,
  `answer` varchar(50) DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `record`
--

INSERT INTO `record` (`id`, `team_id`, `round_id`, `order_index`, `answer`, `correct`) VALUES
(160, 21, 4, 1, 'test', NULL),
(161, 21, 4, 2, 'test', NULL),
(162, 21, 4, 3, 'test', NULL),
(163, 22, 4, 1, 'QSDF', NULL),
(164, 22, 4, 2, 'aezr', NULL),
(165, 22, 4, 3, 'qsdf', NULL),
(166, 31, 4, 1, 'fqsd', NULL),
(167, 31, 4, 2, 'zerazer', NULL),
(168, 31, 4, 3, 'qdsf', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `rolename` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `role`
--

INSERT INTO `role` (`id`, `rolename`) VALUES
(1, 'admin'),
(2, 'author'),
(3, 'editor'),
(4, 'member');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `round`
--

CREATE TABLE `round` (
  `id` int(11) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `order_index` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `round`
--

INSERT INTO `round` (`id`, `quiz_id`, `name`, `slug`, `order_index`) VALUES
(3, 1, 'muziekronde', 'muziekronde', 2),
(4, 1, 'filmronde', 'filmronde', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sl_image`
--

CREATE TABLE `sl_image` (
  `id` int(11) NOT NULL,
  `imagepath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `sl_image`
--

INSERT INTO `sl_image` (`id`, `imagepath`) VALUES
(2, 'uploads/slider/testslider-test-3-yii-base.jpg'),
(4, 'uploads/slider/testslider-blog-1-yii-base.jpg'),
(5, 'uploads/slider/testslider-blog-4-yii-base.jpg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sl_slide`
--

CREATE TABLE `sl_slide` (
  `id` int(11) NOT NULL,
  `slider_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `slide_index` int(2) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `target` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `sl_slide`
--

INSERT INTO `sl_slide` (`id`, `slider_id`, `image_id`, `slide_index`, `url`, `page`, `target`) VALUES
(2, 1, 2, 3, NULL, NULL, NULL),
(3, 1, 5, 2, '', NULL, 0),
(4, 1, 4, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `sl_slider`
--

CREATE TABLE `sl_slider` (
  `id` int(11) NOT NULL,
  `name` varchar(55) DEFAULT NULL,
  `slug` varchar(55) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `aspect_ratio` float DEFAULT NULL,
  `gap` int(11) DEFAULT NULL,
  `lightbox` int(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `sl_slider`
--

INSERT INTO `sl_slider` (`id`, `name`, `slug`, `width`, `height`, `aspect_ratio`, `gap`, `lightbox`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'TestSlider', 'testslider', 600, 400, 1.5, 40, 1, 1, 1597932616, 1598878520),
(2, 'One Slider', 'one-slider', 500, 300, 1.66667, 2, 1, NULL, 1600698166, 1600698166);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `team`
--

CREATE TABLE `team` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(55) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `quiz_event_id` int(11) UNSIGNED NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `team`
--

INSERT INTO `team` (`id`, `username`, `score`, `quiz_event_id`, `auth_key`, `access_token`) VALUES
(14, 'minarona', 0, 1, 'iy55SJZuvtZ8JtI3ACDpIFn0QIq09X7u', '9OnYJnOF5cqdfoBjONwtBFMrp7I0LTmR'),
(15, 'azerttttt', 0, 1, 'HlOU66fVUEnD_118o6VLX8T15tGFPUNf', 'z8ytvpl4e4zoV0gYCNufv6AsL-xre78o'),
(16, 'oooopppp', 0, 1, 'aN2G7KQUZ_MiR1mfNfzhxGhF02DtbiVs', '_8E6aX04w6i2ftbbo9uSgy6_pNS5TEbb'),
(17, 'azeraezrazer', 0, 1, 'YDfrrl_FWDW8ss43VScJN0vKO7yji8Sk', 'nv5f28fOo7Lt_bLHJ0N3u8nheOAB-Bx2'),
(18, 'Team America', 0, 1, 'D7BPKdYiCbWyEJ4k-p5DNVrCXEUa-nH_', 'hkkFpBDsV6gcDl--98VpVuAqOER-A3za'),
(19, 'World Police', 0, 1, '_mXl0RAuzTIZ_Xhj6YsGpRgVwsZAnxoC', 'xGQsfUJU191thcOZBmIeFKNrcUgh_KGA'),
(20, 'Rick Riker', 0, 1, '_I9OYI5uUqZ3MPW3anFgCRhSHe9tR3Sg', '9MF2x9HvCkXsv46oIA3-ZrRSu69k9rEc'),
(21, 'Peter Parker', 0, 1, 'f8XjNTpbfmu_4dMb5vay2XYI-GQIF4xQ', 'OyFk8bQLi74QFBvWN06oouzF76qxjO3Q'),
(22, 'holleb', 0, 1, '1t2YboMMXqiNsqdlCHI8_INsFLrF_biy', 'SIy2s6P3ZpIgOetRT4UQXm3gBISaJPY2'),
(23, 'dsqf', 0, 1, 'fHspqCjPWoZphfFbNuJnX9OdyXRcUEIT', 'UH1PGUfg_djWHfebOxhfR-25NFNbL_Sw'),
(24, 'testteam', 0, 1, 'CBPaQZmOLiI__XhZodX6SD3qe12VIHyq', 'rEJE2peJzPxCVp392kgDUV6dod_sP553'),
(25, 'Milli Vanily', 0, 2, 'J33llaPIBgnAo_GczC8tnSnSW65pycCu', 'kwNRZTNh8K7Sns0DVPDN0FMIluwAhT9p'),
(26, 'testtttttt', 0, 2, '0ue72oDWA4jANl48sv0Dz2Iwpx-lx5sk', '0GPMQXnOqmjnvwjv7HsXgpImFB-MrL9b'),
(27, 'Rick Rikerqfds', 0, 2, 'lElgfILWnAPmkviVLfrfXs49re7vJwfw', 'ZH3iIKyoU97OJDYeZFpT7RRR8ATvvnrs'),
(28, 'wijzijncool', 0, 2, 'taKicrKUCM_czP8gCPHGoqgO_GOzJPyI', '49alH0A0J497PLeCdb1n3PCkh2sREa3M'),
(29, 'qsdjfiodsjfmqidsjf', 0, 1, 'ZaGaC-22ImjGIjOMhC7p7iN_KQYI4fMO', 'bjQFl01NSqOz62lwVnPYALwMPuRcv-nA'),
(30, 'dghfhfghdghdgh', 0, 1, 'ipQ-6HPYMiuXAxd870q9YeV3PkcdQadA', '1bOEX2_oa8Yml1sXsCbL_jyBS7nXo1TB'),
(31, 'qsdeeeeeee', 0, 1, 'SoV-sJq0Tgdf-Iv3XTCLIRAQWr6JaAFi', 'vk_T8xSCJQppWTA_44ghDOUU2JyAkG3d');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `testimonial`
--

CREATE TABLE `testimonial` (
  `id` int(11) NOT NULL,
  `name` varchar(55) DEFAULT NULL,
  `jobtitle` varchar(55) DEFAULT NULL,
  `company` varchar(55) DEFAULT NULL,
  `text` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `testimonial`
--

INSERT INTO `testimonial` (`id`, `name`, `jobtitle`, `company`, `text`) VALUES
(1, 'Testimonial1', 'Ma', 'SCSberhinr', 'I really liked it, the performance was incredible!'),
(2, 'Johnny', 'CEO', '§Z\'E§', 'I really liked the treatment we got here! Would recommend.'),
(3, 'Test', 'qdsf', 'TEST', 'qdfs');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `auth_key`, `access_token`) VALUES
(1, 'sysAdmin', 'testadmin@admin.com', '$2y$13$MkqI8uTWseuHVM8rMHapB.fwaMlNiFjZMqdkb5T.Y50IdJWyo8Cr2', 'kCJ_xibEEbJAG8g_8u7M3ULztT1uLRLS', 'm0xw7MN41OWB1o7AD1iwUXnSRJxj82MG');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`) VALUES
(2, 1, 1),
(3, 1, 2),
(4, 1, 3);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexen voor tabel `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_round_id` (`round_id`);

--
-- Indexen voor tabel `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `quiz_event`
--
ALTER TABLE `quiz_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_to_quiz` (`quiz_id`);

--
-- Indexen voor tabel `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_round_record` (`round_id`),
  ADD KEY `fk_team` (`team_id`);

--
-- Indexen voor tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `round`
--
ALTER TABLE `round`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quiz_id` (`quiz_id`);

--
-- Indexen voor tabel `sl_image`
--
ALTER TABLE `sl_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `sl_slide`
--
ALTER TABLE `sl_slide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slide_slider_fk` (`slider_id`),
  ADD KEY `slide_image_fk` (`image_id`);

--
-- Indexen voor tabel `sl_slider`
--
ALTER TABLE `sl_slider`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_slider_created` (`created_by`);

--
-- Indexen voor tabel `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_event_id` (`quiz_event_id`);

--
-- Indexen voor tabel `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk` (`user_id`),
  ADD KEY `role_fk` (`role_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `quiz_event`
--
ALTER TABLE `quiz_event`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `record`
--
ALTER TABLE `record`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT voor een tabel `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `round`
--
ALTER TABLE `round`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `sl_image`
--
ALTER TABLE `sl_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `sl_slide`
--
ALTER TABLE `sl_slide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `sl_slider`
--
ALTER TABLE `sl_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT voor een tabel `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Beperkingen voor tabel `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `fk_round_id` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `quiz_event`
--
ALTER TABLE `quiz_event`
  ADD CONSTRAINT `fk_to_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `record`
--
ALTER TABLE `record`
  ADD CONSTRAINT `fk_round_record` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `round`
--
ALTER TABLE `round`
  ADD CONSTRAINT `fk_quiz_id` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `sl_slide`
--
ALTER TABLE `sl_slide`
  ADD CONSTRAINT `slide_image_fk` FOREIGN KEY (`image_id`) REFERENCES `sl_image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `slide_slider_fk` FOREIGN KEY (`slider_id`) REFERENCES `sl_slider` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `quiz_event_id` FOREIGN KEY (`quiz_event_id`) REFERENCES `quiz_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `role_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
