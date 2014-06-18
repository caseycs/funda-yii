# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.30)
# Database: insided
# Generation Time: 2014-06-18 22:32:11 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table agent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `agent`;

CREATE TABLE `agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MakelaarId` int(10) unsigned NOT NULL,
  `MakelaarNaam` varchar(100) NOT NULL DEFAULT '',
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table funda_filter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `funda_filter`;

CREATE TABLE `funda_filter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `is_garden` tinyint(1) unsigned NOT NULL,
  `fetch_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `funda_filter` WRITE;
/*!40000 ALTER TABLE `funda_filter` DISABLE KEYS */;

INSERT INTO `funda_filter` (`id`, `location`, `type`, `is_garden`, `fetch_time`)
VALUES
	(1,'amsterdam','koop',0,'2014-06-19 02:15:48'),
	(2,'amsterdam','koop',1,'2014-06-19 01:38:49');

/*!40000 ALTER TABLE `funda_filter` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table funda_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `funda_page`;

CREATE TABLE `funda_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `funda_filter_id` int(10) unsigned NOT NULL,
  `number` int(10) NOT NULL,
  `fetch_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `funda_filter_id_number` (`funda_filter_id`,`number`),
  CONSTRAINT `funda_page_ibfk_1` FOREIGN KEY (`funda_filter_id`) REFERENCES `funda_filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table funda_page_realty_link
# ------------------------------------------------------------

DROP TABLE IF EXISTS `funda_page_realty_link`;

CREATE TABLE `funda_page_realty_link` (
  `d_funda_filter_id` int(10) unsigned NOT NULL,
  `funda_page_id` int(10) unsigned NOT NULL,
  `realty_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`d_funda_filter_id`,`funda_page_id`,`realty_id`),
  KEY `funda_page_id` (`funda_page_id`),
  KEY `realty_id` (`realty_id`),
  CONSTRAINT `funda_page_realty_link_ibfk_3` FOREIGN KEY (`realty_id`) REFERENCES `realty` (`id`),
  CONSTRAINT `funda_page_realty_link_ibfk_1` FOREIGN KEY (`d_funda_filter_id`) REFERENCES `funda_filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `funda_page_realty_link_ibfk_2` FOREIGN KEY (`funda_page_id`) REFERENCES `funda_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table realty
# ------------------------------------------------------------

DROP TABLE IF EXISTS `realty`;

CREATE TABLE `realty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` int(10) unsigned NOT NULL,
  `GlobalId` int(10) unsigned NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
