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

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewEvent extends JViewLegacy
{
    function display($tpl = null)
    {
		$model = $this->getModel();

		$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$paramsObj->merge( $menuparams );
		}
		$params = array(
			'show_history'		=> $paramsObj->get('show_history', 0),
			'macro_format'		=> $paramsObj->get('macro_format', ''),
			'allow_rating'		=> $paramsObj->get('allow_rating', 0),
			'multi_raid_signup'	=> $paramsObj->get('multi_raid_signup', 0)
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
				foreach($attendants as $att_key => $att_char) {
					// remove that from all_characters
					foreach ($all_characters as $all_key => $all_char) {
						if ($att_char->character_id == $all_char->character_id) {
							unset($all_characters[$all_key]);
							break;
						}
					}
				}
				if ($params['macro_format'] != '')
				{
					$macro = '';
					foreach ($attendants as $attendant)
					{
						if ($attendant->confirmed==1)
						{
							$macro .= str_replace ( '%c', $attendant->char_name , $params['macro_format'] ) . "\n";
						}
					}
				}
			} else {
				$all_characters = array();
			}

			RaidPlannerHelper::loadGuildCSS( @$event->guild_id );
			$guild_plugin = RaidPlannerHelper::getGuildPlugin( @$event->guild_id );

			if ( $params['multi_raid_signup'] > 0 ) {
				$upcoming = $model->getUpcomingEvents( $event->start_time, ($params['multi_raid_signup'] == 2) );
				$this->assignRef( 'upcoming', $upcoming );
			}

			$this->assignRef( 'guild_plugin', $guild_plugin );
			$this->assignRef( 'params', $params);
			$this->assignRef( 'macro', $macro);
			$this->assignRef( 'event', $event );
			$this->assignRef( 'attendants' , $attendants );
			$this->assign( 'confirmed_roles' , $model->getConfirmedRoles( $attendants ) );
			$this->assign( 'xml_history', $model->getHistory( $event->raid_id, true ) );
			$this->assign( 'roles' , $model->getRoles() );
			$this->assignRef( 'characters' , $characters );
			$this->assignRef( 'all_characters' , $all_characters );
			$this->assign( 'selfstatus' , $model->getUserStatus( $event->raid_id ) );
			$this->assignRef( 'isOfficer' , $isOfficer );
			$this->assign( 'canSignup' , $model->userCanSignUp( $event->raid_id ) );
			$this->assign( 'onvacation' , $model->usersOnVacation( $event->start_time ) );
			$this->assign( 'missingSignups' , $model->getMissingSignUps( $event->raid_id ) );
			$this->assignRef( 'finished' , $event->finished );
			$this->assign( 'canRate' , $model->userCanRate( $event->raid_id ) );
			$this->assign( 'ratings' , $model->getRates( $event->raid_id ) ); 
			
			parent::display($tpl);
		}
    }
}
