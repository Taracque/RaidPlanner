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
class TableCharacter extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $character_id = null;

	/**
	 * @var string
	 */
	var $char_name = null;

	/**
	 * @var int
	 */
	var $profile_id	= null;
	var $class_id = null;
	var $role_id = null;
	var $gender_id = null;
	var $race_id = null;
	var $char_level = null;
	var $rank = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCharacter(& $db) {
		parent::__construct('#__raidplanner_character', 'character_id', $db);
	}
}