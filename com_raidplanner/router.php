<?php

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
	if(isset($query['modalevent'])) {
		$segments[] = $query['modalevent'];
		unset( $query['modalevent'] );
	};
	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset( $query['id'] );
	};
/*
	if(isset($query['month'])) {
		$segments[] = 'month';
		$segments[] = $query['month'];
		unset( $query['month'] );
	};
*/
	return $segments;
}

function RaidPlannerParseRoute( $segments )
{
	$vars = array();
	switch($segments[0])
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
			$vars['modalevent'] = intval(@$segments[2]);
		break;
	}
	return $vars;
}