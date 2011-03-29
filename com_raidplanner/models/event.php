<?php
/**
 * Raid Planner for Raid Planner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_2
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
jimport( 'joomla.application.component.helper' );

/**
 * Hello Model
 *
 * @package    RaidPlanner
 * @subpackage Components
 */
class RaidPlannerModelEvent extends JModel
{

    /**
    * Gets the event specified event
    * @return array The array contains event
    */
    function getEvent($id)
    {
    	$result = new stdClass;
    	if ($id>0) {
			$db = & JFactory::getDBO();
	
			$query = "SELECT * FROM #__raidplanner_raid WHERE raid_id=".intval($id);
			
			$db->setQuery($query);
			$result = $db->loadObject();
		} else {
			$result->start_time = '';
			$result->invite_time = '';
			$result->raid_id = -1;
			$result->location = '';
			$result->description = '';
		}
    	return $result;
    }
    
    /**
    * Gets attendants for the specified event
    * @return array The array contains attendants
    */
    function getAttendants($id)
    {
    	$db = & JFactory::getDBO();

    	$query = "SELECT s.character_id,c.char_name,r.role_name,s.queue,s.confirmed,s.timestamp,s.comments,s.class_id,
    				cl.class_name,cl.class_color,c.char_level,s.profile_id,s.role_id
    			FROM #__raidplanner_signups AS s 
    				LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
    				LEFT JOIN #__raidplanner_class AS cl ON cl.class_id = c.class_id
					LEFT JOIN #__raidplanner_role AS r ON r.role_id=s.role_id
   				WHERE raid_id=".intval($id);
    	
    	$db->setQuery($query);
    	$result = $db->loadObjectList('profile_id');
		
    	return $result;
    }

	function getConfirmedRoles($data) {
		$roles = array();
		// get roles by status
		foreach ($data as $signup) {
			if ($signup->confirmed!=0) {
				if (!isset($roles[$signup->confirmed][$signup->role_name])) {
					$roles[$signup->confirmed][$signup->role_name] = 1;
				} else {
					$roles[$signup->confirmed][$signup->role_name] ++;
				}
			}
		}
		return $roles;
	}
	
	function getRoles()
	{
		$db = & JFactory::getDBO();
		$query = "SELECT role_name,role_id FROM #__raidplanner_role ORDER BY role_name ASC";

		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	}
	
	function syncProfile()
	{
		$user =& JFactory::getUser();
		$db = & JFactory::getDBO();

		$charset = explode("\n",$user->getParam('characters'));
		$pbroster = (JComponentHelper::isEnabled('com_pbroster', true));

		// check if raidplanner profile exists
		$query = "SELECT profile_id FROM #__raidplanner_profile WHERE profile_id = ".$user->id;
		$db->setQuery($query);
		$profile_id = $db->loadResult();
		if (!$profile_id) {
			// DEFAULT GOR
			$query = "INSERT INTO #__raidplanner_profile (profile_id, group_id) VALUES (".$user->id.", (SELECT group_id FROM #__raidplanner_groups WHERE `default`=1))";
			$db->Execute($query);
		}

		if ($pbroster) {
			$query = "SELECT character_id,char_name FROM #__raidplanner_character WHERE profile_id=".$user->id;
			$db->setQuery($query);
			$result = $db->loadObjectList('char_name');
	
			// update character data from pbroster
	    	foreach ($charset as $userchar) {
				$db->setQuery("SELECT name,level,genderId,raceId,classId,rank FROM #__guildroster_charinfo WHERE name=".$db->Quote($userchar));
				$chardata = $db->loadObject();
				if (($chardata) && ($chardata->name)) {
					if (isset($result[$userchar])) {
						$query = "UPDATE #__raidplanner_character SET char_level=".intval($chardata->level).",race_id=".intval($chardata->raceId).",gender_id=".(intval($chardata->genderId)+1).",rank=".intval($chardata->rank).",class_id=(SELECT class_id FROM #__raidplanner_class WHERE armory_id=".intval($chardata->classId).") WHERE profile_id=".$user->id." AND char_name=".$db->Quote($chardata->name);
					} else {
						$query = "INSERT INTO #__raidplanner_character (char_name,char_level,race_id,gender_id,rank,class_id,profile_id) VALUES (".
										$db->Quote($chardata->name).",".intval($chardata->level).",".intval($chardata->raceId).",".(intval($chardata->genderId)+1).",".(intval($chardata->rank)).",(SELECT class_id FROM #__raidplanner_class WHERE armory_id=".intval($chardata->classId)."),".$user->id.")";
					}
					$db->Execute($query);
				}
	    	}
		}
	}
	
	/**
	* Gets the list of user's characters
	*/
	function getCharacters($min_level = null, $max_level = null, $min_rank = null) {
		$user =& JFactory::getUser();
		$db = & JFactory::getDBO();
		
		$this->syncProfile();

		$charset = explode("\n",$user->getParam('characters'));

		$where = " profile_id=".$user->id;
		if ($min_level != null) { $where .= " AND char_level>=".intval($min_level); }
		if ($max_level != null) { $where .= " AND char_level<=".intval($max_level); }
		if ($min_rank != null) { $where .= " AND rank<=".intval($min_rank); }
		$query = "SELECT character_id,char_name FROM #__raidplanner_character WHERE ".$where;
		// reload the list
		$db->setQuery($query);
		$result = $db->loadObjectList('char_name');

		$charlist = array();

    	// reorder if set in characters parameters
    	foreach ($charset as $userchar) {
    		if (isset($result[$userchar])) {
				$charlist[$userchar] = $result[$userchar];
				unset($result[$userchar]);
			}
    	}
		$charlist = $charlist + $result;
		
    	return $charlist;
	}
	
	function getUserStatus($attendants,$user_id = null)
	{
		if (!$user_id) {
			$user =& JFactory::getUser();
			$user_id = $user->id;
		}
		if (isset($attendants[$user_id])) {
			return $attendants[$user_id];
		} else {
			$status->character_id = null;
			$status->role_id = null;
			$status->queue = null;
			$status->confirmed = null;
			$status->comments = null;
			
			return $status;
		}
	}

	/**
	* Signup for an event
	*/
	function signupEvent() {
		$raid_id = JRequest::getVar('raid_id', null, 'INT');
		if ($this->userCanSignUp($raid_id)) {
			$role = JRequest::getVar('role', null, 'INT');
			$queue = JRequest::getVar('queue', null, 'INT');
			$comments = JRequest::getVar('comments', null, 'STRING');
			$char_id = JRequest::getVar('character_id', null, 'INT');
	
			$user =& JFactory::getUser();
			$db = & JFactory::getDBO();
			
			// throw all sigunps by same profile for same raid
			$query = "DELETE FROM #__raidplanner_signups WHERE profile_id=".intval($user->id)." AND raid_id=".$raid_id;
			$db->Execute($query);
	
			$query="INSERT INTO #__raidplanner_signups (raid_id,character_id,queue,profile_id,role_id,comments,`timestamp`,class_id) ".
					"VALUES (".intval($raid_id).",".intval($char_id).",".intval($queue).",".$user->id.",".intval($role).",".$db->Quote($comments).",NOW(),(SELECT class_id FROM #__raidplanner_character WHERE character_id = ".intval($char_id)."))";
			$db->Execute($query);
		}
	}
	
	/**
	* check if user is an officer
	*/
	function userIsOfficer($raid_id = null) {
		$own_raid = false;

		if ($raid_id>0) {
			$db = & JFactory::getDBO();
			$user =& JFactory::getUser();
			$user_id = $user->id;

			$query = "SELECT profile_id FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
			$db->setQuery($query);
			$own_raid = ($db->loadResult() == $user_id);
		}
		
		return ($this->getPermission('edit_raids_any') || ($this->getPermission('edit_raids_own') && ($own_raid)));
	}
	
	function userCanSignUp($raid_id = null) {
		$can_signup = false;
		if ($raid_id>0) {
			$db = & JFactory::getDBO();
			$user =& JFactory::getUser();
			$user_id = $user->id;
			$query = "SELECT DATE_SUB(start_time,interval freeze_time minute) > '".JHTML::_('date', null, '%Y-%m-%d %H:%M')."' FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
			$db->setQuery($query);
			if ($db->loadResult() == 1) {
				$can_signup = $this->getPermission('allow_signup', $user_id);
			}
		}
		
		return $can_signup;
	
	}
	
	function getPermission($permission, $user_id=null) {
		$reply = false;
		
		if ($permission!='') {
			if (!$user_id) {
				$user =& JFactory::getUser();
				$user_id = $user->id;
			}
			$db = & JFactory::getDBO();
			$query = "SELECT permission_value FROM #__raidplanner_profile AS profile LEFT JOIN #__raidplanner_permissions AS perm ON profile.group_id = perm.group_id WHERE profile.profile_id=".intval($user_id)." AND perm.permission_name = ".$db->Quote($permission)." AND perm.permission_value=1";
			$db->setQuery($query);

			$reply = ($db->loadResult() == "1");
		}		
		return $reply;
	}
	
	function confirmEvent() {
		if (!$this->userIsOfficer()) {
			return false;
		}

		$db = & JFactory::getDBO();

		$raid_id = JRequest::getVar('raid_id', null, 'INT');
		$roles = JRequest::getVar('role', null, 'ARRAY');
		$confirm = JRequest::getVar('confirm', null, 'ARRAY');
		$characters = JRequest::getVar('characters', null, 'ARRAY');

		foreach ($characters as $char) {
			$query = "UPDATE #__raidplanner_signups SET role_id='".intval(@$roles[intval($char)])."',confirmed='".intval(@$confirm[intval($char)])."' WHERE raid_id=".intval($raid_id)." AND character_id=".intval($char);
			$db->Execute($query);
		}
	}
	
	/**
	* Saves an event, or create a new one
	**/
	function saveEvent() {
		$raid_id = JRequest::getVar('raid_id', null, 'INT');
		if (!$this->userIsOfficer($raid_id)) {
			return false;
		}

		$user =& JFactory::getUser();
		$user_id = $user->id;
		$tz = $user->getParam('timezone');
		$db = & JFactory::getDBO();
		if ($raid_id == -1) {
			// insert an empty record first
			$query = "INSERT INTO #__raidplanner_raid (profile_id) VALUES (".$user_id.")";
			$db->Execute($query);
			$raid_id = $db->insertid();
		}
		
		$location = JRequest::getVar('location', null, 'STRING');
		$description = JRequest::getVar('description', null, 'STRING');
		$start_time =& JFactory::getDate( JRequest::getVar('start_time', null, 'STRING') , $tz );
		$invite_time =& JFactory::getDate( JRequest::getVar('invite_time', null, 'STRING') , $tz );
		$freeze_time = JRequest::getVar('freeze_time', null, 'INT');
		$minimum_level = JRequest::getVar('minimum_level', null, 'INT');
		$maximum_level = JRequest::getVar('maximum_level', null, 'INT');
		$minimum_rank = JRequest::getVar('minimum_rank', null, 'INT');
		$icon_name = JRequest::getVar('icon_name', null, 'STRING');

		// update the record
		$query = "UPDATE #__raidplanner_raid SET"
				. " location=".$db->Quote($location)
				. ",description=".$db->Quote($description)
				. ",raid_leader=".$db->Quote($user->name)
				. ",invite_time='".$invite_time->toMySQL()
				. "',start_time='".$start_time->toMySQL()
				. "',freeze_time=".intval($freeze_time)
				. ",profile_id=".intval($user_id)
				. ",icon_name=".$db->Quote($icon_name)
				. ",minimum_level=".( ($minimum_level=='')?"NULL":intval($minimum_level) )
				. ",maximum_level=".( ($maximum_level=='')?"NULL":intval($maximum_level) )
				. ",minimum_rank=".( ($minimum_rank=='')?"NULL":intval($minimum_rank) )
				. " WHERE raid_id=".intval($raid_id);

		$db->Execute($query);
		
		return $raid_id;
	}
}
