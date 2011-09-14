<?php
/*------------------------------------------------------------------------
# Event View for RaidPlanner Component
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
 
class RaidPlannerViewEvent extends JView
{
    function display($tpl = null)
    {
		$model = &$this->getModel();

		if ( $model->getPermission('view_raids') != 1 ) {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::_('index.php?option=com_raidplanner&view=default' ) );
		} else {
			$attendants = $model->getAttendants( JRequest::getVar('id') );
			$event = $model->getEvent( JRequest::getVar('id') );
			$characters = $model->getCharacters(@$event->minimum_level,@$event->maximum_level,@$event->minimum_rank);
			$isOfficer = $model->userIsOfficer();
			if ($isOfficer) {
				$all_characters = $model->getCharacters(@$event->minimum_level,@$event->maximum_level,@$event->minimum_rank,true);
				foreach($all_characters as $all_key => $all_char) {
					if(isset($attendants[$all_char->profile_id])) {
						unset($all_characters[$all_key]);
					}
				}
			} else {
				$all_characters = array();
			}
			$this->assignRef( 'event', $event );
			$this->assignRef( 'attendants' , $attendants );
			$this->assignRef( 'confirmed_roles' , $model->getConfirmedRoles($attendants) );
			$this->assignRef( 'xml_history', $model->getHistory( $event->raid_id,true ) );
			$this->assignRef( 'roles' , $model->getRoles() );
			$this->assignRef( 'characters' , $characters );
			$this->assignRef( 'all_characters' , $all_characters );
			$this->assignRef( 'selfstatus' , $model->getUserStatus($attendants) );
			$this->assignRef( 'isOfficer' , $isOfficer );
			$this->assignRef( 'canSignup' , $model->userCanSignUp( JRequest::getVar('id') ) );
			$this->assignRef( 'onvacation' , $model->usersOnVacation( $event->start_time ) );

			parent::display($tpl);
		}
    }
}
