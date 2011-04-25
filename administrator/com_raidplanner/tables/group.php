<?php
/*------------------------------------------------------------------------
# Group Table for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableGroup extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $group_id = null;

	/**
	 * @var string
	 */
	var $group_name = null;

	/**
	 * @var int
	 */
	var $default = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableGroup(& $db) {
		parent::__construct('#__raidplanner_groups', 'group_id', $db);
	}
}