<?php
/*------------------------------------------------------------------------
# Groups View for RaidPlanner Component
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

JHtml::_('behavior.modal', 'a.modal');

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewGroups extends JViewLegacy
{

    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER_GROUPS' ), 'generic.png' );

        // Get data from the model
        $groups =& $this->get( 'Data');

		if ($groups) {
			JToolBarHelper::makeDefault('setDefault');
			JToolBarHelper::deleteList();
			JToolBarHelper::editListX();
			JToolBarHelper::addNewX();
		}

		RaidPlannerHelper::showToolbarButtons();
 
        $this->assignRef( 'groups', $groups );
 
        parent::display($tpl);
    }
}
