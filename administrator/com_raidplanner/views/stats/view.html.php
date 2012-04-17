<?php
/*------------------------------------------------------------------------
# Stats View for RaidPlanner Component
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

JHTML::_('behavior.mootools');

JHTML::stylesheet('raidplanner.css', 'administrator/components/com_raidplanner/assets/');

class RaidPlannerViewStats extends JView
{
	/**
	 * display method of Stats view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the data

		JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER_STATS' ) );

		RaidPlannerHelper::showToolbarButtons();

		 /* Call the state object */
		$state =& $this->get( 'state' );
		
		/* Get the values from the state object that were inserted in the model's construct function */
		$lists['filter_start_time'] = $state->get( 'filter_start_time' );
		$lists['filter_end_time'] = $state->get( 'filter_end_time' );
		$lists['filter_character_id'] = $state->get( 'filter_character_id' );
		$lists['filter_group_id'] = $state->get( 'filter_group_id' );

		$this->assignRef( 'guilds', RaidPlannerHelper::getGuilds() );
		$this->assignRef( 'groups', RaidPlannerHelper::getGroups() );
		$this->assignRef( 'characters', RaidPlannerHelper::getCharacters() );
		$this->assignRef( 'lists', $lists );

		parent::display($tpl);
	}
}