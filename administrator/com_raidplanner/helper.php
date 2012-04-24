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

class RaidPlannerHelper
{
	private static $invite_alert_requested = false;
	private static $use_joomla_acl = false;
	private static $jversion = null;

	public static function RosterSync( $guild_id , $sync_interval , $showOkStatus = false )
	{
		$db = & JFactory::getDBO();
		$query = "SELECT *,(DATE_ADD(lastSync, INTERVAL " . intval( $sync_interval ) . " HOUR)-NOW()) AS needSync FROM #__raidplanner_guild WHERE guild_id=" . intval($guild_id); 
		$db->setQuery($query);
		if ($tmp = $db->loadObject())
		{
			$guild_id = $tmp->guild_id;
			$needsync = $tmp->needSync;
			$plug_class = $tmp->sync_plugin;
	
			if ( ( $needsync<=0 ) && ($plug_class != '') )
			{
				$tmp->params = json_decode($tmp->params, true);
				
				/* Load plugin */
				
				JLoader::register( $plug_class, JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'plugins' . DS . $plug_class . DS . $plug_class . '.php' );
				
				$sync_module = new $plug_class();
				$sync_module->Sync( $tmp, $sync_interval , $showOkStatus );
			}
		}
	}
	
	public static function getSyncPlugins()
	{
		$plugins = JFolder::folders( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'plugins', '.', false );
		/* FIXME: needs to be veryfied if there is anything in those folder */
		
		return $plugins;
	}
	
	public static function getSyncPluginParams( $plugin )
	{
		$params = array();
		
		/* FIXME: Plugin name must be sanitized */
		$plug_xml_file = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'plugins' . DS . $plugin . DS . $plugin . '.xml';
		if (JFile::exists( $plug_xml_file )) {
			$plug_xml =& JFactory::getXMLParser( 'simple' );
			$plug_xml->loadFile( $plug_xml_file );

			foreach( $plug_xml->document->params[0]->param as $param ) {
				$data = null;
				if ($param->attributes( 'type' ) == 'list')
				{
					$data = array();
					foreach ( $param->option as $option )
					{
						$data[] = array(
							'value'	=>	$option->attributes( 'value' ),
							'label'	=>	$option->data()
						);
					}
				}
				$params[] = array(
					'type'	=>	$param->attributes( 'type' ),
					'name'	=>	$param->attributes( 'name' ),
					'label'	=>	$param->attributes( 'label' ),
					'description'	=>	$param->attributes( 'description' ),
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
		JSubMenuHelper::addEntry('', '', false);
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RAIDS'), 'index.php?option=com_raidplanner&view=raids', ($view == 'raids'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_GUILDS'), 'index.php?option=com_raidplanner&view=guilds', ($view == 'guilds'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CHARACTERS'), 'index.php?option=com_raidplanner&view=characters', ($view == 'characters'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_GROUPS'), 'index.php?option=com_raidplanner&view=groups', ($view == 'groups'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_ROLES'), 'index.php?option=com_raidplanner&view=roles', ($view == 'roles'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CLASSES'), 'index.php?option=com_raidplanner&view=classes', ($view == 'classes'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RACES'), 'index.php?option=com_raidplanner&view=races', ($view == 'races'));
		JSubMenuHelper::addEntry('', '', false);
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_STATS'), 'index.php?option=com_raidplanner&view=stats', ($view == 'stats'));
	}
	
	public static function checkACL()
	{
		if ((!self::$use_joomla_acl) && (self::getJVersion() >= '1.6')) {
		// use of Joomla ACL is not set and Joomla >= 1.6, check the database.
			$db = & JFactory::getDBO();
			$db->setQuery("SELECT COUNT(*) FROM #__raidplanner_permissions");
			if ($db->loadResult() == 0) {
				// no RaidPlanner group defined, use Joomla ACL.
				self::$use_joomla_acl = true;
			}
		}
		
		return self::$use_joomla_acl;
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
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete',
		/* frontend actions */
			'raidplanner.allow_signup', 'raidplanner.view_raids', 'raidplanner.view_calendar', 'raidplanner.edit_raids_own',
			'raidplanner.edit_raids_any', 'raidplanner.delete_raid_own', 'raidplanner.delete_raid_any', 'raidplanner.edit_characters'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
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

	public static function getCharacters()
	{
		$db = & JFactory::getDBO();
		$query = ' SELECT character_id,char_name FROM #__raidplanner_character';
		$db->setQuery( $query );
		return $db->loadObjectList('character_id');
	}

	public static function getUsers()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT id,username FROM #__users ORDER BY username ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('id');
	}

	public static function getGroups( $guest = true )
	{
		self::checkACL();
		$db	=& JFactory::getDBO();

		if (self::$use_joomla_acl) {
			// Joomla ACL used, return Joomla groups
			if ($guest) {
				$query = "SELECT id AS group_id,title AS group_name FROM #__usergroups ORDER BY title ASC";
			} else {
				$query = "SELECT id AS group_id,title AS group_name FROM #__usergroups WHERE parent_id<>0 ORDER BY title ASC";
			}
		} else {
			if ($guest) {
				$query = "SELECT group_id,group_name FROM #__raidplanner_groups ORDER BY group_name ASC";
			} else {
				$query = "SELECT group_id,group_name FROM #__raidplanner_groups WHERE group_name<>'Guest' ORDER BY group_name ASC";
			}
		}
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('group_id');
	}
	
	public static function getPermission($permission, $user_id=null)
	{
		$reply = false;
		
		if ($permission!='') {
			self::checkACL();
			// Joomla ACL
			if (self::$use_joomla_acl) {
				if (JFactory::getUser()->authorise('raidplanner.' . $permission, 'com_raidplanner')) {
					$reply = true;
				}
			} else {
			// RaidPlanner groups
				$guest = false;
				if (!$user_id) {
					$user =& JFactory::getUser();
					$user_id = $user->id;
					$guest = $user->guest;
				}
				if (!$guest) {
					$db	=& JFactory::getDBO();
					/* check if user is member of a group, if not, default group used */
					$query = "SELECT count(*) FROM #__raidplanner_profile AS profile WHERE profile.profile_id=".intval($user_id)."";
					$db->setQuery($query);
					$count = $db->loadResult();
					if ($count>0)
					{
						$query = "SELECT permission_value FROM #__raidplanner_profile AS profile LEFT JOIN #__raidplanner_permissions AS perm ON profile.group_id = perm.group_id WHERE profile.profile_id=".intval($user_id)." AND perm.permission_name = ".$db->Quote($permission)." AND perm.permission_value=1";
					} else {
						$query = "SELECT permission_value FROM #__raidplanner_groups AS groups LEFT JOIN #__raidplanner_permissions AS perm ON groups.group_id = perm.group_id WHERE groups.`default`=1 AND perm.permission_name = ".$db->Quote($permission)." AND perm.permission_value=1";
					}
				} else {
					$query = "SELECT permission_value FROM #__raidplanner_permissions AS perm LEFT JOIN #__raidplanner_groups AS g ON g.group_id = perm.group_id WHERE g.group_name='Guest' AND perm.permission_name = ".$db->Quote($permission)." AND perm.permission_value=1";
				}
				$db->setQuery($query);
	
				$dbreply = ($db->loadResultArray());
				$reply = (@$dbreply[0] === "1");
			}
		}
		return $reply;
	}
	
	public static function getDate( $date = 'now', $tzOffset = null )
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
	
	/* Checks the invitations for raids which will be frozen in the next $times_before minutes and user is part of the invited group */
	public static function checkInvitations($time_before = 1440, $user_id=null)
	{
		if (!self::$invite_alert_requested)
		{
			if (!$user_id) {
				$user =& JFactory::getUser();
				$user_id = $user->id;
			}
			if ($user_id) {
				$db = & JFactory::getDBO();
				$date = RaidPlannerHelper::getDate();
				
				self::checkACL();
				// Joomla ACL
				if (self::$use_joomla_acl) {
					$query = "SELECT r.raid_id,r.location,r.start_time FROM #__raidplanner_raid AS r"
							." LEFT JOIN #__user_usergroup_map AS p ON p.group_id = r.invited_group_id"
							." LEFT JOIN #__raidplanner_signups AS s ON s.raid_id = r.raid_id AND s.profile_id = p.profile_id"
							." WHERE r.invited_group_id>0"
							." AND s.raid_id IS NULL"
							." AND p.user_id = ".intval($user_id)
							." AND DATE_SUB(r.start_time,interval r.freeze_time minute) > '" . $date->toMySQL() . "'"
							." AND DATE_SUB(r.start_time,interval (r.freeze_time + " . intval($time_before) . ") minute) < '" . $date->toMySQL() . "'";
				} else {
					$query = "SELECT r.raid_id,r.location,r.start_time FROM #__raidplanner_raid AS r"
							." LEFT JOIN #__raidplanner_profile AS p ON p.group_id = r.invited_group_id"
							." LEFT JOIN #__raidplanner_signups AS s ON s.raid_id = r.raid_id AND s.profile_id = p.profile_id"
							." WHERE r.invited_group_id>0"
							." AND s.raid_id IS NULL"
							." AND p.profile_id = ".intval($user_id)
							." AND DATE_SUB(r.start_time,interval r.freeze_time minute) > '" . $date->toMySQL() . "'"
							." AND DATE_SUB(r.start_time,interval (r.freeze_time + " . intval($time_before) . ") minute) < '" . $date->toMySQL() . "'";
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
		$menu = &JSite::getMenu()->getItems( 'component', 'com_raidplanner', false );
		if (empty($menu)) {
			$itemid = &JSite::getMenu()->getActive()->id;
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
		$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
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
		switch ( self::getJVersion() ) {
			case '1.5':
				$dateformat = JText::_('DATE_FORMAT_LC4') . ' %H:%M';
			break;
			default:
			case '1.6':
				$dateformat = JText::_('DATE_FORMAT_LC4') . ' H:i';
			break;
		}
		return $dateformat;
	}

	public static function sqlDateFormat()
	{
		switch ( self::getJVersion() ) {
			case '1.5':
				$dateformat = '%Y-%m-%d';
			break;
			default:
			case '1.6':
				$dateformat = 'Y-m-d';
			break;
		}
		return $dateformat;
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