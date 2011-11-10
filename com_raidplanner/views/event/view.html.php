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

		$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$paramsObj->merge( $menuparams );
		}
		$params = array(
			'show_history'	=> $paramsObj->get('show_history', 0),
			'macro_format'	=> $paramsObj->get('macro_format', '')
		);

		if ( RaidPlannerHelper::getPermission('view_raids') != 1 ) {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::_('index.php?option=com_raidplanner&view=default' ) );
		} else {
			$macro = false;
			$raid_id = JRequest::getInt('id');
			$attendants = $model->getAttendants( $raid_id );
			$event = $model->getEvent( $raid_id );
			$characters = $model->getCharacters(@$event->minimum_level,@$event->maximum_level,@$event->minimum_rank,@$event->guild_id);
			$isOfficer = $model->userIsOfficer( $event->raid_id );
			if ($isOfficer) {
				$all_characters = $model->getCharacters(@$event->minimum_level,@$event->maximum_level,@$event->minimum_rank,@$event->guild_id,true);
				foreach($all_characters as $all_key => $all_char) {
					if( ($all_char->profile_id > 0) && (isset($attendants[$all_char->profile_id])) ) {
						unset($all_characters[$all_key]);
					}
				}
				if ($params['macro_format'] != '')
				{
					$macro = '';
					foreach ($characters as $character)
					{
						$macro .= str_replace ( '%c', $character , $params['macro_format'] ) ."\n";
					}
				}
			} else {
				$all_characters = array();
			}

			$this->assignRef( 'params', $params);
			$this->assignRef( 'macro', $macro);
			$this->assignRef( 'event', $event );
			$this->assignRef( 'attendants' , $attendants );
			$this->assignRef( 'confirmed_roles' , $model->getConfirmedRoles( $attendants ) );
			$this->assignRef( 'xml_history', $model->getHistory( $event->raid_id, true ) );
			$this->assignRef( 'roles' , $model->getRoles() );
			$this->assignRef( 'characters' , $characters );
			$this->assignRef( 'all_characters' , $all_characters );
			$this->assignRef( 'selfstatus' , $model->getUserStatus($attendants) );
			$this->assignRef( 'isOfficer' , $isOfficer );
			$this->assignRef( 'canSignup' , $model->userCanSignUp( $event->raid_id ) );
			$this->assignRef( 'onvacation' , $model->usersOnVacation( $event->start_time ) );

			parent::display($tpl);
		}
    }
}
