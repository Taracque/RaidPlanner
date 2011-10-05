<?php
/*------------------------------------------------------------------------
# Custom Installer for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.file' );

function com_install()
{

    $installer = & JInstaller::getInstance();
    $source = $installer->getPath('source');

	$out = '';

	$extInstaller = new JInstaller();

	// intsall RaidPlanner Today Module
	if ( $extInstaller->install($source . DS . 'mod_raidplanner_today') ) {
		// module installed
		$out .= 'RaidPlanner Today module installed!<br />';
	} else {
		$out .= 'RaidPlanner Today module installation failed!<br />';
	}
	// intsall RaidPlanner Today Module
	if ( $extInstaller->install($source . DS . 'mod_raidplanner_events') ) {
		// module installed
		$out .= 'RaidPlanner Events module installed!<br />';
	} else {
		$out .= 'RaidPlanner Events module installation failed!<br />';
	}
	
	
	// version specific installation
	$version = new JVersion();
	if ($version->RELEASE >= '1.6') {
		// install RaidPlanner User Plugin (just for J 1.6!)
		if ( $extInstaller->install($source . DS . 'plg_raidplanner') ) {
			// module installed
			$out .= 'RaidPlanner User plugin installed!<br />';
		} else {
			$out .= 'RaidPlanner User plugin installation failed!<br />';
		}
	}
	if ($version->RELEASE == '1.5') {
		$langs =& JLanguage::getKnownLanguages( JPATH_ADMINISTRATOR );
		foreach ($langs as $lang)
		{
			$target = JLanguage::getLanguagePath( JPATH_ADMINISTRATOR, $lang['tag'] );
			// check if raidplanner has it language file in $target
			if (JFile::exists( $target . DS . $lang['tag'] . '.com_raidplanner.ini' ))
			{
				$content = JFile::read( $target . DS . $lang['tag'] . '.com_raidplanner.ini' );
				if ($content != false)
				{
					// copy menu.ini language files
					JFile::copy( $source . DS . 'administrator' . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.menu.ini', $target .DS . $lang['tag'] . '.com_raidplanner.menu.ini' );
					
					// merge sys.ini and j15.ini file to admin language file
					if (JFile::exists( $source . DS . 'administrator' . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.sys.ini' ))
					{
						$content .= "\n" . JFile::read( $source . DS . 'administrator' . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.sys.ini' );
					}
					JFile::write( $target . DS . $lang['tag'] . '.com_raidplanner.ini', $content );

					// remove sys.ini language file
					JFile::delete( $target . DS . $lang['tag'] . '.com_raidplanner.sys.ini' );

					$out .= 'Language file for admin language ' . $lang['name'] . ' patched for Joomla 1.5<br />';
				}
				$target = JLanguage::getLanguagePath( JPATH_SITE, $lang['tag'] );
				// check if raidplanner has it language file in $target
				$content = JFile::read( $target . DS . $lang['tag'] . '.com_raidplanner.ini' );
				if ($content != false)
				{
					if (JFile::exists( $source . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.j15.ini' ))
					{
						$content .= "\n" . JFile::read( $source . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.j15.ini' );
					}
					JFile::write( $target . DS . $lang['tag'] . '.com_raidplanner.ini', $content );

					$out .= 'Language file for frontend language ' . $lang['name'] . ' patched for Joomla 1.5<br />';
				}
			}
		}
	} else {
		// copy sys.ini into .ini language files
		$langs =& JLanguage::getKnownLanguages( JPATH_ADMINISTRATOR );
		foreach ($langs as $lang)
		{
			$target = JLanguage::getLanguagePath( JPATH_ADMINISTRATOR, $lang['tag'] );
			// check if raidplanner has it language file in $target
			if (JFile::exists( $target . DS . $lang['tag'] . '.com_raidplanner.ini' ))
			{
				$content = JFile::read( $target . DS . $lang['tag'] . '.com_raidplanner.ini' );
				if ($content != false)
				{
					// merge sys.ini file to admin language file
					if (JFile::exists( $source . DS . 'administrator' . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.sys.ini' ))
					{
						$content .= "\n" . JFile::read( $source . DS . 'administrator' . DS . 'language' . DS . $lang['tag'] . '.com_raidplanner.sys.ini' );
					}
					JFile::write( $target . DS . $lang['tag'] . '.com_raidplanner.ini', $content );

					$out .= 'Language file for admin language ' . $lang['name'] . ' merged for Joomla 1.6/1.7<br />';
				}
			}
		}
	}

	// check if #__raidplanner_guild table exists
	$db = & JFactory::getDBO();

	$query = "SHOW TABLES LIKE '#__raidplanner_guild'";
	$db->setQuery($query);
	$db->query();
	$result = $db->loadObject();
	if ( !$result  )
	{
		$query = "CREATE TABLE IF NOT EXISTS `#__raidplanner_guild` (
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
				";
		$db->setQuery($query);
		$db->query();
		$out .= 'Guild table added to the database<br />';
	}

	$query = "SHOW COLUMNS FROM `#__raidplanner_class` LIKE  'class_css'";
	$db->setQuery($query);
	$db->query();
	$result = $db->loadObject();
	if ( !$result  )
	{
		$query = "ALTER TABLE `#__raidplanner_class` ADD `class_css` varchar(45) NOT NULL DEFAULT ''";
		$db->setQuery($query);
		$db->query();
		$query = "UPDATE `#__raidplanner_class` SET `class_css` = CONCAT( 'class_', 'class_id')";
		$db->setQuery($query);
		$db->query();
		$out .= 'Class_cc added to Class database table<br />';
	}

	$query = "SHOW COLUMNS FROM `#__raidplanner_guild` LIKE  'guild_id'";
	$db->setQuery($query);
	$db->query();
	$result = $db->loadObject();
	if ( !$result  )
	{
		$query = "ALTER TABLE `#__raidplanner_raid` ADD `guild_id` int(11) DEFAULT NULL";
		$db->setQuery($query);
		$db->query();
		$out .= 'Guild_id added to Raid database table<br />';
	}
	
	$installer->set('message', $out);
}

function com_uninstall()
{
    $installer = & JInstaller::getInstance();
	$installer->set('message', 'RaidPlanner Today module, and RaidPlanner user plugins needs to be removed manualy!');
}