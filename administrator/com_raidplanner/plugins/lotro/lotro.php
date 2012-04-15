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

class lotro
{

	public function Sync( $guild_data , $sync_interval , $showOkStatus = false )
	{
		$db = & JFactory::getDBO();

		$guild_id = $guild_data->guild_id;
		
		$developer = $guild_data->params['developer_name'];
		$api_key = $guild_data->params['lotro_api_key'];
		$world_name = $guild_data->params['world_name'];

		$url = "http://data.lotro.com/" . $developer . "/" . $api_key ."/guildroster/w/";
		$url .= rawurlencode( $world_name ) . "/g/";
		$url .= rawurlencode( $guild_data->guild_name );

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

		$xml_parser =& JFactory::getXMLParser( 'simple' );
		if (( !$xml_parser->loadString( $url_string ) ) || (!$xml_parser->document) ) {
			if (json_last_error() != JSON_ERROR_NONE)
			{
				JError::raiseWarning('100','LotroSync data decoding error');
				return null;
			}
		}
		if ($xml_parser->document->name() != 'apiresponse')
		{
			JError::raiseWarning('100','LotroSync failed');
			return null;
		}
		if (property_exists($xml_parser->document,'error')) {
			JError::raiseWarning('100', $xml_parser->document->error[0]->attributes('message') );
			return null;
		}
		if (!$guild_id)
		{
			$query = "INSERT INTO #__raidplanner_guild (guild_name) VALUES (".$db->Quote($data->name).")";
			$db->setQuery($query);
			$db->query();
			$guild_id=$db->insertid();
		}

		if (($guild_data->guild_name == $xml_parser->document->guild[0]->attributes('name')) && ($xml_parser->document->guild[0]->attributes('name')!=''))
		{
			$params = array(
				'world_name'	=>	$xml_parser->document->guild[0]->attributes('world'),
			);

			$params = array_merge( $guild_data->params, $params );
			
			$query = "UPDATE #__raidplanner_guild SET
							guild_name=".$db->Quote($xml_parser->document->guild[0]->attributes('name')).",
							params=".$db->Quote(json_encode($params)).",
							lastSync=NOW()
							WHERE guild_id=".intval($guild_id);
			$db->setQuery($query);
			$db->query();

			/* detach characters from guild */
			$query = "UPDATE #__raidplanner_character SET guild_id=0 WHERE guild_id=".intval($guild_id)."";
			$db->setQuery($query);
			$db->query();
			
			/* LOTRO api response looks like this:
			
			<apiresponse>
				<guild name="The crafting union" world="Landroval" theme="Mixed Kinship Theme" memberCount="148">
      				<characters>
      					<character name="Drahc" level="13" class="Champion" race="Dwarf" rank="Recruit"/>
      				</characters>
      			</guild>
      		</apiresponse>
      		
      		Class, race, and ranks needs to be loaded into a table first
      		*/
			
			$query = "SELECT class_name,class_id FROM #__raidplanner_class";
			$db->setQuery($query);
			$classes = $db->loadAssocList('class_name');
			
			$query = "SELECT race_name,race_id FROM #__raidplanner_race";
			$db->setQuery($query);
			$races = $db->loadAssocList('race_name');
			$ranks = RaidPlannerHelper::getRanks( true );

			foreach($xml_parser->document->guild[0]->characters[0]->character as $member)
			{
				// check if character exists
				$query = "SELECT character_id FROM #__raidplanner_character WHERE char_name LIKE BINARY ".$db->Quote($member->attributes('name'))."";
				$db->setQuery($query);
				$char_id = $db->loadResult();
				// not found insert it
				if (!$char_id) {
					$query="INSERT INTO #__raidplanner_character SET char_name=".$db->Quote($member->attributes('name'))."";
					$db->setQuery($query);
					$db->query();
					$char_id=$db->insertid();
				}
				
				$query = "UPDATE #__raidplanner_character SET class_id='".intval($classes[ $member->attributes('class') ])."'
															,race_id='".intval($races[ $member->attributes('race') ])."'
															,char_level='".intval($member->attributes('level'))."'
															,rank='".intval($ranks[$member->attributes('rank')])."'
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
				JError::raiseNotice('0', 'LotroSync successed');
			}
		} else {
			JError::raiseWarning('100', 'LotroSync data doesn\'t match');
		}
	}
}