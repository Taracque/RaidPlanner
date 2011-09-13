<?php
/*------------------------------------------------------------------------
# Calendar View for RaidPlanner Component
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

JHTML::_('behavior.modal', 'a.rpevent', array('size' => array('x' => 750,'y' => 500)));

class RaidPlannerViewCalendar extends JView
{
    function display($tpl = null)
    {
		$eventmodel = &$this->getModel('event');
		$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
		$params = array(
			'first_dow'	=> $paramsObj->get('first_dow', 0)
		);

		if ($eventmodel->getPermission('view_calendar') != 1) {
			// redirect to the index page
			$app = &JFactory::getApplication();
			$msg = JText::_('Access Forbidden');
			$app->redirect( JRoute::_(''), $msg);
		}
		
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
		
		$prevmonth = date("Y-m",mktime(0,0,0,$monthparts[1]-1,1,$monthparts[0]));
		$nextmonth = date("Y-m",mktime(0,0,0,$monthparts[1]+1,1,$monthparts[0]));
		$lastday = date("t",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$year = date("Y",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$monthonly = date("m",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));
		$shift = date("w",mktime(0,0,0,$monthparts[1],1,$monthparts[0]));

		$version = new JVersion();
		switch ($version->RELEASE) {
			case '1.5':
				$timeformat = '%H:%M';
			break;
			default:
			case '1.6':
				$timeformat = 'H:i';
			break;
		}

		$user =& JFactory::getUser();
		if ($user->getParam('calendar_secret', '') != '') {
			$calendar_mode = 'subscribe';
			$this->assignRef( 'user_id', $user->id );
			$this->assignRef( 'calendar_secret', $user->getParam('calendar_secret', '') );
		} else {
			$calendar_mode = 'download';
		}
		
		$this->assignRef( 'calendar_mode', $calendar_mode );
		$this->assignRef( 'prevmonth', $prevmonth );
		$this->assignRef( 'nextmonth', $nextmonth );
		$this->assignRef( 'lastday', $lastday);
		$this->assignRef( 'year', $year);
		$this->assignRef( 'monthonly', $monthonly);
		$this->assignRef( 'shift', $shift);
		$this->assignRef( 'params', $params);		
        $this->assignRef( 'events', $model->getEvents( $month . "-01" ) );
        $this->assignRef( 'eventmodel', $eventmodel );
		$this->assignRef( 'timeformat', $timeformat );

        parent::display($tpl);
    }
    
}