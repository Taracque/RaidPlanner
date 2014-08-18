<?php
/*------------------------------------------------------------------------
# Raids Model for RaidPlanner Component
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

/* create JModelLegacy if not exist */
if (!class_exists('JModelLegacy')) {
	class JModelLegacy extends JModel {}
}

class RaidPlannerModelRaids extends JModelLegacy
{
    /**
     * Data array
     *
     * @var array
     */
    var $_data;
	var $_total = null;
	var $_pagination = null;

	function __construct()
	{
		parent::__construct();
		
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		
		$filter_raid_order     = $app->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'r.start_time', 'cmd' );
		$filter_raid_order_Dir = $app->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		$filter_raid_search		= $app->getUserStateFromRequest( $option.'filter_raid_search',	'search', '',	'string');
		$filter_raid_start_time_min	= $app->getUserStateFromRequest( $option.'filter_raid_start_time_min',	'start_time_min', null,	'date');
		$filter_raid_start_time_max	= $app->getUserStateFromRequest( $option.'filter_raid_start_time_max',	'start_time_max', null,	'date');
		$filter_guild_filter		= $app->getUserStateFromRequest( $option.'filter_guild_filter',	'guild_filter', null,	'int');

		// Get pagination request variables
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter_order', $filter_raid_order);
		$this->setState('filter_order_Dir', $filter_raid_order_Dir);
		$this->setState('filter_raid_search', $filter_raid_search);
		$this->setState('filter_raid_start_time_min', $filter_raid_start_time_min);
		$this->setState('filter_raid_start_time_max', $filter_raid_start_time_max);
		$this->setState('filter_guild_filter', $filter_guild_filter);
	}

	function _buildContentOrderBy()
	{
		$orderby = '';
		$filter_order     = $this->getState('filter_order');
		$filter_order_Dir = $this->getState('filter_order_Dir');
		
		/* Error handling is never a bad thing*/
		if (
			(!empty($filter_order) && !empty($filter_order_Dir) ) &&
			(in_array($filter_order, array('r.start_time', 'r.location', 'r.minimum_level', 'r.maximum_level', 'r.minimum_rank', 'r.is_template', 'g.title') ) ) &&
			(in_array($filter_order_Dir, array('asc', 'desc') ) )
		) {
			$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		} else { // default order is now descending raid start time
			$orderby = ' ORDER BY r.start_time desc';
		}
		return $orderby;
	}

	function _buildQueryWhere()
	{
		$db	= JFactory::getDBO();
		
		$filter_raid_start_time_min = $this->getState('filter_raid_start_time_min');
		$filter_raid_start_time_max = $this->getState('filter_raid_start_time_max');
		$filter_raid_search = $this->getState('filter_raid_search');
		$filter_guild_filter = $this->getState('filter_guild_filter');

		$where = '';
		
		$where_arr = array();
		if ($filter_raid_start_time_min!='') {
			$where_arr[] = "r.start_time >= ".$db->Quote($filter_raid_start_time_min);
		}
		if ($filter_raid_start_time_max!='') {
			$where_arr[] = "r.start_time <= DATE_ADD(".$db->Quote($filter_raid_start_time_max).", INTERVAL 1 DAY)";
		}
		if ($filter_raid_search!='') {
			$where_arr[] = "(r.location LIKE '%".$db->escape($filter_raid_search)."%' OR r.description LIKE '%".$db->escape($filter_raid_search)."%')";
		}
		if (intval($filter_guild_filter)>0) {
			$where_arr[] = "r.guild_id = " . intval($filter_guild_filter);
		}
		if (!empty($where_arr)) {
			$where = " WHERE ".implode(" AND ",$where_arr);
		}
		
		return $where;
	}

    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = ' SELECT * ';

		$query .= ' ,g.title AS group_name';
		$query .= ' FROM #__raidplanner_raid AS r';
		$query .= ' LEFT JOIN #__usergroups AS g ON g.id = r.invited_group_id';
		$query .= ' LEFT JOIN #__raidplanner_guild AS gu ON gu.guild_id = r.guild_id '
            		. $this->_buildQueryWhere();
        
        return $query;
    }
 
    /**
     * Retrieves the data
     * @return array Array of objects containing the data from the database
     */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery(). $this->_buildContentOrderBy();
            $this->_data = $this->_getList( $query , $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_data;
    }
    
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);    
		}
		return $this->_total;
	}

	function getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

}
