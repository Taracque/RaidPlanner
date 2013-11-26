<?php
/*------------------------------------------------------------------------
# RaidPlanner Model for RaidPlanner Component
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

/* create JModelLegacy if not exist */
if (!class_exists('JModelLegacy')) {
	class JModelLegacy extends JModel {}
}

class RaidPlannerModelRaidPlanner extends JModelLegacy
{

    /**
    * Gets the events for the given month +-2 weeks
    * @return array The array contains event
    */
    function getEvents($year_month = null, $user_id = null, $attendants = false)
    {
    	$db = JFactory::getDBO();
		if (!$user_id) {
			$user = JFactory::getUser();
		} else {
			$user =& JUser::getInstance( intval( $user_id ) );
		}
    	if ($year_month == null) {
    		$year_month = date("Y-m-")."01";
    	}
    	if ($year_month=='all') {
	    	$query = "SELECT raid_id,location,description,icon_name,status,raid_leader,start_time,(DATE_ADD(start_time,INTERVAL duration_mins MINUTE)) AS end_time
	    				FROM #__raidplanner_raid
	    				GROUP BY raid_id
	    				ORDER BY start_time ASC, location ASC";
    	} else if ($year_month=='own') {
	    	$query = "SELECT r.raid_id,r.location,r.description,r.icon_name,r.status,r.raid_leader,r.start_time,(DATE_ADD(r.start_time,INTERVAL r.duration_mins MINUTE)) AS end_time,r.description,r.invite_time
	    				FROM #__raidplanner_signups AS s
	    				LEFT JOIN #__raidplanner_raid AS r ON r.raid_id=s.raid_id
	    				LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
	    				WHERE c.profile_id = ".$user->id."
	    				GROUP BY r.raid_id
	    				ORDER BY r.start_time ASC, r.location ASC";
    	} else {
	    	$query = "SELECT r.raid_id,r.location,r.description,r.icon_name,r.status,r.raid_leader,r.start_time,(DATE_ADD(r.start_time,INTERVAL r.duration_mins MINUTE)) AS end_time,s.queue
	    				FROM #__raidplanner_raid AS r
	    				LEFT JOIN (#__raidplanner_signups AS s, #__raidplanner_character AS c) ON (s.raid_id=r.raid_id AND c.character_id=s.character_id AND c.profile_id=".$user->id.") 
	    				WHERE r.start_time>=DATE_SUB(".$db->Quote($year_month).",interval 2 week) AND r.start_time<=DATE_ADD(".$db->Quote($year_month).",interval 7 week)
	    				GROUP BY r.raid_id
	    				ORDER BY r.start_time ASC, r.location ASC";
	    }
   	
    	$db->setQuery($query);
    	$rows = $db->loadObjectList();
    	$result = array();
		foreach ($rows as $row) {
			$date = JHTML::_('date', $row->start_time, RaidPlannerHelper::sqlDateFormat() );
			/* get the attendants if requested */
			if ($attendants) {
				$query = "SELECT c.char_name
						FROM #__raidplanner_signups AS s
						LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
						WHERE s.raid_id=".intval($row->raid_id)." AND s.queue=1
						ORDER BY s.confirmed DESC, c.char_name ASC";
		    	$db->setQuery($query);
				$row->attendants = $db->loadResultArray();

			}
    		$result[$date][] = $row;
    	}

    	return $result;
	}

	/**
	 * Translates day of week number to a string.
	 * Joomla 1.6 compatibility, JDate::dayToString is protected
	 */
	public static function dayToString($day, $abbr = false)
	{
		switch ($day) {
			case 0: return $abbr ? JText::_('SUN') : JText::_('SUNDAY');
			case 1: return $abbr ? JText::_('MON') : JText::_('MONDAY');
			case 2: return $abbr ? JText::_('TUE') : JText::_('TUESDAY');
			case 3: return $abbr ? JText::_('WED') : JText::_('WEDNESDAY');
			case 4: return $abbr ? JText::_('THU') : JText::_('THURSDAY');
			case 5: return $abbr ? JText::_('FRI') : JText::_('FRIDAY');
			case 6: return $abbr ? JText::_('SAT') : JText::_('SATURDAY');
		}
	}

	/**
	 * Translates month number to string
	 * Joomla 1.6 compatibility, JDate::monthToString is protected
	 */
	public static function monthToString($month, $abbr = false)
	{
		switch ($month)
		{
			case 1:  return $abbr ? JText::_('JANUARY_SHORT')   : JText::_('JANUARY');
			case 2:  return $abbr ? JText::_('FEBRUARY_SHORT')  : JText::_('FEBRUARY');
			case 3:  return $abbr ? JText::_('MARCH_SHORT')     : JText::_('MARCH');
			case 4:  return $abbr ? JText::_('APRIL_SHORT')     : JText::_('APRIL');
			case 5:  return $abbr ? JText::_('MAY_SHORT')       : JText::_('MAY');
			case 6:  return $abbr ? JText::_('JUNE_SHORT')      : JText::_('JUNE');
			case 7:  return $abbr ? JText::_('JULY_SHORT')      : JText::_('JULY');
			case 8:  return $abbr ? JText::_('AUGUST_SHORT')    : JText::_('AUGUST');
			case 9:  return $abbr ? JText::_('SEPTEMBER_SHORT')  : JText::_('SEPTEMBER');
			case 10: return $abbr ? JText::_('OCTOBER_SHORT')   : JText::_('OCTOBER');
			case 11: return $abbr ? JText::_('NOVEMBER_SHORT')  : JText::_('NOVEMBER');
			case 12: return $abbr ? JText::_('DECEMBER_SHORT')  : JText::_('DECEMBER');
		}
	}

}