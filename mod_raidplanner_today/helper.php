<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

class ModRaidPlannerTodayHelper
{

	/**
	 * Returns a list of post items
	*/
	public function getItems($raidshowNumber, $user_id)
	{

		// get a reference to the database
		$db = &JFactory::getDBO();

		// get a list of $raidshow_number ordered by start_time
		$query = 'SELECT r.raid_id,r.location,r.start_time,s.confirmed,c.char_name,ro.role_name
					FROM `#__raidplanner_raid` AS r
					LEFT JOIN `#__raidplanner_signups` AS s ON s.raid_id = r.raid_id
					LEFT JOIN `#__raidplanner_role` AS ro ON ro.role_id = s.role_id
					LEFT JOIN `#__raidplanner_character` AS c ON c.character_id = s.character_id
					WHERE DATE(start_time)=DATE(NOW()) AND c.profile_id = '.intval($user_id).' ORDER BY location ASC';

		$db->setQuery($query);
		$items = ($items = $db->loadObjectList())?$items:array();
		return $items;

	} //end getItems  */
}