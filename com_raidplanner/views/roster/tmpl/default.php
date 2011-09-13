<?php
/*------------------------------------------------------------------------
# Roster Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.utilities.date');
?>
<table class="rp_container">
	<tr class="rp_header">
		<td class="rp_header_center">
			<h3>Roster</h3>
		</td>
	</tr>
	<?php foreach($this->characters as $character) : ?>
	<tr class="rp_roster">
		<td><?php echo $character->char_name; ?></td>
		<td><?php echo $character->char_level; ?></td>
		<td><?php echo $character->gender_name; ?></td>
		<td><?php echo $character->race_name; ?></td>
		<td><?php echo $character->class_name; ?></td>
		<td><?php echo $character->rank; ?></td>
	</tr>
	<?php endforeach; ?>
</table>