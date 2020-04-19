-- Struktura tabulky `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `product_ids` text NOT NULL COMMENT 'pole id produktu v objednavce',
  `price` decimal(10,0) NOT NULL COMMENT 'celkova cena objednavky',
  `date` datetime NOT NULL COMMENT 'datum vytvoreni objednavky',
  `receiver_name` varchar(128) NOT NULL COMMENT 'jmeno prijemce',
  `cash_on_delivery` tinyint(1) NOT NULL DEFAULT 0,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `state` tinyint(4) NOT NULL COMMENT '1=vytvoreno, 2=zpracovano',
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`order_id`) REFERENCES `service` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
