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

class RaidPlannerViewRaid extends JView
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
		JToolBarHelper::title(   JText::_( 'COM_RAIDPLANNER_RAID' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}
		$groups = $this->getGroupList();
		$guilds = $this->getGuildList();

		$users = $this->getUsers();

		$this->assignRef('users', $users );
		$this->assignRef('guilds', $guilds );
		$this->assignRef('groups', $groups );
		$this->assignRef('icons', $this->getIcons() );
		$this->assignRef('raid', $raid);

		parent::display($tpl);
	}
	
	function getIcons()
	{
		$path = JPATH_BASE . DS . '..' . DS . 'images' . DS . 'raidplanner' . DS . 'raid_icons';
		
		$dhandle = opendir($path);
		$files = array();
		
		if ($dhandle) {
			while (false !== ($fname = readdir($dhandle))) {
				// if the file is not this file, and does not start with a '.' or '..',
				// then store it for later display
				if (
					($fname != '.') && 
					($fname != '..') &&
					($fname != 'index.html') &&
					($fname != basename($_SERVER['PHP_SELF']))
				) {
					// store the filename
					if (!is_dir( $path . DS . $fname )) {
						$info = pathinfo( $path . DS . $fname );
						$files[$fname] = ucwords(str_replace("_"," ",basename($fname,'.'.$info['extension'])));
					}
				}
			}
		   // close the directory
		   closedir($dhandle);
		}
		return $files;
	}
	
	function getUsers()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT id,username FROM #__users ORDER BY username ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('id');
	}

	function getGuildList()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT guild_id,guild_name FROM #__raidplanner_guild ORDER BY guild_name ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('guild_id');
	}

	function getGroupList()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT group_id,group_name FROM #__raidplanner_groups ORDER BY group_name ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('group_id');
	}
}