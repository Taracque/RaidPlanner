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

/**
 * Raid Planner Component Controller
 *
 * @package    RaidPlanner
 * @subpackage Components
 */
class RaidPlannerController extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
		
		if ($this->getTask() == 'service') {
			$db	=& JFactory::getDBO();

			// do service things, remove unanchored database entries
			// remove signups that doesn't have character
			$query = 'SELECT s.raid_id,s.character_id,s.profile_id FROM #__raidplanner_signups AS s LEFT JOIN #__raidplanner_character AS c ON c.character_id = s.character_id WHERE c.char_name IS NULL'; 
			$db->setQuery($query);
			$list = $db->loadObjectList();
			foreach ($list as $remove) {
				$db->Execute( "DELETE FROM #__raidplanner_signups WHERE raid_id=".intval($remove->raid_id)." AND character_id=".intval($remove->character_id)." AND profile_id=".intval($remove->profile_id) );
			}
			echo JText::printf('COM_RAIDPLANNER_REMOVING_UNANCHORED_SIGNUPS', count($list) ); 
		}
	}
}