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

class RaidPlannerModelEvent extends JModel
{

    /**
    * Gets the event specified event
    * @return array The array contains event
    */
    function getEvent($id, $asTemplate = false)
    {
    	$result = new stdClass;
    	if ($id>0) {
			$db = & JFactory::getDBO();
	
			$query = "SELECT *,DATE_ADD(start_time,INTERVAL duration_mins MINUTE) AS end_time FROM #__raidplanner_raid WHERE raid_id=".intval($id);
			
			$db->setQuery($query);
			$result = $db->loadObject();
			if ($asTemplate) {
				$result->raid_id = intval( JRequest::getVar('raid_id') );
				$result->start_time = "0000-00-00" . substr( $result->start_time, 11 );
				$result->invite_time = "0000-00-00" . substr( $result->start_time, 11 );
			}
			$result->raid_history = $this->getHistory($result->raid_id);
		} else {
			$result->start_time = '';
			$result->duration_mins = 0;
			$result->invite_time = '';
			$result->raid_id = -1;
			$result->location = '';
			$result->description = '';
			$result->raid_history = '';
		}
    	return $result;
    }
    
    /**
    * Process, formats raid history XML
    * @return string HTML converted raid history
    */
    function getHistory($raid_id,$xml = false)
    {
    	$html = '';

		$db = & JFactory::getDBO();
		$query = "SELECT history FROM #__raidplanner_history WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$string = $db->loadResult();
		if ($xml) {
			$html = $string;
		} elseif ($string!='') {
			$xml = @simplexml_load_string(str_replace("&","&amp;",$string));
			$html .= "Start: ".$xml->start."<br />";
			$html .= "End: ".$xml->end."<br />";
			$html .= "Zone: ".$xml->zone."<br />";
			$html .= '<fieldset class="rp_history_block"><legend>Bosskills</legend><div>';
			if ($xml->BossKills) {
				foreach ($xml->BossKills->children() as $bosskill) {
					$html .= $bosskill->time . " : " . $bosskill->name . "<br />";
				}
			}
			$html .= '</div></fieldset>';
			$html .= '<fieldset class="rp_history_block"><legend>Loot</legend><div>';
			if ($xml->Loot) {
				foreach ($xml->Loot->children() as $loot) {
					$html .= $loot->Player . " : <a href=\"http://www.wowhead.com/item=" . $loot->ItemID . "\">" .  $loot->ItemName . "</a> (" . $loot->Boss . ")<br />";
				}
			}
			$html .= '</div></fieldset>';
			$html .= '<fieldset class="rp_history_block"><legend>Character join</legend><div>';
			if ($xml->Join) {
				foreach ($xml->Join->children() as $Join) {
					$html .= $Join->player . " : " .  $Join->time . "<br />";
				}
			}
			$html .= '</div></fieldset>';
			$html .= '<fieldset class="rp_history_block"><legend>Character leave</legend><div>';
			if ($xml->Leave) {
				foreach ($xml->Leave->children() as $Leave) {
					$html .= $Leave->player . " : " .  $Leave->time . "<br />";
				}
			}
			$html .= '</div></fieldset>';
		}
		return ($html);
    }
    
    /**
    * Gets attendants for the specified event
    * @return array The array contains attendants
    */
    function getAttendants($id)
    {
    	$db = & JFactory::getDBO();

    	$query = "SELECT s.character_id,c.char_name,r.role_name,s.queue,s.confirmed,s.timestamp,s.comments,s.class_id,
    				cl.class_name,cl.class_color,c.char_level,s.profile_id,s.role_id,s.queue
    			FROM #__raidplanner_signups AS s 
    				LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
    				LEFT JOIN #__raidplanner_class AS cl ON cl.class_id = c.class_id
					LEFT JOIN #__raidplanner_role AS r ON r.role_id=s.role_id
   				WHERE s.raid_id=".intval($id)."
   				ORDER BY s.confirmed DESC, s.queue DESC,r.role_name DESC";
    	
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
			// attendance total 0 index
			if ($signup->queue == 1) {
				if (!isset($roles[0][$signup->role_name])) {
					$roles[0][$signup->role_name] = 1;
				} else {
					$roles[0][$signup->role_name] ++;
				}
			}
		}
		return $roles;
	}
	
	function getRoles()
	{
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__raidplanner_role ORDER BY role_name ASC";

		$db->setQuery($query);
		$result = $db->loadObjectList();
		$reply = array();
		// rekey by name
		foreach ($result as $role) {
			$reply[$role->role_name] = $role;
		}
		
		return $reply;
	}
	
    function getTemplates()
    {
    	$db = & JFactory::getDBO();
    	$query = "SELECT raid_id,location FROM #__raidplanner_raid WHERE is_template = 1 ORDER BY location ASC";
    	
    	$db->setQuery($query);
    	$result = $db->loadObjectList();

    	return $result;
	}

	function syncProfile()
	{
		$user =& JFactory::getUser();
		
		if (!$user->guest) {
			$db = & JFactory::getDBO();
	
			$charset = explode("\n",$user->getParam('characters'));
	
			// check if raidplanner profile exists
			$query = "SELECT profile_id FROM #__raidplanner_profile WHERE profile_id = ".$user->id;
			$db->setQuery($query);
			$profile_id = $db->loadResult();
			if (!$profile_id) {
				// DEFAULT GOR
				$query = "INSERT INTO #__raidplanner_profile (profile_id, group_id) VALUES (".$user->id.", (SELECT group_id FROM #__raidplanner_groups WHERE `default`=1))";
				$db->setQuery($query);
				$db->query();
			}
	
			$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
			if ($paramsObj->get('use_pbroster', '1') == '1') {
				$pbroster = (JComponentHelper::isEnabled('com_pbroster', true));
			} else {
				$pbroster = false;
			}
			if ($pbroster) {
				$query = "SELECT character_id,char_name FROM #__raidplanner_character WHERE profile_id=".$user->id;
				$db->setQuery($query);
				$result = $db->loadObjectList('char_name');
	
				// update character data from pbroster
				foreach ($charset as $userchar) {
					$db->setQuery("SELECT name,level,genderId,raceId,classId,rank FROM #__guildroster_charinfo WHERE name=".$db->Quote($userchar));
					$chardata = $db->loadObject();
					if (($chardata) && ($chardata->name) && (intval($chardata->level) > 0) && (intval($chardata->rank) < 9) ) {	// do not sync community member and invalid characters
						if (isset($result[$userchar])) {
							$query = "UPDATE #__raidplanner_character SET char_level=".intval($chardata->level).",race_id=".intval($chardata->raceId).",gender_id=".(intval($chardata->genderId)+1).",rank=".intval($chardata->rank).",class_id=(SELECT class_id FROM #__raidplanner_class WHERE armory_id=".intval($chardata->classId).") WHERE profile_id=".$user->id." AND char_name=".$db->Quote($chardata->name);
						} else {
							$query = "INSERT INTO #__raidplanner_character (char_name,char_level,race_id,gender_id,rank,class_id,profile_id) VALUES (".
											$db->Quote($chardata->name).",".intval($chardata->level).",".intval($chardata->raceId).",".(intval($chardata->genderId)+1).",".(intval($chardata->rank)).",(SELECT class_id FROM #__raidplanner_class WHERE armory_id=".intval($chardata->classId)."),".$user->id.")";
						}
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}
	}
	
	/**
	* Gets the list of user's characters
	*/
	function getCharacters($min_level = null, $max_level = null, $min_rank = null, $everyone = false) {
		$user =& JFactory::getUser();
		$db = & JFactory::getDBO();
		
		$this->syncProfile();

		$charset = explode("\n",$user->getParam('characters'));

		if (!$everyone) {
			$where = " c.profile_id=".$user->id;
		} else {
			$where = " 1=1";
		}
		if (($min_level != null) && ($min_level!='')) { $where .= " AND c.char_level>=".intval($min_level); }
		if (($max_level != null) && ($max_level!='')) { $where .= " AND c.char_level<=".intval($max_level); }
		if (($min_rank != null) && ($min_rank!='')) { $where .= " AND c.rank<=".intval($min_rank); }
		$query = "SELECT c.character_id,c.char_name,c.profile_id
					FROM #__raidplanner_character AS c 
					WHERE ".$where." ORDER BY c.char_name ASC";
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
			$db->setQuery($query);
			$db->query();
			
			$query="INSERT INTO #__raidplanner_signups (raid_id,character_id,queue,profile_id,role_id,comments,`timestamp`,class_id) ".
					"VALUES (".intval($raid_id).",".intval($char_id).",".intval($queue).",".$user->id.",".intval($role).",".$db->Quote($comments).",NOW(),(SELECT class_id FROM #__raidplanner_character WHERE character_id = ".intval($char_id)."))";
			$db->setQuery($query);
			$db->query();
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
	
	function canDelete($raid_id = null) {
		$own_raid = false;

		if ($raid_id>0) {
			$db = & JFactory::getDBO();
			$user =& JFactory::getUser();
			$user_id = $user->id;

			$query = "SELECT profile_id FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
			$db->setQuery($query);
			$own_raid = ($db->loadResult() == $user_id);
		}

		return ($this->getPermission('delete_raid_any') || ($this->getPermission('delete_raid_own') && ($own_raid)));
	}
	
	function userCanSignUp($raid_id = null) {
		$can_signup = false;
		if ($raid_id>0) {
			$db = & JFactory::getDBO();
			$user =& JFactory::getUser();
			$user_id = $user->id;
			$date = new JDate();
			$query = "SELECT DATE_SUB(start_time,interval freeze_time minute) > '" . $date->toMySQL() . "' FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
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
			$guest = false;
			if (!$user_id) {
				$user =& JFactory::getUser();
				$user_id = $user->id;
				$guest = $user->guest;
			}
			$db = & JFactory::getDBO();
			if (!$guest) {
				$query = "SELECT permission_value FROM #__raidplanner_profile AS profile LEFT JOIN #__raidplanner_permissions AS perm ON profile.group_id = perm.group_id WHERE profile.profile_id=".intval($user_id)." AND perm.permission_name = ".$db->Quote($permission)." AND perm.permission_value=1";
			} else {
				$query = "SELECT permission_value FROM #__raidplanner_permissions AS perm LEFT JOIN #__raidplanner_groups AS g ON g.group_id = perm.group_id WHERE g.group_name='Guest' AND perm.permission_name = ".$db->Quote($permission)." AND perm.permission_value=1";
			}
			$db->setQuery($query);
			
			$dbreply = ($db->loadResultArray());
			$reply = ($dbreply[0] === "1");
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
		$history = trim( JRequest::getVar('history', '', 'post', 'string', JREQUEST_ALLOWRAW ) );

		foreach ($characters as $char) {
			if (intval(@$roles[intval($char)])==0) {
				$query = "DELETE FROM #__raidplanner_signups WHERE raid_id=".intval($raid_id)." AND character_id=".intval($char);
			} else {
				$query = "UPDATE #__raidplanner_signups SET role_id='".intval(@$roles[intval($char)])."',confirmed='".intval(@$confirm[intval($char)])."' WHERE raid_id=".intval($raid_id)." AND character_id=".intval($char);
			}
			$db->setQuery($query);
			$db->query();
		}

		$query = "DELETE FROM #__raidplanner_history WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$db->query();

		// save history if not empty
		if ($history!='') {
			$query = "INSERT INTO #__raidplanner_history SET raid_id=".intval($raid_id).", history=".$db->Quote($history);
			$db->setQuery($query);
			$db->query();
		}
		
		// add new_character if there's one
		$new_char_id = JRequest::getVar('new_character', null, 'INT');
		if ($new_char_id > 0) {
			$query = "SELECT profile_id FROM #__raidplanner_character WHERE character_id=".$new_char_id;
			$db->setQuery($query);
			$profile_id = $db->loadResult();
			if ($profile_id>0) {
				$new_queue = JRequest::getVar('new_queue', null, 'INT');
				$new_role = JRequest::getVar('new_role', null, 'INT');
				
				$query = "DELETE FROM #__raidplanner_signups WHERE profile_id=".intval($profile_id)." AND raid_id=".$raid_id;
				$db->setQuery($query);
				$db->query();
				
				$query="INSERT INTO #__raidplanner_signups (raid_id,character_id,queue,profile_id,role_id,comments,`timestamp`,class_id) ".
						"VALUES (".intval($raid_id).",".intval($new_char_id).",".intval($new_queue).",".$profile_id.",".intval($new_role).",'',NOW(),(SELECT class_id FROM #__raidplanner_character WHERE character_id = ".intval($new_char_id)."))";
				$db->setQuery($query);
				$db->query();
			}
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
			$db->setQuery($query);
			$db->query();
			$raid_id = $db->insertid();
		}

		$location = JRequest::getVar('location', null, 'default', 'STRING');
		$description = JRequest::getVar('description', null, 'default', 'STRING');
		$start_time =& JFactory::getDate( implode(" ", JRequest::getVar('start_time', null, 'default', 'ARRAY') ) , $tz );
		$duration_mins = JRequest::getVar('duration_mins', 0, 'default', 'INT');
		$invite_time =& JFactory::getDate( implode(" ", JRequest::getVar('invite_time', null, 'default', 'ARRAY') ) , $tz );
		$freeze_time = JRequest::getVar('freeze_time', null, 'default', 'INT');
		$minimum_level = JRequest::getVar('minimum_level', null, 'default', 'INT');
		$maximum_level = JRequest::getVar('maximum_level', null, 'default', 'INT');
		$minimum_rank = JRequest::getVar('minimum_rank', null, 'default', 'INT');
		$icon_name = JRequest::getVar('icon_name', null, 'default', 'STRING');

		// update the record
		$query = "UPDATE #__raidplanner_raid SET"
				. " location=".$db->Quote($location)
				. ",description=".$db->Quote($description)
				. ",raid_leader=".$db->Quote($user->name)
				. ",invite_time='".$invite_time->toMySQL()."'"
				. ",start_time='".$start_time->toMySQL()."'"
				. ",duration_mins=".intval($duration_mins)
				. ",freeze_time=".intval($freeze_time)
				. ",profile_id=".intval($user_id)
				. ",icon_name=".$db->Quote($icon_name)
				. ",minimum_level=".( ($minimum_level=='')?"NULL":intval($minimum_level) )
				. ",maximum_level=".( ($maximum_level=='')?"NULL":intval($maximum_level) )
				. ",minimum_rank=".( ($minimum_rank=='')?"NULL":intval($minimum_rank) )
				. " WHERE raid_id=".intval($raid_id);

		$db->setQuery($query);
		$db->query();
		
		return $raid_id;
	}
	
	function deleteEvent() {
		$raid_id = JRequest::getVar('raid_id', null, 'INT');
		if (!$this->canDelete($raid_id)) {
			return false;
		}

		$db = & JFactory::getDBO();

		// delete the record
		$query = "DELETE FROM #__raidplanner_raid WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$db->query();

		$query = "DELETE FROM #__raidplanner_signups WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$db->query();
		
		return true;

	}

}
