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
	function getCharacters( $guild_id )
	{
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__raidplanner_character AS chars
					LEFT JOIN #__raidplanner_class AS class ON class.class_id = chars.class_id
					LEFT JOIN #__raidplanner_race AS race ON race.race_id = chars.race_id
					LEFT JOIN #__raidplanner_gender AS gender ON gender.gender_id = chars.gender_id
					WHERE guild_id = " . intval($guild_id) . "
					ORDER BY chars.rank DESC, chars.char_level DESC, chars.char_name ASC";
			
		$db->setQuery($query);

		return ( $db->loadAssocList('character_id') );
	}

	public function getGuildInfo($guild_id = null)
	{
		$db = & JFactory::getDBO();
		if (intval($guild_id)>0)
		{
			$query = "SELECT * FROM #__raidplanner_guild WHERE guild_id = " . intval($guild_id);
		} else {
			$query = "SELECT * FROM #__raidplanner_guild ORDER BY guild_id ASC LIMIT 1";
		}
		$db->setQuery($query);
		$tmp = $db->loadObject();
		$tmp->params = json_decode($tmp->params);
		return ( $tmp );
	}

}