<?php
/**
 * RaidPlanner Model for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 
class RaidPlannerModelRaidPlanner extends JModel
{

    /**
    * Gets the events for the given month +-2 weeks
    * @return array The array contains event
    */
    function getEvents($year_month = null, $user_id = null)
    {
    	$db = & JFactory::getDBO();
		if (!$user_id) {
			$user = & JFactory::getUser();
		} else {
			$user =& JUser::getInstance( intval( $user_id ) );
		}
    	if ($year_month == null) {
    		$year_month = date("Y-m-")."01";
    	}
    	if ($year_month=='all') {
	    	$query = "SELECT raid_id,location,status,raid_leader,UNIX_TIMESTAMP(start_time) AS start_time,UNIX_TIMESTAMP(DATE_ADD(start_time,INTERVAL duration_mins MINUTE)) AS end_time
	    				FROM #__raidplanner_raid
	    				ORDER BY start_time ASC, location ASC";
    	} else if ($year_month=='own') {
	    	$query = "SELECT r.raid_id,r.location,r.status,r.raid_leader,UNIX_TIMESTAMP(r.start_time) AS start_time,UNIX_TIMESTAMP(DATE_ADD(r.start_time,INTERVAL r.duration_mins MINUTE)) AS end_time,r.description,r.invite_time
	    				FROM #__raidplanner_signups AS s
	    				LEFT JOIN #__raidplanner_raid AS r ON r.raid_id=s.raid_id
	    				WHERE s.profile_id = ".$user->id."
	    				ORDER BY r.start_time ASC, r.location ASC";
    	} else {
	    	$query = "SELECT r.raid_id,r.location,r.status,r.raid_leader,UNIX_TIMESTAMP(r.start_time) AS start_time,UNIX_TIMESTAMP(DATE_ADD(r.start_time,INTERVAL r.duration_mins MINUTE)) AS end_time,s.queue IS NOT NULL AS signed
	    				FROM #__raidplanner_raid AS r
	    				LEFT JOIN #__raidplanner_signups AS s ON s.raid_id=r.raid_id AND s.profile_id=".$user->id." 
	    				WHERE r.start_time>=DATE_SUB(".$db->Quote($year_month).",interval 2 week) AND r.start_time<=DATE_ADD(".$db->Quote($year_month).",interval 7 week)
	    				ORDER BY r.start_time ASC, r.location ASC";
	    }
    	
    	$db->setQuery($query);
    	$rows = $db->loadObjectList();
    	$result = array();
		foreach ($rows as $row) {
    		$result[date("Y-m-d",$row->start_time)][] = $row;
    	}

    	return $result;
	}

}