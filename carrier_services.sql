-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 14. bře 2020, 11:35
-- Verze serveru: 10.4.11-MariaDB
-- Verze PHP: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `carrier_services`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `carrier`
--

CREATE TABLE `carrier` (
  `carrier_id` int(10) UNSIGNED NOT NULL,
  `carrier_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `carrier`
--

INSERT INTO `carrier` (`carrier_id`, `carrier_name`) VALUES
(1, 'Česká pošta'),
(2, 'DPD'),
(3, 'Geis'),
(4, 'GLS'),
(5, 'InTime'),
(6, 'PPL'),
(7, 'TopTrans'),
(8, 'Uloženka'),
(9, 'Zásilkovna');

-- --------------------------------------------------------

--
-- Struktura tabulky `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `order_state` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `carrier_id` int(10) UNSIGNED NOT NULL,
  `service_name` varchar(64) NOT NULL,
  `max_weight` float(11,1) NOT NULL,
  `max_length` int(11) NOT NULL,
  `max_width` int(11) DEFAULT NULL,
  `max_height` int(11) DEFAULT NULL,
  `three_sides` int(11) DEFAULT NULL,
  `circumferential_length` int(11) DEFAULT NULL,
  `check_min_size` tinyint(1) NOT NULL,
  `min_lenght` float(11,1) DEFAULT NULL,
  `min_width` float(11,1) DEFAULT NULL,
  `min_height` float(11,1) DEFAULT NULL,
  `packet_s` tinyint(1) NOT NULL,
  `packet_m` tinyint(1) NOT NULL,
  `packet_l` tinyint(1) NOT NULL,
  `packet_xl` tinyint(1) NOT NULL,
  `address_delivery` tinyint(1) NOT NULL,
  `pickup_delivery` tinyint(1) NOT NULL,
  `cash_on_delivery` tinyint(1) NOT NULL,
  `delivery_from` time DEFAULT NULL,
  `delivery_to` time DEFAULT NULL,
  `evening_delivery` tinyint(1) NOT NULL,
  `weekend_delivery` tinyint(1) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 0,
  `selected` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `service`
--

INSERT INTO `service` (`service_id`, `carrier_id`, `service_name`, `max_weight`, `max_length`, `max_width`, `max_height`, `three_sides`, `circumferential_length`, `check_min_size`, `min_lenght`, `min_width`, `min_height`, `packet_s`, `packet_m`, `packet_l`, `packet_xl`, `address_delivery`, `pickup_delivery`, `cash_on_delivery`, `delivery_from`, `delivery_to`, `evening_delivery`, `weekend_delivery`, `available`, `selected`) VALUES
(1, 2, 'private', 50.0, 175, 0, 0, 0, 300, 0, NULL, NULL, NULL, 1, 1, 1, 0, 1, 1, 1, '00:00:00', '00:00:00', 0, 0, 0, 1),
(2, 2, 'pickup', 20.0, 100, 0, 0, 0, 300, 0, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 1, '00:00:00', '00:00:00', 0, 0, 0, 1),
(3, 1, 'Balík Do ruky', 50.0, 240, 0, 0, 0, 0, 1, 15.0, 10.5, NULL, 1, 1, 1, 0, 1, 1, 1, '08:00:00', '19:00:00', 1, 1, 0, 1),
(7, 1, 'Balík Na poštu', 30.0, 240, NULL, NULL, 300, NULL, 1, 15.0, 10.5, NULL, 1, 1, 0, 0, 0, 1, 1, NULL, NULL, 0, 0, 0, 1),
(8, 1, 'Balík Do balíkovny', 20.0, 70, NULL, NULL, 170, 270, 1, 14.0, 9.0, 0.8, 1, 0, 0, 0, 0, 1, 1, NULL, NULL, 0, 0, 0, 1);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `carrier`
--
ALTER TABLE `carrier`
  ADD PRIMARY KEY (`carrier_id`);

--
-- Klíče pro tabulku `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- Klíče pro tabulku `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `carrier_id_fk` (`carrier_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `carrier`
--
ALTER TABLE `carrier`
  MODIFY `carrier_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pro tabulku `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_service` FOREIGN KEY (`order_id`) REFERENCES `service` (`service_id`);

--
-- Omezení pro tabulku `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `carrier` (`carrier_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
