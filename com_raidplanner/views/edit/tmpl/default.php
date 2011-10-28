<?php
/*------------------------------------------------------------------------
# Event Edit Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$version = new JVersion();
switch ($version->RELEASE) {
	case '1.5':
		$timeformat = '%H:%M';
		$dateFormat = JText::_('%Y-%M-%D');
		$jsdateformat = JText::_('%Y-%M-%D');
	break;
	default:
	case '1.6':
		$timeformat = 'H:i';
		$dateFormat = '%Y-%m-%d';
		$jsdateformat = 'Y-m-d';
	break;
}
$invite_time = array(
	'date'	=>	JHTML::_('date', $this->event->invite_time, $jsdateformat),
	'time'	=>	JHTML::_('date', $this->event->invite_time, $timeformat ),
);
$start_time = array(
	'date'	=>	JHTML::_('date', $this->event->start_time, $jsdateformat),
	'time'	=>	JHTML::_('date', $this->event->start_time, $timeformat ),
);
?>
<div class="rp_container">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" id="rp_edit_form">
		<label for="location"><?php echo JText::_('COM_RAIDPLANNER_RAID_NAME');?>:</label><input type="text" name="location" value="<?php echo $this->event->location;?>">
		<label for="icon_name"><?php echo JText::_('COM_RAIDPLANNER_ICON');?></label><select name="icon_name" id="icon_name">
			<option value=""></option>
			<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
			<option value="<?php echo $icon_file;?>"<?php if ($icon_file==@$this->event->icon_name) {?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
			<?php endforeach; ?>
		</select>
		<label for="template_id"><?php echo JText::_('COM_RAIDPLANNER_TEMPLATE');?></label><select name="template_id" id="template_id" onchange="document.getElementById('rp_edit_form').submit();">
			<option value=""></option>
			<?php foreach ($this->templates as $template) : ?>
			<option value="<?php echo $template->raid_id;?>"><?php echo $template->location;?></option>
			<?php endforeach; ?>
		</select>
		<br />
		<label for="description"><?php echo JText::_('JGLOBAL_DESCRIPTION');?>:</label><br />
			<textarea name="description" cols="40" rows="5"><?php echo $this->event->description;?></textarea>
		<br />
		<label for="start_time_0"><?php echo JText::_('COM_RAIDPLANNER_START_TIME');?>: </label><?php echo JHTML::calendar( $start_time['date'], 'start_time[0]', 'start_time_0', $dateFormat);?> <input type="text" name="start_time[1]" id="start_time_1" value="<?php echo $start_time['time'];?>" size="6" /><br />
		<label for="duration_mins"><?php echo JText::_('COM_RAIDPLANNER_DURATION');?>: </label><input type="text" name="duration_mins" id="duration_mins" value="<?php echo @$this->event->duration_mins;?>" size="3" /> <?php echo JText::_('COM_RAIDPLANNER_MINUTES');?><br />
		<label for="invite_time_0"><?php echo JText::_('COM_RAIDPLANNER_INVITE_TIME');?>: </label><?php echo JHTML::calendar( $invite_time['date'], 'invite_time[0]', 'invite_time_0', $dateFormat);?> <input type="text" name="invite_time[1]" id="invite_time_1" value="<?php echo $invite_time['time'];?>" size="6" /><br />
		<label for="freeze_time"><?php echo JText::_('COM_RAIDPLANNER_FREEZE_TIME');?> </label><input type="text" name="freeze_time" id="freeze_time" value="<?php echo @$this->event->freeze_time;?>" size="3" /> <?php echo JText::_('COM_RAIDPLANNER_MINUTES_BEFORE_START');?><br />
		<label for="minimum_level"><?php echo JText::_('COM_RAIDPLANNER_LEVEL_RANGE');?> </label><input type="text" name="minimum_level" id="minimum_level" value="<?php echo @$this->event->minimum_level;?>" size="3" /> - <input type="text" name="maximum_level" id="maximum_level" value="<?php echo @$this->event->maximum_level;?>" size="3" /><br />
		<label for="minimum_rank"><?php echo JText::_('COM_RAIDPLANNER_MINIMUM_RANK');?> </label><input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo @$this->event->minimum_rank;?>" size="3" /><br />
		<label for="guild_id"><?php echo JText::_('COM_RAIDPLANNER_GUILD');?></label>
			<select name="guild_id" id="guild_id">
				<option value=""></option>
				<?php foreach ($this->guilds as $guild_id => $guild) : ?>
				<option value="<?php echo $guild_id;?>"<?php if ($guild_id==@$this->event->guild_id) {?> selected="selected"<?php } ?>><?php echo $guild->guild_name;?></option>
				<?php endforeach; ?>
			</select>
		<br />
		<label for="invited_group_id"><?php echo JText::_('COM_RAIDPLANNER_INVITED_GROUP');?></label>
			<select name="invited_group_id" id="invited_group_id">
				<option value=""></option>
				<?php foreach ($this->groups as $group_id => $group) : ?>
				<option value="<?php echo $group_id;?>"<?php if ($group_id==@$this->event->invited_group_id) {?> selected="selected"<?php } ?>><?php echo $group->group_name;?></option>
				<?php endforeach; ?>
			</select>
		<br />
	
		<input type="submit" name="SubmitButton" value="<?php echo JText::_('JSAVE');?>" />
<?php if($this->candelete) : ?>
		<script type="text/javascript">
			function confirmDelete() {
				if (confirm("<?php echo JText::_('COM_RAIDPLANNER_SURE_TO_DELETE');?>")) {
					$('task').set('value','deleteevent');
					$('rp_edit_form').submit();
				}
			}
		</script>
		<input type="button" name="DeleteButton" value="<?php echo JText::_('JACTION_DELETE');?>" onclick="confirmDelete();"/>
<?php endif; ?>
		
		<input type="hidden" name="option" value="com_raidplanner" />
		<input type="hidden" name="Itemid" value="<?php echo JSite::getMenu()->getActive()->id; ?>" />
		<input type="hidden" name="task" id="task" value="saveevent" />
		<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
	</form>
</div>
