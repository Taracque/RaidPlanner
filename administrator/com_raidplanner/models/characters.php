<?php
/*------------------------------------------------------------------------
# Characters Model for RaidPlanner Component
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

class RaidPlannerModelCharacters extends JModelLegacy
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

		$filter_char_order     = $app->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'level', 'cmd' );
		$filter_char_order_Dir = $app->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		$filter_char_search		= $app->getUserStateFromRequest( $option.'filter_char_search', 'search', '', 'string');
		$filter_char_level_min	= $app->getUserStateFromRequest( $option.'filter_char_level_min', 'level_min', null, 'int');
		$filter_char_level_max	= $app->getUserStateFromRequest( $option.'filter_char_level_max', 'level_max', null, 'int');
		$filter_guild_filter		= $app->getUserStateFromRequest( $option.'filter_guild_filter', 'guild_filter', null, 'int');

		// Get pagination request variables
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter_order', $filter_char_order);
		$this->setState('filter_order_Dir', $filter_char_order_Dir);
		$this->setState('filter_char_search', $filter_char_search);
		$this->setState('filter_char_level_min', $filter_char_level_min);
		$this->setState('filter_char_level_max', $filter_char_level_max);
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
			(in_array($filter_order, array('c.char_name', 'u.name', 'cl.class_name', 'c.rank', 'c.gender_id', 'rc.race_name', 'c.char_level', 'g.guild_name', 'c.character_id') ) ) &&
			(in_array($filter_order_Dir, array('asc', 'desc') ) )
		) {
		
			$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		}

		return $orderby;
	}

	function _buildQueryWhere()
	{
		$db	= JFactory::getDBO();
		
		$filter_char_level_min = $this->getState('filter_char_level_min');
		$filter_char_level_max = $this->getState('filter_char_level_max');
		$filter_char_search = $this->getState('filter_char_search');
		$filter_guild_filter = $this->getState('filter_guild_filter');

		$where = '';
		
		$where_arr = array();
		if ($filter_char_level_min>0) {
			$where_arr[] = "c.char_level >= ".$db->Quote($filter_char_level_min);
		}
		if ($filter_char_level_max!='') {
			$where_arr[] = "c.char_level <= ".$db->Quote($filter_char_level_max);
		}
		if ($filter_char_search!='') {
			$where_arr[] = "(c.char_name LIKE '%".$db->escape($filter_char_search)."%' OR u.name LIKE '%".$db->escape($filter_char_search)."%')";
		}
		if (intval($filter_guild_filter)>0) {
			$where_arr[] = "c.guild_id = " . intval($filter_guild_filter);
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
        $query = ' SELECT c.*, u.name AS user_name, cl.class_name, rc.race_name, ge.gender_name, cl.class_color, g.guild_name, (c.guild_id<0) AS removed '
            . ' FROM #__raidplanner_character AS c'
            . ' LEFT JOIN #__users AS u ON u.id = c.profile_id'
            . ' LEFT JOIN #__raidplanner_class AS cl ON cl.class_id = c.class_id'
            . ' LEFT JOIN #__raidplanner_race AS rc ON rc.race_id = c.race_id'
            . ' LEFT JOIN #__raidplanner_gender AS ge ON ge.gender_id = c.gender_id'
            . ' LEFT JOIN #__raidplanner_guild AS g ON g.guild_id = c.guild_id'
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
