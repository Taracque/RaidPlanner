<?php
/*------------------------------------------------------------------------
# Guilds View for RaidPlanner Component
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

class RaidPlannerViewGuilds extends JViewLegacy
{

    function display($tpl = null)
    {
    
        JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER_GUILDS' ), 'generic.png' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();

		RaidPlannerHelper::showToolbarButtons();

        // Get data from the model
        $guilds =& $this->get( 'Data');
 		$pagination =& $this->get('Pagination');

        $this->assignRef( 'guilds', $guilds );
 		$this->assignRef( 'pagination', $pagination);
 		
		 /* Call the state object */
		$state =& $this->get( 'state' );
		
		/* Get the values from the state object that were inserted in the model's construct function */
		$lists['search'] = $state->get( 'filter_guild_search' );;
		$lists['order']     = $state->get( 'filter_order' );
		$lists['order_Dir'] = $state->get( 'filter_order_Dir' );
		
		$this->assignRef( 'lists', $lists );

        parent::display($tpl);
    }
}
