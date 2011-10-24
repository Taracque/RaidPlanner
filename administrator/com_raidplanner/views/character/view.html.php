<?php
/*------------------------------------------------------------------------
# Character View for RaidPlanner Component
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

class RaidPlannerViewCharacter extends JView
{

	function display($tpl = null)
	{
		//get the character
		$char	=& $this->get('Data');
		$isNew	= ($char->character_id < 1);

		$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_RAIDPLANNER_CHARACTER' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}

		$model =& $this->getModel();

		$users = $this->getUsers();

		$this->assignRef('users', $users );
		$this->assignRef('character', $char);
		$this->assignRef('classes', $model->getClasses() );
		$this->assignRef('genders', $model->getGenders() );
		$this->assignRef('races', $model->getRaces() );
		$this->assignRef('guilds', $model->getGuilds() );

		parent::display($tpl);
	}

	function getUsers()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT id,username FROM #__users ORDER BY username ASC";
		$db->setQuery($query);
		$db->query();
		
		return $db->loadObjectList('id');
	}
}