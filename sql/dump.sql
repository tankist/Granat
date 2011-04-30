# SQL Manager 2010 for MySQL 4.5.0.9
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : granat


SET FOREIGN_KEY_CHECKS=0;

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
  `key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_collections` table : 
#

DROP TABLE IF EXISTS `gr_collections`;

CREATE TABLE `gr_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
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
  `is_collection_title` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `collection_id` (`collection_id`),
  KEY `order` (`order`),
  CONSTRAINT `gr_models_fk` FOREIGN KEY (`collection_id`) REFERENCES `gr_collections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `gr_model_categories`
--

CREATE TABLE IF NOT EXISTS `gr_model_categories` (
  `category_id` int(11) unsigned NOT NULL,
  `model_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`model_id`),
  KEY `category_id` (`category_id`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `gr_photos` table : 
#

DROP TABLE IF EXISTS `gr_photos`;

CREATE TABLE `gr_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(10) NOT NULL,
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

ALTER TABLE `gr_model_categories`
	ADD FOREIGN KEY ( `category_id` ) REFERENCES `granat`.`gr_categories` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE ;

ALTER TABLE `gr_model_categories`
	ADD FOREIGN KEY ( `model_id` ) REFERENCES `granat`.`gr_models` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE ;