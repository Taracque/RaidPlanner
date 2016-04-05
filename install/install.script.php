<?php
/*------------------------------------------------------------------------
# Custom Installer Script for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2012 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

class com_raidplannerInstallerScript
{
	public function __construct($adapter)
	{
	}

	public function install($adapter)
	{
		$app       = JFactory::getApplication();

		$installer = JInstaller::getInstance();
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
	
	
		// install RaidPlanner User Plugin (just for J 1.6!)
		if ( $extInstaller->install($source . '/plg_raidplanner') ) {
			// module installed
			$out .= 'RaidPlanner User plugin installed!<br />';
		} else {
			$out .= 'RaidPlanner User plugin installation failed!<br />';
		}

		jimport( 'joomla.application.component.helper' );

		/* Detect Community Builder */
		$_CB_adminpath = JPATH_ADMINISTRATOR . '/components/com_comprofiler';
		if ( file_exists( $_CB_adminpath . '/plugin.foundation.php' ) )
		{
			$cbComp = JComponentHelper::getComponent( 'com_comprofiler' );
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
		/* Detect JomSocial */
		if ( file_exists( JPATH_SITE . '/components/com_community/libraries/core.php' ) )
		{
			$ret = $extInstaller->install( $source . '/3rd_party_plugins/jomsocial/' );
			$out .= "\nJomSocial is installed in Joomla. RaidPlanner JomSocial plugin installation:" . (($ret)?"[OK]":"[Failed]") . "<br />";
			/* patching customfields.xml file */
			$xml = simplexml_load_file( JPATH_SITE . '/components/com_community/libraries/fields/customfields.xml' ); 
			$result = $xml->xpath('/jomsocial/fields/field/type[text()="rp_characters"]');
			if (empty($result)) {
				/* add rp_characters to customfields */
				$newfield = $xml->fields->addChild('field');
				$newfield->addChild('type','rp_characters');
				$newfield->addChild('name','RaidPlanner Characters');
				$xml->saveXML( JPATH_SITE . '/components/com_community/libraries/fields/customfields.xml' );
				$out .= "<small>- RaidPlanner Characters fieldtype added to JomSocial</small><br>\n";
			}
		}
		/* Detect EasySocial */
		if ( file_exists( JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/installer/installer.php' ) )
		{
			/* load easysocial apps controller */
			$esComp = JComponentHelper::getComponent( 'com_easysocial' );
			if ( ( $esComp ) && ( $esComp->enabled ) && ( class_exists('FD')) ) {
				$esInstaller 	= FD::get( 'Installer' );
				$esInstaller->load( $source . '/3rd_party_plugins/easysocial/' );

				$ret = $esInstaller->install();
				$out .= "\nEasySocial is installed in Joomla. RaidPlanner EasySocial plugin installation:" . (($ret)?"[OK]":"[Failed]") . "<br />";
			}
		}

		$out .= "<br />IMPORTANT!<br />Read the <a href=\"http://taracque.hu/wiki/raidplanner-docs/whats-new/\" target=\"_blank\">What's new section of RaidPlanner documentation</a> for latest changes!<br />";
	
		$app->enqueueMessage($out);
	}

	public function update($adapter)
	{
		$app       = JFactory::getApplication();

		$this->install($adapter);
	
		$installer = JInstaller::getInstance();

		$out = "";
	
		// check if #__raidplanner_guild table exists
		$db = JFactory::getDBO();
	
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

		/* drop raidplanner_groups */
		$query = "DROP TABLE IF EXISTS `#__raidplanner_groups`;";
		$db->setQuery($query);
		$db->query();
		$query = "DROP TABLE IF EXISTS `#__raidplanner_profile`;";
		$db->setQuery($query);
		$db->query();

		$app->enqueueMessage($out);
	}

	public function postflight( $type, $parent ) {
		// migrate theme folder content…
		echo '<div class="alert alert-info">';
		if (JFolder::exists(JPATH_SITE . '/images/raidplanner' )) {
			echo '<h2>Migrating theme files to media folder</h2>';
			$folders = JFolder::folders( JPATH_SITE . '/images/raidplanner' );
			foreach ($folders as $folder) {
				echo "Migrating path: " . $folder . " [ ";
				if (!JFolder::exists( JPATH_SITE . '/media/com_raidplanner/' . $folder ) ) {
					JFolder::create( JPATH_SITE . '/media/com_raidplanner/' . $folder );
				}
				$files = JFolder::files( JPATH_SITE . '/images/raidplanner/' . $folder );
				$count = 0;
				foreach ($files as $file) {
					$count++;
					JFile::move( JPATH_SITE . '/images/raidplanner/' . $folder . "/" . $file, JPATH_SITE . '/media/com_raidplanner/' . $folder . "/" . $file );
				}
				JFolder::delete( JPATH_SITE . '/images/raidplanner/' . $folder );
				echo $count . " files moved ]<br>";
			}
			JFolder::delete( JPATH_SITE . '/images/raidplanner' ); 
		}
		/* removing manifest.xml file, if exists */
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_raidplanner/manifest.xml')) {
			JFile::delete( JPATH_ADMINISTRATOR . '/components/com_raidplanner/manifest.xml' );
		}
		echo '<h2>RaidPlanner What\'s new?</h2>';
		echo '<iframe frameborder="0" width="800px" height="200px" style="border:none;" src="http://taracque.hu/static/raidplanner/whats-new.php"></iframe><br>';
		echo '<a href="' . JRoute::_('index.php?option=com_raidplanner') . '" class="btn btn-primary">' . JText::_( 'COM_RAIDPLANNER' ) . '</a>';
		echo '</div>';
	}

	public function uninstall($adapter)
	{
		$app       = JFactory::getApplication();

		$installer = JInstaller::getInstance();
		$app->enqueueMessage('RaidPlanner Today module, and RaidPlanner user plugins needs to be removed manualy!');
	}
}