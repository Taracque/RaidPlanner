<?php
/**
 * Event View class for the RaidPlanner Component
 *
 * @package    RaidPlanner
 * @subpackage Components
 * @license    GNU/GPL
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
class RaidPlannerViewEvent extends JView
{
    function display($tpl = null)
    {
		$model = &$this->getModel();

		if ( $model->getPermission('view_raids') != 1 ) {
			$app = JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_raidplanner&view=default' ) );
		} else {
			$attendants = $model->getAttendants( JRequest::getVar('id') );
			$event = $model->getEvent( JRequest::getVar('id') );
	
			$this->assignRef( 'event', $event );
			$this->assignRef( 'attendants' , $attendants );
			$this->assignRef( 'confirmed_roles' , $model->getConfirmedRoles($attendants) );
			$this->assignRef( 'roles' , $model->getRoles() );
			$this->assignRef( 'characters' , $model->getCharacters(@$event->minimum_level,@$event->maximum_level,@$event->minimum_rank) );
			$this->assignRef( 'selfstatus' , $model->getUserStatus($attendants) );
			$this->assignRef( 'isOfficer' , $model->userIsOfficer() );
			$this->assignRef( 'canSignup' , $model->userCanSignUp( JRequest::getVar('id') ) );

			parent::display($tpl);
		}
    }
}
