<?php
/**
 * Raid table class
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
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
	var $profile_id = null;
	var $freeze_time = null;
	var $minimum_level = null;
	var $maximum_level = null;
	var $minimum_rank = null;
	var $is_template = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRaid(& $db) {
		parent::__construct('#__raidplanner_raid', 'raid_id', $db);
	}
}