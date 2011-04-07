<?php
/**
 * Class table class
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
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