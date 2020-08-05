UPDATE `reviews` SET `rating` = 1 WHERE `rating` = 0;
ALTER TABLE `reviews` CHANGE `rating` `rating` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10') NOT NULL;