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

switch ( RaidPlannerHelper::getJVersion() ) {
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
	<form action="<?php echo JRoute::_('index.php');?>" method="post" id="rp_edit_form" class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="location"><?php echo JText::_('COM_RAIDPLANNER_RAID_NAME');?>:</label>
			<div class="controls">
				<input type="text" name="location" value="<?php echo $this->event->location;?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="icon_name"><?php echo JText::_('COM_RAIDPLANNER_ICON');?></label>
			<div class="controls">
				<select name="icon_name" id="icon_name">
					<option value=""></option>
					<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
					<option value="<?php echo $icon_file;?>"<?php if ($icon_file==@$this->event->icon_name) {?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="template_id"><?php echo JText::_('COM_RAIDPLANNER_TEMPLATE');?></label>
			<div class="controls">
				<select name="template_id" id="template_id" onchange="document.getElementById('rp_edit_form').submit();">
					<option value=""></option>
					<?php foreach ($this->templates as $template) : ?>
					<option value="<?php echo $template->raid_id;?>"><?php echo $template->location;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="description"><?php echo JText::_('JGLOBAL_DESCRIPTION');?>:</label>
			<div class="controls">
				<textarea name="description" cols="40" rows="5"><?php echo $this->event->description;?></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="start_time_0"><?php echo JText::_('COM_RAIDPLANNER_START_TIME');?>: </label>
			<div class="controls">
				<?php echo JHTML::calendar( $start_time['date'], 'start_time[0]', 'start_time_0', $dateFormat, 'class="input-small"');?> <input type="text" name="start_time[1]" id="start_time_1" value="<?php echo $start_time['time'];?>" size="6" class="input-mini" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="duration_mins"><?php echo JText::_('COM_RAIDPLANNER_DURATION');?>: </label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="duration_mins" id="duration_mins" value="<?php echo @$this->event->duration_mins;?>" size="3" class="input-mini" />
					<span class="add-on"><?php echo JText::_('COM_RAIDPLANNER_MINUTES');?></span>
				</div>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="invite_time_0"><?php echo JText::_('COM_RAIDPLANNER_INVITE_TIME');?>: </label>
			<div class="controls">
				<?php echo JHTML::calendar( $invite_time['date'], 'invite_time[0]', 'invite_time_0', $dateFormat, 'class="input-small"');?>
				<input type="text" name="invite_time[1]" id="invite_time_1" value="<?php echo $invite_time['time'];?>" size="6" class="input-mini" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="freeze_time"><?php echo JText::_('COM_RAIDPLANNER_FREEZE_TIME');?>: </label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="freeze_time" id="freeze_time" value="<?php echo @$this->event->freeze_time;?>" size="3" class="input-mini" />
					<span class="add-on"><?php echo JText::_('COM_RAIDPLANNER_MINUTES_BEFORE_START');?></span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="minimum_level"><?php echo JText::_('COM_RAIDPLANNER_LEVEL_RANGE');?>: </label>
			<div class="controls">
				<input type="text" name="minimum_level" id="minimum_level" value="<?php echo @$this->event->minimum_level;?>" size="3" class="input-mini" />
				-
				<input type="text" name="maximum_level" id="maximum_level" value="<?php echo @$this->event->maximum_level;?>" size="3" class="input-mini" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="minimum_rank"><?php echo JText::_('COM_RAIDPLANNER_MINIMUM_RANK');?>: </label>
			<div class="controls">
				<select name="minimum_rank" id=minimum_rank>
					<option value=""></option>
					<?php foreach (RaidPlannerHelper::getRanks() as $rank_id => $rank) : ?>
						<option value="<?php echo $rank_id;?>"<?php if (@$this->event->minimum_rank === (string)$rank_id){ echo " selected=\"selected\"";}?>><?php echo $rank;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="guild_id"><?php echo JText::_('COM_RAIDPLANNER_GUILD');?>: </label>
			<div class="controls">
				<select name="guild_id" id="guild_id">
					<option value=""></option>
					<?php foreach ($this->guilds as $guild_id => $guild) : ?>
					<option value="<?php echo $guild_id;?>"<?php if ($guild_id==@$this->event->guild_id) {?> selected="selected"<?php } ?>><?php echo $guild->guild_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="invited_group_id"><?php echo JText::_('COM_RAIDPLANNER_INVITED_GROUP');?>: </label>
			<div class="controls">
				<select name="invited_group_id" id="invited_group_id">
					<option value=""></option>
					<?php foreach ($this->groups as $group_id => $group) : ?>
					<option value="<?php echo $group_id;?>"<?php if ($group_id==@$this->event->invited_group_id) {?> selected="selected"<?php } ?>><?php echo $group->group_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php echo JText::_('JSAVE');?></button>
<?php if($this->candelete) : ?>
			<script type="text/javascript">
				function confirmDelete() {
					if (confirm("<?php echo JText::_('COM_RAIDPLANNER_SURE_TO_DELETE');?>")) {
						$('task').set('value','deleteevent');
						$('rp_edit_form').submit();
					}
				}
			</script>
			<button type="button" class="btn" name="DeleteButton" onclick="confirmDelete();"><?php echo JText::_('JACTION_DELETE');?></button>
<?php endif; ?>
		</div>
	
		<input type="hidden" name="option" value="com_raidplanner" />
		<input type="hidden" name="Itemid" value="<?php echo JSite::getMenu()->getActive()->id; ?>" />
		<input type="hidden" name="task" id="task" value="saveevent" />
		<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
	</form>
</div>
