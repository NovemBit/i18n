-- DROP TABLE IF EXISTS `i18n_translations`;

CREATE TABLE `i18n_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `from_language` varchar(2) COLLATE utf8mb4_bin DEFAULT NULL,
  `to_language` varchar(2) COLLATE utf8mb4_bin DEFAULT NULL,
  `source` longtext COLLATE utf8mb4_bin,
  `translate` longtext COLLATE utf8mb4_bin,
  `level` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`from_language`,`to_language`,`source`(100),`level`)
) ENGINE=InnoDB AUTO_INCREMENT=24988 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;