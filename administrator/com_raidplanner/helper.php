<?php
/*------------------------------------------------------------------------
# Helper for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

jimport( 'joomla.error.error' );

abstract class RaidPlannerHelper
{

	public static function armorySync( $guild_id , $sync_interval )
	{
		$db = & JFactory::getDBO();
		$query = "SELECT *,(DATE_ADD(lastSync, INTERVAL " . intval( $sync_interval ) . " HOUR)-NOW()) AS needSync FROM #__raidplanner_guild WHERE guild_id=" . intval($guild_id); 
		$db->setQuery($query);
		if ($tmp = $db->loadObject())
		{
			$guild_id = $tmp->guild_id;
			$needsync = $tmp->needSync;
	
			if ( ( !$guild_id ) || ( $needsync<=0 ) )
			{
				$url = "http://".$tmp->guild_region.".battle.net/api/wow/guild/";
				$url .= rawurlencode( $tmp->guild_realm ) . "/";
				$url .= rawurlencode( $tmp->guild_name );
				$url = $url . "?fields=members";
	
				// Init cURL
				$ch = curl_init();
	
				// Language
				$header[] = 'Accept-Language: en_EN';
				// Browser
				$browser = 'Mozilla/5.0 (compatible; MSIE 7.01; Windows NT 5.1)';
				
				// cURL options
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_HTTPHEADER, $header); 
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
				curl_setopt ($ch, CURLOPT_USERAGENT, $browser);
				
				$url_string = curl_exec($ch);
				curl_close($ch);
	
	
				$data = json_decode($url_string);
				if (function_exists('json_last_error')) {
					if (json_last_error() != JSON_ERROR_NONE)
					{
						return null;
					}
				}
	
				if (!$guild_id)
				{
					$query = "INSERT INTO #__raidplanner_guild (guild_name) VALUES (".$db->Quote($data->name).")";
					$db->setQuery($query);
					$db->query();
					$guild_id=$db->insertid();
				}
				$params = array(
					'achievementPoints' => $data->achievementPoints,
					'side'		=> ($data->side==0)?"Alliance":"Horde",
					'emblem'	=> $data->emblem,
					'link'		=> "http://" . $tmp->guild_region . ".battle.net/wow/guild/" . $tmp->guild_realm . "/" . $data->name ."/",
					'char_link'	=> "http://" . $tmp->guild_region . ".battle.net/wow/character/" . $tmp->guild_realm . "/%s/advanced",
				);
				
				$query = "UPDATE #__raidplanner_guild SET
								guild_name=".$db->Quote($data->name).",
								guild_realm=".$db->Quote($data->realm).",
								guild_region=".$db->Quote($tmp->guild_region).",
								guild_level=".$db->Quote($data->level).",
								params=".$db->Quote(json_encode($params)).",
								lastSync=NOW()
								WHERE guild_id=".intval($guild_id);
				$db->setQuery($query);
				$db->query();
	
				/* detach characters from guild */
				$query = "UPDATE #__raidplanner_character SET guild_id=0 WHERE guild_id=".intval($guild_id)."";
				$db->setQuery($query);
				$db->query();
	
				foreach($data->members as $member)
				{
					// check if character exists
					$query = "SELECT character_id FROM #__raidplanner_character WHERE char_name=".$db->Quote($member->character->name)."";
					$db->setQuery($query);
					$char_id = $db->loadResult();
					// not found insert it
					if (!$char_id) {
						$query="INSERT INTO #__raidplanner_character SET char_name=".$db->Quote($member->character->name)."";
						$db->setQuery($query);
						$db->query();
						$char_id=$db->insertid();
					}
					$query = "UPDATE #__raidplanner_character SET class_id='".intval($member->character->class)."'
																,race_id='".intval($member->character->race)."'
																,gender_id='".(intval($member->character->gender) + 1)."'
																,char_level='".intval($member->character->level)."'
																,rank='".intval($member->rank)."'
																,guild_id='".intval($guild_id)."'
																WHERE character_id=".$char_id;
					$db->setQuery($query);
					$db->query();
				}
	
				/* delete all guildless characters */
				$query = "DELETE FROM #__raidplanner_character WHERE guild_id=0";
				$db->setQuery($query);
				$db->query();
	
			}
		}
	}
	
	public static function showToolbarButtons()
	{
		$view = JRequest::getVar('view');

		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RAIDS'), 'index.php?option=com_raidplanner&view=raids', ($view == 'raids'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_GUILDS'), 'index.php?option=com_raidplanner&view=guilds', ($view == 'guilds'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CHARACTERS'), 'index.php?option=com_raidplanner&view=characters', ($view == 'characters'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_GROUPS'), 'index.php?option=com_raidplanner&view=groups', ($view == 'groups'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_ROLES'), 'index.php?option=com_raidplanner&view=roles', ($view == 'roles'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CLASSES'), 'index.php?option=com_raidplanner&view=classes', ($view == 'classes'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RACES'), 'index.php?option=com_raidplanner&view=races', ($view == 'races'));
	}
	
	public static function getTimezone( $user = null )
	{
		$user =& JFactory::getUser( $user );
		$tz = $user->getParam('timezone', JFactory::getConfig()->getValue('config.offset'));
		
		return $tz;
	}

	public static function getGuilds()
	{
		$db = & JFactory::getDBO();
		$query = ' SELECT guild_name,guild_id FROM #__raidplanner_guild ORDER BY guild_name ASC ';
		$db->setQuery( $query );
		return $db->loadObjectList('guild_id');
	}
	
	public static function getClasses()
	{
		$db = & JFactory::getDBO();
		$query = ' SELECT class_name,class_id,class_color FROM #__raidplanner_class ORDER BY class_name ASC ';
		$db->setQuery( $query );
		return $db->loadObjectList('class_id');
	}
	
	public static function getGenders()
	{
		$db = & JFactory::getDBO();
		$query = ' SELECT gender_name,gender_id FROM #__raidplanner_gender';
		$db->setQuery( $query );
		return $db->loadObjectList('gender_id');
	}

	public static function getRaces()
	{
		$db = & JFactory::getDBO();
		$query = ' SELECT race_name,race_id FROM #__raidplanner_race';
		$db->setQuery( $query );
		return $db->loadObjectList('race_id');
	}

	function getUsers()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT id,username FROM #__users ORDER BY username ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('id');
	}

	function getGroups()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT group_id,group_name FROM #__raidplanner_groups ORDER BY group_name ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('group_id');
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
			$reply = (@$dbreply[0] === "1");
		}
		return $reply;
	}
	
	function getDate( $date = 'now', $tzOffset = null )
	{
		if ($tzOffset === null)
		{
			$tzOffset = self::getTimezone();
		}
		try {
			$reply =& JFactory::getDate( $date, $tzOffset );
		} catch (Exception $e) {
			JError::raiseNotice( 500, 'Invalid date (' . $date .') entered' );
			$reply =& JFactory::getDate();
		}
		
		return $reply;
	}

}