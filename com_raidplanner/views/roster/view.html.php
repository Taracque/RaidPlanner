<?php
/*------------------------------------------------------------------------
# Roster View for RaidPlanner Component
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

class RaidPlannerViewRoster extends JView
{
    function display($tpl = null)
    {
		$model = &$this->getModel();

		$this->assignRef( 'characters', $model->getCharacters() );
		
        parent::display($tpl);
    }
    
}