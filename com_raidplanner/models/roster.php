<?php
/*------------------------------------------------------------------------
# Roster Model for RaidPlanner Component
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

class RaidPlannerModelRoster extends JModel
{
	function getCharacters()
	{
		$this->armorySync();
		
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__raidplanner_character AS chars
					LEFT JOIN #__raidplanner_class AS class ON class.class_id = chars.class_id
					LEFT JOIN #__raidplanner_race AS race ON race.race_id = chars.race_id
					LEFT JOIN #__raidplanner_gender AS gender ON gender.gender_id = chars.gender_id
					ORDER BY chars.rank DESC, chars.char_level DESC, chars.char_name ASC";
			
		$db->setQuery($query);

		return ( $db->loadAssocList('character_id') );
	}

	public function getGuildInfo()
	{
		$db = & JFactory::getDBO();
		$query = "SELECT * FROM #__raidplanner_guild ORDER BY guild_id ASC LIMIT 1";
		$db->setQuery($query);
		$tmp = $db->loadObject();
		$tmp->params = json_decode($tmp->params);
		return ( $tmp );
	}

	private function armorySync()
	{
		$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
		if ($paramsObj->get('armory_sync', '1'))
		{
			$db = & JFactory::getDBO();
			$query = "SELECT guild_id, (DATE_ADD(lastSync, INTERVAL ".intval( $paramsObj->get('sync_interval',4) )." HOUR)-NOW()) AS needSync FROM #__raidplanner_guild WHERE guild_name=".$db->Quote( $paramsObj->get('armory_guild', '') )." ORDER BY guild_id ASC LIMIT 1"; 
			$db->setQuery($query);
			$tmp = $db->loadObject();
			$guild_id = $tmp->guild_id;
			$needsync = $tmp->needSync;

			if ( ( !$guild_id ) || ( $needsync<=0 ) )
			{
				$url = "http://".$paramsObj->get('armory_region', 'eu').".battle.net/api/wow/guild/";
				$url .= urlencode( $paramsObj->get('armory_realm', '') ) . "/";
				$url .= urlencode( $paramsObj->get('armory_guild', '') );
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
				if (json_last_error() != JSON_ERROR_NONE)
				{
					return null;
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
					'link'		=> "http://" . $paramsObj->get('armory_region', 'eu') . ".battle.net/wow/guild/" . urlencode( $paramsObj->get('armory_realm', '') ) . "/" . urlencode($data->name) ."/",
					'char_link'	=> "http://" . $paramsObj->get('armory_region', 'eu') . ".battle.net/wow/character/" . urlencode( $paramsObj->get('armory_realm', '') ) . "/%s/advanced",
				);
				
				$query = "UPDATE #__raidplanner_guild SET
								guild_name=".$db->Quote($data->name).",
								guild_realm=".$db->Quote($data->realm).",
								guild_region=".$db->Quote($paramsObj->get('armory_region', 'eu')).",
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
}