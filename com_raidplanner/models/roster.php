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

	private function armorySync()
	{
		$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
		if ($paramsObj->get('armory_sync', '1'))
		{
			if ( (intval($paramsObj->get('last_sync',''))) < ( time() - ( (intval($paramsObj->get('sync_interval','4'))) * 60 * 60)) )
			{
				$url = $paramsObj->get('armory_region', 'eu').".battle.net/api/wow/guild/";
				$url .= $paramsObj->get('armory_realm', '') . "/";
				$url .= $paramsObj->get('armory_guild', '');
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
				
				// Throttle requests and try avoid Armory tmp bans.
				sleep(1);
				
				$url_string = curl_exec($ch);
				curl_close($ch);
	
				$db = & JFactory::getDBO();
	
				$data = json_decode($url_string);
				$data = json_decode($url_string);
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
																,gender_id='".intval($member->character->gender)."'
																,char_level='".intval($member->character->level)."'
																WHERE character_id=".$char_id;
					$db->setQuery($query);
					$db->query();
				}
				$paramsObj->set('last_sync',time());
				echo "ARMORY SYNCED (".$url.")";
			}
		}
	}
}