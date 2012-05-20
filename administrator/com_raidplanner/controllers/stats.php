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
		$char_id	= JRequest::getVar('character_id', '', 'get', 'int');
		$group_id	= JRequest::getVar('group_id', '', 'get', 'int');
		
		$where = array();
		$type = 'bychar';
		$where[] = 'c.char_name IS NOT NULL';
		$stat_x = array(
			0	=>	'c.char_name',
			1	=>	'SUM(IF(s.queue=1,1,0)) AS attending',
			2	=>	'SUM(IF(s.queue=2,1,0)) AS late',
			3	=>	'SUM(IF(s.queue=-1,1,0)) AS not_attending',
			4	=>	'SUM(IF(s.confirmed=1,1,0)) AS confirmed',
			5	=>	'COUNT(r.raid_id) AS raids'			// FIXME: Invited raid count
		);
		$stat_y = "c.char_name";
		$titles = array( 
			0	=>	JText::_( 'COM_RAIDPLANNER_CHARACTER_NAME'),
			1	=>	JText::_( 'COM_RAIDPLANNER_STATUSES_1'),
			2	=>	JText::_( 'COM_RAIDPLANNER_STATUSES_2'),
			3	=>	JText::_( 'COM_RAIDPLANNER_STATUSES_-1'),
			4	=>	JText::_( 'COM_RAIDPLANNER_CONFIRMATIONS_1'),
			5	=>	JText::_( 'COM_RAIDPLANNER_RAIDS')
		);

		$db =& JFactory::getDBO();
		if ($group_id > 0) {
			$where[] = 'p.group_id=' . intval($group_id);
			$where[] = 'r.invited_group_id=' . intval($group_id);
			$stat_x[5] = 'COUNT(r.invited_group_id=' . intval($group_id) . ') AS raids';
		}
		if ($char_id > 0) {
			$where[] = 'c.character_id=' . intval($char_id);
			$stat_y = "MONTH(r.start_time)";
			$stat_x[0] = "MONTH(r.start_time) AS month";
			$titles[0] = JText::_( 'COM_RAIDPLANNER_MONTH');
			$type = 'bymonth';
		}
		if ($start_time != '') {
			$where[] = 'r.start_time>=' . $db->Quote( $start_time );
		}
		if ($end_time != '') {
			$where[] = 'r.start_time<=' . $db->Quote( $end_time );
		}
		
		if ( RaidPlannerHelper::checkACL() ) {
			// Joomla ACL used, use Joomla usergroups
			$query = "SELECT " . implode(", ", $stat_x) .
						" FROM #__raidplanner_raid AS r " .
						" LEFT JOIN #__raidplanner_signups AS s ON s.raid_id = r.raid_id " .
						" LEFT JOIN #__raidplanner_character AS c ON c.character_id = s.character_id " .
						" LEFT JOIN #__user_usergroup_map AS p ON p.user_id = c.profile_id " .
						" LEFT JOIN #__usergroups AS g ON g.id = p.group_id ";
		} else {
			$query = "SELECT " . implode(", ", $stat_x) .
						" FROM #__raidplanner_raid AS r " .
						" LEFT JOIN #__raidplanner_signups AS s ON s.raid_id = r.raid_id " .
						" LEFT JOIN #__raidplanner_character AS c ON c.character_id = s.character_id " .
						" LEFT JOIN #__raidplanner_profile AS p ON p.profile_id = c.profile_id " .
						" LEFT JOIN #__raidplanner_groups AS g ON g.group_id = p.group_id ";
		}
		$query .= " WHERE " . implode( " AND ", $where );
		$query .= " GROUP BY " . $stat_y;
		$db->setQuery($query);
		
		echo json_encode(
			array(
				'titles'	=> $titles,
				'data'		=> $db->loadObjectList(),
				'type'		=> $type
			)
		);
		
		$app = &JFactory::getApplication();
		$app->close();
	}
	
}