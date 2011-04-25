<?php
/*------------------------------------------------------------------------
# Class Table for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableClass extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $class_id = null;

	/**
	 * @var string
	 */
	var $class_name = null;
	var $class_color = null;

	/**
	 * @var int
	 */
	var $armory_id = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableClass(& $db) {
		parent::__construct('#__raidplanner_class', 'class_id', $db);
	}
}