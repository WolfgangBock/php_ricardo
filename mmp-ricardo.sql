-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 20. Sep 2019 um 09:19
-- Server-Version: 10.1.23-MariaDB-9+deb9u1
-- PHP-Version: 7.0.19-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `539197_25_2`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `category_name_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`category_name_id`) VALUES
('Computer'),
('Haustier'),
('Lebensmittel'),
('Möbel'),
('Smartphone'),
('Technik');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--

CREATE TABLE `items` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `insert_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` int(1) NOT NULL COMMENT '1 für Angebot; 2 für Anfrage',
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `items`
--

INSERT INTO `items` (`id`, `user_id`, `title`, `description`, `price`, `insert_time`, `type`, `img`) VALUES
(1, 1, 'Konfitüren', 'verschiedene Sorten\r\nErdbeere, Himbeere, ...\r\nPreis pro Glass', '5.50', '2019-09-19 19:00:55', 1, NULL),
(2, 2, 'Qi-Ladegerät', 'Suche Ladegerät zum Einbau in eine Tischplatte', '0.00', '2019-09-19 19:05:23', 2, NULL),
(3, 2, 'Krone', 'Keep Calm\r\nand shut up', '12.50', '2019-09-19 19:05:23', 1, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item_has_category`
--

CREATE TABLE `item_has_category` (
  `item_id` int(11) UNSIGNED NOT NULL,
  `category_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `item_has_category`
--

INSERT INTO `item_has_category` (`item_id`, `category_id`) VALUES
(1, 'Lebensmittel'),
(2, 'Computer'),
(2, 'Smartphone'),
(2, 'Technik'),
(3, 'Technik');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `roles`
--

CREATE TABLE `roles` (
  `role_name_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `roles`
--

INSERT INTO `roles` (`role_name_id`) VALUES
('admin'),
('user');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` varchar(25) NOT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `regtime`, `role`, `img`) VALUES
(1, 'alina.weisser@fhgr.ch', '12345', 'Alina', 'Weisser', '2019-09-19 17:26:44', 'admin', NULL),
(2, 'wolfgang.bock@fhgr.ch', '54321', 'Wolfgang', 'Bock', '2019-09-19 17:28:09', 'user', NULL),
(3, 'urs.thoeny@fhgr.ch', '99999', 'Urs', 'Thöny', '2019-09-19 17:30:04', 'user', NULL),
(4, 'john.doe@gmail.com', '11111', 'John', 'Doe', '2019-09-19 17:30:04', 'user', NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_name_id`);

--
-- Indizes für die Tabelle `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indizes für die Tabelle `item_has_category`
--
ALTER TABLE `item_has_category`
  ADD KEY `item_fk` (`item_id`),
  ADD KEY `category_fk` (`category_id`);

--
-- Indizes für die Tabelle `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_name_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_fk` (`role`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `item_has_category`
--
ALTER TABLE `item_has_category`
  ADD CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_name_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `item_fk` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `role_fk` FOREIGN KEY (`role`) REFERENCES `roles` (`role_name_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
