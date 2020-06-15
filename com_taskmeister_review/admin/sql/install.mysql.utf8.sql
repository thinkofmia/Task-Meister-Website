DROP TABLE IF EXISTS `taskmeister_reviews`;

CREATE TABLE `taskmeister_reviews` (
    `id`            INT(11)     NOT NULL AUTO_INCREMENT,
    `uid`           INT(11)     NOT NULL UNIQUE,
    `ease`          TEXT(255)   NOT NULL,
    `ease_rating`
        ENUM('0', '1', '2', '3', '4', '5', '6', '7', '8', '9','A') NOT NULL,
    `effectiveness` TEXT(255)   NOT NULL,
    `effectiveness_rating`
            ENUM('0', '1', '2', '3', '4', '5', '6', '7', '8', '9','A') NOT NULL,
    `published` tinyint(4)  NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
)
    ENGINE =InnoDB
    DEFAULT CHARSET =utf8mb4
    DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `taskmeister_reviews` (`uid`, `ease`, `ease_rating`, `effectiveness`, `effectiveness_rating`) VALUES
('199', 'Ease of use', '5', 'Effectiveness of use', '9'),
('200', 'Ease of use', '7', 'Effectiveness of use', '4');