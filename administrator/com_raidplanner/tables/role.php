<?php
/*------------------------------------------------------------------------
# Role Table for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableRole extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $role_id = null;

	/**
	 * @var string
	 */
	var $role_name = null;
	var $body_color = null;
	var $header_color = null;
	var $font_color = null;
	var $icon_name = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRole(& $db) {
		parent::__construct('#__raidplanner_role', 'role_id', $db);
	}
}