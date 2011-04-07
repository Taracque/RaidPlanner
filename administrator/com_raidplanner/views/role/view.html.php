<?php
/**
 * Role View for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class RaidPlannerViewRole extends JView
{

	function display($tpl = null)
	{
		//get the character
		$role	=& $this->get('Data');
		$isNew	= ($role->role_id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Character' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$model =& $this->getModel();

		$this->assignRef('role', $role);
		$this->assignRef('icons', $this->getIcons() );

		parent::display($tpl);
	}

	function getIcons()
	{
		$path = JPATH_BASE . DS . '..' . DS . 'images' . DS . 'raidplanner' . DS . 'role_icons';
		
		$dhandle = opendir($path);
		$files = array();
		
		if ($dhandle) {
			while (false !== ($fname = readdir($dhandle))) {
				// if the file is not this file, and does not start with a '.' or '..',
				// then store it for later display
				if (($fname != '.') && ($fname != '..') &&
				($fname != basename($_SERVER['PHP_SELF']))) {
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
}