<?php
/*------------------------------------------------------------------------
# mod_raidplanner_events - RaidPlanner Events Module
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.modal', 'a.open-modal');

// register RaidPlanner Helper
JLoader::register('RaidPlannerHelper', JPATH_ADMINISTRATOR . '/components/com_raidplanner/helper.php' );

// include the helper file
require_once(dirname(__FILE__) . '/helper.php');

JFactory::getLanguage()->load('com_raidplanner', JPATH_SITE);

//get user ID
$user =JFactory::getUser();
$user_id = ($user->id);

// get the parameters from the module's configuration
$alertTimer				= $params->get('alertTimer', 24);
$showInvitationAlerts	= $params->get('showInvitationAlert',0);
$raidshowNumber			= $params->get('raidshowNumber',5);
$raidshowReg 			= $params->get('raidshowReg',1);
$raidshowRole 			= $params->get('raidshowRole',1);
$raidshowChar 			= $params->get('raidshowChar',1);
$raidshowAttendants		= $params->get('raidshowAttendants',0);

$itemid = RaidPlannerHelper::getRaidPlannerItemId('calendar');
if ($showInvitationAlerts>0)
{
	$invitationAlerts = RaidPlannerHelper::checkInvitations( intval($alertTimer) * 60, $user_id );
} else {
	$invitationAlerts = false;
}
// get the items to display from the helper
$items = modRaidPlannerEventsHelper::getItems($raidshowNumber, $user_id);

// include the template for display
require(JModuleHelper::getLayoutPath('mod_raidplanner_events'));
