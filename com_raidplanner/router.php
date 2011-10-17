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

/*
	URL format for SEO friendly URLs:
	0:view / 1:task / 2:id / 3:Itemid (menu id) / raidplanner internals
	internals values and titles are separated by a - character

*/

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
	};
	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset( $query['id'] );
	};
	if(isset($query['Itemid'])) {
		$segments[] = $query['Itemid'];
		unset( $query['Itemid'] );
	};
	if(isset($query['modalevent'])) {
		$segments[] = $query['modalevent'];
		unset( $query['modalevent'] );
	};
	if(isset($query['month'])) {
		$segments[] = 'month-' . $query['month'];
		unset( $query['month'] );
	};

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

	switch($segments[0])
	{
		case 'edit':
			$vars['view'] = 'edit';
			$vars['task'] = $segments[1];
			$vars['id'] = intval(@$segments[2]);
			$vars['Itemid'] = intval(@$segments[3]);
		break;
		case 'event':
			$vars['view'] = 'event';
			$vars['task'] = $segments[1];
			$vars['id'] = intval(@$segments[2]);
			$vars['Itemid'] = intval(@$segments[3]);
		break;
		case 'calendar':
			$vars['view'] = 'calendar';
			$vars['task'] = $segments[1];
			$vars['id'] = intval(@$segments[2]);
			$vars['Itemid'] = intval(@$segments[3]);
			$vars['modalevent'] = intval(@$segments[4]);
			$tmp = explode(":" , @$segments[5]);
			$vars['month'] = $tmp[1];
		break;
		default:
			if ( (is_numeric ( $segments[0] ) ) && ( intval( $segments[0] ) > 0 ) )
			{
				/* only Itemid is given */
				$vars['Itemid'] = intval(@$segments[3]);
			}
		
	}
	return $vars;
}