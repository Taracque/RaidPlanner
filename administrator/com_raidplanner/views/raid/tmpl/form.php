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
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-30 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<ul class="adminformlist">
		<li>
			<label for="location"><?php echo JText::_( 'Location' ); ?>:</label>
			<input class="text_area" type="text" name="location" id="location" size="32" maxlength="250" value="<?php echo @$this->raid->location;?>" />
		</li>
		<li>
			<label for="is_template_set"><?php echo JText::_( 'Template' ); ?>:</label>
			<fieldset id="is_template_set" class="radio inputbox">
				<?php echo JHTML::_('select.booleanlist', 'is_template', 'class="inputbox"', @$this->raid->is_template );?>
			</fieldset>
		</li>
		<li>
			<label for="description"><?php echo JText::_( 'Description' ); ?>:</label>
			<textarea class="text_area" type="text" name="description" id="description" cols="32" rows="10"><?php echo @$this->raid->description;?></textarea>
		</li>
		<li>
			<label for="start_time"><?php echo JText::_( 'Start Time' ); ?>:<br />
				<small><?php echo JText::_( 'GMT' ); ?></small>
			</label>
			<?php echo JHTML::_('calendar',@$this->raid->start_time, 'start_time', 'start_time', '%Y-%m-%d %H:%M:%S' );?>
		</li>
		<li>
			<label for="duration_mins"><?php echo JText::_( 'Duration' ); ?>:<br /></label>
			<input type="text" name="duration_mins" id="duration_mins" value="<?php echo @$this->raid->duration_mins;?>" size="5" />
		</li>
		<li>
			<label for="invite_time"><?php echo JText::_( 'Invite Time' ); ?>:<br />
				<small><?php echo JText::_( 'GMT' ); ?></small>
			</label>
			<?php echo JHTML::_('calendar',@$this->raid->invite_time, 'invite_time', 'invite_time', '%Y-%m-%d %H:%M:%S' );?>
		</li>
		<li>
			<label for="freeze_time"><?php echo JText::_( 'Freeze timer' ); ?>:</label>
			<input type="text" name="freeze_time" id="freeze_time" value="<?php echo @$this->raid->freeze_time;?>" size="5" />
		</li>
		<li>
			<label for="raid_leader"><?php echo JText::_( 'Raid Leader' ); ?>:</label>
			<input class="text_area" type="text" name="raid_leader" id="raid_leader" size="32" maxlength="250" value="<?php echo @$this->raid->raid_leader;?>" />
		</li>
		<li>
			<label for="profile_id"><?php echo JText::_( 'User' ); ?>:</label>
			<?php echo JHTML::_('list.users', 'profile_id', @$this->raid->profile_id, 0, NULL, 'name', 0);?>
		</li>
		<li>
			<label for="minimum_level"><?php echo JText::_( 'Level Range' ); ?>:</label>
			<input type="text" name="minimum_level" id="minimum_level" value="<?php echo @$this->raid->minimum_level;?>" size="5" />
			<input type="text" name="maximum_level" id="maximum_level" value="<?php echo @$this->raid->maximum_level;?>" size="5" />
		</li>
		<li>
			<label for="minimum_rank"><?php echo JText::_( 'Minimum Rank' ); ?>:</label>
			<input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo @$this->raid->minimum_rank;?>" size="5" />
		</li>
		<li>
			<label for="invited_group_id"><?php echo JText::_( 'Invited Group' ); ?>:</label>
			<select name="invited_group_id" id="invited_group_id">
				<option value=""></option>
				<?php foreach ($this->groups as $group_id => $group_name) : ?>
					<option value="<?php echo $group_id;?>"<?php if($group_id==@$this->raid->invited_group_id){?> selected="selected"<?php } ?>><?php echo @$group_name->group_name;?></option>
				<?php endforeach; ?>
			</select>
		</li>
		<li>
			<label for="icon_name"><?php echo JText::_( 'Icon' ); ?>:</label>
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
<div class="width-70 fltrt">
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