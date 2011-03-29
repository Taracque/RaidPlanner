<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable" style="float:left;width:300px">
		<tr>
			<td width="200" align="right" class="key">
				<label for="location">
					<?php echo JText::_( 'Location' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="location" id="location" size="32" maxlength="250" value="<?php echo $this->raid->location;?>" />
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="text_area" type="text" name="description" id="description" cols="32" rows="10"><?php echo $this->raid->description;?></textarea>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="start_time">
					<?php echo JText::_( 'Start Time' ); ?>:<br />
					<small><?php echo JText::_( 'GMT' ); ?></small>
				</label>
			</td>
			<td>
				<?php echo JHTML::_('calendar',$this->raid->start_time, 'start_time','%Y-%m-%d %H:%i');?>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="invite_time">
					<?php echo JText::_( 'Invite Time' ); ?>:<br />
					<small><?php echo JText::_( 'GMT' ); ?></small>
				</label>
			</td>
			<td>
				<?php echo JHTML::_('calendar',$this->raid->invite_time, 'invite_time','%Y-%m-%d %H:%i');?>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="freeze_time">
					<?php echo JText::_( 'Freeze timer' ); ?>:<br />
				</label>
			</td>
			<td>
				<input type="text" name="freeze_time" id="freeze_time" value="<?php echo $this->raid->freeze_time;?>" size="5" />
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="raid_leader">
					<?php echo JText::_( 'Raid Leader' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="raid_leader" id="raid_leader" size="32" maxlength="250" value="<?php echo $this->raid->raid_leader;?>" />
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="profile_id">
					<?php echo JText::_( 'User' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_('list.users', 'profile_id', $this->raid->profile_id, 0, NULL, 'name', 0);?>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="minimum_level">
					<?php echo JText::_( 'Level Range' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="minimum_level" id="minimum_level" value="<?php echo $this->raid->minimum_level;?>" size="5" /> -
				<input type="text" name="maximum_level" id="maximum_level" value="<?php echo $this->raid->maximum_level;?>" size="5" />
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="minimum_rank">
					<?php echo JText::_( 'Minimum Rank' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo $this->raid->minimum_rank;?>" size="5" />
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="icon_name">
					<?php echo JText::_( 'Icon' ); ?>:
				</label>
			</td>
			<td>
				<select name="icon_name" id="icon_name">
					<option value=""></option>
				<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
					<option value="<?php echo $icon_file;?>"<?php if($icon_file==$this->raid->icon_name){?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
	<?php if ($this->raid->raid_id > 0 ) : ?>
	<iframe style="float:left;width:700px;height:500px;border:none;" src="<?php echo JURI::base(); ?>../index.php?option=com_raidplanner&view=event&task=viewevent&tmpl=component&id=<?php echo $this->raid->raid_id;?>"></iframe>
	<?php endif; ?>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="raid_id" value="<?php echo $this->raid->raid_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="raids" />
<input type="hidden" name="controller" value="raids" />
</form>