CREATE TABLE IF NOT EXISTS `#__raidplanner_rating` (
  `rating_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `raid_id` int(10) unsigned NOT NULL DEFAULT '0',
  `character_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rate_count` int(10) unsigned NOT NULL DEFAULT '0',
  `rate_value` int(10) unsigned NOT NULL DEFAULT '0',
  `rated_by` text NOT NULL,
  PRIMARY KEY (`rating_id`),
  KEY `raid_id` (`raid_id`),
  KEY `character_id` (`character_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__raidplanner_signups` DROP INDEX `profile_id`;
ALTER TABLE `#__raidplanner_signups` DROP `profile_id`;
ALTER TABLE `#__raidplanner_signups` ADD INDEX (  `character_id` );
