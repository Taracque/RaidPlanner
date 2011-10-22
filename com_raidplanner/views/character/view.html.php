<?php
/*------------------------------------------------------------------------
# Edit View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.controller' );

class RaidPlannerViewCharacter extends JView
{
    function display($tpl = null)
    {
		$model = &$this->getModel();
		$canEdit = ( $model->canEdit() == 1 );

		$this->assignRef( 'character', $model->getCharacter( JRequest::getVar('character'), JRequest::getInt('character_id') ) );
		$this->assignRef( 'characters', $model->getCharacters( ) );		
		$this->assignRef( 'guilds', $model->getGuilds( ) );		
		$this->assignRef( 'genders', $model->getGenders( ) );		
		$this->assignRef( 'races', $model->getRaces( ) );		
		$this->assignRef( 'classes', $model->getClasses( ) );		
		$this->assignRef( 'canEdit', $canEdit );

		parent::display($tpl);
	}

}