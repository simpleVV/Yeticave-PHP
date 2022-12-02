DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE `yeticave`
 DEFAULT CHARACTER SET utf8mb4
 DEFAULT COLLATE utf8mb4_unicode_ci;

USE yeticave;

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(60) NOT NULL,
    `character_code` VARCHAR(128) UNIQUE NOT NULL,
    PRIMARY KEY (`id`));

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `date_registration` DATETIME NOT NULl,
    `email` VARCHAR(68) NOT NULL UNIQUE,
    `user_name` VARCHAR(128) NOT NULL,
    `password` CHAR(255) NOt NULL,
    `contacts` TEXT,
    PRIMARY KEY (`id`));

DROP TABLE IF EXISTS `lots`;

CREATE TABLE `lots` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `date_creation` DATETIME NOT NULl,
    `title` VARCHAR(128) NOT NULL,
    `description` TEXT,
    `image` VARCHAR(255),
    `start_price` INT NOT NULL,
    `date_end` DATETIME NOT NULL,
    `bet_step` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `winner_id` INT(11) UNSIGNED NOT NULL,
    `category_id` INT(11) UNSIGNED NOT NULL,
    CONSTRAINT `lots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lots_ibfk_2` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lots_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `category`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`id`));

CREATE FULLTEXT INDEX `lots_search` ON `lots`(title, description);

DROP TABLE IF EXISTS `bets`;

CREATE TABLE `bets` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `bet_date` DATETIME NOT NULl,
    `price` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `lot_id` INT(11) UNSIGNED NOT NULL,
    CONSTRAINT `bets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bets_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (`id`));
