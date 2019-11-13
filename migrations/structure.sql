CREATE TABLE `i18n_translations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(3) unsigned NOT NULL,
  `from_language` char(2) COLLATE utf8mb4_bin NOT NULL,
  `to_language` char(2) COLLATE utf8mb4_bin NOT NULL,
  `source` text COLLATE utf8mb4_bin,
  `translate` text COLLATE utf8mb4_bin,
  `level` int(2) unsigned NOT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  `updated_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `main_combo` (`type`,`from_language`,`to_language`,`source`(200),`level`),
  KEY `main_combo_re` (`type`,`from_language`,`to_language`,`translate`(200),`level`),
  KEY `language` (`from_language`,`to_language`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;