<?php
/*------------------------------------------------------------------------
# Guild View for RaidPlanner Component
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

class RaidPlannerViewGuild extends JView
{

	function display($tpl = null)
	{
		//get the guild
		$guild	=& $this->get('Data');
		$isNew	= ($guild->guild_id < 1);

		$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_RAIDPLANNER_GUILD' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}

		$model =& $this->getModel();
		
		$plugins = RaidPlannerHelper::getSyncPlugins();
		
		$plugin_params = array();
		if ($guild->sync_plugin != '') {
			$plugin_params = RaidPlannerHelper::getSyncPluginParams( $guild->sync_plugin );
			
			$plugin = RaidPlannerHelper::getGuildPlugin( $guild->guild_id );
			$this->assignRef( 'do_sync', $plugin->provide_sync );
		}

		$this->assignRef( 'sync_plugins', $plugins );
		$this->assignRef( 'sync_params', $plugin_params );

		$this->assignRef( 'guild', $guild );

		parent::display($tpl);
	}
}