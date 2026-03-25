-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 25, 2026 at 11:14 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `metalgeardle`
--

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `affiliation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_game` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `era` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_small` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_large` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`id`, `name`, `gender`, `affiliation`, `nationality`, `first_game`, `era`, `role_type`, `image_small`, `image_large`, `created_at`) VALUES
(1, 'Solid Snake', 'male', 'FOXHOUND|Philanthropy', 'American', 'Metal Gear', '1995', 'soldier', '/metalgeardle/public/assets/images/characters/small/solid-snake.jpg', '/metalgeardle/public/assets/images/characters/large/solid-snake.jpg', '2026-03-24 14:48:30'),
(2, 'Big Boss', 'male', 'MSF', 'American', 'Metal Gear 2: Solid Snake', '1974', 'commander', '/metalgeardle/public/assets/images/characters/small/big-boss.jpg', '/metalgeardle/public/assets/images/characters/large/big-boss.jpg', '2026-03-24 14:48:30'),
(3, 'The Boss', 'female', 'Cobra Unit', 'American', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/the-boss.jpg', '/metalgeardle/public/assets/images/characters/large/the-boss.jpg', '2026-03-24 14:48:30'),
(4, 'Revolver Ocelot', 'male', 'Patriots|GRU', 'Soviet|American', 'Metal Gear Solid', '2005', 'spy', '/metalgeardle/public/assets/images/characters/small/revolver-ocelot.jpg', '/metalgeardle/public/assets/images/characters/large/revolver-ocelot.jpg', '2026-03-24 14:48:30'),
(5, 'Otacon', 'male', 'Philanthropy', 'American', 'Metal Gear Solid', '2005', 'scientist', '/metalgeardle/public/assets/images/characters/small/otacon.jpg', '/metalgeardle/public/assets/images/characters/large/otacon.jpg', '2026-03-24 14:48:30'),
(6, 'Kazuhira Miller', 'male', 'MSF|Diamond Dogs', 'Japanese|American', 'Metal Gear 2: Solid Snake', '1984', 'support', '/metalgeardle/public/assets/images/characters/small/kazuhira-miller.jpg', '/metalgeardle/public/assets/images/characters/large/kazuhira-miller.jpg', '2026-03-24 14:48:30'),
(7, 'Raiden', 'male', 'FOXHOUND', 'Liberian|American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'soldier', '/metalgeardle/public/assets/images/characters/small/raiden.jpg', '/metalgeardle/public/assets/images/characters/large/raiden.jpg', '2026-03-24 14:48:30'),
(8, 'Meryl Silverburgh', 'female', 'FOXHOUND', 'American', 'Metal Gear Solid', '2005', 'soldier', '/metalgeardle/public/assets/images/characters/small/meryl-silverburgh.jpg', '/metalgeardle/public/assets/images/characters/large/meryl-silverburgh.jpg', '2026-03-24 14:48:30'),
(9, 'Liquid Snake', 'male', 'FOXHOUND', 'British|American', 'Metal Gear Solid', '2005', 'commander', '/metalgeardle/public/assets/images/characters/small/liquid-snake.jpg', '/metalgeardle/public/assets/images/characters/large/liquid-snake.jpg', '2026-03-24 14:48:30'),
(10, 'Solidus Snake', 'male', 'Sons of Liberty', 'American|British', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'commander', '/metalgeardle/public/assets/images/characters/small/solidus-snake.jpg', '/metalgeardle/public/assets/images/characters/large/solidus-snake.jpg', '2026-03-24 14:48:30'),
(11, 'EVA', 'female', 'Philosophers', 'Chinese', 'Metal Gear Solid 3: Snake Eater', '1964', 'spy', '/metalgeardle/public/assets/images/characters/small/eva.jpg', '/metalgeardle/public/assets/images/characters/large/eva.jpg', '2026-03-24 14:48:30'),
(12, 'Para-Medic', 'female', 'FOX', 'American', 'Metal Gear Solid 3: Snake Eater', '1964', 'support', '/metalgeardle/public/assets/images/characters/small/para-medic.jpg', '/metalgeardle/public/assets/images/characters/large/para-medic.jpg', '2026-03-24 14:48:30'),
(13, 'Sigint', 'male', 'FOX', 'American', 'Metal Gear Solid 3: Snake Eater', '1964', 'support', '/metalgeardle/public/assets/images/characters/small/sigint.jpg', '/metalgeardle/public/assets/images/characters/large/sigint.jpg', '2026-03-24 14:48:30'),
(14, 'Major Zero', 'male', 'FOX|Patriots', 'British', 'Metal Gear Solid 3: Snake Eater', '1964', 'commander', '/metalgeardle/public/assets/images/characters/small/major-zero.jpg', '/metalgeardle/public/assets/images/characters/large/major-zero.jpg', '2026-03-24 14:48:30'),
(15, 'Volgin', 'male', 'GRU', 'Soviet', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/volgin.jpg', '/metalgeardle/public/assets/images/characters/large/volgin.jpg', '2026-03-24 14:48:30'),
(16, 'The End', 'male', 'Cobra Unit', 'Hungarian', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/the-end.jpg', '/metalgeardle/public/assets/images/characters/large/the-end.jpg', '2026-03-24 14:48:30'),
(17, 'The Fear', 'male', 'Cobra Unit', 'Unknown', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/the-fear.jpg', '/metalgeardle/public/assets/images/characters/large/the-fear.jpg', '2026-03-24 14:48:30'),
(18, 'The Fury', 'male', 'Cobra Unit', 'Soviet', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/the-fury.jpg', '/metalgeardle/public/assets/images/characters/large/the-fury.jpg', '2026-03-24 14:48:30'),
(19, 'The Sorrow', 'male', 'Cobra Unit', 'Russian', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/the-sorrow.jpg', '/metalgeardle/public/assets/images/characters/large/the-sorrow.jpg', '2026-03-24 14:48:30'),
(20, 'The Pain', 'male', 'Cobra Unit', 'Soviet', 'Metal Gear Solid 3: Snake Eater', '1964', 'boss', '/metalgeardle/public/assets/images/characters/small/the-pain.jpg', '/metalgeardle/public/assets/images/characters/large/the-pain.jpg', '2026-03-24 14:48:30'),
(21, 'Quiet', 'female', 'Diamond Dogs|XOF', 'Unknown', 'Metal Gear Solid V: The Phantom Pain', '1984', 'mercenary', '/metalgeardle/public/assets/images/characters/small/quiet.jpg', '/metalgeardle/public/assets/images/characters/large/quiet.jpg', '2026-03-24 14:48:30'),
(22, 'Venom Snake', 'male', 'Diamond Dogs|MSF', 'Unknown', 'Metal Gear Solid V: The Phantom Pain', '1984', 'soldier', '/metalgeardle/public/assets/images/characters/small/venom-snake.jpg', '/metalgeardle/public/assets/images/characters/large/venom-snake.jpg', '2026-03-24 14:48:30'),
(23, 'Skull Face', 'male', 'XOF', 'Hungarian', 'Metal Gear Solid V: Ground Zeroes', '1975', 'commander', '/metalgeardle/public/assets/images/characters/small/skull-face.jpg', '/metalgeardle/public/assets/images/characters/large/skull-face.jpg', '2026-03-24 14:48:30'),
(24, 'Huey Emmerich', 'male', 'MSF|Diamond Dogs', 'American', 'Metal Gear Solid: Peace Walker', '1974', 'scientist', '/metalgeardle/public/assets/images/characters/small/huey-emmerich.jpg', '/metalgeardle/public/assets/images/characters/large/huey-emmerich.jpg', '2026-03-24 14:48:30'),
(25, 'Strangelove', 'female', 'MSF', 'American', 'Metal Gear Solid: Peace Walker', '1974', 'scientist', '/metalgeardle/public/assets/images/characters/small/strangelove.jpg', '/metalgeardle/public/assets/images/characters/large/strangelove.jpg', '2026-03-24 14:48:30'),
(26, 'Cecile Cosima Caminades', 'female', 'MSF', 'French', 'Metal Gear Solid: Peace Walker', '1974', 'support', '/metalgeardle/public/assets/images/characters/small/cecile-cosima-caminades.jpg', '/metalgeardle/public/assets/images/characters/large/cecile-cosima-caminades.jpg', '2026-03-24 14:48:30'),
(27, 'Amanda Valenciano Libre', 'female', 'Sandinistas', 'Nicaraguan', 'Metal Gear Solid: Peace Walker', '1974', 'soldier', '/metalgeardle/public/assets/images/characters/small/amanda-valenciano-libre.jpg', '/metalgeardle/public/assets/images/characters/large/amanda-valenciano-libre.jpg', '2026-03-24 14:48:30'),
(28, 'Chico', 'male', 'Sandinistas', 'Nicaraguan', 'Metal Gear Solid: Peace Walker', '1974', 'support', '/metalgeardle/public/assets/images/characters/small/chico.jpg', '/metalgeardle/public/assets/images/characters/large/chico.jpg', '2026-03-24 14:48:30'),
(29, 'Paz Ortega Andrade', 'female', 'Cipher|MSF', 'Costa Rican', 'Metal Gear Solid: Peace Walker', '1974', 'spy', '/metalgeardle/public/assets/images/characters/small/paz-ortega-andrade.jpg', '/metalgeardle/public/assets/images/characters/large/paz-ortega-andrade.jpg', '2026-03-24 14:48:30'),
(30, 'Code Talker', 'male', 'Diamond Dogs', 'Navajo', 'Metal Gear Solid V: The Phantom Pain', '1984', 'scientist', '/metalgeardle/public/assets/images/characters/small/code-talker.jpg', '/metalgeardle/public/assets/images/characters/large/code-talker.jpg', '2026-03-24 14:48:30'),
(31, 'Eli', 'male', 'Diamond Dogs', 'British', 'Metal Gear Solid V: The Phantom Pain', '1984', 'soldier', '/metalgeardle/public/assets/images/characters/small/eli.jpg', '/metalgeardle/public/assets/images/characters/large/eli.jpg', '2026-03-24 14:48:30'),
(32, 'Sniper Wolf', 'female', 'FOXHOUND', 'Kurdish|Iraqi', 'Metal Gear Solid', '2005', 'boss', '/metalgeardle/public/assets/images/characters/small/sniper-wolf.jpg', '/metalgeardle/public/assets/images/characters/large/sniper-wolf.jpg', '2026-03-24 14:48:30'),
(33, 'Psycho Mantis', 'male', 'FOXHOUND', 'Russian', 'Metal Gear Solid', '2005', 'boss', '/metalgeardle/public/assets/images/characters/small/psycho-mantis.jpg', '/metalgeardle/public/assets/images/characters/large/psycho-mantis.jpg', '2026-03-24 14:48:30'),
(34, 'Vulcan Raven', 'male', 'FOXHOUND', 'Inuit', 'Metal Gear Solid', '2005', 'boss', '/metalgeardle/public/assets/images/characters/small/vulcan-raven.jpg', '/metalgeardle/public/assets/images/characters/large/vulcan-raven.jpg', '2026-03-24 14:48:30'),
(35, 'Decoy Octopus', 'male', 'FOXHOUND', 'Mexican', 'Metal Gear Solid', '2005', 'spy', '/metalgeardle/public/assets/images/characters/small/decoy-octopus.jpg', '/metalgeardle/public/assets/images/characters/large/decoy-octopus.jpg', '2026-03-24 14:48:30'),
(36, 'Gray Fox', 'male', 'FOXHOUND', 'American', 'Metal Gear 2: Solid Snake', '1999', 'soldier', '/metalgeardle/public/assets/images/characters/small/gray-fox.jpg', '/metalgeardle/public/assets/images/characters/large/gray-fox.jpg', '2026-03-24 14:48:30'),
(37, 'Naomi Hunter', 'female', 'FOXHOUND|Patriots', 'British|American', 'Metal Gear Solid', '2005', 'scientist', '/metalgeardle/public/assets/images/characters/small/naomi-hunter.jpg', '/metalgeardle/public/assets/images/characters/large/naomi-hunter.jpg', '2026-03-24 14:48:30'),
(38, 'Mei Ling', 'female', 'U.S. Army', 'Chinese|American', 'Metal Gear Solid', '2005', 'support', '/metalgeardle/public/assets/images/characters/small/mei-ling.jpg', '/metalgeardle/public/assets/images/characters/large/mei-ling.jpg', '2026-03-24 14:48:30'),
(39, 'Roy Campbell', 'male', 'FOXHOUND', 'American', 'Metal Gear 2: Solid Snake', '2005', 'commander', '/metalgeardle/public/assets/images/characters/small/roy-campbell.jpg', '/metalgeardle/public/assets/images/characters/large/roy-campbell.jpg', '2026-03-24 14:48:30'),
(40, 'Rosemary', 'female', 'Army Intelligence', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'support', '/metalgeardle/public/assets/images/characters/small/rosemary.jpg', '/metalgeardle/public/assets/images/characters/large/rosemary.jpg', '2026-03-24 14:48:30'),
(41, 'Fortune', 'female', 'Dead Cell', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'boss', '/metalgeardle/public/assets/images/characters/small/fortune.jpg', '/metalgeardle/public/assets/images/characters/large/fortune.jpg', '2026-03-24 14:48:30'),
(42, 'Vamp', 'male', 'Dead Cell', 'Romanian', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'mercenary', '/metalgeardle/public/assets/images/characters/small/vamp.jpg', '/metalgeardle/public/assets/images/characters/large/vamp.jpg', '2026-03-24 14:48:30'),
(43, 'Fatman', 'male', 'Dead Cell', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'boss', '/metalgeardle/public/assets/images/characters/small/fatman.jpg', '/metalgeardle/public/assets/images/characters/large/fatman.jpg', '2026-03-24 14:48:30'),
(44, 'Olga Gurlukovich', 'female', 'Gurlukovich Mercenaries|Patriots', 'Russian', 'Metal Gear Solid 2: Sons of Liberty', '2007', 'soldier', '/metalgeardle/public/assets/images/characters/small/olga-gurlukovich.jpg', '/metalgeardle/public/assets/images/characters/large/olga-gurlukovich.jpg', '2026-03-24 14:48:30'),
(45, 'Emma Emmerich', 'female', 'Navy', 'American', 'Metal Gear Solid 2: Sons of Liberty', '2009', 'scientist', '/metalgeardle/public/assets/images/characters/small/emma-emmerich.jpg', '/metalgeardle/public/assets/images/characters/large/emma-emmerich.jpg', '2026-03-24 14:48:30'),
(46, 'Sunny', 'female', 'Philanthropy', 'American', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'support', '/metalgeardle/public/assets/images/characters/small/sunny.jpg', '/metalgeardle/public/assets/images/characters/large/sunny.jpg', '2026-03-24 14:48:30'),
(47, 'Drebin 893', 'male', 'Drebin Network', 'Liberian', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'support', '/metalgeardle/public/assets/images/characters/small/drebin-893.jpg', '/metalgeardle/public/assets/images/characters/large/drebin-893.jpg', '2026-03-24 14:48:30'),
(48, 'Johnny Sasaki', 'male', 'Rat Patrol 01', 'American', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'soldier', '/metalgeardle/public/assets/images/characters/small/johnny-sasaki.jpg', '/metalgeardle/public/assets/images/characters/large/johnny-sasaki.jpg', '2026-03-24 14:48:30'),
(49, 'Liquid Ocelot', 'male', 'Patriots|Outer Haven', 'Unknown', 'Metal Gear Solid 4: Guns of the Patriots', '2014', 'commander', '/metalgeardle/public/assets/images/characters/small/liquid-ocelot.jpg', '/metalgeardle/public/assets/images/characters/large/liquid-ocelot.jpg', '2026-03-24 14:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `character_aliases`
--

CREATE TABLE `character_aliases` (
  `id` int NOT NULL,
  `character_id` int NOT NULL,
  `alias_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `character_aliases`
--

INSERT INTO `character_aliases` (`id`, `character_id`, `alias_name`) VALUES
(1, 1, 'Snake'),
(2, 1, 'Old Snake'),
(3, 1, 'Iroquois Pliskin'),
(4, 2, 'Naked Snake'),
(5, 2, 'Jack'),
(6, 4, 'Ocelot'),
(7, 4, 'Shalashaska'),
(8, 5, 'Hal Emmerich'),
(9, 6, 'Kaz'),
(10, 6, 'Master Miller'),
(11, 7, 'Jack the Ripper'),
(12, 9, 'Liquid'),
(13, 10, 'George Sears'),
(14, 11, 'Tatyana'),
(15, 14, 'Zero'),
(16, 15, 'Colonel Volgin'),
(17, 22, 'Punished Snake'),
(18, 24, 'Huey'),
(19, 29, 'Pacifica Ocean'),
(20, 31, 'White Mamba'),
(21, 33, 'Mantis'),
(22, 36, 'Frank Jaeger'),
(23, 36, 'Cyborg Ninja'),
(24, 39, 'Colonel Campbell'),
(25, 40, 'Rose'),
(26, 44, 'Olga'),
(27, 47, 'Drebin'),
(28, 48, 'Akiba'),
(29, 49, 'Ocelot');

-- --------------------------------------------------------

--
-- Table structure for table `daily_challenges`
--

CREATE TABLE `daily_challenges` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `character_id` int NOT NULL,
  `mode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'classic'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_challenges`
--

INSERT INTO `daily_challenges` (`id`, `date`, `character_id`, `mode`) VALUES
(1, '2026-03-24', 1, 'classic');

-- --------------------------------------------------------

--
-- Table structure for table `daily_challenge_solves`
--

CREATE TABLE `daily_challenge_solves` (
  `id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `solver_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_challenge_solves`
--

INSERT INTO `daily_challenge_solves` (`id`, `challenge_id`, `solver_token`, `created_at`) VALUES
(1, 1, '9421d781-f0a2-4ff7-8666-f0dcbcb01390', '2026-03-25 10:31:44'),
(2, 1, '6d6cfcca-cf46-485a-8ff0-a3bf0b6d3c8a', '2026-03-25 11:13:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `character_aliases`
--
ALTER TABLE `character_aliases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_character_aliases_character` (`character_id`);

--
-- Indexes for table `daily_challenges`
--
ALTER TABLE `daily_challenges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_daily_mode` (`date`,`mode`),
  ADD KEY `fk_daily_challenges_character` (`character_id`);

--
-- Indexes for table `daily_challenge_solves`
--
ALTER TABLE `daily_challenge_solves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_daily_solver` (`challenge_id`,`solver_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `character_aliases`
--
ALTER TABLE `character_aliases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `daily_challenges`
--
ALTER TABLE `daily_challenges`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_challenge_solves`
--
ALTER TABLE `daily_challenge_solves`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `character_aliases`
--
ALTER TABLE `character_aliases`
  ADD CONSTRAINT `fk_character_aliases_character` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_challenges`
--
ALTER TABLE `daily_challenges`
  ADD CONSTRAINT `fk_daily_challenges_character` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_challenge_solves`
--
ALTER TABLE `daily_challenge_solves`
  ADD CONSTRAINT `fk_daily_challenge_solves_challenge` FOREIGN KEY (`challenge_id`) REFERENCES `daily_challenges` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
