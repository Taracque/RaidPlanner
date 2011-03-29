<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.utilities.date');

?>
<script type="text/javascript">
<?php if ( (JRequest::getVar('modalevent')) && ($this->canView) ) { ?>
window.addEvent('domready',function(){
	SqueezeBox.fromElement($("event_<?php echo intval(JRequest::getVar('modalevent')); ?>"));
});
<?php } ?>
</script>
<table class="rp_container">
	<tr class="rp_header">
		<td class="rp_header_left">
			<a class="rp_button_prev" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&month='.$this->prevmonth);?>"> ◄ </a>
			<a class="rp_button_next" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&month='.$this->nextmonth);?>"> ► </a>
			<a class="rp_button_today" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&month='.date("Y-m"));?>"> Today </a>
		</td>
		<td class="rp_header_center">
			<h3><?php echo $this->year." ".JDate::_monthToString($this->monthonly); ?></h3>
		</td>
		<td class="rp_header_right">
	<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=calendar&task=feed');?>" class="rp_button"><?php echo JText::_('Calendar Feed');?></a>
<?php if ($this->isOfficer) { ?>
	<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=edit&task=edit&id=-1');?>" class="rp_button new"><?php echo JText::_('New Event');?></a>
<?php } ?>
		</td>
	</tr>
	<tr class="rp_calendar">
		<td colspan="3">
			<table class="rp_calendar_body">
				<thead>
					<tr>
				<?php for ($days=1;$days<8;$days++) { ?>
						<th><?php echo JDate::_dayToString($days % 7);?></th>
				<?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php
					$day = -$this->shift + 1;
					for ($weeks=1;$weeks<7;$weeks++) {
				?>
					<tr>
					<?php for ($days=1;$days<8;$days++) {
						$day++;
						$daystamp = mktime(0, 0, 0, $this->monthonly, $day, $this->year);
						$thedate = date("Y-m-d", $daystamp);
						$dom = date("j",$daystamp);
					?>
						<td class="<?php if ($thedate==date("Y-m-d")) {?>today <?php } ?><?php if ($this->monthonly!=date("m", $daystamp)) {?>noncurrent <?php } ?>">
							<span class="day_no"><?php echo $dom; ?></span>
							<div class="events"><?php
								if (@$this->events[$thedate]) {
									foreach ($this->events[$thedate] as $event) {
					?>
								<div class="event">
									<?php if($this->canView) { ?>
									<a class="modal" id="event_<?php echo $event->raid_id;?>" href="<?php echo JRoute::_('index.php?option=com_raidplanner&view=event&task=viewevent&tmpl=component&id='.$event->raid_id); ?>">
									<?php } else { ?>
									<a>
									<?php } ?>
										<strong><?php
											echo JHTML::_('date', $event->start_time, '%H');
											$mins = date("i", $event->start_time);
											if ($mins!=0) {
												echo $mins;
											}
										?></strong> <?php echo $event->location;?>
									</a>
								</div>
					<?php
									}
								}
							?></div>
						</td>
					<?php } ?>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</td>
	</tr>
</table>