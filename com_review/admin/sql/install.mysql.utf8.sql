DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
    `id`            INT(11)     UNSIGNED NOT NULL AUTO_INCREMENT,
    `created`       DATE        NOT NULL DEFAULT '0000-00-00',
    `uid`           INT(11)     UNSIGNED NOT NULL,
    `aid`           INT(11)     UNSIGNED NOT NULL,
    `auid`          BINARY(23) AS (CONCAT(CAST(`aid` AS BINARY), ':', CAST(`uid` AS BINARY))) VIRTUAL UNIQUE,
    `ease`          TEXT(255)   NOT NULL,
    `ease_rating`   TINYINT(4) NOT NULL,
    `effectiveness` TEXT(255)   NOT NULL,
    `effectiveness_rating` TINYINT(4) NOT NULL,
    `summary`       TEXT(255)   NOT NULL,
    `overall_rating` TINYINT(4) AS (CEILING((`ease_rating` + `effectiveness_rating`)/2)) VIRTUAL NOT NULL,
    `published` TINYINT(4)  NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    CHECK (`ease_rating` >= 0 AND `ease_rating` <= 10 AND `effectiveness_rating` >= 0 and `effectiveness_rating` <= 10 )
)
    ENGINE =InnoDB
    DEFAULT CHARSET =utf8mb4
    DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`aid`, `uid`, `ease`, `ease_rating`, `effectiveness`, `effectiveness_rating`, `summary`) VALUES
(2, 199, 'Ease of use', 5, 'Effectiveness of use', 9, 'Sample summary'),
(2, 200, 'Ease of use', 7, 'Effectiveness of use', 4, 'Sample summary');