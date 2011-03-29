<?php
/**
 * @package    RaidPlanner
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1
 * @license    GNU/GPL
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.controller' );

JHTML::_('behavior.modal', 'a.modal', array('size' => array('x' => 750,'y' => 500)));

/**
 * HTML View class for the RaidPlanner Component
 *
 * @package    RaidPlanner
 */
 
class RaidPlannerViewCalendar extends JView
{
    function display($tpl = null)
    {
		$eventmodel = &$this->getModel('event');
		
		$eventmodel->syncProfile();
		$canView = ($eventmodel->getPermission('view_raids') == 1);
 		$this->assignRef( 'isOfficer', $eventmodel->userIsOfficer() );
		$this->assignRef( 'canView', $canView );
		$model = &$this->getModel();
		
        $this->assignRef( 'events', $model->getEvents('own') );

        parent::display($tpl);
        die();
    }
    
}