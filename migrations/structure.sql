CREATE TABLE `i18n_translations`
(
    `id`             int(10) unsigned                                  NOT NULL AUTO_INCREMENT,
    `type`           int(3)                                            NOT NULL,
    `from_language`  char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `to_language`    char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `source`         text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
    `translate`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
    `level`          int(2)                                            NOT NULL,
    `source_hash`    varchar(32) DEFAULT NULL,
    `translate_hash` varchar(32) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `main_combo_hash` (`type`, `from_language`, `to_language`, `source_hash`, `level`),
    KEY `main_combo_re_hash` (`type`, `from_language`, `to_language`, `translate_hash`, `level`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = latin1;
