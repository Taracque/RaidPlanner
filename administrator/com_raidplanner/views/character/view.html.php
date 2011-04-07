<?php
/**
 * Character View for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class RaidPlannerViewCharacter extends JView
{

	function display($tpl = null)
	{
		//get the character
		$char	=& $this->get('Data');
		$isNew	= ($char->character_id < 1);

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

		$this->assignRef('character', $char);
		$this->assignRef('classes', $model->getClasses() );
		$this->assignRef('genders', $model->getGenders() );
		$this->assignRef('races', $model->getRaces() );

		parent::display($tpl);
	}
}