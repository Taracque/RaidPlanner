<?php
/*------------------------------------------------------------------------
# RaidPlanner View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

JHTML::stylesheet('raidplanner.css', 'administrator/components/com_raidplanner/assets/');

class RaidPlannerViewRaidPlanner extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the data

		JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER' ) );
		JToolBarHelper::preferences( 'com_raidplanner' );

		RaidPlannerHelper::showToolbarButtons();

		parent::display($tpl);
	}
}