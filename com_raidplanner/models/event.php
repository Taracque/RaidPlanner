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

class RaidPlannerModelEvent extends JModelLegacy
{

    /**
    * Gets the event specified event
    * @return array The array contains event
    */
    function getEvent($id, $asTemplate = false)
    {
    	$result = new stdClass;
    	if ($id>0) {
			$db = JFactory::getDBO();
	
			$query = "SELECT r.*,g.guild_name,DATE_ADD(r.start_time,INTERVAL r.duration_mins MINUTE) AS end_time,NOW()>DATE_ADD(r.start_time,INTERVAL r.duration_mins MINUTE) AS finished 
						FROM #__raidplanner_raid AS r
						LEFT JOIN #__raidplanner_guild AS g ON g.guild_id=r.guild_id
						WHERE r.raid_id=".intval($id);
			
			$db->setQuery($query);
			$result = $db->loadObject();
			if ($asTemplate) {
				$result->raid_id = intval( JRequest::getVar('raid_id') );
				$result->start_time = "0000-00-00" . substr( $result->start_time, 11 );
				$result->invite_time = "0000-00-00" . substr( $result->invite_time, 11 );
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
    * Get list of users which are on vacation
    * @return array user names
    */
    function usersOnVacation( $date )
    {
    	$onvacation = array();
		$db = JFactory::getDBO();

		/* quick and dirty query on users */
		/* FIXME, needs to be a Joomla way */
		$query = "SELECT id,name FROM #__users WHERE params LIKE '%\"vacation\":\"2%' ORDER BY id ASC";

		$db->setQuery($query);
		$results = $db->loadObjectList();
		foreach ($results as $result) {
			$user = JUser::getInstance( $result->id );
			$vac = $user->getParam('vacation', '');
			$vacs = explode("\n", $vac);
			foreach ($vacs as $vac)
			{
				$vac_period = explode(" ", $vac);
				if ((@$vac_period[0]<= $date) && (@$vac_period[1] >= $date)) {
					$onvacation[] = $user->name;
					break;
				}
			}
		}

    	return $onvacation;
    
    }

    /**
    * Process, formats raid history XML
    * @return string HTML converted raid history
    */
    function getHistory($raid_id,$source = false)
    {
    	$html = '';

		$db = JFactory::getDBO();
		$query = "SELECT history FROM #__raidplanner_history WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$string = $db->loadResult();
		if ($source) {
			$html = $string;
		} elseif ($string!='') {
			$xml = json_decode($string);
			if (json_last_error()===JSON_ERROR_NONE) {
				$html .= '<style>th.table-th-sort{text-decoration:underline}</style>';
				$html .= '<table style="margin-bottom:0">';
				$html .= '<tr><th colspan="4">Roster</th></tr>';
				$html .= '</table>';
				$html .= '<table class="sortable">';
				$html .= '<thead>';
				$html .= '<tr><th>' . JText::_( 'COM_RAIDPLANNER_CHARACTER_NAME') . '</th><th>EP</th><th>GP</th><th>PR</th></tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
				foreach ($xml->roster as $char) {
					$html .= '<tr>';
					$html .= '<td>' . $char[0] . '</td><td>' . $char[1] . '</td><td>' . $char[2] . '</td><td>' . round($char[1]/$char[2],3) . '</td>';
					$html .= '</tr>';
				}
				$html .= '</tbody>';
				$html .= '</table>';
				$html .= '<table style="margin-bottom:0">';
				$html .= '<tr><th colspan="4">Loot</th></tr>';
				$html .= '</table>';
				$html .= '<table class="sortable">';
				$html .= '<thead>';
				$html .= '<tr><th>' . JText::_( 'COM_RAIDPLANNER_CHARACTER_NAME') . '</th><th>' .  JText::_( 'TIME') . '</td><th>Item</th><th>GP Price</th></tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
				foreach ($xml->loot as $loot) {
					$html .= '<tr>';
					// process item
					$item = explode(":",$loot[2]);
					
					$html .= '<td>' . $loot[1] . '</td><td>' . date('m/d H:i', $loot[0]) . '</td><td><a href="http://www.wowhead.com/item=' . $item[1] . '&bonus=' . $item[14] .':' . $item[15] .'" target="_blank" class="__rename_this" data-itemid="' .$item[1]. '">ITEM</a></td><td>' . $loot[3] . '</td>';
					$html .= '</tr>';
				}
				$html .= '</tbody>';
				$html .= '</table>';
				$html .= '<script>
	function itemNameCallback(itemData) {
		jQuery("table.sortable").each(function(idx,el){
			rpMakeSortable(el);
		});
		jQuery("a[data-itemid=" + itemData.id + "]").each(function(idx,el) {
			el = jQuery(el);
			if (el.hasClass("__rename_this")) {
				if (itemData.name) {
					el.text( itemData.name );
					el.removeClass("__rename_this");
				} else {
					if (itemData.availableContexts[0] != "") {
						jQuery.ajax({
							url: "http://eu.battle.net/api/wow/item/" + el.data("itemid") + "/" + itemData.availableContexts[0] + "?locale=en_GB&jsonp=itemNameCallback",
							dataType: "jsonp",
							success: function(response) {
							}
						})
					}
				}
			}
		});
	}
	jQuery("a.__rename_this").each(function(idx,el){
		el = jQuery(el);
		if (el.hasClass("__rename_this")) {
			jQuery.ajax({
			    url: "http://eu.battle.net/api/wow/item/" + el.data("itemid") + "?locale=en_GB&jsonp=itemNameCallback",
			    dataType: "jsonp",
			    success: function(response) {
			    }
			})
		}
	})
	</script>';
			} else {	// json not works, maybe XML
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
		}
		$html .= '';
		return ($html);
    }
    
    /**
    * Gets attendants for the specified event
    * @return array The array contains attendants
    */
    function getAttendants($id)
    {
    	$db = JFactory::getDBO();

    	$query = "SELECT s.character_id,c.char_name,c.guild_id,r.role_name,s.queue,s.confirmed,s.timestamp,s.comments,s.class_id,
    				cl.class_name,cl.class_color,c.char_level,c.profile_id,s.role_id,s.queue,cl.class_css
    			FROM #__raidplanner_signups AS s 
    				LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
    				LEFT JOIN #__raidplanner_class AS cl ON cl.class_id = c.class_id
					LEFT JOIN #__raidplanner_role AS r ON r.role_id=s.role_id
   				WHERE s.raid_id=".intval($id)."
   				ORDER BY s.confirmed DESC, s.queue DESC,r.role_name DESC";
    	
    	$db->setQuery($query);
    	$result = $db->loadObjectList();
		
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
		$db = JFactory::getDBO();
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

	function getMissingSignUps($raid_id = null)
	{
		$result = array();

		if ($raid_id>0) {
			$db = JFactory::getDBO();
			$query = "SELECT u.name FROM #__raidplanner_raid AS r
						LEFT JOIN #__user_usergroup_map AS p ON p.group_id = r.invited_group_id
						LEFT JOIN (#__raidplanner_signups AS s,#__raidplanner_character AS c) ON (s.raid_id = r.raid_id AND c.character_id = s.character_id AND c.profile_id = p.user_id)
						LEFT JOIN #__users AS u ON u.id=p.user_id
						WHERE r.raid_id = " . intval($raid_id) . "
						AND s.raid_id IS NULL";

			$db->setQuery($query);
			$result = $db->loadColumn();
			return $result;
		}
		return false;
	}

    function getTemplates()
    {
    	$db = JFactory::getDBO();
    	$query = "SELECT raid_id,location FROM #__raidplanner_raid WHERE is_template = 1 ORDER BY location ASC";
    	
    	$db->setQuery($query);
    	$result = $db->loadObjectList();

    	return $result;
	}

	/**
	* Gets the list of user's characters
	*/
	function getCharacters($min_level = null, $max_level = null, $min_rank = null, $guild_id = null, $everyone = false) {
		$db = JFactory::getDBO();
		$user =JFactory::getUser();
		
		if (!$everyone) {
			$where = " WHERE (c.profile_id=" . intval($user->id) . " OR c.profile_id = 0)";
		} else {
			$where = " WHERE 1=1";
		}
		if (($min_level != null) && (intval($min_level) > 0)) { $where .= " AND c.char_level>=".intval($min_level); }
		if (($max_level != null) && (intval($max_level) > 0)) { $where .= " AND c.char_level<=".intval($max_level); }
		if (($min_rank != null) && (intval($min_rank) > 0)) { $where .= " AND c.rank<=".intval($min_rank); }
		if (($guild_id != null) && (intval($guild_id) > 0)) { $where .= " AND c.guild_id=".intval($guild_id); }
		$query = "SELECT c.character_id,c.char_name,c.profile_id
					FROM #__raidplanner_character AS c 
					" . $where . " ORDER BY c.char_name ASC";
		// reload the list
		$db->setQuery($query);
		$result = $db->loadObjectList( 'character_id' );

		$charlist = array();
		$charset = RaidPlannerHelper::getProfileChars( $user->getParam('characters') , true );
		
    	// reorder if set in characters parameters
    	foreach ($charset as $userchar) {
    		/* If character id  matches add this character */
    		if ( (isset($result[$userchar['char_id']])) && ($result[$userchar['char_id']]->character_id == $userchar['char_id']) ) {
				$charlist[$userchar['char_id']] = $result[$userchar['char_id']];
				/* Write it back to the database, if needed */
				if ( $result[$userchar['char_id']]->profile_id == 0 )	/* no profile attached to it */
				{
					$query = "UPDATE #__raidplanner_character SET profile_id = " . intval($user->id) . " WHERE character_id = " . intval($result[$userchar['char_id']]->character_id);
					$db->setQuery($query);
					$db->query();
				}

				unset( $result[ $userchar['char_id'] ]);
			}
    	}
		if (is_array($result))
		{
			foreach ($result as $charname => $charvalue)
			{
				if ( ( $charvalue->profile_id != 0 ) || ( $everyone ) )
				{
					$charlist[$charname] = $charvalue;
				}
			}
		}

    	return $charlist;
	}

	function getUserStatus($event_id,$user_id = null)
	{
		if (!$user_id) {
			$user =JFactory::getUser();
			$user_id = $user->id;
		}
		
    	$db = JFactory::getDBO();
		$query = "SELECT s.character_id,s.role_id,s.queue,s.confirmed,s.comments
    			FROM #__raidplanner_signups AS s 
    			LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id 
   				WHERE s.raid_id = " . intval($event_id) . "
   				AND c.profile_id = " . intval($user_id) . "";

    	$db->setQuery($query);
    	if ( $result = $db->loadObject() ) {
			if ( $result->character_id ) {
				return $result;
			} else {
				$status->character_id = null;
				$status->role_id = null;
				$status->queue = null;
				$status->confirmed = null;
				$status->comments = null;
			
				return $status;
			}
		} else {
			return false;
		}
	}

	/**
	* Get Month of an event
	*/
	
	function getMonth( $event_id = null )
	{
		$raid_id = JRequest::getVar('raid_id', $event_id, 'INT');
		if ($raid_id)
		{
			$event = $this->getEvent($raid_id);
			
			return substr($event->start_time,0,7);
			
		} else {
			return date("Y-m");
		}
	}

	/**
	*
	*/
	function getUpcomingEvents( $date, $invited_only = false )
	{
    	$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$query = "SELECT r.raid_id,r.location,r.description,r.icon_name,r.status,r.raid_leader,r.start_time,s.queue
					FROM #__raidplanner_raid AS r
					LEFT JOIN (#__raidplanner_signups AS s,#__raidplanner_character AS c) ON (s.raid_id=r.raid_id AND c.character_id=s.character_id AND c.profile_id=" . intval($user->id) . ")";
		if ($invited_only) {
			$query.="	LEFT JOIN `#__user_usergroup_map` AS p ON p.group_id = r.invited_group_id AND p.user_id = " .intval($user->id). "";
			$query.="	WHERE r.start_time>='" . $date . "' AND p.user_id IS NOT NULL";
		} else {
			$query.="	WHERE r.start_time>='" . $date . "'";
		}
		$query.="	GROUP BY raid_id
					ORDER BY r.start_time ASC, r.location ASC";
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	/**
	* Save ratings for an event
	*/
	function rateEvent() {
		$raid_id = JRequest::getVar('raid_id', null, 'INT');
		if ($this->userCanRate($raid_id)) {
			$rates = JRequest::getVar('character_vote', null, 'post', 'ARRAY');
			$db = JFactory::getDBO();
			$user = JFactory::getUser();
			$user_id = $user->id;

			foreach ($rates as $char_id => $rate) {
				$query = "SELECT queue FROM #__raidplanner_signups WHERE raid_id = " . $raid_id . " AND character_id=" . $char_id;
				$db->setQuery( $query );
				if ( ( $queue = $db->loadResult() ) && ( $queue > 0) ) {
					$query = "SELECT  rating_id, rated_by FROM #__raidplanner_rating WHERE raid_id = " . $raid_id . " AND character_id=" . $char_id;
					$db->setQuery( $query );
					if ( $result = $db->loadObject() ) {
						$ratedby = json_decode( $result->rated_by );
						$ratedby[] = (int) $user_id;
					
						$query = "UPDATE #__raidplanner_rating SET rate_count=rate_count+1, rate_value=rate_value + " . intval( $rate ) . ",rated_by = ) " .
								"VALUES (" . intval($raid_id) . ", " . intval($char_id) . ", 1, " . intval($rate) . ", '" . json_encode( $ratedby ) . "')";
					} else {
						$query = "INSERT INTO #__raidplanner_rating (raid_id,character_id,rate_count,rate_value,rated_by) " .
								"VALUES (" . intval($raid_id) . ", " . intval($char_id) . ", 1, " . intval($rate) . ", '" . json_encode( array( (int) $user_id ) ) . "')";
					}
					$db->setQuery( $query );
					$db->query();
				}
			}
		}
	}

	/**
	* Get ratings for an event
	*/
	function getRates( $raid_id ) {
		$rates = array();
		$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
		if ($paramsObj->get('allow_rating', 0) == 1) {
			$db = JFactory::getDBO();
			$query = "SELECT character_id,(rate_value/rate_count) AS rating FROM #__raidplanner_rating WHERE raid_id = " . intval($raid_id);
			$db->setQuery( $query );
			$rates = $db->loadObjectList( 'character_id' );
		}
		return $rates;
	}

	/**
	* Signup for an event
	*/
	function signupEvent() {
		$raid_id = JRequest::getVar('raid_id', null, 'post', 'INT');
		if ($this->userCanSignUp($raid_id)) {
			$role = JRequest::getVar('role', null, 'post', 'INT');
			$queue = JRequest::getVar('queue', null, 'post', 'INT');
			$comments = JRequest::getVar('comments', null, 'post', 'STRING');
			$char_id = JRequest::getVar('character_id', null, 'post', 'INT');

			$db = JFactory::getDBO();
			$user =JFactory::getUser();

			// throw all sigunps by same profile for same raid
			$query = "DELETE #__raidplanner_signups FROM #__raidplanner_signups LEFT JOIN #__raidplanner_character AS c ON c.character_id=#__raidplanner_signups.character_id WHERE c.profile_id=".intval($user->id)." AND #__raidplanner_signups.raid_id=".$raid_id;
			$db->setQuery($query);
			$db->query();
			
			$query="INSERT INTO #__raidplanner_signups (raid_id,character_id,queue,role_id,comments,`timestamp`,class_id) ".
					"VALUES (".intval($raid_id).",".intval($char_id).",".intval($queue).",".intval($role).",".$db->Quote($comments).",'".RaidPlannerHelper::getDate('now', null, 'sql')."',(SELECT class_id FROM #__raidplanner_character WHERE character_id = ".intval($char_id)."))";
			$db->setQuery($query);
			$db->query();

			/* signup for other raids if available */
			$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
			if ($paramsObj->get('multi_raid_signup', 0) == 1) {
				$signup_raid = JRequest::getVar('signup_raid', null, 'post', 'ARRAY');
				foreach ($signup_raid as $raid_id => $signup) {
					if ( ( $signup == 1 ) && ( $this->userCanSignUp( $raid_id ) ) ) {
						// throw all sigunps by same profile for same raid
						$query = "DELETE #__raidplanner_signups FROM #__raidplanner_signups LEFT JOIN #__raidplanner_character AS c ON c.character_id=#__raidplanner_signups.character_id WHERE c.profile_id=".intval($user->id)." AND #__raidplanner_signups.raid_id=".$raid_id;
						$db->setQuery($query);
						$db->query();
			
						$query="INSERT INTO #__raidplanner_signups (raid_id,character_id,queue,role_id,comments,`timestamp`,class_id) ".
								"VALUES (".intval($raid_id).",".intval($char_id).",".intval($queue).",".intval($role).",".$db->Quote($comments).",'".RaidPlannerHelper::getDate('now', null, 'sql')."',(SELECT class_id FROM #__raidplanner_character WHERE character_id = ".intval($char_id)."))";
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}
	}
	
	/**
	* check if user is an officer
	*/
	function userIsOfficer($raid_id = null) {
		$own_raid = true;

		if ($raid_id>0) {
			$db = JFactory::getDBO();
			$user =JFactory::getUser();
			$user_id = $user->id;

			$query = "SELECT profile_id FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
			$db->setQuery($query);
			$own_raid = ($db->loadResult() == $user_id);
		}
		
		return (RaidPlannerHelper::getPermission('edit') || (RaidPlannerHelper::getPermission('edit.own') && ($own_raid)));
	}
	
	function canDelete($raid_id = null) {
		$own_raid = false;

		if ($raid_id>0) {
			$db = JFactory::getDBO();
			$user =JFactory::getUser();
			$user_id = $user->id;

			$query = "SELECT profile_id FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
			$db->setQuery($query);
			$own_raid = ($db->loadResult() == $user_id);
		}

		return (RaidPlannerHelper::getPermission('delete') || (RaidPlannerHelper::getPermission('delete.own') && ($own_raid)));
	}
	
	function userCanSignUp($raid_id = null) {
		$can_signup = false;
		if ($raid_id>0) {
			$db = JFactory::getDBO();
			$user =JFactory::getUser();
			$user_id = $user->id;
			$date = RaidPlannerHelper::getDate();
			$query = "SELECT DATE_SUB(start_time,interval freeze_time minute) > '" . RaidPlannerHelper::date2Sql( $date ) . "' FROM #__raidplanner_raid WHERE raid_id = ".intval($raid_id);
			$db->setQuery($query);
			if ($db->loadResult() == 1) {
				$can_signup = RaidPlannerHelper::getPermission('signup', $user_id);
			}
		}
		return $can_signup;
	}

	/*
		Returns true if user is allowed to rate the particular raid_history
		Rating is allowed if:
			 user has at least one character in the signup table
			 rating is allowed
			 and user didn't rated the raid before
	*/
	function userCanRate($raid_id = null) {
		$can_rate = false;
		if ($raid_id>0) {
			$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
			if ($paramsObj->get('allow_rating', 0) == 1) {
				$db = JFactory::getDBO();
				$user = JFactory::getUser();
				$user_id = $user->id;
				$query = "SELECT rt.rated_by
							FROM #__raidplanner_raid AS r
							LEFT JOIN #__raidplanner_rating AS rt ON rt.raid_id=r.raid_id AND rt.character_id=0
							WHERE r.raid_id = ".intval($raid_id);
				$db->setQuery($query);
				if ($profiles = $db->loadResult()) {
					$user_ids = json_decode($profiles);
					$can_rate = !in_array($user_id, $user_ids);
				} else {	// $profiles is empty, no rates yet…
					$can_rate = true;
				}
				if ($can_rate) {
					$query = "SELECT queue
								FROM #__raidplanner_signups AS s
								LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
								WHERE s.raid_id = ".intval($raid_id)." AND c.profile_id = " . $user_id;
					$db->setQuery($query);
					$queue = $db->loadResult();
					if ( $queue <= 0) {
						$can_rate = false;
					}
				}
			}
		}
		return $can_rate;
	}

	function confirmEvent() {
		if (!$this->userIsOfficer()) {
			return false;
		}

		$db = JFactory::getDBO();

		$raid_id = JRequest::getVar('raid_id', null, 'post', 'INT');
		$roles = JRequest::getVar('role', null, 'post', 'ARRAY');
		$comments = JRequest::getVar('comments', null, 'post', 'ARRAY');
		$confirm = JRequest::getVar('confirm', null, 'post', 'ARRAY');
		$characters = JRequest::getVar('characters', null, 'post', 'ARRAY');
		$history = trim( JRequest::getVar('history', '', 'post', 'string', JREQUEST_ALLOWRAW ) );
		$queues = JRequest::getVar('queue', null, 'post', 'ARRAY');

		if (is_array($characters))
		{
			foreach ($characters as $char) {
				if (intval(@$roles[intval($char)])==0) {
					$query = "DELETE FROM #__raidplanner_signups WHERE raid_id=".intval($raid_id)." AND character_id=".intval($char);
				} else {
					$query = "UPDATE #__raidplanner_signups SET role_id='".intval(@$roles[intval($char)])."',confirmed='".intval(@$confirm[intval($char)])."',queue='".intval(@$queues[intval($char)])."' WHERE raid_id=".intval($raid_id)." AND character_id=".intval($char);
				}
				$db->setQuery($query);
				$db->query();
			}
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

			$new_queue = JRequest::getVar('new_queue', null, 'INT');
			$new_role = JRequest::getVar('new_role', null, 'INT');
			$new_confirm = JRequest::getVar('new_confirm', null, 'INT');

			if ($profile_id > 0)
			{
				$query = "DELETE #__raidplanner_signups FROM #__raidplanner_signups LEFT JOIN #__raidplanner_character AS c ON c.character_id=#__raidplanner_signups.character_id WHERE c.profile_id=".intval($profile_id)." AND #__raidplanner_signups.raid_id=".$raid_id;
				$db->setQuery($query);
				$db->query();
			}
			// remove the same character, before adding it once again
			$query = "DELETE FROM #__raidplanner_signups WHERE character_id=".intval($new_char_id)." AND raid_id=".$raid_id;
			$db->setQuery($query);
			$db->query();
				
			$query="INSERT INTO #__raidplanner_signups (raid_id,character_id,queue,role_id,confirmed,comments,`timestamp`,class_id) ".
					"VALUES (".intval($raid_id).",".intval($new_char_id).",".intval($new_queue).",".intval($new_role).",".intval($new_confirm).",'','".RaidPlannerHelper::getDate('now', null, 'sql')."',(SELECT class_id FROM #__raidplanner_character WHERE character_id = ".intval($new_char_id)."))";
			$db->setQuery($query);
			$db->query();
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

		$user =JFactory::getUser();
		$user_id = $user->id;
		$db = JFactory::getDBO();
		if ($raid_id == -1) {
			// insert an empty record first
			$query = "INSERT INTO #__raidplanner_raid (profile_id) VALUES (".$user_id.")";
			$db->setQuery($query);
			$db->query();
			$raid_id = $db->insertid();
		}

		$location = JRequest::getVar('location', null, 'default', 'STRING');
		$description = JRequest::getVar('description', null, 'default', 'STRING');
		$start_time = RaidPlannerHelper::getDate( implode(" ", JRequest::getVar('start_time', null, 'default', 'ARRAY') ) );
		$duration_mins = JRequest::getVar('duration_mins', 0, 'default', 'INT');
		$invite_time = RaidPlannerHelper::getDate( implode(" ", JRequest::getVar('invite_time', null, 'default', 'ARRAY') ) );
		$freeze_time = JRequest::getVar('freeze_time', null, 'default', 'INT');
		$minimum_level = JRequest::getVar('minimum_level', null, 'default', 'INT');
		$maximum_level = JRequest::getVar('maximum_level', null, 'default', 'INT');
		$minimum_rank = JRequest::getVar('minimum_rank', null, 'default', 'INT');
		$icon_name = JRequest::getVar('icon_name', null, 'default', 'STRING');
		$guild_id = JRequest::getVar('guild_id', null, 'default', 'INT');
		$invited_group_id = JRequest::getVar('invited_group_id', null, 'default', 'INT');

		// update the record
		$query = "UPDATE #__raidplanner_raid SET"
				. " location=".$db->Quote($location)
				. ",description=".$db->Quote($description)
				. ",raid_leader=".$db->Quote($user->name)
				. ",invite_time='".RaidPlannerHelper::date2Sql( $invite_time )."'"
				. ",start_time='".RaidPlannerHelper::date2Sql( $start_time )."'"
				. ",duration_mins=".intval($duration_mins)
				. ",freeze_time=".intval($freeze_time)
				. ",profile_id=".intval($user_id)
				. ",icon_name=".$db->Quote($icon_name)
				. ",minimum_level=".( ($minimum_level=='')?"NULL":intval($minimum_level) )
				. ",maximum_level=".( ($maximum_level=='')?"NULL":intval($maximum_level) )
				. ",minimum_rank=".( ($minimum_rank=='')?"NULL":intval($minimum_rank) )
				. ",guild_id=".( ($guild_id=='')?"NULL":intval($guild_id) )
				. ",invited_group_id=".( ($invited_group_id=='')?"NULL":intval($invited_group_id) )
				. " WHERE raid_id=".intval($raid_id);

		$db->setQuery($query);
		$db->query();

		$query = "UPDATE #__raidplanner_raid SET"
				. " raid_leader=".$db->Quote($user->name)
				. " WHERE raid_id=".intval($raid_id)
				. " AND raid_leader=''";

		$db->setQuery($query);
		$db->query();
		
		return $raid_id;
	}
	
	function deleteEvent() {
		$raid_id = JRequest::getVar('raid_id', null, 'INT');
		if (!$this->canDelete($raid_id)) {
			return false;
		}

		$db = JFactory::getDBO();

		// delete the record
		$query = "DELETE FROM #__raidplanner_raid WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$db->query();

		$query = "DELETE FROM #__raidplanner_signups WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$db->query();

		$query = "DELETE FROM #__raidplanner_rating WHERE raid_id=".intval($raid_id);
		$db->setQuery($query);
		$db->query();

		return true;

	}

}
