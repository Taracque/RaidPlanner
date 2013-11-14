<?php
/*------------------------------------------------------------------------
# Raid Form Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$dateformat = RaidPlannerHelper::shortDateFormat();
?>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
		<div class="control-group">
			<label class="control-label" for="location"><?php echo JText::_( 'COM_RAIDPLANNER_LOCATION' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="location" id="location" size="32" maxlength="250" value="<?php echo @$this->raid->location;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="is_template"><?php echo JText::_( 'COM_RAIDPLANNER_TEMPLATE' ); ?></label>
			<div class="controls">
				<select name="is_template" id="is_template">
					<option value="0"></option>
					<option value="1" <?php if ( @$this->raid->is_template) { echo "selected=\"selected\""; }?>><?php echo JText::_( 'JYES' );?></option>
					<optgroup label="<?php echo JText::_( 'COM_RAIDPLANNER_AUTO_REPEAT_DAYS' );?>">
						<?php for($i=1;$i<=14;$i++): ?>
						<option value="<?php echo -$i;?>" <?php if (-$i == @$this->raid->is_template) { echo "selected=\"selected\""; }?>><?php echo JText::sprintf( 'COM_RAIDPLANNER_DAYS_BEFORE', $i ); ?></option>
						<?php endfor; ?>
					</optgroup>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="description"><?php echo JText::_( 'JGLOBAL_DESCRIPTION' ); ?></label>
			<div class="controls">
				<textarea class="text_area" type="text" name="description" id="description" cols="32" rows="3"><?php echo @$this->raid->description;?></textarea>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="start_time"><?php echo JText::_( 'COM_RAIDPLANNER_START_TIME' ); ?></label>
			<div class="controls">
				<?php echo JHTML::_('calendar', JHTML::_('date', @$this->raid->start_time, $dateformat), 'start_time', 'start_time', '%Y-%m-%d %H:%M:%S' );?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="duration_mins"><?php echo JText::_( 'COM_RAIDPLANNER_DURATION' ); ?></label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="duration_mins" id="duration_mins" value="<?php echo @$this->raid->duration_mins;?>" size="5" class="input-small">
					<span class="add-on">
						<?php echo JTEXT::_( 'COM_RAIDPLANNER_MINUTES' ); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="invite_time"><?php echo JText::_( 'COM_RAIDPLANNER_INVITE_TIME' ); ?></label>
			<div class="controls">
				<?php echo JHTML::_('calendar', JHTML::_('date', @$this->raid->invite_time, $dateformat), 'invite_time', 'invite_time', '%Y-%m-%d %H:%M:%S' );?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="freeze_time"><?php echo JText::_( 'COM_RAIDPLANNER_FREEZE_TIME' ); ?></label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="freeze_time" id="freeze_time" value="<?php echo @$this->raid->freeze_time;?>" size="5" class="input-small">
					<span class="add-on">
						<?php echo JText::_('COM_RAIDPLANNER_MINUTES_BEFORE_START');?>
					</span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="raid_leader"><?php echo JText::_( 'COM_RAIDPLANNER_RAID_LEADER' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="raid_leader" id="raid_leader" size="32" maxlength="250" value="<?php echo @$this->raid->raid_leader;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="profile_id"><?php echo JText::_( 'JGLOBAL_USERNAME' ); ?></label>
			<div class="controls">
				<select name="profile_id" id="profile_id">
					<option value=""></option>
					<?php foreach ($this->users as $user_id => $user) : ?>
						<option value="<?php echo $user_id;?>"<?php if($user_id==@$this->raid->profile_id){?> selected="selected"<?php } ?>><?php echo @$user->username;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="minimum_level"><?php echo JText::_( 'COM_RAIDPLANNER_LEVEL_RANGE' ); ?></label>
			<div class="controls">
				<div class="input-append input-prepend">
					<input type="text" name="minimum_level" id="minimum_level" value="<?php echo @$this->raid->minimum_level;?>" size="5" class="input-small">
					<span class="add-on">-</span>
					<input type="text" name="maximum_level" id="maximum_level" value="<?php echo @$this->raid->maximum_level;?>" size="5" class="input-small">
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="minimum_rank"><?php echo JText::_( 'COM_RAIDPLANNER_MINIMUM_RANK' ); ?></label>
			<div class="controls">
				<select name="minimum_rank" id=minimum_rank>
					<option value=""></option>
					<?php foreach (RaidPlannerHelper::getRanks() as $rank_id => $rank) : ?>
						<option value="<?php echo $rank_id;?>"<?php if (@$this->raid->minimum_rank === (string)$rank_id){ echo " selected=\"selected\"";}?>><?php echo $rank;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="invited_group_id"><?php echo JText::_( 'COM_RAIDPLANNER_INVITED_GROUP' ); ?></label>
			<div class="controls">
				<select name="invited_group_id" id="invited_group_id">
					<option value=""></option>
					<?php foreach ($this->groups as $group_id => $group_name) : ?>
						<option value="<?php echo $group_id;?>"<?php if($group_id==@$this->raid->invited_group_id){?> selected="selected"<?php } ?>><?php echo @$group_name->group_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="guild_id"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD' ); ?></label>
			<div class="controls">
				<select name="guild_id" id="guild_id">
					<option value=""></option>
					<?php foreach ($this->guilds as $guild_id => $guild) : ?>
						<option value="<?php echo $guild_id;?>"<?php if($guild_id==@$this->raid->guild_id){?> selected="selected"<?php } ?>><?php echo @$guild->guild_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="icon_name"><?php echo JText::_( 'COM_RAIDPLANNER_ICON' ); ?></label>
			<div class="controls">
				<select name="icon_name" id="icon_name">
					<option value=""></option>
					<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
						<option value="<?php echo $icon_file;?>"<?php if($icon_file==@$this->raid->icon_name){?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="raid_id" value="<?php echo $this->raid->raid_id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="raids" />
	<input type="hidden" name="controller" value="raids" />
</form>