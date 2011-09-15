CREATE TABLE IF NOT EXISTS `#__raidplanner_guild` (
  `guild_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guild_name` varchar(80) NOT NULL DEFAULT '',
  `guild_realm` varchar(80) NOT NULL DEFAULT '',
  `guild_region` varchar(10) NOT NULL DEFAULT '',
  `guild_level` int(10) NOT NULL DEFAULT '0',
  `lastSync` timestamp NULL DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`guild_id`),
  KEY `lastSync` (`lastSync`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;
