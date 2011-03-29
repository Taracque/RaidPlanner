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
		
		$month = JRequest::getVar('month', null);
		if ($month=='') {
			$month = date("Y-m");
		}
		$monthparts = explode("-",$month);
		$month = date("Y-m",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$prevmonth = date("Y-m",mktime(0,0,0,$monthparts[1]-1,1,$monthparts[0]));
		$nextmonth = date("Y-m",mktime(0,0,0,$monthparts[1]+1,1,$monthparts[0]));
		$lastday = date("t",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$year = date("Y",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$monthonly = date("m",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$shift = date("w",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));

		$this->assignRef( 'month', $month );
		$this->assignRef( 'prevmonth', $prevmonth );
		$this->assignRef( 'nextmonth', $nextmonth );
		$this->assignRef( 'lastday', $lastday);
		$this->assignRef( 'year', $year);
		$this->assignRef( 'monthonly', $monthonly);
		$this->assignRef( 'shift', $shift);
		
        $this->assignRef( 'events', $model->getEvents() );

        parent::display($tpl);
    }
    
}