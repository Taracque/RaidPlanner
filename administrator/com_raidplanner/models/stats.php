<?php
/*------------------------------------------------------------------------
# Stats Model for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 
class RaidPlannerModelStats extends JModel
{

	function __construct()
	{
		parent::__construct();
		
		$option = JRequest::getCmd('option');
		$app = &JFactory::getApplication();
		
		$filter_min_time	= $app->getUserStateFromRequest( $option.'filter_min_time',	'min_time', null,	'date');
		$filter_max_time	= $app->getUserStateFromRequest( $option.'filter_max_time',	'max_time', null,	'date');

		$this->setState('filter_min_time', $filter_min_time);
		$this->setState('filter_max_time', $filter_max_time);
	}

}
