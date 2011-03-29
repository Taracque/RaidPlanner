<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>
<?php /* echo JText::_('TOPIC TEXT'); */ ?>

<?php
/*
 * Function to get a list for a label
 */
 
$format = JText::_('DATE_FORMAT_LC1');
$event_count = 0;
?>

<table border="0">
	<?php
	if ($raidshowDate) {
		echo "<tr><td><strong>";
		setlocale(LC_TIME, $raidshowDateLocal);
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
			echo "<td><a href='".JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&modalevent='.$item->raid_id)."'";
			$tip = '';
			if ($raidshowReg) {
				// show if registered
				$tip .= "";
			}
			if ($raidshowChar) {
				// show which car is registered
				$tip .= "";
			}
			if ($raidshowRole) {
				// show registered role
				$tip .= "";
			}
			if ($raidshowRace) {
				// show registered race
				$tip .= "";
			}
			if ($tip != '') {
				echo ' class="hasTip" title="'.$tip.'"';
			}
			echo ">" . $item->location . "</a><br />";
			echo "</td></tr>";
		}
	}
	?>
</table>