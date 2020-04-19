-- Struktura tabulky `ceska_posta`
--

CREATE TABLE `ceska_posta` (
`psc` int(5) NOT NULL,
`nazev` varchar(50) NOT NULL,
`okres` varchar(50) NOT NULL,
`adresa` varchar(100) NOT NULL,
`info` text,
PRIMARY KEY (`psc`),
KEY `nazev` (`nazev`),
KEY `adresa` (`adresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;