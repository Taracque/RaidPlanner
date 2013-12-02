<?php
/*------------------------------------------------------------------------
# Role View for RaidPlanner Component
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
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewRole extends JViewLegacy
{

	function display($tpl = null)
	{
		//get the character
		$role	=& $this->get('Data');
		$isNew	= ($role->role_id < 1);

		$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_RAIDPLANNER_ROLE' ).': ' . $text.'' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}

		$model =& $this->getModel();

		$this->assignRef('role', $role);
		$this->assignRef('icons', $this->getIcons() );

		parent::display($tpl);
	}

	function getIcons()
	{
		$path = JPATH_SITE . '/media/com_raidplanner/role_icons';
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