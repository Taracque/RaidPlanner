<?php
/*------------------------------------------------------------------------
# Default Template for RaidPlanner Today Module
# mod_raidplanner_today - RaidPlanner Today Module
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
$format = JText::_('DATE_FORMAT_LC');
$event_count = 0;

$lang =& JFactory::getLanguage();
$lang->load('com_raidplanner');

$menu = &JSite::getMenu()->getItems( 'component', 'com_raidplanner', true );
if (empty($menu)) {
	$itemid = &JSite::getMenu()->getActive()->id;
} else {
	$itemid = $menu->id;
}

$version = new JVersion();
switch ($version->RELEASE) {
	case '1.6':
		$timeformat = 'H:i';
	break;
	default:
	case '1.5':
		$timeformat = '%H:%M';
	break;
}

?>
<table>
	<?php
	if ($raidshowDate) {
		echo "<tr><td><strong>";
		echo JHTML::_('date', 'now', $format) . "<br />";
		echo "</strong></td></tr>";
	}
	$event_count = count($items);
	if ($event_count == 0) {
		echo "<tr>";
		echo "<td>".JTEXT::_('There are no events today')."<br />";
		echo "</td></tr>";
	} else {
		foreach ($items as $item) { 
			echo "<tr>";
			echo "<td><a href='".JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&modalevent='.$item->raid_id."&Itemid=".$itemid)."'><span";
			$tip = '';
			if ( ($raidshowReg) && ($item->confirmed) ) {
				// show if registered
				$tip .= JText::_('RAIDPLANNER_CONFIRMATION_' . $item->confirmed) . " ";
			}
			if ( ($raidshowChar) && ($item->char_name) ) {
				// show which car is registered
				$tip .= $item->char_name . " ";
			}
			if ( ($raidshowRole) && ($item->role_name) ) {
				// show registered role
				$tip .= $item->role_name . " ";
			}
			if ($tip != '') {
				echo ' class="hasTip" title="'.$tip.'"';
			}
			echo "><strong>" . JHTML::_('date', $item->start_time, $timeformat, true) . "</strong> " . $item->location . "</span></a><br />";
			echo "</td></tr>";
		}
	}
	?>
</table>