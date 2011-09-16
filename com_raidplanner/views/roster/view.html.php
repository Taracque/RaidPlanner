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

		$this->assignRef( 'characters', $model->getCharacters() );
		$this->assignRef( 'guildinfo', $model->getGuildInfo() );

		parent::display($tpl);
	}

}