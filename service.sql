-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 21. bře 2020, 17:33
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
  `packet_s` tinyint(1) NOT NULL,
  `packet_m` tinyint(1) NOT NULL,
  `packet_l` tinyint(1) NOT NULL,
  `packet_xl` tinyint(1) NOT NULL,
  `address_delivery` tinyint(1) NOT NULL,
  `pickup_delivery` tinyint(1) NOT NULL,
  `cash_on_delivery` tinyint(1) NOT NULL,
  `evening_delivery` tinyint(1) NOT NULL,
  `weekend_delivery` tinyint(1) NOT NULL,
  `selected` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `service`
--

INSERT INTO `service` (`service_id`, `carrier_id`, `service_name`, `max_weight`, `max_length`, `max_width`, `max_height`, `three_sides`, `circumferential_length`, `packet_s`, `packet_m`, `packet_l`, `packet_xl`, `address_delivery`, `pickup_delivery`, `cash_on_delivery`, `evening_delivery`, `weekend_delivery`, `selected`) VALUES
(1, 2, 'private', 50.0, 175, 0, 0, 0, 300, 1, 1, 1, 0, 1, 1, 1, 0, 0, 0),
(2, 2, 'pickup', 20.0, 100, 0, 0, 0, 300, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(3, 1, 'Balík Do ruky', 50.0, 240, 0, 0, 0, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0),
(7, 1, 'Balík Na poštu', 30.0, 240, NULL, NULL, 300, NULL, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0),
(8, 1, 'Balík Do balíkovny', 20.0, 70, NULL, NULL, 170, 270, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(9, 9, 'Doručení na výdejní místa', 10.0, 120, NULL, NULL, 150, NULL, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(10, 3, 'Standardní zásilka', 50.0, 200, 80, 60, 0, 300, 1, 1, 1, 0, 1, 1, 1, 0, 0, 0),
(11, 3, 'Na výdejní místo', 15.0, 80, 60, 42, 182, NULL, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(12, 7, 'Top Trans Doručení', 3000.0, 600, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
(13, 5, 'Standard - Na adresu', 30.0, 120, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 1, 0, 1, 0, 0, 0),
(14, 5, 'Standard - Na výdejní místo', 15.0, 120, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(15, 5, 'Nadrozměrný balík', 50.0, 150, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 1, 0, 1, 0, 0, 0),
(16, 4, 'Business Parcel', 40.0, 200, 80, 60, NULL, 300, 1, 1, 1, 0, 1, 1, 1, 1, 0, 0),
(17, 4, 'Business Small Parcel', 2.0, 40, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 1, 1, 1, 1, 0, 0),
(18, 8, 'Na výdejní místo', 20.0, 100, NULL, NULL, 175, 0, 1, 0, 0, 0, 0, 1, 1, 0, 0, 0),
(19, 8, 'Na adresu', 31.0, 120, NULL, NULL, 210, NULL, 1, 1, 0, 0, 1, 0, 1, 0, 0, 0);

--
-- Klíče pro exportované tabulky
--

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
-- AUTO_INCREMENT pro tabulku `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `carrier` (`carrier_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
