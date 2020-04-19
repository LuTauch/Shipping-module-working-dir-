-- Struktura tabulky `zasilkovna`
--

CREATE TABLE `zasilkovna` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `zip` varchar(6) NOT NULL,
  `country` varchar(30) DEFAULT NULL,
  `directions` text,
  `zasilkovna_url` varchar(200) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4795 DEFAULT CHARSET=utf8;