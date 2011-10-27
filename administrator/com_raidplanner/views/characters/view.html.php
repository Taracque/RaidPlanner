<?php
/*------------------------------------------------------------------------
# Characters View for RaidPlanner Component
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

class RaidPlannerViewCharacters extends JView
{

    function display($tpl = null)
    {
    
        JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER_CHARACTERS' ), 'generic.png' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();

		RaidPlannerHelper::showToolbarButtons();

        // Get data from the model
        $characters =& $this->get( 'Data');
 		$pagination =& $this->get('Pagination');

        $this->assignRef( 'characters', $characters );
 		$this->assignRef( 'pagination', $pagination);
 		
		 /* Call the state object */
		$state =& $this->get( 'state' );
		
		/* Get the values from the state object that were inserted in the model's construct function */
		$lists['search'] = $state->get( 'filter_char_search' );;
		$lists['level_min'] = $state->get( 'filter_char_level_min' );;
		$lists['level_max'] = $state->get( 'filter_char_level_max' );;
		$lists['order_Dir'] = $state->get( 'filter_order_Dir' );
		$lists['order']     = $state->get( 'filter_order' );
		
		$this->assignRef( 'lists', $lists );

        parent::display($tpl);
    }
}
