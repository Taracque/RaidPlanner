<?php
/*------------------------------------------------------------------------
# Calendar Template for RaidPlanner Component
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
JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">
<?php if ( (JRequest::getVar('modalevent')) && ($this->canView) ) : ?>
window.addEvent('domready',function(){
<?php if (($this->mobile_browser) || ($this->params['use_modal']==0)): ?>
	window.location.href='<?php echo JRoute::_('index.php?option=com_raidplanner&view=event&task=viewevent&id=' . intval(JRequest::getVar('modalevent')) . '&Itemid='.$this->menuitemid); ?>';
<?php else: ?>
	SqueezeBox.fromElement($("event_<?php echo intval(JRequest::getVar('modalevent')); ?>"));
<?php endif; ?>
});
<?php endif; ?>
</script>
<?php if (!empty($this->invitations)) :?>
<div id="rp_invitation_alert">
	<h3><?php echo JText::_('COM_RAIDPLANNER_PENDING_INVITATIONS');?></h3>
	<ul>
<?php foreach($this->invitations as $invitation):?>
		<li><a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&modalevent='.$invitation->raid_id);?>"><?php echo $invitation->location . " (" . JHTML::_('date', $invitation->start_time, RaidPlannerHelper::shortDateFormat() ) . ")"; ?></a></li>
<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
<table class="rp_container">
	<tr class="rp_header">
		<td class="rp_header_left btn-group">
			<a class="rp_button_prev btn" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&month='.$this->prevmonth);?>"> ◄ </a>
			<a class="rp_button_today btn" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&month='.date("Y-m"));?>"><?php echo JText::_('COM_RAIDPLANNER_TODAY');?></a>
			<a class="rp_button_next btn" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&month='.$this->nextmonth);?>"> ► </a>
		</td>
		<td class="rp_header_center">
			<h3><?php echo $this->year." ".RaidPlannerModelRaidPlanner::monthToString($this->monthonly); ?></h3>
		</td>
		<td class="rp_header_right">
<?php if ($this->calendar_mode == 'subscribe') : ?>
	<label>
		<?php echo JText::_('COM_RAIDPLANNER_SUBSCRIBE_CALENDAR_URL');?>:<br />
		<input type="text" value="<?php echo JURI::base()."index.php?option=com_raidplanner&view=feed&task=feed&user=".$this->user_id."&secret=".$this->calendar_secret;?>" size="40" />
	</label>
<?php else: ?>
			<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&task=feed');?>" class="rp_button btn"><?php echo JText::_('COM_RAIDPLANNER_DOWNLOAD_CALENDAR');?></a>
<?php endif; ?>
<?php if ($this->isOfficer) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=edit&task=edit&id=-1');?>" class="rp_button new btn"><?php echo JText::_('COM_RAIDPLANNER_NEW_EVENT');?></a>
<?php endif; ?>
		</td>
	</tr>
	<tr class="rp_calendar">
		<td colspan="3">
			<table class="rp_calendar_body">
				<thead>
					<tr>
				<?php for ($days=$this->params['first_dow'];$days<($this->params['first_dow']+7);$days++) { ?>
						<th><?php echo RaidPlannerModelRaidPlanner::dayToString($days % 7);?></th>
				<?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php
					$day = ( -$this->shift + ( $this->params['first_dow'] - 7)) % 7;
					for ($weeks=1;$weeks<7;$weeks++) :
				?>
					<tr class="<?php if (date("W", mktime(0, 0, 0, date("m"), date("d") + $this->params['first_dow'], date("Y"))) == date("W", mktime(0, 0, 0, $this->monthonly, $day + 3, $this->year)) ) { echo "curweek"; } ?>">
					<?php for ($days=$this->params['first_dow'];$days<($this->params['first_dow']+7);$days++) {
						$day++;
						$daystamp = mktime(0, 0, 0, $this->monthonly, $day, $this->year);
						$thedate = date("Y-m-d", $daystamp);
						$dom = date("j",$daystamp);
					?>
						<td class="<?php if ($thedate==date("Y-m-d")) {?>today <?php } ?><?php if ($this->monthonly!=date("m", $daystamp)) {?>noncurrent <?php } ?>">
							<?php
								$onvacation = $this->eventmodel->usersOnVacation($thedate);
							?>
							<span class="day_no <?php if ($onvacation) {?>hasTip" title="<strong><?php echo JText::_('COM_RAIDPLANNER_ON_VACATION');?></strong><br /><?php echo implode(", ",$onvacation); }?>"><?php echo $dom; ?></span>
							<div class="events"><?php
								if (@$this->events[$thedate]) {
									foreach ($this->events[$thedate] as $event) {
					?>
								<div class="event signed_<?php echo str_replace("-","_",intval($event->queue));?> <?php if($event->queue==0) { echo "un";}?>signed">
									<?php if($this->canView) : ?>
										<?php if (($this->mobile_browser) || ($this->params['use_modal']==0)): ?>
									<a class="rpevent" id="event_<?php echo $event->raid_id;?>" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=event&task=viewevent&id='.$event->raid_id.'&Itemid='.$this->menuitemid); ?>">
										<?php else: ?>
									<a class="rpevent" id="event_<?php echo $event->raid_id;?>" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=event&task=viewevent&tmpl=component&id='.$event->raid_id.'&Itemid='.$this->menuitemid); ?>">
										<?php endif; ?>
										<?php if ($this->params['show_icons']==1): ?>
											<img src="<?php echo JURI::base() . "media/com_raidplanner/raid_icons/" . $event->icon_name; ?>" alt="<?php echo $event->location;?> " style="float:left; margin:2px; height: 2.5em;" />
										<?php endif; ?>
										<?php if ($this->params['show_tooltips']==1) : ?>
										<?php
											echo RaidPlannerHelper::raidTooltip( $event->raid_id );
										?>
										<?php else: ?>
										<?php echo JHTML::_('date', $event->start_time, $this->timeformat );?></strong> <?php echo $event->location;?>
										<?php endif; ?>
									<?php else: ?>
									<a><strong><?php echo JHTML::_('date', $event->start_time, $this->timeformat );?></strong> <?php echo $event->location;?>
									<?php endif; ?>
									</a>
								</div>
					<?php
									}
								}
							?></div>
						</td>
					<?php } ?>
					</tr>
				<?php endfor; ?>
				</tbody>
			</table>
		</td>
	</tr>
</table>