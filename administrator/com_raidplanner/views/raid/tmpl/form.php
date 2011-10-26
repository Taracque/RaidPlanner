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
$version = new JVersion();
switch ($version->RELEASE) {
	case '1.5':
		$dateformat = '%Y-%m-%d %H:%M';
	break;
	default:
	case '1.6':
		$dateformat = 'Y-m-d H:i';
	break;
}
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-30 fltlft col30">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
		<ul class="adminformlist">
		<li>
			<label for="location"><?php echo JText::_( 'COM_RAIDPLANNER_LOCATION' ); ?>:</label>
			<input class="text_area" type="text" name="location" id="location" size="32" maxlength="250" value="<?php echo @$this->raid->location;?>" />
		</li>
		<li>
			<label for="is_template_set"><?php echo JText::_( 'COM_RAIDPLANNER_TEMPLATE' ); ?>:</label>
			<fieldset id="is_template_set" class="radio inputbox">
				<?php echo JHTML::_('select.booleanlist', 'is_template', 'class="inputbox"', @$this->raid->is_template );?>
			</fieldset>
		</li>
		<li>
			<label for="description"><?php echo JText::_( 'JGLOBAL_DESCRIPTION' ); ?>:</label>
			<textarea class="text_area" type="text" name="description" id="description" cols="32" rows="10"><?php echo @$this->raid->description;?></textarea>
		</li>
		<li>
			<label for="start_time"><?php echo JText::_( 'COM_RAIDPLANNER_START_TIME' ); ?>:</label>
			<?php echo JHTML::_('calendar', JHTML::_('date', $this->raid->start_time, $dateformat), 'start_time', 'start_time', '%Y-%m-%d %H:%M:%S' );?>
		</li>
		<li>
			<label for="duration_mins"><?php echo JText::_( 'COM_RAIDPLANNER_DURATION' ); ?>:</label>
			<input type="text" name="duration_mins" id="duration_mins" value="<?php echo @$this->raid->duration_mins;?>" size="5" />
		</li>
		<li>
			<label for="invite_time"><?php echo JText::_( 'COM_RAIDPLANNER_INVITE_TIME' ); ?>:</label>
			<?php echo JHTML::_('calendar', JHTML::_('date', $this->raid->invite_time, $dateformat), 'invite_time', 'invite_time', '%Y-%m-%d %H:%M:%S' );?>
		</li>
		<li>
			<label for="freeze_time"><?php echo JText::_( 'COM_RAIDPLANNER_FREEZE_TIME' ); ?>:</label>
			<div class="fltlft">
				<input type="text" name="freeze_time" id="freeze_time" value="<?php echo @$this->raid->freeze_time;?>" size="5" /> <?php echo JText::_('COM_RAIDPLANNER_MINUTES_BEFORE_START');?>
			</div>
		</li>
		<li>
			<label for="raid_leader"><?php echo JText::_( 'COM_RAIDPLANNER_RAID_LEADER' ); ?>:</label>
			<input class="text_area" type="text" name="raid_leader" id="raid_leader" size="32" maxlength="250" value="<?php echo @$this->raid->raid_leader;?>" />
		</li>
		<li>
			<label for="profile_id"><?php echo JText::_( 'JGLOBAL_USERNAME' ); ?>:</label>
			<select name="profile_id" id="profile_id">
				<option value=""></option>
				<?php foreach ($this->users as $user_id => $user) : ?>
					<option value="<?php echo $user_id;?>"<?php if($user_id==@$this->raid->profile_id){?> selected="selected"<?php } ?>><?php echo @$user->username;?></option>
				<?php endforeach; ?>
			</select>
		</li>
		<li>
			<label for="minimum_level"><?php echo JText::_( 'COM_RAIDPLANNER_LEVEL_RANGE' ); ?>:</label>
			<input type="text" name="minimum_level" id="minimum_level" value="<?php echo @$this->raid->minimum_level;?>" size="5" />
			<input type="text" name="maximum_level" id="maximum_level" value="<?php echo @$this->raid->maximum_level;?>" size="5" />
		</li>
		<li>
			<label for="minimum_rank"><?php echo JText::_( 'COM_RAIDPLANNER_MINIMUM_RANK' ); ?>:</label>
			<input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo @$this->raid->minimum_rank;?>" size="5" />
		</li>
		<li>
			<label for="invited_group_id"><?php echo JText::_( 'COM_RAIDPLANNER_INVITE_GROUP' ); ?>:</label>
			<select name="invited_group_id" id="invited_group_id">
				<option value=""></option>
				<?php foreach ($this->groups as $group_id => $group_name) : ?>
					<option value="<?php echo $group_id;?>"<?php if($group_id==@$this->raid->invited_group_id){?> selected="selected"<?php } ?>><?php echo @$group_name->group_name;?></option>
				<?php endforeach; ?>
			</select>
		</li>
		<li>
			<label for="invited_group_id"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD' ); ?>:</label>
			<select name="guild_id" id="guild_id">
				<option value=""></option>
				<?php foreach ($this->guilds as $guild_id => $guild_name) : ?>
					<option value="<?php echo $guild_id;?>"<?php if($guild_id==@$this->raid->guild_id){?> selected="selected"<?php } ?>><?php echo @$guild_name->guild_name;?></option>
				<?php endforeach; ?>
			</select>
		</li>
		<li>
			<label for="icon_name"><?php echo JText::_( 'COM_RAIDPLANNER_ICON' ); ?>:</label>
			<select name="icon_name" id="icon_name">
				<option value=""></option>
				<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
					<option value="<?php echo $icon_file;?>"<?php if($icon_file==@$this->raid->icon_name){?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
				<?php endforeach; ?>
			</select>
		</li>
	</ul>
	</fieldset>
</div>
<div class="width-65 fltrt col65">
	<?php if ($this->raid->raid_id > 0 ) : ?>
	<iframe style="width:100%;height:500px;border:none;" src="<?php echo JURI::base(); ?>../index.php?option=com_raidplanner&view=event&task=viewevent&tmpl=component&id=<?php echo $this->raid->raid_id;?>"></iframe>
	<?php endif; ?>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="raid_id" value="<?php echo $this->raid->raid_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="raids" />
<input type="hidden" name="controller" value="raids" />
</form>