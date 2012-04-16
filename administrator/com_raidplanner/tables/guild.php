<?php
/*------------------------------------------------------------------------
# Guild Table for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableGuild extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $guild_id = null;

	/**
	 * @var string
	 */
	var $guild_name = null;
	var $sync_plugin = null;
	var $lastSync = null;
	var $params = null;

	/**
	 * @var int
	 */
	var $guild_level = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableGuild(& $db) {
		parent::__construct('#__raidplanner_guild', 'guild_id', $db);
	}
}