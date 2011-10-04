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

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

class ComRaidPlannerHelper
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
				$url .= urlencode( $tmp->guild_realm ) . "/";
				$url .= urlencode( $tmp->guild_name );
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
					'link'		=> "http://" . $tmp->guild_region . ".battle.net/wow/guild/" . urlencode( $tmp->guild_realm ) . "/" . urlencode($data->name) ."/",
					'char_link'	=> "http://" . $tmp->guild_region . ".battle.net/wow/character/" . urlencode( $tmp->guild_realm ) . "/%s/advanced",
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
}