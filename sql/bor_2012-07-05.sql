# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.8)
# Database: bor
# Generation Time: 2012-07-05 06:30:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table elgg_access_collection_membership
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_access_collection_membership`;

CREATE TABLE `elgg_access_collection_membership` (
  `user_guid` int(11) NOT NULL,
  `access_collection_id` int(11) NOT NULL,
  PRIMARY KEY (`user_guid`,`access_collection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table elgg_access_collections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_access_collections`;

CREATE TABLE `elgg_access_collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `owner_guid` (`owner_guid`),
  KEY `site_guid` (`site_guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table elgg_annotations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_annotations`;

CREATE TABLE `elgg_annotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_guid` bigint(20) unsigned NOT NULL,
  `name_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `value_type` enum('integer','text') NOT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `access_id` int(11) NOT NULL,
  `time_created` int(11) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `entity_guid` (`entity_guid`),
  KEY `name_id` (`name_id`),
  KEY `value_id` (`value_id`),
  KEY `owner_guid` (`owner_guid`),
  KEY `access_id` (`access_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_annotations` WRITE;
/*!40000 ALTER TABLE `elgg_annotations` DISABLE KEYS */;

INSERT INTO `elgg_annotations` (`id`, `entity_guid`, `name_id`, `value_id`, `value_type`, `owner_guid`, `access_id`, `time_created`, `enabled`)
VALUES
	(1,49,33,32,'text',35,2,1341390705,'yes'),
	(2,49,35,34,'text',35,2,1341390751,'yes'),
	(3,49,51,51,'text',48,2,1341391129,'yes'),
	(4,50,51,51,'text',48,2,1341391144,'yes');

/*!40000 ALTER TABLE `elgg_annotations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_api_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_api_users`;

CREATE TABLE `elgg_api_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_guid` bigint(20) unsigned DEFAULT NULL,
  `api_key` varchar(40) DEFAULT NULL,
  `secret` varchar(40) NOT NULL,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table elgg_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_config`;

CREATE TABLE `elgg_config` (
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `site_guid` int(11) NOT NULL,
  PRIMARY KEY (`name`,`site_guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_config` WRITE;
/*!40000 ALTER TABLE `elgg_config` DISABLE KEYS */;

INSERT INTO `elgg_config` (`name`, `value`, `site_guid`)
VALUES
	('view','N;',1),
	('language','s:2:\"en\";',1),
	('default_access','s:1:\"1\";',1),
	('allow_registration','b:1;',1),
	('walled_garden','b:1;',1),
	('allow_user_default_access','i:0;',1),
	('admin_defined_profile_1','s:13:\"Date of Birth\";',1),
	('admin_defined_profile_type_1','s:4:\"date\";',1),
	('admin_defined_profile_2','s:13:\"Date of Death\";',1),
	('admin_defined_profile_type_2','s:4:\"date\";',1),
	('profile_custom_fields','s:13:\"1,2,3,4,5,6,7\";',1),
	('admin_defined_profile_3','s:29:\"College Attended or Attending\";',1),
	('admin_defined_profile_type_3','s:4:\"text\";',1),
	('admin_defined_profile_4','s:33:\"High School Attended or Attending\";',1),
	('admin_defined_profile_type_4','s:4:\"text\";',1),
	('admin_defined_profile_5','s:27:\"High School Graduation Date\";',1),
	('admin_defined_profile_type_5','s:4:\"date\";',1),
	('admin_defined_profile_6','s:23:\"College Graduation Date\";',1),
	('admin_defined_profile_type_6','s:4:\"date\";',1),
	('admin_defined_profile_7','s:13:\"City of Birth\";',1),
	('admin_defined_profile_type_7','s:4:\"text\";',1);

/*!40000 ALTER TABLE `elgg_config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_datalists
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_datalists`;

CREATE TABLE `elgg_datalists` (
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_datalists` WRITE;
/*!40000 ALTER TABLE `elgg_datalists` DISABLE KEYS */;

INSERT INTO `elgg_datalists` (`name`, `value`)
VALUES
	('filestore_run_once','1341390257'),
	('plugin_run_once','1341390257'),
	('elgg_widget_run_once','1341390257'),
	('installed','1341390323'),
	('path','/Users/philvas/bookofrelations/'),
	('dataroot','/Users/philvas/bookofrelations_data/'),
	('default_site','1'),
	('version','2012061800'),
	('simplecache_enabled','1'),
	('system_cache_enabled','1'),
	('processed_upgrades','a:40:{i:0;s:14:\"2008100701.php\";i:1;s:14:\"2008101303.php\";i:2;s:14:\"2009022701.php\";i:3;s:14:\"2009041701.php\";i:4;s:14:\"2009070101.php\";i:5;s:14:\"2009102801.php\";i:6;s:14:\"2010010501.php\";i:7;s:14:\"2010033101.php\";i:8;s:14:\"2010040201.php\";i:9;s:14:\"2010052601.php\";i:10;s:14:\"2010060101.php\";i:11;s:14:\"2010060401.php\";i:12;s:14:\"2010061501.php\";i:13;s:14:\"2010062301.php\";i:14;s:14:\"2010062302.php\";i:15;s:14:\"2010070301.php\";i:16;s:14:\"2010071001.php\";i:17;s:14:\"2010071002.php\";i:18;s:14:\"2010111501.php\";i:19;s:14:\"2010121601.php\";i:20;s:14:\"2010121602.php\";i:21;s:14:\"2010121701.php\";i:22;s:14:\"2010123101.php\";i:23;s:14:\"2011010101.php\";i:24;s:61:\"2011021800-1.8_svn-goodbye_walled_garden-083121a656d06894.php\";i:25;s:61:\"2011022000-1.8_svn-custom_profile_fields-390ac967b0bb5665.php\";i:26;s:60:\"2011030700-1.8_svn-blog_status_metadata-4645225d7b440876.php\";i:27;s:51:\"2011031300-1.8_svn-twitter_api-12b832a5a7a3e1bd.php\";i:28;s:57:\"2011031600-1.8_svn-datalist_grows_up-0b8aec5a55cc1e1c.php\";i:29;s:61:\"2011032000-1.8_svn-widgets_arent_plugins-61836261fa280a5c.php\";i:30;s:59:\"2011032200-1.8_svn-admins_like_widgets-7f19d2783c1680d3.php\";i:31;s:14:\"2011052801.php\";i:32;s:60:\"2011061200-1.8b1-sites_need_a_site_guid-6d9dcbf46c0826cc.php\";i:33;s:62:\"2011092500-1.8.0.1-forum_reply_river_view-5758ce8d86ac56ce.php\";i:34;s:54:\"2011123100-1.8.2-fix_friend_river-b17e7ff8345c2269.php\";i:35;s:53:\"2011123101-1.8.2-fix_blog_status-b14c2a0e7b9e7d55.php\";i:36;s:50:\"2012012000-1.8.3-ip_in_syslog-87fe0f068cf62428.php\";i:37;s:50:\"2012012100-1.8.3-system_cache-93100e7d55a24a11.php\";i:38;s:59:\"2012041800-1.8.3-dont_filter_passwords-c0ca4a18b38ae2bc.php\";i:39;s:58:\"2012041801-1.8.3-multiple_user_tokens-852225f7fd89f6c5.php\";}'),
	('admin_registered','1'),
	('simplecache_lastupdate_default','1341457396'),
	('simplecache_lastcached_default','1341457396'),
	('__site_secret__','7849d59e231148a88e247d6586287239'),
	('simplecache_lastupdate_rss','0'),
	('simplecache_lastcached_rss','0'),
	('simplecache_lastupdate_failsafe','0'),
	('simplecache_lastcached_failsafe','0'),
	('simplecache_lastupdate_foaf','0'),
	('simplecache_lastcached_foaf','0'),
	('simplecache_lastupdate_ical','0'),
	('simplecache_lastcached_ical','0'),
	('simplecache_lastupdate_installation','0'),
	('simplecache_lastcached_installation','0'),
	('simplecache_lastupdate_json','0'),
	('simplecache_lastcached_json','0'),
	('simplecache_lastupdate_opendd','0'),
	('simplecache_lastcached_opendd','0'),
	('simplecache_lastupdate_php','0'),
	('simplecache_lastcached_php','0'),
	('simplecache_lastupdate_xml','0'),
	('simplecache_lastcached_xml','0');

/*!40000 ALTER TABLE `elgg_datalists` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_entities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_entities`;

CREATE TABLE `elgg_entities` (
  `guid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('object','user','group','site') NOT NULL,
  `subtype` int(11) DEFAULT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL,
  `container_guid` bigint(20) unsigned NOT NULL,
  `access_id` int(11) NOT NULL,
  `time_created` int(11) NOT NULL,
  `time_updated` int(11) NOT NULL,
  `last_action` int(11) NOT NULL DEFAULT '0',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`guid`),
  KEY `type` (`type`),
  KEY `subtype` (`subtype`),
  KEY `owner_guid` (`owner_guid`),
  KEY `site_guid` (`site_guid`),
  KEY `container_guid` (`container_guid`),
  KEY `access_id` (`access_id`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_entities` WRITE;
/*!40000 ALTER TABLE `elgg_entities` DISABLE KEYS */;

INSERT INTO `elgg_entities` (`guid`, `type`, `subtype`, `owner_guid`, `site_guid`, `container_guid`, `access_id`, `time_created`, `time_updated`, `last_action`, `enabled`)
VALUES
	(1,'site',0,0,1,0,2,1341390323,1341391659,1341390323,'yes'),
	(2,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(3,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(4,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(5,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(6,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(7,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(8,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(9,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(10,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(11,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(12,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(13,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(14,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(15,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(16,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(17,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(18,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(19,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(20,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(21,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(22,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(23,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(24,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(25,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(26,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(27,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(28,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(29,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(30,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(31,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(32,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(33,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(34,'object',2,1,1,1,2,1341390323,1341390323,1341390323,'yes'),
	(35,'user',0,0,1,0,2,1341390356,1341457399,1341391118,'yes'),
	(36,'object',3,35,1,35,0,1341390356,1341390356,1341390356,'yes'),
	(37,'object',3,35,1,35,0,1341390356,1341390356,1341390356,'yes'),
	(38,'object',3,35,1,35,0,1341390356,1341390356,1341390356,'yes'),
	(39,'object',3,35,1,35,0,1341390356,1341390356,1341390356,'yes'),
	(40,'object',3,35,1,35,0,1341390356,1341390356,1341390356,'yes'),
	(41,'user',0,0,1,0,2,1341390399,1341391682,1341391108,'yes'),
	(42,'object',3,41,1,41,2,1341390399,1341390399,1341390399,'yes'),
	(43,'object',3,41,1,41,2,1341390399,1341390399,1341390399,'yes'),
	(44,'object',3,41,1,41,2,1341390399,1341390399,1341390399,'yes'),
	(45,'object',3,41,1,41,2,1341390399,1341390399,1341390399,'yes'),
	(46,'object',3,41,1,41,2,1341390399,1341390399,1341390399,'yes'),
	(47,'user',0,0,1,0,2,1341390439,1341390439,1341390439,'yes'),
	(48,'user',0,0,1,0,2,1341390471,1341391246,1341390471,'yes'),
	(49,'object',6,35,1,35,2,1341390705,1341390705,1341390751,'yes'),
	(50,'object',1,35,1,35,2,1341390844,1341390844,1341390844,'yes'),
	(51,'object',7,41,1,41,0,1341391238,1341391238,1341391238,'yes'),
	(52,'object',7,48,1,48,0,1341391238,1341391238,1341391238,'yes'),
	(54,'object',2,1,1,1,2,1341417725,1341417725,1341417725,'yes'),
	(55,'object',9,35,1,35,2,1341423220,1341423220,1341423220,'yes');

/*!40000 ALTER TABLE `elgg_entities` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_entity_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_entity_relationships`;

CREATE TABLE `elgg_entity_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid_one` bigint(20) unsigned NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `guid_two` bigint(20) unsigned NOT NULL,
  `time_created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid_one` (`guid_one`,`relationship`,`guid_two`),
  KEY `relationship` (`relationship`),
  KEY `guid_two` (`guid_two`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_entity_relationships` WRITE;
/*!40000 ALTER TABLE `elgg_entity_relationships` DISABLE KEYS */;

INSERT INTO `elgg_entity_relationships` (`id`, `guid_one`, `relationship`, `guid_two`, `time_created`)
VALUES
	(1,2,'active_plugin',1,1341390323),
	(2,3,'active_plugin',1,1341390323),
	(3,11,'active_plugin',1,1341390323),
	(4,12,'active_plugin',1,1341390323),
	(5,13,'active_plugin',1,1341390323),
	(6,14,'active_plugin',1,1341390323),
	(7,15,'active_plugin',1,1341390323),
	(8,16,'active_plugin',1,1341390323),
	(9,17,'active_plugin',1,1341390323),
	(10,18,'active_plugin',1,1341390323),
	(11,19,'active_plugin',1,1341390323),
	(12,20,'active_plugin',1,1341390323),
	(13,21,'active_plugin',1,1341390323),
	(14,22,'active_plugin',1,1341390323),
	(15,24,'active_plugin',1,1341390323),
	(16,25,'active_plugin',1,1341390323),
	(17,26,'active_plugin',1,1341390323),
	(18,27,'active_plugin',1,1341390323),
	(19,29,'active_plugin',1,1341390323),
	(20,30,'active_plugin',1,1341390323),
	(21,33,'active_plugin',1,1341390323),
	(22,34,'active_plugin',1,1341390324),
	(23,35,'member_of_site',1,1341390356),
	(24,41,'member_of_site',1,1341390399),
	(25,47,'member_of_site',1,1341390439),
	(26,48,'member_of_site',1,1341390471),
	(27,35,'friend',41,1341390495),
	(28,41,'friend',35,1341391027),
	(29,48,'friend',41,1341391108),
	(30,48,'friend',35,1341391118),
	(31,4,'active_plugin',1,1341416866),
	(32,10,'active_plugin',1,1341416900),
	(33,5,'active_plugin',1,1341417269),
	(34,7,'active_plugin',1,1341417277);

/*!40000 ALTER TABLE `elgg_entity_relationships` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_entity_subtypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_entity_subtypes`;

CREATE TABLE `elgg_entity_subtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('object','user','group','site') NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`subtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_entity_subtypes` WRITE;
/*!40000 ALTER TABLE `elgg_entity_subtypes` DISABLE KEYS */;

INSERT INTO `elgg_entity_subtypes` (`id`, `type`, `subtype`, `class`)
VALUES
	(1,'object','file','ElggFile'),
	(2,'object','plugin','ElggPlugin'),
	(3,'object','widget','ElggWidget'),
	(4,'object','blog','ElggBlog'),
	(5,'object','thewire','ElggWire'),
	(6,'object','page_top',''),
	(7,'object','messages',''),
	(8,'object','admin_notice',''),
	(9,'object','about','');

/*!40000 ALTER TABLE `elgg_entity_subtypes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_geocode_cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_geocode_cache`;

CREATE TABLE `elgg_geocode_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(128) DEFAULT NULL,
  `lat` varchar(20) DEFAULT NULL,
  `long` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`location`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;



# Dump of table elgg_groups_entity
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_groups_entity`;

CREATE TABLE `elgg_groups_entity` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`guid`),
  KEY `name` (`name`(50)),
  KEY `description` (`description`(50)),
  FULLTEXT KEY `name_2` (`name`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table elgg_hmac_cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_hmac_cache`;

CREATE TABLE `elgg_hmac_cache` (
  `hmac` varchar(255) NOT NULL,
  `ts` int(11) NOT NULL,
  PRIMARY KEY (`hmac`),
  KEY `ts` (`ts`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;



# Dump of table elgg_metadata
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_metadata`;

CREATE TABLE `elgg_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_guid` bigint(20) unsigned NOT NULL,
  `name_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `value_type` enum('integer','text') NOT NULL,
  `owner_guid` bigint(20) unsigned NOT NULL,
  `access_id` int(11) NOT NULL,
  `time_created` int(11) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `entity_guid` (`entity_guid`),
  KEY `name_id` (`name_id`),
  KEY `value_id` (`value_id`),
  KEY `owner_guid` (`owner_guid`),
  KEY `access_id` (`access_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_metadata` WRITE;
/*!40000 ALTER TABLE `elgg_metadata` DISABLE KEYS */;

INSERT INTO `elgg_metadata` (`id`, `entity_guid`, `name_id`, `value_id`, `value_type`, `owner_guid`, `access_id`, `time_created`, `enabled`)
VALUES
	(1,1,1,2,'text',0,2,1341390323,'yes'),
	(2,35,3,4,'text',35,2,1341390356,'yes'),
	(3,35,5,4,'text',0,2,1341390356,'yes'),
	(4,35,6,7,'text',0,2,1341390356,'yes'),
	(5,41,3,4,'text',41,2,1341390399,'yes'),
	(6,41,5,4,'text',35,2,1341390399,'yes'),
	(7,41,6,7,'text',35,2,1341390399,'yes'),
	(8,41,8,4,'text',41,2,1341390399,'yes'),
	(9,41,9,10,'integer',41,2,1341390399,'yes'),
	(10,47,3,4,'text',47,2,1341390439,'yes'),
	(11,47,8,4,'text',47,2,1341390439,'yes'),
	(12,47,9,10,'integer',47,2,1341390439,'yes'),
	(13,48,3,4,'text',48,2,1341390471,'yes'),
	(14,48,8,4,'text',48,2,1341390471,'yes'),
	(15,48,9,10,'integer',48,2,1341390471,'yes'),
	(16,35,11,12,'text',35,2,1341390651,'yes'),
	(17,35,13,14,'text',35,2,1341390651,'yes'),
	(18,35,15,16,'text',35,2,1341390651,'yes'),
	(19,35,17,18,'text',35,2,1341390651,'yes'),
	(20,35,17,19,'text',35,2,1341390651,'yes'),
	(21,35,17,20,'text',35,2,1341390651,'yes'),
	(22,35,17,21,'text',35,2,1341390651,'yes'),
	(23,35,22,23,'text',35,2,1341390651,'yes'),
	(24,35,24,2,'text',35,2,1341390651,'yes'),
	(25,35,25,26,'text',35,2,1341390651,'yes'),
	(26,35,27,28,'text',35,2,1341390651,'yes'),
	(27,35,29,28,'text',35,2,1341390651,'yes'),
	(28,35,30,28,'text',35,2,1341390651,'yes'),
	(29,49,31,4,'text',35,2,1341390705,'yes'),
	(30,50,36,37,'text',35,2,1341390844,'yes'),
	(31,50,38,39,'text',35,2,1341390844,'yes'),
	(32,50,40,41,'text',35,2,1341390844,'yes'),
	(33,50,42,43,'text',35,2,1341390844,'yes'),
	(34,50,44,45,'text',35,2,1341390844,'yes'),
	(35,50,46,47,'text',35,2,1341390844,'yes'),
	(36,50,48,49,'text',35,2,1341390844,'yes'),
	(37,41,11,50,'text',41,2,1341390972,'yes'),
	(38,41,13,28,'text',41,2,1341390972,'yes'),
	(39,41,15,16,'text',41,2,1341390972,'yes'),
	(40,41,17,18,'text',41,2,1341390972,'yes'),
	(41,41,17,19,'text',41,2,1341390972,'yes'),
	(42,41,17,20,'text',41,2,1341390972,'yes'),
	(43,41,17,21,'text',41,2,1341390972,'yes'),
	(44,41,24,2,'text',41,2,1341390972,'yes'),
	(45,41,25,26,'text',41,2,1341390972,'yes'),
	(46,41,27,28,'text',41,2,1341390972,'yes'),
	(47,41,29,28,'text',41,2,1341390972,'yes'),
	(48,41,30,28,'text',41,2,1341390972,'yes'),
	(49,51,52,53,'text',41,2,1341391238,'yes'),
	(50,51,54,55,'integer',41,2,1341391238,'yes'),
	(51,51,56,57,'integer',41,2,1341391238,'yes'),
	(52,51,58,57,'integer',41,2,1341391238,'yes'),
	(53,51,59,57,'integer',41,2,1341391238,'yes'),
	(54,51,60,4,'integer',41,2,1341391238,'yes'),
	(55,52,52,53,'text',48,0,1341391238,'yes'),
	(56,52,54,55,'integer',48,0,1341391238,'yes'),
	(57,52,56,57,'integer',48,0,1341391238,'yes'),
	(58,52,58,57,'integer',48,0,1341391238,'yes'),
	(59,52,59,57,'integer',48,0,1341391238,'yes'),
	(60,52,60,4,'integer',48,0,1341391238,'yes'),
	(62,1,63,19,'text',35,2,1341417231,'yes');

/*!40000 ALTER TABLE `elgg_metadata` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_metastrings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_metastrings`;

CREATE TABLE `elgg_metastrings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `string` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `string` (`string`(50))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_metastrings` WRITE;
/*!40000 ALTER TABLE `elgg_metastrings` DISABLE KEYS */;

INSERT INTO `elgg_metastrings` (`id`, `string`)
VALUES
	(1,'email'),
	(2,'tdcribb@gmail.com'),
	(3,'notification:method:email'),
	(4,'1'),
	(5,'validated'),
	(6,'validated_method'),
	(7,'admin_user'),
	(8,'admin_created'),
	(9,'created_by_guid'),
	(10,'35'),
	(11,'description'),
	(12,'<p>This is where the user will type in their autobiography...</p>\r\n<p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum </p>\r\n<p> </p>\r\n<p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum </p>'),
	(13,'briefdescription'),
	(14,'Test autobiography section'),
	(15,'location'),
	(16,'Charleston SC'),
	(17,'interests'),
	(18,'Sports'),
	(19,'Books'),
	(20,'Movies'),
	(21,'etc'),
	(22,'skills'),
	(23,'Creating websites'),
	(24,'contactemail'),
	(25,'phone'),
	(26,'843-555-1212'),
	(27,'mobile'),
	(28,''),
	(29,'website'),
	(30,'twitter'),
	(31,'write_access_id'),
	(32,'<p>This is where the user will add their new page information...</p>\r\n<p>Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;</p>\r\n<p>Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;</p>'),
	(33,'page'),
	(34,'<p>Comment text about this page here.</p>'),
	(35,'generic_comment'),
	(36,'tags'),
	(37,'Tod Cribb'),
	(38,'filename'),
	(39,'file/1341390844resume.odt'),
	(40,'mimetype'),
	(41,'application/vnd.oasis.opendocument.text'),
	(42,'originalfilename'),
	(43,'Resume.odt'),
	(44,'simpletype'),
	(45,'document'),
	(46,'filestore::dir_root'),
	(47,'/Users/philvas/bookofrelations_data/'),
	(48,'filestore::filestore'),
	(49,'ElggDiskFilestore'),
	(50,'<p>Tod Cribb\'s autobiography would be written here....</p>\r\n<p>Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum </p>'),
	(51,'likes'),
	(52,'toId'),
	(53,'41'),
	(54,'fromId'),
	(55,'48'),
	(56,'readYet'),
	(57,'0'),
	(58,'hiddenFrom'),
	(59,'hiddenTo'),
	(60,'msg'),
	(61,'admin_notice_id'),
	(62,'categories_admin_notice_no_categories'),
	(63,'categories');

/*!40000 ALTER TABLE `elgg_metastrings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_objects_entity
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_objects_entity`;

CREATE TABLE `elgg_objects_entity` (
  `guid` bigint(20) unsigned NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`guid`),
  FULLTEXT KEY `title` (`title`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_objects_entity` WRITE;
/*!40000 ALTER TABLE `elgg_objects_entity` DISABLE KEYS */;

INSERT INTO `elgg_objects_entity` (`guid`, `title`, `description`)
VALUES
	(2,'blog',''),
	(3,'bookmarks',''),
	(4,'categories',''),
	(5,'custom_index',''),
	(6,'dashboard',''),
	(7,'developers',''),
	(8,'diagnostics',''),
	(9,'embed',''),
	(10,'externalpages',''),
	(11,'file',''),
	(12,'garbagecollector',''),
	(13,'groups',''),
	(14,'htmlawed',''),
	(15,'invitefriends',''),
	(16,'likes',''),
	(17,'logbrowser',''),
	(18,'logrotate',''),
	(19,'members',''),
	(20,'messageboard',''),
	(21,'messages',''),
	(22,'notifications',''),
	(23,'oauth_api',''),
	(24,'pages',''),
	(25,'profile',''),
	(26,'reportedcontent',''),
	(27,'search',''),
	(28,'tagcloud',''),
	(29,'thewire',''),
	(30,'tinymce',''),
	(31,'twitter',''),
	(32,'twitter_api',''),
	(33,'uservalidationbyemail',''),
	(34,'zaudio',''),
	(36,'',''),
	(37,'',''),
	(38,'',''),
	(39,'',''),
	(40,'',''),
	(42,'',''),
	(43,'',''),
	(44,'',''),
	(45,'',''),
	(46,'',''),
	(49,'Test Page 1','<p>This is where the user will add their new page information...</p>\r\n<p>Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;</p>\r\n<p>Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;Lorem ipsum&nbsp;</p>'),
	(50,'Test Document','<p>This is the description of the uploaded file.</p>'),
	(51,'Test Message','<p>Test message</p>'),
	(52,'Test Message','<p>Test message</p>'),
	(54,'phloor_custom_favicon',''),
	(55,'about','<p>Book of Relations was created around the table discussing family history and the&nbsp;relationships involved with family members over the centuries. The idea of a free, common networking site to share this information with like minded individuals was the logical next step. Giving anyone the ability to share family memorandums and books to keep alive the knowledge of long lost relatives and passing on this information for future generations.</p>\r\n<p>Write your own autobiography, upload photos, created profiles and add biographical information about relatives who no longer have the ability to do it for themselves, share stories you heard as a child from elders in your family, comment on books, photos, pages, etc, create private discussion forums, and so on. This site has been created to inform and keep alive information that may have once been lost.</p>\r\n<p>Welcome to Book of Relations, and we look forward to you joining the family.</p>');

/*!40000 ALTER TABLE `elgg_objects_entity` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_private_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_private_settings`;

CREATE TABLE `elgg_private_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_guid` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entity_guid` (`entity_guid`,`name`),
  KEY `name` (`name`),
  KEY `value` (`value`(50))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_private_settings` WRITE;
/*!40000 ALTER TABLE `elgg_private_settings` DISABLE KEYS */;

INSERT INTO `elgg_private_settings` (`id`, `entity_guid`, `name`, `value`)
VALUES
	(1,2,'elgg:internal:priority','1'),
	(2,3,'elgg:internal:priority','2'),
	(3,4,'elgg:internal:priority','3'),
	(4,5,'elgg:internal:priority','4'),
	(5,6,'elgg:internal:priority','5'),
	(6,7,'elgg:internal:priority','6'),
	(7,8,'elgg:internal:priority','7'),
	(8,9,'elgg:internal:priority','8'),
	(9,10,'elgg:internal:priority','9'),
	(10,11,'elgg:internal:priority','10'),
	(11,12,'elgg:internal:priority','11'),
	(12,13,'elgg:internal:priority','12'),
	(13,14,'elgg:internal:priority','13'),
	(14,15,'elgg:internal:priority','14'),
	(15,16,'elgg:internal:priority','15'),
	(16,17,'elgg:internal:priority','16'),
	(17,18,'elgg:internal:priority','17'),
	(18,19,'elgg:internal:priority','18'),
	(19,20,'elgg:internal:priority','19'),
	(20,21,'elgg:internal:priority','20'),
	(21,22,'elgg:internal:priority','21'),
	(22,23,'elgg:internal:priority','22'),
	(23,24,'elgg:internal:priority','23'),
	(24,25,'elgg:internal:priority','24'),
	(25,26,'elgg:internal:priority','25'),
	(26,27,'elgg:internal:priority','26'),
	(27,28,'elgg:internal:priority','27'),
	(28,29,'elgg:internal:priority','28'),
	(29,30,'elgg:internal:priority','29'),
	(30,31,'elgg:internal:priority','30'),
	(31,32,'elgg:internal:priority','31'),
	(32,33,'elgg:internal:priority','32'),
	(33,34,'elgg:internal:priority','33'),
	(34,36,'handler','control_panel'),
	(35,36,'context','admin'),
	(36,36,'column','1'),
	(37,36,'order','0'),
	(38,37,'handler','admin_welcome'),
	(39,37,'context','admin'),
	(40,37,'order','10'),
	(41,37,'column','1'),
	(42,38,'handler','online_users'),
	(43,38,'context','admin'),
	(44,38,'column','2'),
	(45,38,'order','0'),
	(46,39,'handler','new_users'),
	(47,39,'context','admin'),
	(48,39,'order','10'),
	(49,39,'column','2'),
	(50,40,'handler','content_stats'),
	(51,40,'context','admin'),
	(52,40,'order','20'),
	(53,40,'column','2'),
	(54,38,'num_display','8'),
	(55,39,'num_display','5'),
	(56,40,'num_display','8'),
	(57,42,'handler','control_panel'),
	(58,42,'context','admin'),
	(59,42,'column','1'),
	(60,42,'order','0'),
	(61,43,'handler','admin_welcome'),
	(62,43,'context','admin'),
	(63,43,'order','10'),
	(64,43,'column','1'),
	(65,44,'handler','online_users'),
	(66,44,'context','admin'),
	(67,44,'column','2'),
	(68,44,'order','0'),
	(69,45,'handler','new_users'),
	(70,45,'context','admin'),
	(71,45,'order','10'),
	(72,45,'column','2'),
	(73,46,'handler','content_stats'),
	(74,46,'context','admin'),
	(75,46,'order','20'),
	(76,46,'column','2'),
	(77,44,'num_display','8'),
	(78,45,'num_display','5'),
	(79,46,'num_display','8'),
	(80,13,'hidden_groups','no'),
	(81,54,'elgg:internal:priority','34');

/*!40000 ALTER TABLE `elgg_private_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_river
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_river`;

CREATE TABLE `elgg_river` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) NOT NULL,
  `subtype` varchar(32) NOT NULL,
  `action_type` varchar(32) NOT NULL,
  `access_id` int(11) NOT NULL,
  `view` text NOT NULL,
  `subject_guid` int(11) NOT NULL,
  `object_guid` int(11) NOT NULL,
  `annotation_id` int(11) NOT NULL,
  `posted` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `action_type` (`action_type`),
  KEY `access_id` (`access_id`),
  KEY `subject_guid` (`subject_guid`),
  KEY `object_guid` (`object_guid`),
  KEY `annotation_id` (`annotation_id`),
  KEY `posted` (`posted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_river` WRITE;
/*!40000 ALTER TABLE `elgg_river` DISABLE KEYS */;

INSERT INTO `elgg_river` (`id`, `type`, `subtype`, `action_type`, `access_id`, `view`, `subject_guid`, `object_guid`, `annotation_id`, `posted`)
VALUES
	(1,'user','','friend',2,'river/relationship/friend/create',35,41,0,1341390495),
	(2,'object','page_top','create',2,'river/object/page/create',35,49,0,1341390705),
	(3,'object','page_top','comment',2,'river/annotation/generic_comment/create',35,49,2,1341390751),
	(4,'object','file','create',2,'river/object/file/create',35,50,0,1341390844),
	(5,'user','','friend',2,'river/relationship/friend/create',41,35,0,1341391028),
	(6,'user','','friend',2,'river/relationship/friend/create',48,41,0,1341391108),
	(7,'user','','friend',2,'river/relationship/friend/create',48,35,0,1341391118);

/*!40000 ALTER TABLE `elgg_river` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_sites_entity
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_sites_entity`;

CREATE TABLE `elgg_sites_entity` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`guid`),
  UNIQUE KEY `url` (`url`),
  FULLTEXT KEY `name` (`name`,`description`,`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_sites_entity` WRITE;
/*!40000 ALTER TABLE `elgg_sites_entity` DISABLE KEYS */;

INSERT INTO `elgg_sites_entity` (`guid`, `name`, `description`, `url`)
VALUES
	(1,'Book of Relations','','http://bookofrelations.local/');

/*!40000 ALTER TABLE `elgg_sites_entity` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_system_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_system_log`;

CREATE TABLE `elgg_system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object_class` varchar(50) NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `object_subtype` varchar(50) NOT NULL,
  `event` varchar(50) NOT NULL,
  `performed_by_guid` int(11) NOT NULL,
  `owner_guid` int(11) NOT NULL,
  `access_id` int(11) NOT NULL,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `time_created` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `object_class` (`object_class`),
  KEY `object_type` (`object_type`),
  KEY `object_subtype` (`object_subtype`),
  KEY `event` (`event`),
  KEY `performed_by_guid` (`performed_by_guid`),
  KEY `access_id` (`access_id`),
  KEY `time_created` (`time_created`),
  KEY `river_key` (`object_type`,`object_subtype`,`event`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_system_log` WRITE;
/*!40000 ALTER TABLE `elgg_system_log` DISABLE KEYS */;

INSERT INTO `elgg_system_log` (`id`, `object_id`, `object_class`, `object_type`, `object_subtype`, `event`, `performed_by_guid`, `owner_guid`, `access_id`, `enabled`, `time_created`, `ip_address`)
VALUES
	(1,2,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(2,3,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(3,4,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(4,5,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(5,6,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(6,7,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(7,8,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(8,9,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(9,10,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(10,11,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(11,12,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(12,13,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(13,14,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(14,15,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(15,16,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(16,17,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(17,18,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(18,19,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(19,20,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(20,21,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(21,22,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(22,23,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(23,24,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(24,25,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(25,26,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(26,27,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(27,28,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(28,29,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(29,30,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(30,31,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(31,32,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(32,33,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(33,34,'ElggPlugin','object','plugin','create',0,1,2,'yes',1341390323,'127.0.0.1'),
	(34,1,'ElggRelationship','relationship','active_plugin','create',0,0,2,'yes',1341390323,'127.0.0.1'),
	(35,22,'ElggRelationship','relationship','active_plugin','create',0,0,2,'yes',1341390324,'127.0.0.1'),
	(36,23,'ElggRelationship','relationship','member_of_site','create',0,0,2,'yes',1341390356,'127.0.0.1'),
	(37,35,'ElggUser','user','','create',0,0,2,'yes',1341390356,'127.0.0.1'),
	(38,2,'ElggMetadata','metadata','notification:method:email','create',0,35,2,'yes',1341390356,'127.0.0.1'),
	(39,36,'ElggWidget','object','widget','create',0,35,0,'yes',1341390356,'127.0.0.1'),
	(40,37,'ElggWidget','object','widget','create',0,35,0,'yes',1341390356,'127.0.0.1'),
	(41,38,'ElggWidget','object','widget','create',0,35,0,'yes',1341390356,'127.0.0.1'),
	(42,39,'ElggWidget','object','widget','create',0,35,0,'yes',1341390356,'127.0.0.1'),
	(43,40,'ElggWidget','object','widget','create',0,35,0,'yes',1341390356,'127.0.0.1'),
	(44,35,'ElggUser','user','','make_admin',0,0,2,'yes',1341390356,'127.0.0.1'),
	(45,3,'ElggMetadata','metadata','validated','create',0,0,2,'yes',1341390356,'127.0.0.1'),
	(46,4,'ElggMetadata','metadata','validated_method','create',0,0,2,'yes',1341390356,'127.0.0.1'),
	(47,35,'ElggUser','user','','update',35,0,2,'yes',1341390356,'127.0.0.1'),
	(48,35,'ElggUser','user','','login',35,0,2,'yes',1341390356,'127.0.0.1'),
	(49,24,'ElggRelationship','relationship','member_of_site','create',35,0,2,'yes',1341390399,'127.0.0.1'),
	(50,41,'ElggUser','user','','create',35,0,2,'yes',1341390399,'127.0.0.1'),
	(51,5,'ElggMetadata','metadata','notification:method:email','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(52,41,'ElggUser','user','','update',35,0,2,'yes',1341390399,'127.0.0.1'),
	(53,42,'ElggWidget','object','widget','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(54,43,'ElggWidget','object','widget','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(55,44,'ElggWidget','object','widget','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(56,45,'ElggWidget','object','widget','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(57,46,'ElggWidget','object','widget','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(58,6,'ElggMetadata','metadata','validated','create',35,35,2,'yes',1341390399,'127.0.0.1'),
	(59,7,'ElggMetadata','metadata','validated_method','create',35,35,2,'yes',1341390399,'127.0.0.1'),
	(60,41,'ElggUser','user','','make_admin',35,0,2,'yes',1341390399,'127.0.0.1'),
	(61,8,'ElggMetadata','metadata','admin_created','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(62,9,'ElggMetadata','metadata','created_by_guid','create',35,41,2,'yes',1341390399,'127.0.0.1'),
	(63,25,'ElggRelationship','relationship','member_of_site','create',35,0,2,'yes',1341390439,'127.0.0.1'),
	(64,47,'ElggUser','user','','create',35,0,2,'yes',1341390439,'127.0.0.1'),
	(65,10,'ElggMetadata','metadata','notification:method:email','create',35,47,2,'yes',1341390439,'127.0.0.1'),
	(66,47,'ElggUser','user','','update',35,0,2,'yes',1341390439,'127.0.0.1'),
	(67,11,'ElggMetadata','metadata','admin_created','create',35,47,2,'yes',1341390439,'127.0.0.1'),
	(68,12,'ElggMetadata','metadata','created_by_guid','create',35,47,2,'yes',1341390439,'127.0.0.1'),
	(69,26,'ElggRelationship','relationship','member_of_site','create',35,0,2,'yes',1341390471,'127.0.0.1'),
	(70,48,'ElggUser','user','','create',35,0,2,'yes',1341390471,'127.0.0.1'),
	(71,13,'ElggMetadata','metadata','notification:method:email','create',35,48,2,'yes',1341390471,'127.0.0.1'),
	(72,48,'ElggUser','user','','update',35,0,2,'yes',1341390471,'127.0.0.1'),
	(73,14,'ElggMetadata','metadata','admin_created','create',35,48,2,'yes',1341390471,'127.0.0.1'),
	(74,15,'ElggMetadata','metadata','created_by_guid','create',35,48,2,'yes',1341390471,'127.0.0.1'),
	(75,27,'ElggRelationship','relationship','friend','create',35,0,2,'yes',1341390495,'127.0.0.1'),
	(76,16,'ElggMetadata','metadata','description','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(77,17,'ElggMetadata','metadata','briefdescription','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(78,18,'ElggMetadata','metadata','location','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(79,19,'ElggMetadata','metadata','interests','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(80,20,'ElggMetadata','metadata','interests','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(81,21,'ElggMetadata','metadata','interests','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(82,22,'ElggMetadata','metadata','interests','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(83,23,'ElggMetadata','metadata','skills','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(84,24,'ElggMetadata','metadata','contactemail','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(85,25,'ElggMetadata','metadata','phone','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(86,26,'ElggMetadata','metadata','mobile','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(87,27,'ElggMetadata','metadata','website','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(88,28,'ElggMetadata','metadata','twitter','create',35,35,2,'yes',1341390651,'127.0.0.1'),
	(89,35,'ElggUser','user','','update',35,0,2,'yes',1341390651,'127.0.0.1'),
	(90,35,'ElggUser','user','','profileupdate',35,0,2,'yes',1341390651,'127.0.0.1'),
	(91,29,'ElggMetadata','metadata','write_access_id','create',35,35,2,'yes',1341390705,'127.0.0.1'),
	(92,49,'ElggObject','object','page_top','create',35,35,2,'yes',1341390705,'127.0.0.1'),
	(93,49,'ElggObject','object','page_top','annotate',35,35,2,'yes',1341390705,'127.0.0.1'),
	(94,1,'ElggAnnotation','annotation','page','create',35,35,2,'yes',1341390705,'127.0.0.1'),
	(95,49,'ElggObject','object','page_top','annotate',35,35,2,'yes',1341390751,'127.0.0.1'),
	(96,2,'ElggAnnotation','annotation','generic_comment','create',35,35,2,'yes',1341390751,'127.0.0.1'),
	(97,30,'ElggMetadata','metadata','tags','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(98,31,'ElggMetadata','metadata','filename','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(99,32,'ElggMetadata','metadata','mimetype','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(100,33,'ElggMetadata','metadata','originalfilename','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(101,34,'ElggMetadata','metadata','simpletype','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(102,50,'ElggFile','object','file','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(103,35,'ElggMetadata','metadata','filestore::dir_root','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(104,36,'ElggMetadata','metadata','filestore::filestore','create',35,35,2,'yes',1341390844,'127.0.0.1'),
	(105,35,'ElggUser','user','','logout',35,0,2,'yes',1341390878,'127.0.0.1'),
	(106,35,'ElggUser','user','','update',35,0,2,'yes',1341390878,'127.0.0.1'),
	(107,41,'ElggUser','user','','update',41,0,2,'yes',1341390900,'127.0.0.1'),
	(108,41,'ElggUser','user','','login',41,0,2,'yes',1341390900,'127.0.0.1'),
	(109,37,'ElggMetadata','metadata','description','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(110,38,'ElggMetadata','metadata','briefdescription','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(111,39,'ElggMetadata','metadata','location','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(112,40,'ElggMetadata','metadata','interests','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(113,41,'ElggMetadata','metadata','interests','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(114,42,'ElggMetadata','metadata','interests','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(115,43,'ElggMetadata','metadata','interests','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(116,44,'ElggMetadata','metadata','contactemail','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(117,45,'ElggMetadata','metadata','phone','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(118,46,'ElggMetadata','metadata','mobile','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(119,47,'ElggMetadata','metadata','website','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(120,48,'ElggMetadata','metadata','twitter','create',41,41,2,'yes',1341390972,'127.0.0.1'),
	(121,41,'ElggUser','user','','update',41,0,2,'yes',1341390972,'127.0.0.1'),
	(122,41,'ElggUser','user','','profileupdate',41,0,2,'yes',1341390972,'127.0.0.1'),
	(123,28,'ElggRelationship','relationship','friend','create',41,0,2,'yes',1341391028,'127.0.0.1'),
	(124,41,'ElggUser','user','','logout',41,0,2,'yes',1341391070,'127.0.0.1'),
	(125,41,'ElggUser','user','','update',41,0,2,'yes',1341391070,'127.0.0.1'),
	(126,48,'ElggUser','user','','update',48,0,2,'yes',1341391091,'127.0.0.1'),
	(127,48,'ElggUser','user','','login',48,0,2,'yes',1341391091,'127.0.0.1'),
	(128,29,'ElggRelationship','relationship','friend','create',48,0,2,'yes',1341391108,'127.0.0.1'),
	(129,30,'ElggRelationship','relationship','friend','create',48,0,2,'yes',1341391118,'127.0.0.1'),
	(130,49,'ElggObject','object','page_top','annotate',48,35,2,'yes',1341391129,'127.0.0.1'),
	(131,3,'ElggAnnotation','annotation','likes','create',48,48,2,'yes',1341391129,'127.0.0.1'),
	(132,50,'ElggFile','object','file','annotate',48,35,2,'yes',1341391144,'127.0.0.1'),
	(133,4,'ElggAnnotation','annotation','likes','create',48,48,2,'yes',1341391144,'127.0.0.1'),
	(134,49,'ElggMetadata','metadata','toId','create',48,41,2,'yes',1341391238,'127.0.0.1'),
	(135,50,'ElggMetadata','metadata','fromId','create',48,41,2,'yes',1341391238,'127.0.0.1'),
	(136,51,'ElggMetadata','metadata','readYet','create',48,41,2,'yes',1341391238,'127.0.0.1'),
	(137,52,'ElggMetadata','metadata','hiddenFrom','create',48,41,2,'yes',1341391238,'127.0.0.1'),
	(138,53,'ElggMetadata','metadata','hiddenTo','create',48,41,2,'yes',1341391238,'127.0.0.1'),
	(139,54,'ElggMetadata','metadata','msg','create',48,41,2,'yes',1341391238,'127.0.0.1'),
	(140,55,'ElggMetadata','metadata','toId','create',48,48,2,'yes',1341391238,'127.0.0.1'),
	(141,56,'ElggMetadata','metadata','fromId','create',48,48,2,'yes',1341391238,'127.0.0.1'),
	(142,57,'ElggMetadata','metadata','readYet','create',48,48,2,'yes',1341391238,'127.0.0.1'),
	(143,58,'ElggMetadata','metadata','hiddenFrom','create',48,48,2,'yes',1341391238,'127.0.0.1'),
	(144,59,'ElggMetadata','metadata','hiddenTo','create',48,48,2,'yes',1341391238,'127.0.0.1'),
	(145,60,'ElggMetadata','metadata','msg','create',48,48,2,'yes',1341391238,'127.0.0.1'),
	(146,51,'ElggObject','object','messages','update',48,41,2,'yes',1341391238,'127.0.0.1'),
	(147,52,'ElggObject','object','messages','update',48,48,2,'yes',1341391238,'127.0.0.1'),
	(148,48,'ElggUser','user','','logout',48,0,2,'yes',1341391246,'127.0.0.1'),
	(149,48,'ElggUser','user','','update',48,0,2,'yes',1341391246,'127.0.0.1'),
	(150,41,'ElggUser','user','','update',41,0,2,'yes',1341391270,'127.0.0.1'),
	(151,41,'ElggUser','user','','login',41,0,2,'yes',1341391270,'127.0.0.1'),
	(152,1,'ElggSite','site','','update',41,0,2,'yes',1341391606,'127.0.0.1'),
	(153,1,'ElggSite','site','','update',41,0,2,'yes',1341391659,'127.0.0.1'),
	(154,41,'ElggUser','user','','logout',41,0,2,'yes',1341391682,'127.0.0.1'),
	(155,41,'ElggUser','user','','update',41,0,2,'yes',1341391682,'127.0.0.1'),
	(156,35,'ElggUser','user','','update',35,0,2,'yes',1341416676,'127.0.0.1'),
	(157,35,'ElggUser','user','','login',35,0,2,'yes',1341416676,'127.0.0.1'),
	(158,31,'ElggRelationship','relationship','active_plugin','create',35,0,2,'yes',1341416866,'127.0.0.1'),
	(159,61,'ElggMetadata','metadata','admin_notice_id','create',35,35,0,'yes',1341416866,'127.0.0.1'),
	(160,53,'ElggObject','object','admin_notice','create',35,35,0,'yes',1341416866,'127.0.0.1'),
	(161,32,'ElggRelationship','relationship','active_plugin','create',35,0,2,'yes',1341416900,'127.0.0.1'),
	(162,62,'ElggMetadata','metadata','categories','create',35,35,2,'yes',1341417231,'127.0.0.1'),
	(163,53,'ElggObject','object','admin_notice','delete',35,35,0,'yes',1341417231,'127.0.0.1'),
	(164,61,'ElggMetadata','metadata','admin_notice_id','delete',35,35,0,'yes',1341417231,'127.0.0.1'),
	(165,33,'ElggRelationship','relationship','active_plugin','create',35,0,2,'yes',1341417269,'127.0.0.1'),
	(166,34,'ElggRelationship','relationship','active_plugin','create',35,0,2,'yes',1341417277,'127.0.0.1'),
	(167,54,'ElggPlugin','object','plugin','create',35,1,2,'yes',1341417725,'127.0.0.1'),
	(168,35,'ElggUser','user','','logout',35,0,2,'yes',1341419308,'127.0.0.1'),
	(169,35,'ElggUser','user','','update',35,0,2,'yes',1341419308,'127.0.0.1'),
	(170,35,'ElggUser','user','','update',35,0,2,'yes',1341422568,'127.0.0.1'),
	(171,35,'ElggUser','user','','login',35,0,2,'yes',1341422568,'127.0.0.1'),
	(172,35,'ElggUser','user','','logout',35,0,2,'yes',1341422599,'127.0.0.1'),
	(173,35,'ElggUser','user','','update',35,0,2,'yes',1341422599,'127.0.0.1'),
	(174,35,'ElggUser','user','','update',35,0,2,'yes',1341422733,'127.0.0.1'),
	(175,35,'ElggUser','user','','login',35,0,2,'yes',1341422733,'127.0.0.1'),
	(176,55,'ElggObject','object','about','create',35,35,2,'yes',1341423220,'127.0.0.1'),
	(177,35,'ElggUser','user','','logout',35,0,2,'yes',1341423836,'127.0.0.1'),
	(178,35,'ElggUser','user','','update',35,0,2,'yes',1341423836,'127.0.0.1'),
	(179,35,'ElggUser','user','','update',35,0,2,'yes',1341425212,'127.0.0.1'),
	(180,35,'ElggUser','user','','login',35,0,2,'yes',1341425212,'127.0.0.1'),
	(181,35,'ElggUser','user','','logout',35,0,2,'yes',1341425305,'127.0.0.1'),
	(182,35,'ElggUser','user','','update',35,0,2,'yes',1341425305,'127.0.0.1'),
	(183,35,'ElggUser','user','','update',35,0,2,'yes',1341425371,'127.0.0.1'),
	(184,35,'ElggUser','user','','login',35,0,2,'yes',1341425371,'127.0.0.1'),
	(185,35,'ElggUser','user','','update',35,0,2,'yes',1341457391,'127.0.0.1'),
	(186,35,'ElggUser','user','','login',35,0,2,'yes',1341457391,'127.0.0.1'),
	(187,35,'ElggUser','user','','logout',35,0,2,'yes',1341457399,'127.0.0.1'),
	(188,35,'ElggUser','user','','update',35,0,2,'yes',1341457399,'127.0.0.1');

/*!40000 ALTER TABLE `elgg_system_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_users_apisessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_users_apisessions`;

CREATE TABLE `elgg_users_apisessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_guid` bigint(20) unsigned NOT NULL,
  `site_guid` bigint(20) unsigned NOT NULL,
  `token` varchar(40) DEFAULT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_guid` (`user_guid`,`site_guid`),
  KEY `token` (`token`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;



# Dump of table elgg_users_entity
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_users_entity`;

CREATE TABLE `elgg_users_entity` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `username` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(8) NOT NULL DEFAULT '',
  `email` text NOT NULL,
  `language` varchar(6) NOT NULL DEFAULT '',
  `code` varchar(32) NOT NULL DEFAULT '',
  `banned` enum('yes','no') NOT NULL DEFAULT 'no',
  `admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_action` int(11) NOT NULL DEFAULT '0',
  `prev_last_action` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `prev_last_login` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`guid`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `email` (`email`(50)),
  KEY `code` (`code`),
  KEY `last_action` (`last_action`),
  KEY `last_login` (`last_login`),
  KEY `admin` (`admin`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `name_2` (`name`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_users_entity` WRITE;
/*!40000 ALTER TABLE `elgg_users_entity` DISABLE KEYS */;

INSERT INTO `elgg_users_entity` (`guid`, `name`, `username`, `password`, `salt`, `email`, `language`, `code`, `banned`, `admin`, `last_action`, `prev_last_action`, `last_login`, `prev_last_login`)
VALUES
	(35,'Admin','admin','fc638b3fd06be5cf58b9e8cabb3fb3eb','a6f3287f','tdcribb@gmail.com','en','','no','yes',1341457399,1341457397,1341457391,1341425371),
	(41,'Tod Cribb','tdcribb','a244c25c3fb20c51db2a95e7bdd62433','f451e4ff','tdcribb@gmail.com','en','','no','yes',1341391682,1341391679,1341391270,1341390900),
	(47,'Samantha Cribb','samantha','0099181664c2f2a70b0439c45af38640','7e415619','samantha@cribb.com','en','','no','no',0,0,0,0),
	(48,'David Lucas','dhlucas','7da155ab7e0d0745daeda5a2e249b2e7','6eec0d2e','tdcribb@gmail.com','en','','no','no',1341391246,1341391239,1341391091,0);

/*!40000 ALTER TABLE `elgg_users_entity` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table elgg_users_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `elgg_users_sessions`;

CREATE TABLE `elgg_users_sessions` (
  `session` varchar(255) NOT NULL,
  `ts` int(11) unsigned NOT NULL DEFAULT '0',
  `data` mediumblob,
  PRIMARY KEY (`session`),
  KEY `ts` (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `elgg_users_sessions` WRITE;
/*!40000 ALTER TABLE `elgg_users_sessions` DISABLE KEYS */;

INSERT INTO `elgg_users_sessions` (`session`, `ts`, `data`)
VALUES
	('genpd57b11k4ob988g3nr9vhp3',1341390878,X'5F5F656C67675F73657373696F6E7C733A33323A226263333162383465613264333163336663363062623639386563346662376461223B6D73677C613A303A7B7D'),
	('mj6jpg5fm7lgd9ju9l53jbaka1',1341431208,X'5F5F656C67675F73657373696F6E7C733A33323A226635386562356133393661343461363035346463633261633561613265363462223B6D73677C613A303A7B7D'),
	('ne16ik00scq0ciu1rgcr7krfc3',1341416676,X'5F5F656C67675F73657373696F6E7C733A33323A223166336138336561336565396364356538303832313938383066643461363366223B6D73677C613A303A7B7D'),
	('64p9ub5a2n0ufi6q8dhubatth2',1341452955,X'5F5F656C67675F73657373696F6E7C733A33323A223061353939633132336530336431356139646131366431663164306133336561223B6D73677C613A303A7B7D757365727C4F3A383A22456C676755736572223A383A7B733A31353A22002A0075726C5F6F76657272696465223B4E3B733A31363A22002A0069636F6E5F6F76657272696465223B4E3B733A31363A22002A0074656D705F6D65746164617461223B613A303A7B7D733A31393A22002A0074656D705F616E6E6F746174696F6E73223B613A303A7B7D733A32343A22002A0074656D705F707269766174655F73657474696E6773223B613A303A7B7D733A31313A22002A00766F6C6174696C65223B613A303A7B7D733A31333A22002A0061747472696275746573223B613A32353A7B733A31323A2274696D655F63726561746564223B733A31303A2231333431333930333536223B733A343A2267756964223B693A33353B733A343A2274797065223B733A343A2275736572223B733A373A2273756274797065223B733A313A2230223B733A31303A226F776E65725F67756964223B733A313A2230223B733A31343A22636F6E7461696E65725F67756964223B733A313A2230223B733A393A22736974655F67756964223B733A313A2231223B733A393A226163636573735F6964223B733A313A2232223B733A31323A2274696D655F75706461746564223B733A31303A2231333431343235333731223B733A31313A226C6173745F616374696F6E223B733A31303A2231333431343532373839223B733A373A22656E61626C6564223B733A333A22796573223B733A31323A227461626C65735F73706C6974223B693A323B733A31333A227461626C65735F6C6F61646564223B693A323B733A343A226E616D65223B733A353A2241646D696E223B733A383A22757365726E616D65223B733A353A2261646D696E223B733A383A2270617373776F7264223B733A33323A226663363338623366643036626535636635386239653863616262336662336562223B733A343A2273616C74223B733A383A226136663332383766223B733A353A22656D61696C223B733A31373A227464637269626240676D61696C2E636F6D223B733A383A226C616E6775616765223B733A323A22656E223B733A343A22636F6465223B733A303A22223B733A363A2262616E6E6564223B733A323A226E6F223B733A353A2261646D696E223B733A333A22796573223B733A31363A22707265765F6C6173745F616374696F6E223B733A31303A2231333431343338383632223B733A31303A226C6173745F6C6F67696E223B733A31303A2231333431343235333731223B733A31353A22707265765F6C6173745F6C6F67696E223B733A31303A2231333431343235323132223B7D733A383A22002A0076616C6964223B623A303B7D677569647C693A33353B69647C693A33353B757365726E616D657C733A353A2261646D696E223B6E616D657C733A353A2241646D696E223B'),
	('muao6taa9a4gvv2qa1dr8gur95',1341391071,X'5F5F656C67675F73657373696F6E7C733A33323A223162666138653765363038646233613739363532336333333066376665386562223B6D73677C613A303A7B7D'),
	('jbb4191mplqf2tbrjvpj8mc4i3',1341391246,X'5F5F656C67675F73657373696F6E7C733A33323A223936663832373633626438653463633865316666616565636632393264363336223B6D73677C613A303A7B7D'),
	('cqvj2ith551j5tg0l685j41pi6',1341392288,X'5F5F656C67675F73657373696F6E7C733A33323A226530653134393037363063373335646264643439653332376232383265316139223B6D73677C613A303A7B7D'),
	('k9a45kg7666gkdgnuucekn6g82',1341422524,X'5F5F656C67675F73657373696F6E7C733A33323A223633313936663335613836383962373231646364346139326264353732356639223B6D73677C613A303A7B7D'),
	('imrn50cc90g4me0qk23r12pnv6',1341422655,X'5F5F656C67675F73657373696F6E7C733A33323A223264653230626537326138666236363035353131366532653935356564633263223B6D73677C613A303A7B7D'),
	('5rtboukq9lclals9g92dh6bss2',1341424967,X'5F5F656C67675F73657373696F6E7C733A33323A223534653038396131333838346637366661323161353766616230623134616132223B6D73677C613A303A7B7D'),
	('s7scn1nh0i8dp05on4lmf16q81',1341425356,X'5F5F656C67675F73657373696F6E7C733A33323A223061353939633132336530336431356139646131366431663164306133336561223B6D73677C613A303A7B7D'),
	('7s3pof9v3o5hol4773ml8itv21',1341457377,X'5F5F656C67675F73657373696F6E7C733A33323A223836316538326662376630363734373232346661393332333166363836383466223B6D73677C613A303A7B7D'),
	('m324bpgjjdshq794tltvnu3685',1341460521,X'5F5F656C67675F73657373696F6E7C733A33323A226632623332353237373835376266346266633561633135336234653161343333223B6D73677C613A303A7B7D6C6173745F666F72776172645F66726F6D7C733A35323A22687474703A2F2F626F6F6B6F6672656C6174696F6E732E6C6F63616C2F696D616765732F6C6F67696E2D627574746F6E2E706E67223B'),
	('skae3b8avriefi63drsjuo7t36',1341468326,X'5F5F656C67675F73657373696F6E7C733A33323A223236326537663765623533636666363531343163383639333437623432646234223B6D73677C613A303A7B7D6C6173745F666F72776172645F66726F6D7C733A37343A22687474703A2F2F626F6F6B6F6672656C6174696F6E732E6C6F63616C2F5F67726170686963732F77616C6C65645F67617264656E2F74776F5F636F6C756D6E5F6D6964646C652E706E67223B'),
	('d7sphkanb2cbber6bhrdtjnaf6',1341469633,X'5F5F656C67675F73657373696F6E7C733A33323A223561663166633039636261326131396362666430643363303630383132386666223B6D73677C613A303A7B7D');

/*!40000 ALTER TABLE `elgg_users_sessions` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
