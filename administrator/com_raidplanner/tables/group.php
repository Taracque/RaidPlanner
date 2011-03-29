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