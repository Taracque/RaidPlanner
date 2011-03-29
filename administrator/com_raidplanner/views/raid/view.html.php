<?php
/**
 * Hello View for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.application.component.controller' );

/**
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
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

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Raid' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('icons', $this->getIcons() );
		$this->assignRef('raid', $raid);

		parent::display($tpl);
	}
	
	function getIcons()
	{
		$path = JPATH_BASE . DS . '..' . DS . 'media' . DS . 'com_raidplanner' . DS . 'icons';
		
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