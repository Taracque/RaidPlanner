<?php
/*------------------------------------------------------------------------
# Classes View for RaidPlanner Component
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

/* include the helper */
require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'helper.php' );

class RaidPlannerViewRaces extends JView
{

    function display($tpl = null)
    {
    
        JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER_CLASSES' ), 'generic.png' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();

		ComRaidPlannerHelper::showToolbarButtons();

        // Get data from the model
        $races =& $this->get( 'Data');

        $this->assignRef( 'races', $races );
 
        parent::display($tpl);
    }
}
