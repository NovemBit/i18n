CREATE TABLE `i18n_translations` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL,
  `from_language` char(2) COLLATE utf8mb4_bin NOT NULL,
  `to_language` char(2) COLLATE utf8mb4_bin NOT NULL,
  `source` text COLLATE utf8mb4_bin,
  `translate` text COLLATE utf8mb4_bin,
  `level` int(2) NOT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  `updated_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `main_combo` (`type`,`from_language`,`to_language`,`source`(100),`level`),
  KEY `language` (`from_language`,`to_language`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;