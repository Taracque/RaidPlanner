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

JHTML::_('behavior.tooltip');

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewCalendar extends JViewLegacy
{
    function display($tpl = null)
    {
		$eventmodel = $this->getModel('event');
		$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
		$menuitemid = JFactory::getApplication()->getMenu()->getActive()->id;
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$paramsObj->merge( $menuparams );
		}
		$params = array(
			'first_dow'			=> $paramsObj->get('first_dow', 0),
			'popup_width'		=> $paramsObj->get('popup_width', 750),
			'popup_height'		=> $paramsObj->get('popup_height', 500),
			'show_history'		=> $paramsObj->get('show_history', 0),
			'use_modal'			=> $paramsObj->get('use_modal', 1),
			'show_tooltips'		=> $paramsObj->get('show_tooltips', 1),
			'show_icons'		=> $paramsObj->get('show_icons', 1)
		);
		$is_mobile = RaidPlannerHelper::detectMobile();
		if ((!$is_mobile) && ($params['use_modal']==1))
		{
			JHtml::_('behavior.modal', 'a.rpevent', array('size' => array('x' => $params['popup_width'],'y' => $params['popup_height'])));
		}

		if (RaidPlannerHelper::getPermission('viewcalendar') != 1) {
			// redirect to the index page
			$app = JFactory::getApplication();
			$msg = JText::_('JGLOBAL_AUTH_ACCESS_DENIED');
			$app->redirect( JRoute::_(JURI::root().'index.php'), $msg);
		}

		$user =JFactory::getUser();
		
		$canView = (RaidPlannerHelper::getPermission('viewraid') == 1);
 		$this->assign( 'isOfficer', $eventmodel->userIsOfficer() );
		$this->assignRef( 'canView', $canView );
		$model = $this->getModel();
		
		$month = JRequest::getVar('month', null);
		if ($month=='') {
			$modalevent_id = JRequest::getInt('modalevent', 0);
			if ( $modalevent_id > 0 )
			{
				$month = $eventmodel->getMonth( $modalevent_id );
			} else {
				$month = date("Y-m");
			}
		}
		$monthparts = explode("-",$month);
		$display_year = intval($monthparts[0]);
		$display_month = intval($monthparts[1]);
		if ( ($display_year < 1900) || ($display_year > 2199) )
		{
			$display_year = date("Y");
		}
		if ( ($display_month < 1) || ($display_month > 12) )
		{
			$display_month = date("m");
		}
		
		$prevmonth = date("Y-m",mktime(0,0,0,$display_month-1,1,$display_year));
		$nextmonth = date("Y-m",mktime(0,0,0,$display_month+1,1,$display_year));
		$lastday = date("t",mktime(0,0,0,$display_month,1,$display_year));
		$year = date("Y",mktime(0,0,0,$display_month,1,$display_year));
		$monthonly = date("m",mktime(0,0,0,$display_month,1,$display_year));
		$shift = date("w",mktime(0,0,0,$display_month,1,$display_year));

		if ($user->getParam('calendar_secret', '') != '') {
			$calendar_mode = 'subscribe';
			$this->assignRef( 'user_id', $user->id );
			$this->assign( 'calendar_secret', $user->getParam('calendar_secret', '') );
		} else {
			$calendar_mode = 'download';
		}
		$this->assignRef( 'mobile_browser', $is_mobile );
		$this->assign( 'invitations', RaidPlannerHelper::checkInvitations() );
		$this->assignRef( 'menuitemid', $menuitemid );
		$this->assignRef( 'calendar_mode', $calendar_mode );
		$this->assignRef( 'prevmonth', $prevmonth );
		$this->assignRef( 'nextmonth', $nextmonth );
		$this->assignRef( 'lastday', $lastday);
		$this->assignRef( 'year', $year);
		$this->assignRef( 'monthonly', $monthonly);
		$this->assignRef( 'shift', $shift);
		$this->assignRef( 'params', $params);		
        $this->assign( 'events', $model->getEvents( $display_year . "-" . $display_month . "-01", null ) );
        $this->assignRef( 'eventmodel', $eventmodel );
	
        parent::display($tpl);
    }
    
}