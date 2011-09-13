<?php
/*------------------------------------------------------------------------
# Roster Model for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
jimport( 'joomla.application.component.helper' );
jimport( 'joomla.utilities.date' );

class RaidPlannerModelRoster extends JModel
{
	function getCharacters()
	{
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__raidplanner_character AS chars
					LEFT JOIN #__raidplanner_class AS class ON class.class_id = chars.class_id
					LEFT JOIN #__raidplanner_race AS race ON race.race_id = chars.race_id
					LEFT JOIN #__raidplanner_gender AS gender ON gender.gender_id = chars.gender_id
					ORDER BY chars.rank DESC, chars.char_level DESC, chars.char_name ASC";
			
		$db->setQuery($query);

		return ( $db->loadAssocList('character_id') );
	}
}