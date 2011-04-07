<?php
/**
 * Character table class
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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