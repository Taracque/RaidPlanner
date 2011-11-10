<?php
/*------------------------------------------------------------------------
# Raids View for RaidPlanner Component
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

class RaidPlannerViewRaids extends JView
{

    function display($tpl = null)
    {
    
        JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER_RAIDS' ), 'generic.png' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();

		RaidPlannerHelper::showToolbarButtons();

        // Get data from the model
        $raids =& $this->get( 'Data' );
		$pagination =& $this->get('Pagination');

        $this->assignRef( 'raids', $raids );
		$this->assignRef( 'pagination', $pagination);

		 /* Call the state object */
		$state =& $this->get( 'state' );
		
		/* Get the values from the state object that were inserted in the model's construct function */
		$lists['search'] = $state->get( 'filter_raid_search' );
		$lists['start_time_min'] = $state->get( 'filter_raid_start_time_min' );
		$lists['start_time_max'] = $state->get( 'filter_raid_start_time_max' );
		$lists['order_Dir'] = $state->get( 'filter_order_Dir' );
		$lists['order']     = $state->get( 'filter_order' );
		
		$this->assignRef( 'lists', $lists );

        parent::display($tpl);
    }
}
