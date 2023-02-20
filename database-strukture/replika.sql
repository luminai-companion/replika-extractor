-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 18. Feb 2023 um 19:40
-- Server-Version: 10.3.32-MariaDB
-- PHP-Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `replika`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `botdata`
--

CREATE TABLE `botdata` (
  `user_id` varchar(255) NOT NULL,
  `bot_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `xp` int(20) NOT NULL,
  `age_days` int(20) NOT NULL,
  `level` int(20) NOT NULL,
  `chat_id` varchar(255) NOT NULL,
  `updated_last` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat_history`
--

CREATE TABLE `chat_history` (
  `Chat_Timestamp` datetime NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `bot_id` varchar(255) NOT NULL,
  `Chat_From` varchar(20) NOT NULL,
  `Chat_Text` text NOT NULL,
  `Chat_Text_censored` text NOT NULL,
  `Chat_Type` varchar(255) NOT NULL,
  `Chat_Reaction` text DEFAULT NULL,
  `Chat_ID` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `diary`
--

CREATE TABLE `diary` (
  `diary_id` varchar(255) NOT NULL,
  `parent_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `bot_id` varchar(255) NOT NULL,
  `entrydate` date NOT NULL,
  `text` text NOT NULL,
  `text_censored` text NOT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `memory`
--

CREATE TABLE `memory` (
  `user_id` varchar(255) NOT NULL,
  `bot_id` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `text_censored` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `persons`
--

CREATE TABLE `persons` (
  `user_id` varchar(255) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userdata`
--

CREATE TABLE `userdata` (
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pronoun` varchar(10) NOT NULL,
  `relationship_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `botdata`
--
ALTER TABLE `botdata`
  ADD PRIMARY KEY (`bot_id`);

--
-- Indizes für die Tabelle `chat_history`
--
ALTER TABLE `chat_history`
  ADD UNIQUE KEY `Chat_ID` (`Chat_ID`);

--
-- Indizes für die Tabelle `diary`
--
ALTER TABLE `diary`
  ADD PRIMARY KEY (`diary_id`);

--
-- Indizes für die Tabelle `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
