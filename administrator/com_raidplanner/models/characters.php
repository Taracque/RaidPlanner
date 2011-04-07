<?php
/**
 * Characters Model for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license        GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
class RaidPlannerModelCharacters extends JModel
{
    /**
     * Data array
     *
     * @var array
     */
    var $_data;
 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = ' SELECT c.*, u.name AS user_name, cl.class_name, rc.race_name, ge.gender_name, cl.class_color  '
            . ' FROM #__raidplanner_character AS c'
            . ' LEFT JOIN #__users AS u ON u.id = c.profile_id'
            . ' LEFT JOIN #__raidplanner_class AS cl ON cl.class_id = c.class_id'
            . ' LEFT JOIN #__raidplanner_race AS rc ON rc.race_id = c.race_id'
            . ' LEFT JOIN #__raidplanner_gender AS ge ON ge.gender_id = c.gender_id'
        ;
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
            $query = $this->_buildQuery();
            $this->_data = $this->_getList( $query );
        }

        return $this->_data;
    }
}
