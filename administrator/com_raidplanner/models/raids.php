<?php
/**
 * Raids Model for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license        GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
class RaidPlannerModelRaids extends JModel
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
		$app = &JFactory::getApplication();
		
		$filter_raid_order     = $app->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'start_time', 'cmd' );
		$filter_raid_order_Dir = $app->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		$filter_raid_search		= $app->getUserStateFromRequest( $option.'filter_raid_search',	'search', '',	'string');
		$filter_raid_start_time_min	= $app->getUserStateFromRequest( $option.'filter_raid_start_time_min',	'start_time_min', null,	'date');
		$filter_raid_start_time_max	= $app->getUserStateFromRequest( $option.'filter_raid_start_time_max',	'start_time_max', null,	'date');

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
	}

	function _buildContentOrderBy()
	{
		$orderby = '';
		$filter_order     = $this->getState('filter_order');
		$filter_order_Dir = $this->getState('filter_order_Dir');
		
		/* Error handling is never a bad thing*/
		if (
			(!empty($filter_order) && !empty($filter_order_Dir) ) &&
			(in_array($filter_order, array('start_time', 'location', 'minimum_level', 'maximum_level', 'minimum_rank', 'is_template') ) ) &&
			(in_array($filter_order_Dir, array('asc', 'desc') ) )
		) {
			$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		}
		return $orderby;
	}

	function _buildQueryWhere()
	{
		$db	=& JFactory::getDBO();
		
		$filter_raid_start_time_min = $this->getState('filter_raid_start_time_min');
		$filter_raid_start_time_max = $this->getState('filter_raid_start_time_max');
		$filter_raid_search = $this->getState('filter_raid_search');

		$where = '';
		
		$where_arr = array();
		if ($filter_raid_start_time_min!='') {
			$where_arr[] = "start_time >= ".$db->Quote($filter_raid_start_time_min);
		}
		if ($filter_raid_start_time_max!='') {
			$where_arr[] = "start_time <= DATE_ADD(".$db->Quote($filter_raid_start_time_max).", INTERVAL 1 DAY)";
		}
		if ($filter_raid_search!='') {
			$where_arr[] = "(location LIKE '%".$db->getEscaped($filter_raid_search)."%' OR description LIKE '%".$db->getEscaped($filter_raid_search)."%')";
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
        $query = ' SELECT * '
            . ' FROM #__raidplanner_raid ' . $this->_buildQueryWhere();
        
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
