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

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewCharacter extends JViewLegacy
{

	function display($tpl = null)
	{
		//get the character
		$char	=& $this->get('Data');
		$isNew	= ($char->character_id < 1);

		$text = $isNew ? JText::_( 'JTOOLBAR_NEW' ) : JText::_( 'JTOOLBAR_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_RAIDPLANNER_CHARACTER' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}

		$model =& $this->getModel();

		$this->assignRef('users', RaidPlannerHelper::getUsers() );
		$this->assignRef('character', $char);
		$this->assignRef('classes', RaidPlannerHelper::getClasses() );
		$this->assignRef('genders', RaidPlannerHelper::getGenders() );
		$this->assignRef('races', RaidPlannerHelper::getRaces() );
		$this->assignRef('guilds', RaidPlannerHelper::getGuilds() );

		parent::display($tpl);
	}

}