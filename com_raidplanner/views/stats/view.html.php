<?php
/*------------------------------------------------------------------------
# Stats View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2013 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.application.component.helper' );

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewStats extends JViewLegacy
{
	/**
	 * display method of Stats view
	 * @return void
	 **/
	function display($tpl = null)
	{
		/* Load Javascript and CSS files */
		RaidPlannerHelper::loadJSFramework();

		 /* Get state, and params */
		$app		= JFactory::getApplication();
		$params		= $app->getParams();
		$state 		= $this->get( 'state' );

		$guild_id = $params->get('guild_id', '0');
		$groups = $params->get('allowed_groups');
		$by_chars = $params->get('stats_by_chars', 0);
		$show_rating = $params->get('show_rating', 0);

		/* Get the values from the state object that were inserted in the model's construct function */
		$lists['filter_start_time'] = $state->get( 'filter_start_time' );
		$lists['filter_end_time'] = $state->get( 'filter_end_time' );
		$lists['filter_character_id'] = $state->get( 'filter_character_id' );
		$lists['filter_group_id'] = $state->get( 'filter_group_id' );

		$this->assignRef( 'guilds', RaidPlannerHelper::getGuilds() );
		$this->assignRef( 'groups', RaidPlannerHelper::getGroups( true, $groups ) );
		$this->assignRef( 'characters', RaidPlannerHelper::getCharacters() );
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'by_chars', $by_chars );
		$this->assignRef( 'show_rating', $show_rating );
		$this->assignRef( 'guild_id', $guild_id );

		parent::display($tpl);
	}
}