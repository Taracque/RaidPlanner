<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

$dateFormat = JText::_('DATE_FORMAT_LC4')." %H:%M";
$hasChars = !empty($this->characters);
?>
<table class="rp_container" onload="javascript:addTips('.rp_container');">
	<tr class="rp_header">
		<td>
		<?php if ($this->event->icon_name) : ?>
			<img src="<?php echo JURI::base()."media/com_raidplanner/icons/".$this->event->icon_name;?>" float="left" style="float:left; margin: 0 5px 5px 0;" />
		<?php endif; ?>
			<ul>
				<li><strong><?php echo $this->event->location; ?></strong>, <?php echo JText::_('RAIDPLANNER_INVITE');?>: <?php echo JHTML::_('date', $this->event->invite_time, $dateFormat); ?>, <?php echo JText::_('RAIDPLANNER_START_TIME');?>: <?php echo JHTML::_('date', $this->event->start_time, $dateFormat); ?></li>
				<li>
					<strong><?php echo JText::_('RAIDPLANNER_ORGANIZER');?>:</strong><?php echo $this->event->raid_leader;?>
				<?php if ($this->isOfficer) { ?>
					<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&task=edit&view=edit&id='.$this->event->raid_id);?>" class="rp_button edit"><?php echo JText::_('RAIDPLANNER_EDIT');?></a></li>
				<?php } ?>
				</li>
			</ul>
		</td>
	</tr>
	<tr class="rp_event_details">
		<td>
			<div class="rp_event_description">
				<strong><?php echo JText::_('RAIDPLANNER_EDIT_DESCRIPTION');?>:</strong><br />
				<p><?php echo $this->event->description;?></p>
			</div>
			<div class="rp_event_roles">
				<ul>
					<li>
						<strong><?php echo JText::_('RAIDPLANNER_CONFIRMED_ROLES');?>:</strong><br />
						<?php if (@$this->confirmed_roles[1]) foreach ($this->confirmed_roles[1] as $key => $role) { ?>
							<strong><?php echo $key;?>:</strong> <?php echo $role; ?>
						<? } ?>
					</li>
					<li>
						<strong><?php echo JText::_('RAIDPLANNER_SITTING_ROLES');?>:</strong><br />
						<?php if (@$this->confirmed_roles[2]) foreach ($this->confirmed_roles[2] as $key => $role) { ?>
							<strong><?php echo $key;?>:</strong> <?php echo $role; ?>
						<? } ?>
					</li>
					<li>
						<strong><?php echo JText::_('RAIDPLANNER_WAITLIST_ROLES');?>:</strong><br />
						<?php if (@$this->confirmed_roles[-1]) foreach ($this->confirmed_roles[-1] as $key => $role) { ?>
							<strong><?php echo $key;?>:</strong> <?php echo $role; ?>
						<? } ?>
					</li>
				</ul>
			</div>
		</td>
	</tr>
	<tr class="rp_event_buttons">
		<td>
			<div>
				<a href="#" id="rp_switcher_attendants" class="active" onclick="javascript:rpSwitchTab('signup', 'attendants');return false;"><?php echo JText::_('RAIDPLANNER_ATTENDANTS');?></a>
			<?php if (($hasChars) && ($this->canSignup)) { ?>
				<a href="#" id="rp_switcher_signup" class="" onclick="javascript:rpSwitchTab('attendants', 'signup');return false;"><?php echo JText::_('RAIDPLANNER_SIGNUP');?></a>
			<?php } ?>
			</div>
		</td>
	</tr>
	<tr class="rp_event_attendants" id="rp_event_attendants">
		<td>
			<form action="index.php" method="post">
				<table>
					<thead>
						<tr>
							<th><?php echo JText::_('RAIDPLANNER_NAME');?></th>
							<th><?php echo JText::_('RAIDPLANNER_STATUS');?></th>
							<th><?php echo JText::_('RAIDPLANNER_ROLE');?></th>
							<th><?php echo JText::_('RAIDPLANNER_CONFIRMED');?></th>
							<th><?php echo JText::_('RAIDPLANNER_SIGNEDUP');?></th>
						</tr>
					</thead>
					<tbody>
			<?php foreach ($this->attendants as $attendant) { ?>
						<tr>
							<td>
								<input type="hidden" name="characters[]" value="<?php echo $attendant->character_id;?>" />
								<a href="#" onclick="javascript:rpShowTooltip('att_char_name_<?php echo $attendant->character_id;?>');return false;" onmouseenter="javascript:rpShowTooltip('att_char_name_<?php echo $attendant->character_id;?>');" id="att_char_name_<?php echo $attendant->character_id;?>" style="color:<?php echo $attendant->class_color;?>" class="rp_tooltips" title="<?php echo $attendant->char_level." lvl. ".$attendant->class_name;?>"><?php echo $attendant->char_name;?></a>
							</td>
							<td><a href="#" onclick="javascript:rpShowTooltip('att_char_queue_<?php echo $attendant->character_id;?>');return false;" onmouseenter="javascript:rpShowTooltip('att_char_queue_<?php echo $attendant->character_id;?>');" id="att_char_queue_<?php echo $attendant->character_id;?>" class="attendance<?php if ($attendant->comments!='') { ?> rp_tooltips" title="<?php echo $attendant->comments;?><?php } ?>"><?php echo JText::_('RAIDPLANNER_STATUS_'.$attendant->queue); ?></a></td>
							<td><?php
								if (!$this->isOfficer) {
									echo $attendant->role_name;
								} else { ?>
								<select name="role[<?php echo $attendant->character_id;?>]">
								<?php 
									foreach ($this->roles as $role) { ?>
									<option value="<?php echo $role->role_id;?>" <?php if ($role->role_id==$attendant->role_id) {?>selected="selected"<?php } ?>><?php echo $role->role_name;?></option>
								<?php
									}
								?>
								</select>
								<?php }							
							?></td>
							<td><?php
								if (!$this->isOfficer) {
									echo JText::_('RAIDPLANNER_CONFIRMATION_'.$attendant->confirmed);
								} else {
							?><select name="confirm[<?php echo $attendant->character_id;?>]">
								<option value="0">-</option>
								<option value="-1" <?php if ($attendant->confirmed==-1) {?>selected="selected"<?php } ?>><?php echo JText::_('RAIDPLANNER_CONFIRMATION_-1');?></option>
								<option value="1" <?php if ($attendant->confirmed==1) {?>selected="selected"<?php } ?>><?php echo JText::_('RAIDPLANNER_CONFIRMATION_1');?></option>
								<option value="2" <?php if ($attendant->confirmed==2) {?>selected="selected"<?php } ?>><?php echo JText::_('RAIDPLANNER_CONFIRMATION_2');?></option>
								</select>
							<?php
								}
							?></td>
							<td><?php echo JHTML::_('date', $attendant->timestamp, $dateFormat);?></td>
						</tr>
			<?php } ?>
					</tbody>
				</table>
			<?php if ($this->isOfficer) { ?>
				<input type="submit" name="SubmitButton" value="Save" />

				<input type="hidden" name="option" value="com_raidplanner" />
				<input type="hidden" name="controller" value="" />
				<input type="hidden" name="task" value="confirm" />
				<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
				<input type="hidden" name="month" value="<?php echo JHTML::_('date', $this->event->start_time, '%Y-%m'); ?>" />
			<?php } ?>
			</form>
		</td>
	</tr>
<?php if (($hasChars) && ($this->canSignup)) { ?>
	<tr class="rp_event_signup" id="rp_event_signup" style="display:none;">
		<td>
			<form action="<?php echo JRoute::_('index.php');?>" method="post">
				<ul class="queue">
					<li><strong>Attendance:</strong></li>
					<li><label><input type="radio" name="queue" value="1" <?php if ($this->selfstatus->queue==1) { ?>checked="checked"<?php } ?> /><?php echo JText::_('RAIDPLANNER_STATUS_1');?></label></li>
					<li><label><input type="radio" name="queue" value="-1" <?php if ($this->selfstatus->queue==-1) { ?>checked="checked"<?php } ?> /><?php echo JText::_('RAIDPLANNER_STATUS_-1');?></label></li>
					<li><label><input type="radio" name="queue" value="2" <?php if ($this->selfstatus->queue==2) { ?>checked="checked"<?php } ?> /><?php echo JText::_('RAIDPLANNER_STATUS_2');?></label></li>
				</ul>
				<ul class="role">
					<li><strong>Role:</strong></li>
				<?php foreach ($this->roles as $role) { ?>
					<li><label><input type="radio" name="role" value="<?php echo $role->role_id;?>" <?php if ($this->selfstatus->role_id==$role->role_id) { ?>checked="checked"<?php } ?> /><?php echo $role->role_name;?></label></li>
				<?php } ?>
				</ul>
				<div class="characters">
					<label>Character:
				<?php if($hasChars) { ?>
					<select name="character_id">
					<?php foreach ($this->characters as $character) { ?>
						<option value="<?php echo $character->character_id;?>" <?php if ($this->selfstatus->character_id==$character->character_id) { ?>selected="selected"<?php } ?>><?php echo $character->char_name;?></option>
					<?php } ?>
					</select>
				<?php } ?>
					</label>
				</div>
				<div class="comments">
					<label><strong>Comments:</strong><br />
					<textarea name="comments" rows="5" cols="40"><?php echo $this->selfstatus->comments; ?></textarea></label>
				</div>
				<div style="clear:both;"></div>
				<input type="submit" name="SubmitButton" value="Save" />

				<input type="hidden" name="option" value="com_raidplanner" />
				<input type="hidden" name="controller" value="" />
				<input type="hidden" name="task" value="signup" />
				<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
				<input type="hidden" name="month" value="<?php echo JHTML::_('date', $this->event->start_time, '%Y-%m'); ?>" />
			</form>
		</td>
	</tr>
<?php } ?>
</table>