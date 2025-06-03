-- hotel.sql: Clean version

CREATE DATABASE IF NOT EXISTS hotel CHARACTER SET UTF8 COLLATE utf8_hungarian_ci;
USE hotel;

-- Guests table
CREATE TABLE `guests` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) DEFAULT NULL,
  `age` INT(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- Rooms table
CREATE TABLE `rooms` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `floor` INT(10) NOT NULL,
  `room_number` INT(10) NOT NULL,
  `accommodation` INT(10) NOT NULL,
  `price` INT(10) NOT NULL,
  `comment` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

-- Reservations table
CREATE TABLE `reservations` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `room_id` INT(10) DEFAULT NULL,
  `guest_id` INT(10) DEFAULT NULL,
  `days` INT(10) DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guests_id` (`guest_id`),
  KEY `rooms_id` (`room_id`),
  CONSTRAINT `guests_id` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rooms_id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
