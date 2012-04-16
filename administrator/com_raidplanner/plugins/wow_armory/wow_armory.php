<?php
/*------------------------------------------------------------------------
# WoW Armory Sync Plugin
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class wow_armory
{

	public function Sync( $guild_data , $sync_interval , $showOkStatus = false )
	{
		$db = & JFactory::getDBO();

		$guild_id = $guild_data->guild_id;
		
		$region = $guild_data->params['guild_region'];
		$realm = $guild_data->params['guild_realm'];

		$url = "http://" . $region . ".battle.net/api/wow/guild/";
		$url .= rawurlencode( $realm ) . "/";
		$url .= rawurlencode( $guild_data->guild_name );
		$url = $url . "?fields=members";

		// Init cURL
		$ch = curl_init();

		// Language
		$header[] = 'Accept-Language: en_EN';
		// Browser
		$browser = 'Mozilla/5.0 (compatible; MSIE 7.01; Windows NT 5.1)';
		
		// cURL options
		curl_setopt ($ch, CURLOPT_URL, $url );
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
				JError::raiseWarning('100','ArmorySync data decoding error');
				return null;
			}
		}
		if (isset($data->status) && ($data->status=="nok"))
		{
			JError::raiseWarning('100','ArmorySync failed');
			return null;
		}
		if (!$guild_id)
		{
			$query = "INSERT INTO #__raidplanner_guild (guild_name) VALUES (".$db->Quote($data->name).")";
			$db->setQuery($query);
			$db->query();
			$guild_id=$db->insertid();
		}

		if (($guild_data->guild_name == @$data->name) && ($data->name!=''))
		{
			$params = array(
				'achievementPoints' => $data->achievementPoints,
				'side'		=> ($data->side==0)?"Alliance":"Horde",
				'emblem'	=> $data->emblem,
				'link'		=> "http://" . $region . ".battle.net/wow/guild/" . rawurlencode($realm) . "/" . rawurlencode($data->name) ."/",
				'char_link'	=> "http://" . $region . ".battle.net/wow/character/%s/%s/advanced",
				'guild_realm'	=>	$data->realm,
				'guild_region'	=>	$region,
				'guild_level'	=>	$data->level
			);

			$params = array_merge( $guild_data->params, $params );
			
			$query = "UPDATE #__raidplanner_guild SET
							guild_name=".$db->Quote($data->name).",
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
				$query = "SELECT character_id FROM #__raidplanner_character WHERE char_name LIKE BINARY ".$db->Quote($member->character->name)."";
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
			
			if ($showOkStatus)
			{
				JError::raiseNotice('0', 'ArmorySync successed');
			}
		} else {
			JError::raiseWarning('100', 'ArmorySync data doesn\'t match');
		}
	}
}