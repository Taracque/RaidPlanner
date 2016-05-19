<?php
/*------------------------------------------------------------------------
# Event Model for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
jimport( 'joomla.application.component.helper' );
jimport( 'joomla.utilities.date' );

/* create JModelLegacy if not exist */
if (!class_exists('JModelLegacy')) {
	class JModelLegacy extends JModel {}
}

class RaidPlannerModelCharacter extends JModelLegacy
{
	/**
	* Save a new character, or create a new one
	*/
	function saveCharacter() {
		$character_id = JRequest::getVar('character_id', null, 'INT');
		$user =JFactory::getUser();
		$user_id = $user->id;

		// check if user have edit_characters privilegs
		if ($this->canEdit())
		{
			$char_name = JRequest::getVar('char_name', null, 'default', 'STRING');
			$class_id = JRequest::getVar('class_id', null, 'default', 'INT');
			$gender_id = JRequest::getVar('gender_id', 1, 'default', 'INT');
			$race_id = JRequest::getVar('race_id', null, 'default', 'INT');
			$char_level = JRequest::getVar('char_level', null, 'default', 'INT');
			$rank = JRequest::getVar('rank', null, 'default', 'INT');
			$guild_id = JRequest::getVar('guild_id', null, 'default', 'INT');

			$db = JFactory::getDBO();

			if ($character_id <= 0)
			{
				// if name is not already used
				$query = "SELECT character_id FROM #__raidplanner_character WHERE char_name=".$db->Quote( $char_name )." AND guild_id=" . intval($guild_id);
				$db->setQuery($query);
				if ($db->loadResult() > 0)
				{
					JFactory::getApplication()->enqueueMessage(JText::_('COM_RAIDPLANNER_CHARACTER_ALREADY_EXISTS'), 'warning');

					return false;
				}
				// insert an empty record first
				$query = "INSERT INTO #__raidplanner_character (profile_id) VALUES (".$user_id.")";
				$db->setQuery($query);
				$db->query();
				$character_id = $db->insertid();
			} else {
				// check if character is own, or not assigned
				$query = "SELECT character_id FROM #__raidplanner_character WHERE character_id = ".$character_id." AND (profile_id=0 OR profile_id=".$user_id.")";
				$db->setQuery($query);
				$character_id = $db->loadResult();
			}
	
			if ($character_id > 0)
			{
				// update the record
				$query = "UPDATE #__raidplanner_character SET"
						. " char_name=".$db->Quote($char_name)
						. ",class_id=".intval($class_id)
						. ",gender_id=".intval($gender_id)
						. ",race_id=".intval($race_id)
						. ",char_level=".intval($char_level)
						. ",rank=".intval($rank)
						. ",guild_id=".intval($guild_id)
						. " WHERE character_id=".intval($character_id);
		
				$db->setQuery($query);
				$db->query();
			}
		}
		return $character_id;
	}
	
	function canEdit($user_id=null) {
		return RaidPlannerHelper::getPermission( 'characteredit', $user_id );
	}


	/**
	* Gets the list of own/unasigned characters
	*/
	function getCharacters( $ownOnly = false) {
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$query = "SELECT c.character_id,c.char_name,g.guild_id,g.guild_name
					FROM #__raidplanner_character AS c
					LEFT JOIN #__raidplanner_guild AS g ON g.guild_id = c.guild_id
					WHERE " . ( (!$ownOnly)?"c.profile_id = 0 OR":"" ) . " c.profile_id= " . $user->id . " ORDER BY g.guild_id ASC, c.char_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadAssocList('character_id');

    	return $result;
	}

	function getCharacter( $char_name, $char_id = null ) {
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		if (intval($char_id) <= 0)
		{
			$query = "SELECT * FROM #__raidplanner_character AS c 
						WHERE (c.profile_id = 0 OR c.profile_id=".$user->id.") AND char_name='".$db->escape( $char_name )."'";
		} else {
			$query = "SELECT * FROM #__raidplanner_character AS c 
						WHERE (c.profile_id = 0 OR c.profile_id=".$user->id.") AND character_id=".intval($char_id)."";
		}
		// load the list
		$db->setQuery($query);
		$result = $db->loadObject();

    	return $result;
	}


}
