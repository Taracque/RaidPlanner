CREATE TABLE IF NOT EXISTS `#__raidplanner_character` (
  `character_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gender_id` int(10) unsigned NOT NULL DEFAULT '0',
  `guild_id` int(10) unsigned NOT NULL DEFAULT '0',
  `race_id` int(10) unsigned NOT NULL DEFAULT '0',
  `char_level` int(10) unsigned NOT NULL DEFAULT '0',
  `char_name` varchar(45) NOT NULL DEFAULT '',
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`character_id`),
  KEY `profile_id` (`profile_id`),
  KEY `char_level` (`char_level`),
  KEY `rank` (`rank`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__raidplanner_class` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_color` varchar(45) NOT NULL DEFAULT '',
  `class_name` varchar(45) NOT NULL DEFAULT '',
  `armory_id` int(11) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (1,	'#7c5f48',	'Warrior',	1);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (2,	'#cb7497',	'Paladin',	2);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (3,	'#668e40',	'Hunter',	3);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (4,	'#a8a556',	'Rogue',	4);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (5,	'#8ca5a3',	'Priest',	5);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (6,	'#b1474f',	'Death Knight',	6);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (7,	'#4a71ca',	'Shaman',	7);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (8,	'#60a4ba',	'Mage',	8);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (9,	'#825c87',	'Warlock',	9);
INSERT IGNORE INTO `#__raidplanner_class` (`class_id`, `class_color`, `class_name`, `armory_id`) VALUES (11,	'#dc8043',	'Druid',	11);

CREATE TABLE IF NOT EXISTS `#__raidplanner_gender` (
  `gender_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gender_name` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`gender_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__raidplanner_gender` (`gender_id`, `gender_name`) VALUES (1,	'Male');
INSERT IGNORE INTO `#__raidplanner_gender` (`gender_id`, `gender_name`) VALUES (2,	'Female');

CREATE TABLE IF NOT EXISTS `#__raidplanner_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(45) NOT NULL DEFAULT '',
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__raidplanner_groups` (`group_id`, `group_name`, `default`) VALUES (1, 'Guest', 0);
INSERT IGNORE INTO `#__raidplanner_groups` (`group_id`, `group_name`, `default`) VALUES (2, 'Registered', 1);

CREATE TABLE IF NOT EXISTS `#__raidplanner_permissions` (
  `permission_name` varchar(45) NOT NULL DEFAULT '',
  `permission_value` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__raidplanner_profile` (
  `profile_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__raidplanner_race` (
  `race_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `race_name` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`race_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (1,	'Human');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (2,	'Orc');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (3,	'Dwarf');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (4,	'Nightelf');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (5,	'Undead');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (6,	'Tauren');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (7,	'Gnome');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (8,	'Troll');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (9,	'Goblin');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (10,	'Bloodelf');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (11,	'Draenei');
INSERT IGNORE INTO `#__raidplanner_race` (`race_id`, `race_name`) VALUES (22,	'Worgen');

CREATE TABLE IF NOT EXISTS `#__raidplanner_raid` (
  `raid_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `location` varchar(45) NOT NULL DEFAULT '',
  `raid_leader` varchar(45) NOT NULL DEFAULT '',
  `invite_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration_mins` int(11) NOT NULL,
  `freeze_time` int(10) unsigned NOT NULL DEFAULT '0',
  `maximum_level` int(10) unsigned DEFAULT NULL,
  `minimum_level` int(10) unsigned DEFAULT NULL,
  `icon_name` varchar(45) NOT NULL DEFAULT '',
  `profile_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `minimum_rank` int(11) DEFAULT NULL,
  `invited_group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`raid_id`),
  KEY `start_time` (`start_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__raidplanner_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL DEFAULT '',
  `body_color` varchar(255) NOT NULL DEFAULT '',
  `header_color` varchar(255) NOT NULL DEFAULT '',
  `font_color` varchar(255) NOT NULL DEFAULT '',
  `icon_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__raidplanner_role` (`role_id`, `role_name`, `body_color`, `header_color`, `font_color`, `icon_name`) VALUES (1,	'Tank',	'white',	'#93232b',	'black',	'role_tank.png');
INSERT IGNORE INTO `#__raidplanner_role` (`role_id`, `role_name`, `body_color`, `header_color`, `font_color`, `icon_name`) VALUES (2,	'Melee DPS',	'white',	'#a06729',	'black',	'role_melee_dps.png');
INSERT IGNORE INTO `#__raidplanner_role` (`role_id`, `role_name`, `body_color`, `header_color`, `font_color`, `icon_name`) VALUES (3,	'Ranged DPS',	'white',	'#2983a0',	'black',	'role_ranged_dps.png');
INSERT IGNORE INTO `#__raidplanner_role` (`role_id`, `role_name`, `body_color`, `header_color`, `font_color`, `icon_name`) VALUES (4,	'Healer',	'white',	'#6aa64d',	'black',	'role_heal.png');

CREATE TABLE IF NOT EXISTS `#__raidplanner_signups` (
  `raid_id` int(10) unsigned NOT NULL DEFAULT '0',
  `character_id` int(10) unsigned NOT NULL DEFAULT '0',
  `queue` tinyint(1) NOT NULL DEFAULT '0',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `profile_id` int(10) unsigned NOT NULL DEFAULT '0',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `class_id` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `raid_id` (`raid_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__raidplanner_history` (
  `raid_id` int(10) unsigned NOT NULL DEFAULT '0',
  `history` text NOT NULL,
  KEY `raid_id` (`raid_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;