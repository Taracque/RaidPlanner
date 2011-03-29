<?php
/**
 * Raid Planner for Raid Planner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_2
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 
/**
 * Hello Model
 *
 * @package    RaidPlanner
 * @subpackage Components
 */
class RaidPlannerModelRaidPlanner extends JModel
{

    /**
    * Gets the events for the given month +-2 weeks
    * @return array The array contains event
    */
    function getEvents($year_month = null)
    {
    	$db = & JFactory::getDBO();
    	if ($year_month == null) {
    		$year_month = date("Y-m-")."01";
    	}
    	if ($year_month=='all') {
	    	$query = "SELECT raid_id,location,status,raid_leader,UNIX_TIMESTAMP(start_time) AS start_time FROM #__raidplanner_raid ORDER BY start_time ASC";
    	}
		else if ($year_month=='own') {
    		$user = & JFactory::getUser();
	    	$query = "SELECT r.raid_id,r.location,r.status,r.raid_leader,UNIX_TIMESTAMP(r.start_time) AS start_time,r.description,r.invite_time
	    				FROM #__raidplanner_signups AS s
	    				LEFT JOIN #__raidplanner_raid AS r ON r.raid_id=s.raid_id
	    				WHERE s.profile_id = ".$user->id."
	    				ORDER BY r.start_time ASC";
    	} else {
	    	$query = "SELECT raid_id,location,status,raid_leader,UNIX_TIMESTAMP(start_time) AS start_time FROM #__raidplanner_raid WHERE start_time>=DATE_SUB(".$db->Quote($year_month).",interval 2 week) AND start_time<=DATE_ADD(".$db->Quote($year_month).",interval 7 week)";
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