<?php
/*------------------------------------------------------------------------
# Stats Controller for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2012 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerControllerStats extends RaidPlannerController
{
	/**
	 * Return JSON encoded data for Statistic page
	 */
	function display()
	{
		$start_time = JRequest::getVar('start_time', '', 'get', 'date');
		$end_time	= JRequest::getVar('end_time', '', 'get', 'date');
		$char_id	= JRequest::getVar('char_id', '', 'get', 'int');
		$group_id	= JRequest::getVar('group_id', '', 'get', 'int');
		
		$where = array();
		$where[] = 'c.char_name IS NOT NULL';
		$stat_x = "c.char_name,SUM(IF(s.queue=-1,1,0)) AS not_attending,SUM(IF(s.queue=1,1,0)) AS attending,SUM(IF(s.queue=2,1,0)) AS late, SUM(IF(s.confirmed=1,1,0)) AS confirmed, COUNT(r.raid_id) AS raids";
		$stat_y = "c.char_name";
		
		$db =& JFactory::getDBO();
		if ($group_id > 0) {
			$where[] = 'g.group_id=' . intval($group_id);
		}
		if ($char_id > 0) {
			$where[] = 'c.char_id=' . intval($char_id);
			$stat_y = "MONTH(r.start_time)";
		}
		if ($start_time != '') {
			$where[] = 'r.start_time>=' . $db->Quote( $start_time );
		}
		if ($start_end != '') {
			$where[] = 'r.start_time<=' . $db->Quote( $end_time );
		}
		
		$query = "SELECT " . $stat_x .
					" FROM #__raidplanner_raid AS r " .
					" LEFT JOIN #__raidplanner_signups AS s ON s.raid_id = r.raid_id " .
					" LEFT JOIN #__raidplanner_character AS c ON c.character_id = s.character_id " .
					" LEFT JOIN #__raidplanner_profile AS p ON p.profile_id = c.profile_id " .
					" LEFT JOIN #__raidplanner_groups AS g ON g.group_id = p.group_id ";
		$query .= " WHERE " . implode( " AND ", $where );
		$query .= " GROUP BY " . $stat_y;
		$db->setQuery($query);
		
		echo json_encode( $db->loadObjectList() );
		
		die();
	}
	
}