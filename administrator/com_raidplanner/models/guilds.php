<?php
/*------------------------------------------------------------------------
# Guilds Model for RaidPlanner Component
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

class RaidPlannerModelGuilds extends JModelLegacy
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

		$filter_guild_order     = $app->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'level', 'cmd' );
		$filter_guild_order_Dir = $app->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		$filter_guild_search		= $app->getUserStateFromRequest( $option.'filter_guild_search',	'search', '',	'string');

		// Get pagination request variables
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter_order', $filter_guild_order);
		$this->setState('filter_order_Dir', $filter_guild_order_Dir);
		$this->setState('filter_guild_search', $filter_guild_search);
	}

	function _buildContentOrderBy()
	{
		$orderby = '';
		$filter_order     = $this->getState('filter_order');
		$filter_order_Dir = $this->getState('filter_order_Dir');
		
		/* Error handling is never a bad thing*/
		if (
			(!empty($filter_order) && !empty($filter_guild_order_Dir) ) &&
			(in_array($filter_order, array('guild_name', 'members', 'sync_plugin', 'lastSync') ) ) &&
			(in_array($filter_order_Dir, array('asc', 'desc') ) )
		) {
		
			$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		}

		return $orderby;
	}

	function _buildQueryWhere()
	{
		$db	=& JFactory::getDBO();
		
		$filter_guild_search = $this->getState('filter_guild_search');

		$where = '';
		
		$where_arr = array();
		if ($filter_guild_search!='') {
			$where_arr[] = " guild.guild_name LIKE '%".$db->getEscaped($filter_guild_search)."%'";
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
        $query = 'SELECT guild.*,(SELECT COUNT(character_id) FROM #__raidplanner_character WHERE guild_id=guild.guild_id) AS members FROM #__raidplanner_guild AS guild' . $this->_buildQueryWhere();
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
            $query = $this->_buildQuery() . $this->_buildContentOrderBy();
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
