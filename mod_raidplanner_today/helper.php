<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

class ModRaidPlannerTodayHelper
{

    /**
     * Returns a list of post items
    */
  public function getItems($raidshowNumber, $user_id)
        {

        // get a reference to the database
        $db = &JFactory::getDBO();

        // get a list of $raidshow_number ordered by start_time
		$query = 'SELECT raid_id,location,start_time FROM `#__raidplanner_raid` WHERE DATE(start_time)=DATE(NOW()) ORDER BY location ASC LIMIT '.intval($raidshowNumber);

        $db->setQuery($query);
        $items = ($items = $db->loadObjectList())?$items:array();
        return $items;

        } //end getItems  */

}
?>
