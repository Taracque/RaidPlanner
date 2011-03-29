<?php
/**
 * @package    RaidPlanner
 * @subpackage Components
 * components/com_raidplanner/raidplanner.php
 * @license    GNU/GPL
*/
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// add css and js

JHTML::stylesheet('raidplanner.css', 'components/com_raidplanner/assets/');
JHTML::script('raidplanner.js', 'components/com_raidplanner/assets/');

// Require the base controller
 
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
 
// Create the controller
$classname    = 'RaidPlannerController'.$controller;
$controller   = new $classname( );
 

// Perform the Request task
$controller->execute( JRequest::getWord( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();
