<?php
/**
 * Race table class
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
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