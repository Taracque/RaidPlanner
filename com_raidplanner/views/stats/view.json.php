<?php
/*------------------------------------------------------------------------
# Stats Controller for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2012 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewStats extends JViewLegacy
{
	/**
	 * Return JSON encoded data for Statistic page
	 */
	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$params		= $app->getParams();
		jimport( 'joomla.environment.request' );

		/* validating request */
		$guild_id = $params->get('guild_id', '0');
		$groups = $params->get('allowed_groups');
		$by_chars = $params->get('stats_by_chars', 0);

		if ($guild_id != 0) {
			JRequest::setVar('guild_id', $guild_id, 'get', true);
		}
		if ($by_chars == 0) {
			JRequest::setVar('character_id', 0, 'get', true);
		}
		if (JRequest::getVar('group_id', '', 'get', 'int') != '') {
			if (!in_array(JRequest::getVar('group_id', '', 'get', 'int'), $groups)) {
				JRequest::setVar('group_id', '', 'get', true);
			}
		}

		/* load backend controller */
		JLoader::register('RaidPlannerControllerStats', JPATH_ADMINISTRATOR . '/components/com_raidplanner/controllers/stats.php');

		$tmp = new RaidPlannerControllerStats();
		$tmp->display();
	}
	
}