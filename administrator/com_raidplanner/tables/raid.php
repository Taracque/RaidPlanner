<?php
/*------------------------------------------------------------------------
# Raid Table for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableRaid extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $raid_id = null;

	/**
	 * @var string
	 */
	var $location = null;
	var $description = null;
	var $start_time = null;
	var $invite_time = null;
	var $raid_leader = null;
	var $icon_name = null;

	/**
	* @var int
	*/
	var $status = 0;
	var $profile_id = null;
	var $freeze_time = null;
	var $minimum_level = null;
	var $maximum_level = null;
	var $minimum_rank = null;
	var $is_template = null;
	var $invited_group_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRaid(& $db) {
		parent::__construct('#__raidplanner_raid', 'raid_id', $db);
	}
}