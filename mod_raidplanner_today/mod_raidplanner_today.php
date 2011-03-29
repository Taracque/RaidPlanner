<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// include the helper file
require_once(dirname(__FILE__).DS.'helper.php');


//get user ID
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();

$mainframe->route();
$user =& JFactory::getUser();
$user_id = ($user->id);


// get the parameters from the module's configuration
$raidshowDateFormat	= $params->get('raidshowDateFormat');
$raidshowDateLocal	= $params->get('raidshowDateLocal');
$raidshowNumber		= $params->get('raidshowNumber');
$raidshowReg 		= $params->get('raidshowReg',1);
$raidshowRole 		= $params->get('raidshowRole',1);
$raidshowRace 		= $params->get('raidshowRace',1);
$raidshowChar 		= $params->get('raidshowChar',1);
$raidshowDate		= $params->get('raidshowDate',1);


// get the items to display from the helper
$items = ModRaidPlannerTodayHelper::getItems($raidshowNumber, $user_id);

// include the template for display
require(JModuleHelper::getLayoutPath('mod_raidplanner_today'));
?>