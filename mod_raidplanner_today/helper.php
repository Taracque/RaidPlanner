<?php
/*------------------------------------------------------------------------
# Helper for RaidPlanner Today Module
# mod_raidplanner_today - RaidPlanner Today Module
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );

class modRaidPlannerTodayHelper
{

	/**
	 * Returns a list of post items
	*/
	public function getItems($user_id)
	{

		// get a reference to the database
		$db = &JFactory::getDBO();
		
		// get a list of raids ordered by start_time
		$query = "SELECT r.raid_id,r.location,r.start_time,s.confirmed,c.char_name,ro.role_name
					FROM `#__raidplanner_raid` AS r
					LEFT JOIN `#__raidplanner_signups` AS s ON s.raid_id = r.raid_id AND s.profile_id = ".intval($user_id)." 
					LEFT JOIN `#__raidplanner_role` AS ro ON ro.role_id = s.role_id
					LEFT JOIN `#__raidplanner_character` AS c ON c.character_id = s.character_id
					WHERE DATE(DATE_ADD(start_time, INTERVAL " . intval(RaidPlannerHelper::getTimezone()) . " HOUR))=DATE(NOW()) ORDER BY location ASC";

		$db->setQuery($query);
		$items = ($items = $db->loadObjectList())?$items:array();
		return $items;

	} //end getItems  */
}