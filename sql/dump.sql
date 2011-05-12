DROP DATABASE IF EXISTS `granat`;

CREATE DATABASE `granat`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `granat`;

--
-- Структура таблицы `gr_categories`
--

CREATE TABLE IF NOT EXISTS `gr_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_collections` table :
#

DROP TABLE IF EXISTS `gr_collections`;

CREATE TABLE `gr_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `order` tinyint(3) unsigned NOT NULL,
  `main_model_id` INT( 11 ) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `order` (`order`),
  KEY `main_model_id` (`main_model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_fabrics` table :
#

DROP TABLE IF EXISTS `gr_fabrics`;

CREATE TABLE `gr_fabrics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `photo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_models` table :
#

DROP TABLE IF EXISTS `gr_models`;

CREATE TABLE `gr_models` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `collection_id` int(11) unsigned NOT NULL,
  `category_id` INT( 11 ) UNSIGNED NOT NULL,
  `main_photo_id` INT( 11 ) UNSIGNED NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `collection_id` (`collection_id`),
  KEY `category_id` (`category_id`),
  KEY `main_photo_id` (`main_photo_id`),
  KEY `order` (`order`),
  CONSTRAINT `model_collection` FOREIGN KEY (`collection_id`) REFERENCES `gr_collections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `model_category` FOREIGN KEY (`category_id`) REFERENCES `gr_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_photos` table :
#

DROP TABLE IF EXISTS `gr_photos`;

CREATE TABLE `gr_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(10) NOT NULL,
  `extension` VARCHAR(5) NOT NULL,
  `model_id` int(11) unsigned NOT NULL,
  `order` int(3) unsigned NOT NULL DEFAULT '0',
  `is_model_title` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  KEY `order` (`order`),
  CONSTRAINT `gr_photos_fk` FOREIGN KEY (`model_id`) REFERENCES `gr_models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_users` table :
#

DROP TABLE IF EXISTS `gr_users`;

CREATE TABLE `gr_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `role` enum('admin','user','moderator') DEFAULT 'user',
  `date_added` datetime NOT NULL,
  `status` enum('active','banned','suspended') NOT NULL DEFAULT 'suspended',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `gr_users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `date_added`, `status`) VALUES
(1, 'Victor', 'Gryshko', 'victor@skaya.net', 'cca8dd8babd4c9996c8dfee788a49d18', 'admin', '2011-05-07 17:02:53', 'active');
