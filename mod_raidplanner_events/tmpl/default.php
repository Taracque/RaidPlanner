<?php
/*------------------------------------------------------------------------
# Default Template for RaidPlanner Events Module
# mod_raidplanner_events - RaidPlanner Events Module
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
?>
<?php if ($invitationAlerts): ?>
<div id="rp_invitation_alert">
	<h3><?php echo JText::_('COM_RAIDPLANNER_PENDING_INVITATIONS');?></h3>
	<ul>
	<?php foreach($invitationAlerts as $invitation):?>
		<li><a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&modalevent=' . $invitation->raid_id . '&Itemid=' . $itemid);?>"><?php echo $invitation->location . " (" . JHTML::_('date', $invitation->start_time, RaidPlannerHelper::shortDateFormat() ) . ")"; ?></a></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php if ($showInvitationAlerts == 2): ?>
<script type="text/javascript">
	window.addEvent('domready',function(){
		SqueezeBox.initialize();
		SqueezeBox.fromElement( $('rp_invitation_alert'),{
			handler: 'adopt',
			shadow: true,
			id: 'system-message',
			overlayOpacity: 0.5,
			size: {x: 300, y: 100}
		});
	});
</script>
<?php endif; ?>
<?php endif; ?>
<table>
	<?php if (count($items) == 0) : ?>
	<tr>
		<td><?php echo JText::_('MOD_RAIDPLANNER_NO_EVENTS');?><br /></td>
	</tr>
	<?php else:?>
	<?php	
		foreach ($items as $item) {
			$tip = '';
			if ( ($raidshowReg) && ($item->confirmed) ) {
				// show if registered
				$tip .= JText::_('COM_RAIDPLANNER_CONFIRMATIONS_' . $item->confirmed) . " ";
			}
			if ( ($raidshowChar) && ($item->char_name) ) {
				// show which car is registered
				$tip .= $item->char_name . " ";
			}
			if ( ($raidshowRole) && ($item->role_name) ) {
				// show registered role
				$tip .= $item->role_name . " ";
			}
	?>
	<tr>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&modalevent='.$item->raid_id.'&Itemid='.$itemid);?>">
				<span<?php if ($tip != '') { echo ' class="hasTip" title="'.$tip.'"'; } ?>>
					<strong><?php echo JHTML::_('date', $item->start_time, RaidPlannerHelper::shortDateFormat() );?> </strong><?php echo $item->location;?>
				</span>
			</a><br />
		</td>
	</tr>
	<?php } //endforeach ?>
<?php endif; ?>
</table>