<?php
/*------------------------------------------------------------------------
# Raid View for RaidPlanner Component
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
jimport( 'joomla.application.component.controller' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewRaid extends JViewLegacy
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the raid
		$raid		=& $this->get('Data');
		$isNew		= ($raid->raid_id < 1);

		$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_RAIDPLANNER_RAID' ).': ' . $text.'' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}

		$this->assignRef('users', RaidPlannerHelper::getUsers() );
		$this->assignRef('guilds', RaidPlannerHelper::getGuilds() );
		$this->assignRef('groups', RaidPlannerHelper::getGroups() );
		$this->assignRef('icons', $this->getIcons() );
		$this->assignRef('raid', $raid);

		parent::display($tpl);
	}
	
	function getIcons()
	{
		$path = JPATH_ROOT . '/media/com_raidplanner/raid_icons';
		$files = array();

		if (JFolder::exists( $path ) )
		{
			$allFiles = JFolder::files( $path );
			if ( !empty( $allFiles ) )
			{
				foreach ($allFiles as $fname)
				{
					if (
						($fname != '.') && 
						($fname != '..') &&
						($fname != 'index.html') &&
						($fname != basename($_SERVER['PHP_SELF']))
					) {
						$files[$fname] = ucwords(str_replace("_"," ",JFile::stripExt($fname)));
					}
				}
			}
		}
		return $files;
	}
	
}