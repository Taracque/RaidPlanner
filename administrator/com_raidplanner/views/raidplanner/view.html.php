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

require_once ( JPATH_ADMINISTRATOR . '/components/com_raidplanner/includes/installer.php' );

JHTML::stylesheet('com_raidplanner/raidplanner_admin.css', false, true, false);

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewRaidPlanner extends JViewLegacy
{
	private function getPluginList()
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_plugins/models');
		$plugins_model = JModelLegacy::getInstance('plugins', 'pluginsModel');
		$plugin_state = $plugins_model->getState();

		$plugins_model->setState('filter.folder','raidplanner');
		$plugins_model->setState('filter.search','');
		$plugins_model->setState('filter.access','0');
		$plugins_model->setState('filter.enabled','');
		$plugins_model->setState('filter.language','');
		
		$plugins = $plugins_model->getItems();

		$plugins_model->setState( 'filter.folder',$plugin_state->{'filter.folder'} );
		$plugins_model->setState( 'filter.search',$plugin_state->{'filter.search'} );
		$plugins_model->setState( 'filter.access',$plugin_state->{'filter.access'} );
		$plugins_model->setState( 'filter.enabled',$plugin_state->{'filter.enabled'} );
		$plugins_model->setState( 'filter.language',$plugin_state->{'filter.language'} );
		
		return $plugins;
	}

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
		
		$plugins = $this->getPluginList();

		$this->assignRef( 'installed_plugins', $plugins );

		parent::display($tpl);
	}
}