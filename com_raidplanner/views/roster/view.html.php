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
JHTML::script('guild-tabard.js', 'components/com_raidplanner/assets/');

class RaidPlannerViewRoster extends JView
{
	function display($tpl = null)
	{
		$model = &$this->getModel();
		$paramsObj = &JComponentHelper::getParams( 'COM_RAIDPLANNER' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$paramsObj->merge( $menuparams );
		}

		$guild_id = $paramsObj->get('guild_id', '0');
		if ($paramsObj->get('armory_sync', '0') == 1)
		{
			// sync armory
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_raidplanner'.DS.'helper.php' );
			ComRaidPlannerHelper::armorySync( $guild_id, $paramsObj->get( 'sync_interval', 4 ) );
		}

		$this->assignRef( 'characters', $model->getCharacters( $guild_id ) );
		$this->assignRef( 'guildinfo', $model->getGuildInfo( $guild_id ) );

		parent::display($tpl);
	}

}