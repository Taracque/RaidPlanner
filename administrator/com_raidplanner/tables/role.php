<?php
/**
 * Role table class
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
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