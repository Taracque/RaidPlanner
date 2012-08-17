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
	if ( $extInstaller->install($source . '/mod_raidplanner_today') ) {
		// module installed
		$out .= 'RaidPlanner Today module installed!<br />';
	} else {
		$out .= 'RaidPlanner Today module installation failed!<br />';
	}
	// intsall RaidPlanner Today Module
	if ( $extInstaller->install($source . '/mod_raidplanner_events') ) {
		// module installed
		$out .= 'RaidPlanner Events module installed!<br />';
	} else {
		$out .= 'RaidPlanner Events module installation failed!<br />';
	}
	
	
	// version specific installation
	$version = new JVersion();
	if ($version->RELEASE >= '1.6') {
		// install RaidPlanner User Plugin (just for J 1.6!)
		if ( $extInstaller->install($source . '/plg_raidplanner') ) {
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
			if (JFile::exists( $target . '/' . $lang['tag'] . '.com_raidplanner.ini' ))
			{
				$content = JFile::read( $target . '/' . $lang['tag'] . '.com_raidplanner.ini' );
				if ($content != false)
				{
					// copy menu.ini language files
					JFile::copy( $source . '/administrator/language/' . $lang['tag'] . '.com_raidplanner.menu.ini', $target . '/' . $lang['tag'] . '.com_raidplanner.menu.ini' );
					
					// merge sys.ini and j15.ini file to admin language file
					if (JFile::exists( $source . '/administrator/language/' . $lang['tag'] . '.com_raidplanner.sys.ini' ))
					{
						$content .= "\n" . JFile::read( $source . '/administrator/language/' . $lang['tag'] . '.com_raidplanner.sys.ini' );
					}
					JFile::write( $target . '/' . $lang['tag'] . '.com_raidplanner.ini', $content );

					// remove sys.ini language file
					JFile::delete( $target . '/' . $lang['tag'] . '.com_raidplanner.sys.ini' );

					$out .= 'Language file for admin language ' . $lang['name'] . ' patched for Joomla 1.5<br />';
				}
			}
		}
		$langs =& JLanguage::getKnownLanguages( JPATH_SITE );
		foreach ($langs as $lang)
		{
			$target = JLanguage::getLanguagePath( JPATH_SITE, $lang['tag'] );
			// check if raidplanner has it language file in $target
			if (JFile::exists( $target . '/' . $lang['tag'] . '.com_raidplanner.ini' ))
			{
				$content = JFile::read( $target . '/' . $lang['tag'] . '.com_raidplanner.ini' );
				if ($content != false)
				{
					if (JFile::exists( $source . '/language/' . $lang['tag'] . '.com_raidplanner.j15.ini' ))
					{
						$content .= "\n" . JFile::read( $source . '/language/' . $lang['tag'] . '.com_raidplanner.j15.ini' );
					}
					JFile::write( $target . '/' . $lang['tag'] . '.com_raidplanner.ini', $content );
	
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
			if (JFile::exists( $target . '/' . $lang['tag'] . '.com_raidplanner.ini' ))
			{
				$content = JFile::read( $target . '/' . $lang['tag'] . '.com_raidplanner.ini' );
				if ($content != false)
				{
					// merge sys.ini file to admin language file
					if (JFile::exists( $source . '/administrator/language/' . $lang['tag'] . '.com_raidplanner.sys.ini' ))
					{
						$content .= "\n" . JFile::read( $source . '/administrator/language/' . $lang['tag'] . '.com_raidplanner.sys.ini' );
					}
					JFile::write( $target . '/' . $lang['tag'] . '.com_raidplanner.ini', $content );

					$out .= 'Language file for admin language ' . $lang['name'] . ' merged for Joomla 1.6/1.7<br />';
				}
			}
		}
	}

	// check if #__raidplanner_guild table exists
	$db = & JFactory::getDBO();

	$query = "SHOW TABLES LIKE '%_raidplanner_guild'";
	$db->setQuery($query);
	$db->query();
	if ( $db->getNumRows() == 0  )
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
	if ( $db->getNumRows() == 0  )
	{
		$query = "ALTER TABLE `#__raidplanner_class` ADD `class_css` varchar(45) NOT NULL DEFAULT ''";
		$db->setQuery($query);
		$db->query();
		$out .= 'Class_css added to Class database table<br />';
		$query = "UPDATE `#__raidplanner_class` SET `class_css` = CONCAT( 'class_', `class_id`) WHERE class_css = ''";
		$db->setQuery($query);
		$db->query();
	}

	$query = "SHOW COLUMNS FROM `#__raidplanner_raid` LIKE 'guild_id'";
	$db->setQuery($query);
	$db->query();
	if ( $db->getNumRows() == 0  )
	{
		$query = "ALTER TABLE `#__raidplanner_raid` ADD `guild_id` int(11) DEFAULT NULL";
		$db->setQuery($query);
		$db->query();
		$out .= 'Guild_id added to Raid database table<br />';
	}
	
	/* Drop primary index from profile table */
	$query = "SHOW KEYS FROM `#__raidplanner_raid` WHERE Key_name = 'is_template'";
	$db->setQuery($query);
	$db->query();
	if ( $db->getNumRows() == 0  )
	{
		$query = "ALTER TABLE `#__raidplanner_raid` ADD INDEX (  `is_template` ), ADD INDEX (  `freeze_time` )";
		$db->setQuery($query);
		$db->query();
	}
	/* Move guild_region, guild_realm to params field */
	$query = "SHOW COLUMNS FROM `#__raidplanner_guild` LIKE 'guild_region'";
	$db->setQuery($query);
	$db->query();
	if ( $db->getNumRows() > 0  )
	{
		$db->setQuery("SELECT * FROM `#__raidplanner_guild` WHERE guild_region <> ''");
		$list = $db->loadObjectList();
		if (count($list) > 0)
		{
			if (function_exists('json_decode')) {
				foreach ($list as $guild) {
					$params = json_decode( $guild->params , true );
					$params['guild_region'] = $guild->guild_region;
					$params['guild_realm'] = $guild->guild_realm;
					$params['guild_level'] = $guild->guild_level;
					
					$db->setQuery( "UPDATE FROM #__raidplanner_guild SET guild_realm='wow_armory',params=" . $db->Quote( json_encode($params) ) . " WHERE guild_id=".intval($guild->guild_id) );
					$db->query();
				}
			}
		}
		$db->setQuery( "ALTER TABLE `#__raidplanner_guild` DROP `guild_level`, DROP `guild_region`" );
		$db->query();
		$db->setQuery( "ALTER TABLE `#__raidplanner_guild` CHANGE `guild_realm` `sync_plugin` VARCHAR( 80 ) NOT NULL DEFAULT ''" );
		$db->query();
		
		$out .= 'Guild table changed to support syncing plugins<br />';
	}
	/* Remove armory_id  */
	$query = "SHOW COLUMNS FROM `#__raidplanner_class` LIKE 'armory_id'";
	$db->setQuery($query);
	$db->query();
	if ( $db->getNumRows() > 0  )
	{
		$db->setQuery( "ALTER TABLE `#__raidplanner_class` DROP `armory_id`" );
		$db->query();
		$db->setQuery( "ALTER TABLE `#__raidplanner_race` ADD `race_css` varchar(45) NOT NULL DEFAULT ''" );
		$db->query();
		
		/* check if wow_armory plugin is used */
		$db->setQuery("SELECT * FROM `#__raidplanner_guild` WHERE sync_plugin='wow_armory'");
		$db->query();
		if ( $db->getNumRows() > 0  )
		{
			/* display a warning */
			$out .= '<span class="message"><h3 class="error">WoW Armory plugin detected. Please download RaidPlanner-wow theme package, and install it using RaidPlanner installer!</h3><br />';
			$out .= 'More information can be found on the <a href="http://taracque.hu/wiki/raidplanner-docs/whats-new/">What\'s new page</a>.<br /><br />';
			$out .= '<script type="text/javascript">alert("WoW Armory plugin detected.\nPlease download RaidPlanner-wow theme package, and install it using RaidPlanner installer!");</script>';
			$out .= '</span>';
		}
	}	
	
	/* Detect Community Builder */
	jimport( 'joomla.application.component.helper' );
	$_CB_adminpath = JPATH_ADMINISTRATOR . '/components/com_comprofiler';
	if ( file_exists( $_CB_adminpath . '/plugin.foundation.php' ) )
	{
		$cbComp = &JComponentHelper::getComponent( 'com_comprofiler' );
		if ( ( $cbComp ) && ( $cbComp->enabled ) )
		{
			try {
				global $_CB_framework;
		
				include_once $_CB_adminpath . '/plugin.foundation.php';
		
				$_CB_framework->cbset( '_ui', 2 );
		
				cbimport( 'cb.tabs' );
				cbimport( 'cb.adminfilesystem' );
				cbimport( 'cb.installer' );
		
				$CB_installer = new cbInstallerPlugin();
		
				$install_dir = $source . '/3rd_party_plugins/community_builder/plug_raidplanner/';
				
				$ret = $CB_installer->install( $install_dir );
			} catch (Exception $e) {
				$ret = 0;
			}
			$out .= "\nCommunity Builder installed in Joomla. RaidPlanner Community builder installation:" . (($ret)?"[OK]":"[Failed]") . "<br />";
		}
	}
	
	$out .= "<br />IMPORTANT!<br />Read the <a href=\"http://taracque.hu/wiki/raidplanner-docs/whats-new/\" target=\"_blank\">What's new section of RaidPlanner documentation</a> for latest changes!<br />";

	$installer->set('message', $out);
}

function com_uninstall()
{
    $installer = & JInstaller::getInstance();
	$installer->set('message', 'RaidPlanner Today module, and RaidPlanner user plugins needs to be removed manualy!');
}