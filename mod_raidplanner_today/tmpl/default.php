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

$lang =JFactory::getLanguage();
$lang->load('com_raidplanner');

$timeformat = 'H:i';

jimport('joomla.application.component.helper');
JHtml::_('behavior.tooltip');

$use_modal = JComponentHelper::getParams('com_raidplanner')->get('use_modal');
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
<?php if ($raidshowDate) : ?>
	<tr>
		<td>
			<strong><?php echo JHTML::_('date', 'now', $format);?><br /></strong>
		</td>
	</tr>
<?php endif;
if ((count($items) == 0) || (substr($items[0]->start_time,0,10)!=date('Y-m-d'))): ?>
	<tr>
		<td><?php echo JText::_('MOD_RAIDPLANNER_NO_EVENTS_TODAY');?><br /></td>
	</tr>
<?php endif; ?>
<?php if (count($items) > 0) : ?>
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
		if ($tip != '') {
			$tip = '<hr />' . $tip;
		}
	?>
	<tr>
		<td>
			<?php if (substr($item->start_time,0,10)!=date('Y-m-d')) : ?>
			<small>
			<?php endif; ?>
			<?php if ($use_modal) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=event&task=viewevent&tmpl=component&id=' . $item->raid_id . '&Itemid=' . $itemid);?>" class="open-modal">
			<?php else: ?>
			<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=event&task=viewevent&id=' . $item->raid_id . '&Itemid=' . $itemid);?>">
			<?php endif; ?>
			<?php if (substr($item->start_time,0,10)!=date('Y-m-d')) : ?>
				<?php echo JHTML::_('date', $item->start_time,'m-d'); ?>
			<?php endif; ?>
			<?php echo RaidPlannerHelper::raidTooltip( $item->raid_id, $raidshowAttendants, $tip ); ?>
			<?php if ($item->signed) : ?>
			<span class="icon-checkmark"></span>​
			<?php else: ?>
				<?php if ($item->invited) : ?>
				<span class="icon-question"></span>​
				<?php endif; ?>
			<?php endif; ?>
			</a><br />
			<?php if (substr($item->start_time,0,10)!=date('Y-m-d')) : ?>
			</small>
			<?php endif; ?>
		</td>
	</tr>
	<?php } //endforeach ?>
<?php endif; ?>
</table>