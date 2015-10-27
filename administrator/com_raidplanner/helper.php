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

require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );

jimport( 'joomla.error.error' );
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

class RaidPlannerHelper
{
	private static $invite_alert_requested = false;
	private static $use_joomla_acl = false;
	private static $jversion = null;
	private static $acl_map = array(
		'raidplanner.edit_raids_own'	=>	'raidplanner.edit.own',
		'raidplanner.edit_raids_any'	=>	'raidplanner.edit',
		'raidplanner.delete_raid_any'	=>	'raidplanner.delete',
		'raidplanner.delete_raid_own'	=>	'raidplanner.delete.own',
	);
	private static $rp_plugin = array();

	/* Fixes bootstrap css elements in Joomla 2.5 */
	public static function fixBootstrap( $direct_write = false )
	{
		if (self::getJVersion() < '3.0')
		{
			$style = '
						div.btn-group.pull-left { float: left; }
						div.btn-group.pull-right { float: right; }
						div.btn-group div { float: left;}
						div.btn-group label { float: left; margin: 0 10px; }
						a.btn { display: inline-block; cursor: pointer; padding: 4px 12px; border-width: 1px; border-style: solid; border-color: #e6e6e6 #e6e6e6 #bfbfbf; box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); background: #fff; text-decoration: none; }
						a.btn:hover { background: #fff; border-color: #E8F6FE; }
						div.input-append { float: left; }
						span.add-on { float: left; display: inline-block; margin: 5px 5px 5px 0; }
						div.btn-group input, div.btn-group select, div.btn-group img, div.btn-group label { float: left; }
						div.controls { display:inline-block; }
						fieldset.radio.btn-group { margin: 8px 0; padding: 0; }
						fieldset.radio.btn-group input[type=radio] { display:none; margin:10px; }
						fieldset.radio.btn-group input[type=radio] + label { display:inline-block; margin:-2px; padding: 1px 8px; background-color: #e7e7e7; border: 1px solid #bbb; }
						fieldset.radio.btn-group label i { font-style: normal; font-weight:bold; }
						fieldset.radio.btn-group label i.icon-minus:after { content: "-"; }
						fieldset.radio.btn-group label i.icon-question:after { content: "?"; }
						fieldset.radio.btn-group label i.icon-plus:after { content: "+"; }
						';
			if (!$direct_write) {
				JFactory::getDocument()->addStyleDeclaration( $style );
			} else {
				echo '<style type="text/css">' . $style . '</style>' . "\n";
			}
			/* script not added in direct write, as it will not run */
			if (!$direct_write) {
				$script = '
					window.addEvent("domready",function() {
						$$("i.icon-search").each( function(el) { el.getParent().set("html","' . JText::_( 'JSEARCH_FILTER_SUBMIT' ) . '"); } );
						$$("i.icon-remove").each( function(el) { el.getParent().set("html","' . JText::_( 'JSEARCH_FILTER_CLEAR' ) . '"); } );
						$$("i.icon-plus").each( function(el) { el.getParent().set("html","+"); } );
						$$("i.icon-question").each( function(el) { el.getParent().set("html","?"); } );
						$$("i.icon-minus").each( function(el) { el.getParent().set("html","-"); } );
						$$("i.icon-publish").each( function(el) { el.getParent().set("html","<span class=\"jgrid\"><span class=\"state publish\"></span></span>"); } );
						$$("i.icon-unpublish").each( function(el) { el.getParent().set("html","<span class=\"jgrid\"><span class=\"state unpublish\"></span></span>"); } );
					});
				';
				JFactory::getDocument()->addScriptDeclaration( $script );
			}
		}
	}

	public static function getGuildPlugin( $guild_id )
	{
		if ((array_key_exists($guild_id, self::$rp_plugin)) && (self::$rp_plugin[ $guild_id ]->guild_id == $guild_id)) {
			return self::$rp_plugin[ $guild_id ];
		} else {
			$db = JFactory::getDBO();
			$query = "SELECT guild_id, guild_name, sync_plugin, params FROM #__raidplanner_guild WHERE guild_id=" . intval($guild_id); 
			$db->setQuery($query);
			if ($guild = $db->loadObject()) {
				$guild->params = json_decode( $guild->params, true );
				if (JPluginHelper::importPlugin('raidplanner', $guild->sync_plugin)) {
					/* Plugin loaded */
					if (self::getJVersion() < '3.0') {
						$plugin = JDispatcher::getInstance();
					} else {
						$plugin = JEventDispatcher::getInstance();
					}
					$plugin->trigger( 'onRPInitGuild', array( $guild_id, $guild->params ) );
				
					self::$rp_plugin[ $guild_id ] = $plugin;
					
					return self::$rp_plugin[ $guild_id ];
				}
			} else {
				return false;
			}
		}
	}

	public static function loadGuildCSS( $guild_id )
	{
		if ($plugin = self::getGuildPlugin( $guild_id ) )
		{
			return $plugin->trigger( 'onRPLoadCSS' );
		}
		
		return false;
	}

	public static function RosterSync( $guild_id , $sync_interval , $showOkStatus = false )
	{
		if ($plugin = self::getGuildPlugin( $guild_id ) )
		{
			$plugin->trigger( 'onRPSyncGuild' , array( $showOkStatus, $sync_interval, true ) );
		}
	}
	
	public static function getSyncPlugins()
	{
		$plugins = array();
		$j_plugs = JPluginHelper::getPlugin( 'raidplanner' );
		foreach ($j_plugs as $jplug) {
			$plugins[] = $jplug->name;
		}
		
		return $plugins;
	}
	
	public static function getSyncPluginParams( $plugin )
	{
		$params = array();
		
		$plugin = preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin);
		$plug_xml_file = JPATH_SITE . '/plugins/raidplanner/' . $plugin . '/' . $plugin .'.xml';
		if (JFile::exists( $plug_xml_file )) {
			$plug_xml = simplexml_load_file( $plug_xml_file );
			foreach( $plug_xml->params->param as $param ) {
				$data = null;
				if ( (string)$param->attributes()->type == 'list')
				{
					$data = array();
					foreach ( $param->option as $option )
					{
						$data[] = array(
							'value'	=>	(string)$option->attributes()->value,
							'label'	=>	(string)$option
						);
					}
				}
				$params[] = array(
					'type'	=>	(string)$param->attributes()->type,
					'name'	=>	(string)$param->attributes()->name,
					'label'	=>	(string)$param->attributes()->label,
					'description'	=>	(string)$param->attributes()->description,
					'data'	=>	$data
				);
			}
		} else {
			JError::raiseWarning('100','RaidPlanner Sync plugin configuration file not exists!');
		}

		return  ($params);
	}

	public static function getJVersion()
	{
		if (!self::$jversion)
		{
			self::$jversion = new JVersion();
		}
		return self::$jversion->RELEASE;
	}

	public static function showToolbarButtons()
	{
		$view = JRequest::getVar('view');

		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER'), 'index.php?option=com_raidplanner&view=raidplanner', ($view == 'raidplanner'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RAIDS'), 'index.php?option=com_raidplanner&view=raids', ($view == 'raids'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_GUILDS'), 'index.php?option=com_raidplanner&view=guilds', ($view == 'guilds'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CHARACTERS'), 'index.php?option=com_raidplanner&view=characters', ($view == 'characters'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_ROLES'), 'index.php?option=com_raidplanner&view=roles', ($view == 'roles'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CLASSES'), 'index.php?option=com_raidplanner&view=classes', ($view == 'classes'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RACES'), 'index.php?option=com_raidplanner&view=races', ($view == 'races'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_STATS'), 'index.php?option=com_raidplanner&view=stats', ($view == 'stats'));
	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions( $id = null)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_raidplanner';

		$actions = array(
		/* admin actions */
			'core.admin', 'core.manage',
		/* frontend actions */
			'core.delete', 'core.edit', 'core.edit.own',
			'raidplanner.delete_raid_own', 'raidplanner.allow_signup', 'raidplanner.view_raids', 'raidplanner.view_calendar', 'raidplanner.edit_characters'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	public static function getTimezone( $user = null, $forcenumeric = true )
	{
		$user =JFactory::getUser( $user );
		$tz = $user->getParam('timezone', JFactory::getApplication()->getCfg('offset'));
		if ((!is_numeric($tz)) && ($forcenumeric)) {
			$date = new JDate('now',$tz);
			if (method_exists($date,'getOffsetFromGMT')) {
				$tz = $date->getOffsetFromGMT(true);
			} else {
				$tz = $date->getOffset();
			}
		}
		return $tz;
	}

	public static function getTimezoneName( $user = null )
	{
		if (method_exists( 'JDate', 'getTimezone' )) {
			$timezoneName = self::getDate('now', self::getTimezone( $user ))->getTimezone()->getName();
		} else {
	    	$timezoneName = timezone_name_from_abbr("", self::getTimezone( $user ) * 3600, 0);
	    }
	    
	    return $timezoneName;
	}

	public static function getGuilds()
	{
		$db = JFactory::getDBO();
		$query = ' SELECT guild_name,guild_id FROM #__raidplanner_guild ORDER BY guild_name ASC ';
		$db->setQuery( $query );
		return $db->loadObjectList('guild_id');
	}
	
	public static function getClasses()
	{
		$db = JFactory::getDBO();
		$query = ' SELECT class_name,class_id,class_color FROM #__raidplanner_class ORDER BY class_name ASC ';
		$db->setQuery( $query );
		return $db->loadObjectList('class_id');
	}
	
	public static function getGenders()
	{
		$db = JFactory::getDBO();
		$query = ' SELECT gender_name,gender_id FROM #__raidplanner_gender';
		$db->setQuery( $query );
		return $db->loadObjectList('gender_id');
	}

	public static function getRaces()
	{
		$db = JFactory::getDBO();
		$query = ' SELECT race_name,race_id FROM #__raidplanner_race';
		$db->setQuery( $query );
		return $db->loadObjectList('race_id');
	}

	public static function getCharacters()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT character_id,char_name FROM #__raidplanner_character ORDER BY char_name ASC';
		$db->setQuery( $query );
		return $db->loadObjectList('character_id');
	}

	public static function getUsers()
	{
		$db	= JFactory::getDBO();
		$query = "SELECT id,username FROM #__users ORDER BY username ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('id');
	}

	public static function getGroups( $guest = true, $only_groups = array() )
	{
		$db	= JFactory::getDBO();

		// Joomla ACL used, return Joomla groups
		if (is_array($only_groups) && !empty($only_groups)) {
			foreach ($only_groups as &$group) {
				$group = intval($group);
			}
			$query = "SELECT id AS group_id,title AS group_name FROM #__usergroups WHERE id IN (" . implode(",", $only_groups) . ") ORDER BY title ASC";
		} elseif ($guest) {
			$query = "SELECT id AS group_id,title AS group_name FROM #__usergroups ORDER BY title ASC";
		} else {
			$query = "SELECT id AS group_id,title AS group_name FROM #__usergroups WHERE parent_id<>0 ORDER BY title ASC";
		}

		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('group_id');
	}
	
	public static function getPermission($permission, $user_id=null)
	{
		$reply = false;
		
		if ($permission!='') {
			$permission = 'raidplanner.' . $permission;
			if (isset(self::$acl_map[ $permission ])) {
				$permission = self::$acl_map[ $permission ];
			}
			if (JFactory::getUser()->authorise($permission, 'com_raidplanner')) {
				$reply = true;
			}
		}
		return $reply;
	}
	
	public static function getDate( $date = 'now', $tzOffset = null, $format = null )
	{
		if ($tzOffset === null)
		{
			$tzOffset = self::getTimezone( null, false);
		}
		try {
			$reply =JFactory::getDate( $date, $tzOffset );
		} catch (Exception $e) {
			JError::raiseNotice( 500, 'Invalid date (' . $date .') entered' );
			$reply =JFactory::getDate();
		}
		if ($format != null) {
			if ($format == 'sql') {
				return self::date2Sql( $reply );
			} else {
				if (method_exists($reply,'format')) {
					return $reply->format( $format );
				} else {
					return $reply->toFormat( $format );
				}
			}
		}
		return $reply;
	}
	
	/* Checks the invitations for raids which will be frozen in the next $times_before minutes and user is part of the invited group */
	public static function checkInvitations($time_before = 1440, $user_id=null)
	{
		if (!self::$invite_alert_requested)
		{
			if (!$user_id) {
				$user =JFactory::getUser();
				$user_id = $user->id;
			}
			if ($user_id) {
				$db = JFactory::getDBO();
				$date = RaidPlannerHelper::getDate();
				/* get user vacation first */
				$user = JUser::getInstance( $user_id );
				$vac = $user->getParam('vacation', '');

				// Joomla ACL
				$query = "SELECT r.raid_id,r.location,r.start_time FROM #__raidplanner_raid AS r"
						." LEFT JOIN #__user_usergroup_map AS p ON p.group_id = r.invited_group_id"
						." LEFT JOIN (#__raidplanner_signups AS s,#__raidplanner_character AS c) ON (s.raid_id = r.raid_id AND c.character_id = s.character_id AND c.profile_id = p.user_id)"
						." WHERE r.invited_group_id>0"
						." AND s.raid_id IS NULL"
						." AND p.user_id = ".intval($user_id)
						." AND DATE_SUB(r.start_time,interval r.freeze_time minute) > '" . self::date2Sql($date) . "'"
						." AND DATE_SUB(r.start_time,interval (r.freeze_time + " . intval($time_before) . ") minute) < '" . self::date2Sql($date) . "'";
				
				if ($vac<>'')
				{
					/* exclude vacation dates */
					$vacs = explode("\n", $vac);
					if (!empty($vacs))
					{
						foreach ($vacs as $vac)
						{
							$vac_period = explode(" ", $vac);
							$vac_period[0] = preg_replace('/[^0-9-]/i', '', $vac_period[0]);
							$vac_period[1] = preg_replace('/[^0-9-]/i', '', $vac_period[1]);
							$query .= "\nAND NOT (r.start_time>='" . $vac_period[0] . "' AND r.start_time<='" . $vac_period[1] . "')";
						}
					}
				}
				
				$db->setQuery( $query );
				self::$invite_alert_requested = true;

				return $db->loadObjectList();
			}
		}
		return null;
	}

	public static function getRaidPlannerItemId( $view = 'calendar' )
	{
		$menu = JFactory::getApplication()->getMenu()->getItems( 'component', 'com_raidplanner', false );
		if (empty($menu)) {
			$itemid = JFactory::getApplication()->getMenu()->getActive()->id;
		} else {
			foreach ($menu as $menuItem)
			{
				if ( ($menuItem->query['view'] == $view) && ($menuItem->query['option'] == 'com_raidplanner') )
				{
					return $menuItem->id;
				}
			}
			$itemid = $menu[0]->id;
		}
		
		return $itemid;
	}
	
	public static function getRanks( $transposed = false )
	{
		$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
		$ranks = array();
		for ($i=0; $i<=9; $i++)
		{
			if ($transposed) {
				$ranks[$paramsObj->get('ranks_' . $i, '- '.JText::_('COM_RAIDPLANNER_RANK') . ' ' . $i .' -')] = $i;
			} else {
				$ranks[$i] = $paramsObj->get('ranks_' . $i, '- '.JText::_('COM_RAIDPLANNER_RANK') . ' ' . $i .' -');
			}
		}

		return $ranks;

	}
	
	public static function shortDateFormat()
	{
		return JText::_('DATE_FORMAT_LC4') . ' H:i';
	}

	public static function sqlDateFormat( $time = false )
	{
		$format = 'Y-m-d';
		if ($time) {
			$format = $format . ' H:i';
		}
		return $format;
	}

	public static function date2Sql( $date )
	{
		if (method_exists($date,'toSql')) {
			return $date->toSql();
		} else {
			return $date->toMySQL();
		}
	}

	public static function loadJSFramework( $load_extras = false )
	{
		JHtml::_('behavior.framework',  $load_extras);
	}

	public static function downloadData( $url )
	{
		$data = false;

		if(function_exists('curl_init') && function_exists('curl_exec')) {
			$ch = @curl_init();

			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, false);
			@curl_setopt($ch, CURLOPT_FAILONERROR, true);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			@curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$data = @curl_exec($ch);
			@curl_close($ch);
		}

		if ((!$data) && (function_exists('fsockopen') && $data == '')) {
			$errno = 0;
			$errstr = '';

			$url_parts = parse_url($url);
			
			$fsock = @fsockopen( $url_parts['host'], 80, $errno, $errstr, 10);

			if ($fsock) {
				@fputs($fsock, "GET " . $url_parts['path'] . " HTTP/1.1\r\n");
				@fputs($fsock, "HOST: " . $url_parts['host'] . "\r\n");
				@fputs($fsock, "Connection: close\r\n\r\n");
				@stream_set_blocking($fsock, 1);
				@stream_set_timeout($fsock, 30);
				$get_info = false;
				while (!@feof($fsock)) {
					if ($get_info) {
						$data .= @fread($fsock, 1024);
					} else {
						if (@fgets($fsock, 1024) == "\r\n") {
							$get_info = true;
						}
					}
				}
				@fclose($fsock);
			}
		}

		if ((!$data) && (function_exists('fopen') && ini_get('allow_url_fopen') && $data == '')) {
			ini_set('default_socket_timeout', 15);
			
			$handle = @fopen ($url, 'r');

			//set stream timeout
			@stream_set_blocking($handle, 1);
			@stream_set_timeout($handle, 30);
			
			while (!@feof($handle)) {
				$data .= @fread($handle, 8192);
			}
			
			@fclose($handle);
		}
		
		return $data;
	}
	
	/**
	 * Parse a string which contains a separated list of chars. Separator can be , ; \n \r \t
	 * if $forceids is true, character id is given back even if it's not in the data (fetched from the database by name)
	 */
	public static function getProfileChars( $data , $forceids = false, $getGuild = false)
	{
		$reply = array();
		
		$db = JFactory::getDBO();

		$chars = str_replace( array("\n", ",", ";", "\r", "\t"), "\n", $data );
		$charlist = explode( "\n", $chars);
		
		foreach ($charlist as $char) {
			if ($char) {
				$guild_id = '';
				$guild_name = '';
				$class_css = '';
				$race_css = '';

				if ( strpos($char, ':') !== false ) {
					list($char_id, $char_name) = explode (":", trim($char) );
					$char_id = intval($char_id);
					if ($getGuild) {
						$query = "SELECT c.character_id,g.guild_id,g.guild_name,cl.class_css,r.race_css
									FROM #__raidplanner_character AS c
									LEFT JOIN #__raidplanner_guild AS g ON g.guild_id=c.guild_id
									LEFT JOIN #__raidplanner_race AS r ON r.race_id=c.race_id
									LEFT JOIN #__raidplanner_class AS cl ON cl.class_id=c.class_id
									WHERE c.character_id='" . trim($char_id) . "' ORDER BY g.guild_id ASC, c.char_name ASC LIMIT 1";
						$db->setQuery( $query );
						if ($result = $db->loadObject()) {
							$guild_name = $result->guild_name;
							$guild_id = $result->guild_id;
							$class_css = $result->class_css;
							$race_css = $result->race_css;
						}
					}
				} else {
					$char_name = trim($char);
					$char_id = '';
					if ( $forceids ) {
						$query = "SELECT c.character_id,g.guild_id,g.guild_name,cl.class_css,r.race_css
									FROM #__raidplanner_character AS c
									LEFT JOIN #__raidplanner_guild AS g ON g.guild_id=c.guild_id
									LEFT JOIN #__raidplanner_race AS r ON r.race_id=c.race_id
									LEFT JOIN #__raidplanner_class AS cl ON cl.class_id=c.class_id
									WHERE c.char_name='" . trim($char) . "'
									ORDER BY g.guild_id ASC, c.char_name ASC LIMIT 1";
						$db->setQuery( $query );
						if ($result = $db->loadObject()) {
							$guild_name = $result->guild_name;
							$guild_id = $result->guild_id;
							$char_id = $result->character_id;
							$class_css = $result->class_css;
							$race_css = $result->race_css;
						}
					}
				}
				$reply[] = array(
					'char_id'		=>	$char_id,
					'char_name'		=>	$char_name,
					'guild_id'		=>	$guild_id,
					'guild_name'	=>	$guild_name,
					'class_css'		=>	$class_css,
					'race_css'		=>	$race_css
				);
			}
		}
		
		return $reply;
	}

	public static function autoRepeatRaids()
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT raid_id,DATE_ADD(start_time, INTERVAL 7 DAY) AS new_time,DATE_ADD(invite_time, INTERVAL 7 DAY) AS new_invite,location,invited_group_id,guild_id FROM #__raidplanner_raid WHERE is_template<0 AND DATE_ADD(start_time, INTERVAL is_template DAY)<NOW()";
		$db->setQuery( $query );
		if ($raids = $db->loadObjectList()) {
			JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_raidplanner/tables');
			$row = JTable::getInstance('raid', 'Table');

			foreach ($raids as $raid) {
				/* Duplicate it, but 7 day later */
				if ($row->load($raid->raid_id)) {
					/* Change the original to a non template version */
					$old_template = $row->is_template;
					$row->is_template = 0;
					if (!$row->store()) {
						JError::raiseError(500, $row->getError() );
					}
					
					/* Check if no raid present with the same settings */
					$query = "SELECT raid_id FROM #__raidplanner_raid WHERE start_time='" . $raid->new_time . "' AND location=" . $db->Quote($raid->location) . " AND guild_id=" . intval($raid->guild_id) . " AND invited_group_id=" . intval($raid->invited_group_id) . "";
					$db->setQuery($query);
					if (!$db->loadResult()) {
						/* Create a duplicate one */
						$row->raid_id = 0;
						$row->is_template = $old_template;
						$row->start_time = $raid->new_time;
						$row->invite_time = $raid->new_invite;
						if (!$row->store()) {
							JError::raiseError(500, $row->getError() );
						}
					}
				} else {
					return JError::raiseWarning( 500, $row->getError() );
				}
			}
		}
	}

	public static function raidTooltip( $raid_id = 0, $showAttendants = true, $additional = '', $timeformat = 'H:i')
	{
		$db = JFactory::getDBO();
		/* get event details */
		$query = "SELECT description, icon_name, location, raid_leader, start_time FROM #__raidplanner_raid WHERE raid_id=" . intval( $raid_id );
		$db->setQuery( $query );
		$event = $db->loadObject();
		list($icon_name) = explode(".",$event->icon_name);
		$icon_name = ucwords ( str_replace("_"," ",basename( $icon_name ) ) );
		$tooltipTitle = '' . ($event->description == "") ? ( $icon_name ) : $event->description . '';
		$tooltip = '';
		if ($event->icon_name!='') {
			$tooltip .= '<img src="' . JURI::base() . "media/com_raidplanner/raid_icons/" . $event->icon_name . '" alt="' . $event->location . '" style="float:left; margin:0 5px 5px 0;"/>';
		}
		$tooltip.= '<small><b>' . JText::_('COM_RAIDPLANNER_RAID_LEADER') . ':</b> ' . $event->raid_leader . '<br />';
		if ($showAttendants) {
			/* get attendants */
			$query = "SELECT c.char_name
					FROM #__raidplanner_signups AS s
					LEFT JOIN #__raidplanner_character AS c ON c.character_id=s.character_id
					WHERE s.raid_id=".intval($raid_id)." AND s.queue=1
					ORDER BY s.confirmed DESC, c.char_name ASC";
			$db->setQuery($query);
			$attendants = $db->loadColumn();
			if ($attendants) {
				$tooltip.= '<b>' . JText::_('COM_RAIDPLANNER_STATUSES_1') . '</b> (' . count($attendants) . '): ' . join(", ", $attendants);
			}
		}
		if ($additional != '') {
			$tooltip.= "<br />" . $additional;
		}
		$tooltip.= '</small>';

		return JHTML::tooltip( $tooltip, $tooltipTitle, '', '<strong>' . JHTML::_('date', $event->start_time, $timeformat ) . '</strong> ' . $event->location );
	}

	public static function detectMobile()
	{
		$isMobile	= false;
		$userAgent	= $_SERVER['HTTP_USER_AGENT'];
		$httpAccept	= $_SERVER['HTTP_ACCEPT'];
		switch(true){
			case (preg_match('/ipod/i',$userAgent)||preg_match('/iphone/i',$userAgent)):	// iPod or iPhone detected in user agent
			case (preg_match('/android/i',$userAgent)):										// Android detected in user agent
			case (preg_match('/opera mini/i',$userAgent)):									// Opera mini
			case (preg_match('/blackberry/i',$userAgent)):									// blackberry
			case (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$userAgent)):		// Windows CE variants
				$isMobile = true;
			break;
		}
		
		return $isMobile;
	}
}