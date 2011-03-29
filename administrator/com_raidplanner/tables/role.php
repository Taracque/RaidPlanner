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

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRole(& $db) {
		parent::__construct('#__raidplanner_role', 'role_id', $db);
	}
}