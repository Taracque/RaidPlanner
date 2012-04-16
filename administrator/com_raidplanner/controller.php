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

			// remove profile that doesn't have group or doesn't have user
			$query = "SELECT p.profile_id,p.group_id FROM #__raidplanner_profile AS c LEFT JOIN #__raidplanner_groups AS g ON g.group_id = p.guild_id LEFT JOIN #__users AS u ON u.id = p.profile_id WHERE (g.group_name IS NULL OR u.name IS NULL OR g.group_name='Guest')"; 
			$db->setQuery($query);
			$list = $db->loadObjectList();
			if (count($list) > 0)
			{
				foreach ($list as $remove) {
					$db->setQuery( "DELETE FROM #__raidplanner_profile WHERE profile_id=".intval($remove->profile_id)." AND group_id=".intval($remove->group_id) );
					$db->query();
				}
			}
			echo JText::sprintf('COM_RAIDPLANNER_REMOVING_UNASSIGNED_PROFILE_DATA', count($list) ) . "<br />";
		}
	}
	
	/* Ajax gateway for statistics */
	/*
		{ "title": "My Chart", "colNames": ["Internet Explorer", "FireFox"], "rowNames": ["Q1", "Q2", "Q3", "Q4"], "rows": [ [1,2], [3,4], [5,6], [7,8] ] }
	*/
	function getStats()
	{
		$app = &JFactory::getApplication();	
		$from = JRequest::getVar('from', '');
		$to = JRequest::getVar('to', '');
		$type = JRequest::getVar('type', 'attendance');
		
		$db	=& JFactory::getDBO();
		switch ($type) {
			case 'raidlocations':
				$reply->title = JText::_('COM_RAIDPLANNER_RAID_LOCATIONS');

				$where = array();
				if ($from!='')
				{
					$where[] = "start_time>='" . $db->getEscaped( $from ) . "'";
				}
				if ($to!='')
				{
					$where[] = "start_time<='" . $db->getEscaped( $to ) . "'";
				}
				if (!empty($where))
				{
					$wherestr = "WHERE " . implode(" AND ", $where );
				} else {
					$wherestr = "";
				}
				
				$query = "SELECT COUNT(*) AS `count`,icon_name FROM `#__raidplanner_raid` " . $wherestr . " GROUP BY icon_name";
				$db->setQuery($query);
				$list = $db->loadObjectList();
				if (count($list) > 0)
				{
					foreach ($list as $data) {
						$reply->rowNames[] = array( $data->icon_name );
						$reply->rows[] = array( $data->count , $data->count );
					}
				} else {
					$reply->rowNames[] = array(JText::_('COM_RAIDPLANNER_NO_RAID'));
					$reply->rows[] = array(0);
				}
				$reply->colNames = array();
			break;
			case 'attendance':
			default:
				$reply->title = JText::_('COM_RAIDPLANNER_RAID_ATTENDANCE');

				$where = array();
				if ($from!='')
				{
					$where[] = "r.start_time>='" . $db->getEscaped( $from ) . "'";
				}
				if ($to!='')
				{
					$where[] = "r.start_time<='" . $db->getEscaped( $to ) . "'";
				}
				if (!empty($where))
				{
					$wherestr = "WHERE " . implode(" AND ", $where );
				} else {
					$wherestr = "";
				}
				$rows = array();
				$rowNames = array();
				
				$query = "SELECT COUNT(*) AS `count`,c.char_name,s.queue FROM `#__raidplanner_raid` AS r LEFT JOIN `#__raidplanner_signups` AS s ON s.raid_id=r.raid_id LEFT JOIN `#__raidplanner_character` AS c ON c.character_id = s.character_id " . $wherestr . " GROUP BY s.character_id,s.queue";
				$db->setQuery($query);
				$list = $db->loadObjectList();
				if (count($list) > 0)
				{
					foreach ($list as $data) {
						if (!isset($rows[$data->char_name]))
						{
							$rows[$data->char_name] = array(
								-1 => 0,
								0 => 0,
								1 => 0,
								2 => 0
							);
							$rowNames[$data->char_name] = $data->char_name;
						}
						$rows[$data->char_name][$data->queue] = $reply->rows[$data->char_name][intval($data->queue)] + $data->count;
					}
				}
				foreach ($rows as $row)
				{
					$reply->rows[] = array_values($row);
				}
				$reply->rowNames = array_values($rowNames);

				$reply->colNames = array(
					JText::_('COM_RAIDPLANNER_STATUSES_-1'), JText::_('COM_RAIDPLANNER_STATUSES_0'), JText::_('COM_RAIDPLANNER_STATUSES_1'), JText::_('COM_RAIDPLANNER_STATUSES_2')
				);
			
		}

		echo json_encode($reply);

		$app->close();
	}
}