<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>
<?php /* echo JText::_('TOPIC TEXT'); */ ?>

<?php
/*
 * Function to get a list for a label
 */
 
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
			echo "><strong>" . JHTML::_('date', $item->start_time, '%H:%M', true) . "</strong> " . $item->location . "</span></a><br />";
			echo "</td></tr>";
		}
	}
	?>
</table>