<?php
/**
 * Raids View for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license        GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.view' );

class RaidPlannerViewRaids extends JView
{

    function display($tpl = null)
    {
    
        JToolBarHelper::title( JText::_( 'Raids' ), 'generic.png' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editListX();
        JToolBarHelper::addNewX();

		$view = JRequest::getVar('view');

		JSubMenuHelper::addEntry(JText::_('Raids'), 'index.php?option=com_raidplanner&view=raids', ($view == 'raids'));
		JSubMenuHelper::addEntry(JText::_('Characters'), 'index.php?option=com_raidplanner&view=characters', ($view == 'characters'));
		JSubMenuHelper::addEntry(JText::_('Groups'), 'index.php?option=com_raidplanner&view=groups', ($view == 'groups'));
		JSubMenuHelper::addEntry(JText::_('Roles'), 'index.php?option=com_raidplanner&view=roles', ($view == 'roles'));
		JSubMenuHelper::addEntry(JText::_('Classes'), 'index.php?option=com_raidplanner&view=classes', ($view == 'classes'));

        // Get data from the model
        $raids =& $this->get( 'Data');
		$pagination =& $this->get('Pagination');

        $this->assignRef( 'raids', $raids );
		$this->assignRef( 'pagination', $pagination);

		 /* Call the state object */
		$state =& $this->get( 'state' );
		
		/* Get the values from the state object that were inserted in the model's construct function */
		$lists['search'] = $state->get( 'filter_raid_search' );;
		$lists['start_time_min'] = $state->get( 'filter_raid_start_time_min' );;
		$lists['start_time_max'] = $state->get( 'filter_raid_start_time_max' );;
		$lists['order_Dir'] = $state->get( 'filter_raid_order_Dir' );
		$lists['order']     = $state->get( 'filter_raid_order' );
		
		$this->assignRef( 'lists', $lists );

        parent::display($tpl);
    }
}
