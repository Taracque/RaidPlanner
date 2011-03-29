<?php
/**
 * Hello World table class
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
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

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRaid(& $db) {
		parent::__construct('#__raidplanner_raid', 'raid_id', $db);
	}
}