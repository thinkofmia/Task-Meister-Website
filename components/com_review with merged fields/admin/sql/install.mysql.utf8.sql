DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
    `id`            INT(11)     UNSIGNED NOT NULL AUTO_INCREMENT,
    `created`       DATE        NOT NULL DEFAULT '0000-00-00',
    `updated`       DATE        NOT NULL DEFAULT '0000-00-00',
    `uid`           INT(11)     UNSIGNED NOT NULL,
    `aid`           INT(11)     UNSIGNED NOT NULL,
    `auid`          BINARY(23) AS (CONCAT(CAST(`aid` AS BINARY), ':', CAST(`uid` AS BINARY))) VIRTUAL UNIQUE,
    `rating`        TINYINT(4) NOT NULL,
    `review`        TEXT(255)   NOT NULL,
    `published` TINYINT(4)  NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    CHECK (`rating` >= 0 AND `rating` <= 10)
)
    ENGINE =InnoDB
    DEFAULT CHARSET =utf8mb4
    DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`aid`, `uid`, `review`, `rating`) VALUES
(2, 199, 'Sample review', 5),
(2, 200, 'Sample review', 7);