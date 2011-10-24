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

class RaidPlannerModelCharacter extends JModel
{
	/**
	* Save a new character, or create a new one
	*/
	function saveCharacter() {
		$character_id = JRequest::getVar('character_id', null, 'INT');
		$user =& JFactory::getUser();
		$user_id = $user->id;

		// check if user have edit_characters privilegs
		if ($this->canEdit())
		{
			$db = & JFactory::getDBO();

			if ($character_id <= 0)
			{
				// if name is not already used
				$query = "SELECT character_id FROM #__raidplanner_character WHERE char_name=".$db->Quote( JRequest::getVar('char_name', null, 'default', 'STRING') )."";
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
				$char_name = JRequest::getVar('char_name', null, 'default', 'STRING');
				$class_id = JRequest::getVar('class_id', null, 'default', 'INT');
				$gender_id = JRequest::getVar('gender_id', 1, 'default', 'INT');
				$race_id = JRequest::getVar('race_id', null, 'default', 'INT');
				$char_level = JRequest::getVar('char_level', null, 'default', 'INT');
				$rank = JRequest::getVar('rank', null, 'default', 'INT');
				$guild_id = JRequest::getVar('guild_id', null, 'default', 'INT');
		
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
		$reply = false;
		
		$guest = false;
		if (!$user_id) {
			$user =& JFactory::getUser();
			$user_id = $user->id;
			$guest = $user->guest;
		}
		$db = & JFactory::getDBO();
		if (!$guest) {
			$query = "SELECT permission_value FROM #__raidplanner_profile AS profile LEFT JOIN #__raidplanner_permissions AS perm ON profile.group_id = perm.group_id WHERE profile.profile_id=".intval($user_id)." AND perm.permission_name = 'edit_characters' AND perm.permission_value=1";
		} else {
			$query = "SELECT permission_value FROM #__raidplanner_permissions AS perm LEFT JOIN #__raidplanner_groups AS g ON g.group_id = perm.group_id WHERE g.group_name='Guest' AND perm.permission_name = 'edit_characters' AND perm.permission_value=1";
		}
		$db->setQuery($query);
		
		$dbreply = ($db->loadResultArray());
		$reply = (@$dbreply[0] === "1");

		return $reply;
	}


	/**
	* Gets the list of own/unasigned characters
	*/
	function getCharacters( $ownOnly = false) {
		$db = & JFactory::getDBO();
		$user =& JFactory::getUser();
		
		$query = "SELECT c.character_id,c.char_name
					FROM #__raidplanner_character AS c 
					WHERE " . ( (!$ownOnly)?"c.profile_id = 0 OR":"" ) . "c.profile_id= " . $user->id . " ORDER BY c.char_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadAssocList('character_id');

    	return $result;
	}

	function getGuilds() {
		$db = & JFactory::getDBO();
		
		$query = "SELECT g.guild_id,g.guild_name
					FROM #__raidplanner_guild AS g 
					ORDER BY g.guild_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadAssocList('guild_id');

    	return $result;
	}

	function getClasses() {
		$db = & JFactory::getDBO();
		
		$query = "SELECT c.class_id,c.class_name
					FROM #__raidplanner_class AS c 
					ORDER BY c.class_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadAssocList('class_id');

    	return $result;
	}

	function getRaces() {
		$db = & JFactory::getDBO();
		
		$query = "SELECT r.race_id,r.race_name
					FROM #__raidplanner_race AS r 
					ORDER BY r.race_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadAssocList('race_id');

    	return $result;
	}

	function getGenders() {
		$db = & JFactory::getDBO();
		
		$query = "SELECT g.gender_id,g.gender_name
					FROM #__raidplanner_gender AS g 
					ORDER BY g.gender_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadAssocList('gender_id');

    	return $result;
	}

	function getCharacter( $char_name, $char_id = null ) {
		$db = & JFactory::getDBO();
		$user =& JFactory::getUser();
		
		if (intval($char_id) <= 0)
		{
			$query = "SELECT * FROM #__raidplanner_character AS c 
						WHERE (c.profile_id = 0 OR c.profile_id=".$user->id.") AND char_name='".$db->getEscaped( $char_name )."'";
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
