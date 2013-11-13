<?php
/*------------------------------------------------------------------------
# Raid Planner default controller
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

// register the installer
JLoader::register('RaidPlannerInstaller', JPATH_ADMINISTRATOR . '/components/com_raidplanner/includes/installer.php' );

/* create JControllerLegacy if not exist */
if (!class_exists('JControllerLegacy')) {
	class JControllerLegacy extends JController {}
}

/**
 * Raid Planner Component Controller
 *
 * @package    RaidPlanner
 * @subpackage Components
 */
class RaidPlannerController extends JControllerLegacy
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		if ($this->getTask() == 'doInstall') {
			$installer = new RaidPlannerInstaller();
			if ( !$installer->installUploaded( 'install_theme' ) ) {
				if ( $url = JRequest::getVar ( 'install_url', NULL ) ) {
					$installer->installFromURL( $url );
				} else {
					$installer->installPackage( JRequest::getVar ( 'install_directory', NULL ) );
				}
			}
		}
		if ($this->getTask() == 'doUninstall') {
			$installer = new RaidPlannerInstaller();
			$installer->uninstall( JRequest::getVar( 'plugin' ) );
		}

		parent::display();
		
		if ($this->getTask() == 'service') {
			$db	=& JFactory::getDBO();

			// do service things, remove unanchored database entries
			// remove signups that doesn't have character
			$query = 'SELECT s.raid_id,s.character_id,s.profile_id FROM #__raidplanner_signups AS s LEFT JOIN #__raidplanner_character AS c ON c.character_id = s.character_id WHERE c.char_name IS NULL'; 
			$db->setQuery($query);
			$list = $db->loadObjectList();
			if (count($list) > 0)
			{
				foreach ($list as $remove) {
					$db->setQuery( "DELETE FROM #__raidplanner_signups WHERE raid_id=".intval($remove->raid_id)." AND character_id=".intval($remove->character_id)." AND profile_id=".intval($remove->profile_id) );
					$db->query();
				}
			}
			echo JText::sprintf('COM_RAIDPLANNER_REMOVING_UNANCHORED_SIGNUPS', count($list) ) . "<br />";

			// remove characters that doesn't have guild
			$query = 'SELECT c.character_id,c.profile_id FROM #__raidplanner_character AS c LEFT JOIN #__raidplanner_guild AS g ON g.guild_id = c.guild_id WHERE g.guild_name IS NULL'; 
			$db->setQuery($query);
			$list = $db->loadObjectList();
			if (count($list) > 0)
			{
				foreach ($list as $remove) {
					$db->setQuery( "DELETE FROM #__raidplanner_character WHERE character_id=".intval($remove->character_id)." AND profile_id=".intval($remove->profile_id) );
					$db->query();
				}
			}
			echo JText::sprintf('COM_RAIDPLANNER_REMOVING_GUILDLESS_CHARS', count($list) ) . "<br />";
		}
	}

}