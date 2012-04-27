<?php
/*------------------------------------------------------------------------
# Roster View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.controller' );

$version = new JVersion();
if ($version->RELEASE == '1.5') {
	JHTML::script('mootools.more.125.additional.js', 'components/com_raidplanner/assets/');
}
JHTML::script('HtmlTable.Extended.js', 'components/com_raidplanner/assets/');

class RaidPlannerViewRoster extends JView
{
	function display($tpl = null)
	{
		$model = &$this->getModel();
		$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$paramsObj->merge( $menuparams );
		}

		$guild_id = $paramsObj->get('guild_id', '0');
		$show_account = $paramsObj->get('show_account', '0');
		$guild_plugin = RaidPlannerHelper::getGuildPlugin( $guild_id );
		if (($paramsObj->get('armory_sync', '0') == 1) && ($guild_plugin) && ($guild_plugin->needSync( $paramsObj->get( 'sync_interval', 4 ) ) ))
		{
			// sync armory
			$guild_plugin->doSync();
		}

		$this->assignRef( 'guild_plugin', $guild_plugin );
		$this->assignRef( 'characters', $model->getGuildCharacters( $guild_id ) );
		$this->assignRef( 'guildinfo', $model->getGuildInfo( $guild_id ) );
		$this->assignRef( 'ranks', RaidPlannerHelper::getRanks() );
		$this->assignRef( 'show_account', $show_account );

		parent::display($tpl);
	}

}