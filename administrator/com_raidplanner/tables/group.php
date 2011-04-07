<?php
/**
 * Group table class
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
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