<?php
/*------------------------------------------------------------------------
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// register the helper
JLoader::register('RaidPlannerHelper', JPATH_ADMINISTRATOR . '/components/com_raidplanner/helper.php' );

// add css and js
JHTML::_('behavior.framework');

if ( RaidPlannerHelper::getJVersion() == '1.5') {
	JFactory::getLanguage()->load('com_raidplanner.j15', JPATH_ADMINISTRATOR);
}

$paramsObj = &JComponentHelper::getParams( 'com_raidplanner' );
if ($paramsObj->get('load_css', '1')) {
	JHTML::stylesheet('raidplanner.css', 'components/com_raidplanner/assets/');
}
JHTML::script('raidplanner.js', 'components/com_raidplanner/assets/');

// Require the base controller
 
require_once( JPATH_COMPONENT . '/controller.php' );

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';
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
