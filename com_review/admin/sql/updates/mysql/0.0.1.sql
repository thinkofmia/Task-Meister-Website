DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
    `id`            INT(11)     NOT NULL AUTO_INCREMENT,
    `uid`           INT(11)     NOT NULL,
    `aid`           INT(11)     NOT NULL,
    `auid`          BINARY(23) AS (CONCAT(CAST(`aid` AS BINARY), ':', CAST(`uid` AS BINARY))) UNIQUE,
    `ease`          TEXT(255)   NOT NULL,
    `ease_rating`   TINYINT(4) NOT NULL,
    `effectiveness` TEXT(255)   NOT NULL,
    `effectiveness_rating` TINYINT(4) NOT NULL,
    `published` tinyint(4)  NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    CHECK (`ease_rating` >= 0 AND `ease_rating` <= 10 AND `effectiveness_rating` >= 0 and `effectiveness_rating` <= 10 )
)
    ENGINE =InnoDB
    DEFAULT CHARSET =utf8mb4
    DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`aid`, `uid`, `ease`, `ease_rating`, `effectiveness`, `effectiveness_rating`) VALUES
(2, 199, 'Ease of use', 5, 'Effectiveness of use', 9),
(2, 200, 'Ease of use', 7, 'Effectiveness of use', 4);