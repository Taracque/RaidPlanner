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
		$user =& JFactory::getUser();
		if($user->id == 0) {
			// user not logged in
			$user =& JUser::getInstance( intval(@$_REQUEST['user']) );
			if ( ($user->getParam('calendar_secret', '') != '') && ($user->getParam('calendar_secret', '') == $_REQUEST['secret'] ) ) {
				// access validated
			} else {
				die('Invalid access!');
			}
		}
    	$tz = $user->getParam('timezone');
    	$tzname = timezone_name_from_abbr("", $tz * 3600, 0);
    	
		$eventmodel = &$this->getModel('event');
		$canView = ($eventmodel->getPermission('view_raids') == 1);
		$this->assignRef( 'canView', $canView );
		$this->assignRef( 'tzoffset', $tz );
		$this->assignRef( 'tzname', $tzname );
		
		$model = &$this->getModel();
		
        $this->assignRef( 'events', $model->getEvents('own', $user->id) );

        parent::display($tpl);
        die();
    }
    
}