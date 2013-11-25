<?php
/*------------------------------------------------------------------------
# RaidPlanner Sync Plugin master class
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2012 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerPlugin
{
	protected $params = array();
	protected $guild_name = null;
	protected $guild_id = null;
	
	public $provide_sync = false;
	
	function __construct( $guild_id, $guild_name, $params)
	{
		$this->params = $params;
		$this->guild_name = $guild_name;
		$this->guild_id = $guild_id;
	}

	public function needSync( $sync_interval = 4)
	{
		$db = JFactory::getDBO();
		$query = "SELECT IF(lastSync IS NULL,-1,DATE_ADD(lastSync, INTERVAL " . intval( $sync_interval ) . " HOUR)-NOW()) AS needSync FROM #__raidplanner_guild WHERE guild_id=" . intval($this->guild_id); 
		$db->setQuery($query);
		if ( ($needsync = $db->loadResult()) && ( $needsync<0 ) )
		{
			return true;
		}
		
		return false;
	}

	protected function getData( $url )
	{
		// register the helper
		JLoader::register('RaidPlannerHelper', JPATH_ADMINISTRATOR . '/components/com_raidplanner/helper.php' );

		return RaidPlannerHelper::downloadData( $url );
	}

	public function doSync( $showOkStatus = false )
	{
		return false;
	}
	
	public function characterLink()
	{
		return "#";
	}
	
	public function guildHeader()
	{
		return "<h2>" . $this->guild_name . "</h2>";
	}
	
	public function loadCSS()
	{
		return false;
	}
}