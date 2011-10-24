<?php
/*------------------------------------------------------------------------
# Router for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Function to build a RaidPlanner URL route.
 *
 * @param	array	The array of query string values for which to build a route.
 * @return	array	The URL route with segments represented as an array.
 */

function RaidPlannerBuildRoute( &$query )
{
	$segments = array();
	
	if(isset($query['view'])) {
		$segments[] = $query['view'];
		unset( $query['view'] );
	}
	if(isset($query['task'])) {
		$segments[] = $query['task'];
		unset( $query['task'] );
	}
	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset( $query['id'] );
	}
	if(isset($query['modalevent']))
	{
		if ($query['modalevent']!='') {
			$segments[] = 'modalevent-' . $query['modalevent'];
		}
		unset( $query['modalevent'] );
	}
	if(isset($query['month']))
	{
		if($query['month']!='')
		{
			$segments[] = 'month-' . $query['month'];
		}
		unset( $query['month'] );
	}
	if(isset($query['tmpl']))
	{
		if($query['tmpl']!='')
		{
			$segments[] = 'tmpl-' . $query['tmpl'];
		}
		unset( $query['tmpl'] );
	}

	return $segments;
}

/**
 * Function to parse a RaidPlanner URL route.
 *
 * @param	array	The URL route with segments represented as an array.
 * @return	array	The array of variables to set in the request.
 */
function RaidPlannerParseRoute( $segments )
{
	$vars = array();

	// get month and modalevent (if exists)
	foreach ($segments as $segment)
	{
		if (strpos($segment, ":") !== false)
		{
			$tmp = explode(":" , @$segment);
			$vars[$tmp[0]] = (@$tmp[1]);
		}
	}

	//Handle View and Identifier
	switch( $segments[0] )
	{
		case 'edit':
			$vars['view'] = 'edit';
			$vars['task'] = $segments[1];
			$vars['id'] = intval(@$segments[2]);
		break;
		case 'event':
			$vars['view'] = 'event';
			$vars['task'] = $segments[1];
			$vars['id'] = intval(@$segments[2]);
		break;
		case 'calendar':
			$vars['view'] = 'calendar';
			$vars['task'] = $segments[1];
			$vars['id'] = intval(@$segments[2]);
		break;
		default:
			$app =& JFactory::getApplication();
			$menu =& $app->getMenu();
			$item =& $menu->getActive();
			if ($item)
			{
				// no view defined, needs to figure out
				$vars['view'] = $item->query('view');
			}
	}
	return $vars;
}