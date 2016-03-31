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

JHTML::stylesheet('com_raidplanner/raidplanner_admin.css', false, true, false);

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewRaidPlanner extends JViewLegacy
{
	private function getPluginList()
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_installer/models');
		$installModel = JModelLegacy::getInstance('Manage', 'InstallerModel' , array( 'filter.type' => 'filter.plugin', 'folder' => 'raidplanner') );
		$plugin_state = $installModel->getState();

		$installModel->setState('filter.type','plugin');
		$installModel->setState('filter.group','raidplanner');
		$installModel->setState('filter.folder','raidplanner');
		$installModel->setState('filter.search','');
		$installModel->setState('filter.access','0');
		$installModel->setState('filter.enabled','');
		$installModel->setState('filter.language','');
		
		$plugins = $installModel->getItems();

		$installModel->setState( 'filter.type',$plugin_state->{'filter.type'} );
		$installModel->setState( 'filter.group',$plugin_state->{'filter.group'} );
		$installModel->setState( 'filter.folder',$plugin_state->{'filter.folder'} );
		$installModel->setState( 'filter.search',$plugin_state->{'filter.search'} );
		$installModel->setState( 'filter.access',$plugin_state->{'filter.access'} );
		$installModel->setState( 'filter.enabled',$plugin_state->{'filter.enabled'} );
		$installModel->setState( 'filter.language',$plugin_state->{'filter.language'} );

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