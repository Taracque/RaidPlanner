<?php
/*------------------------------------------------------------------------
# Race Table for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableRace extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $race_id = null;

	/**
	 * @var string
	 */
	var $race_name = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRace(& $db) {
		parent::__construct('#__raidplanner_race', 'race_id', $db);
	}
}