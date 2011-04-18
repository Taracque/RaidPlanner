<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

$dateFormat = JText::_('DATE_FORMAT_LC4')." %H:%M";
$hasChars = !empty($this->characters);
?>
<table class="rp_header_container">
	<tr>
		<td class="rp_header">
		<?php if ($this->event->icon_name) : ?>
			<img src="<?php echo JURI::base()."images/raidplanner/raid_icons/".$this->event->icon_name;?>" float="left" style="float:left; margin: 0 5px 5px 0;" />
		<?php endif; ?>
			<ul>
				<li><h2><?php echo $this->event->location; ?></h2></li>
				<li>
					<strong><?php echo JText::_('Invite time');?>:</strong> <?php echo JHTML::_('date', $this->event->invite_time, $dateFormat); ?>, 
					<strong><?php echo JText::_('Start time');?>:</strong> <?php echo JHTML::_('date', $this->event->start_time, $dateFormat); ?>,
					<strong><?php echo JText::_('End time');?>:</strong> <?php echo JHTML::_('date', $this->event->end_time, $dateFormat); ?></li>
				<li>
					<strong><?php echo JText::_('Organizer');?>:</strong> <?php echo $this->event->raid_leader;?>
				<?php if ($this->isOfficer) { ?>
					<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&task=edit&view=edit&id='.$this->event->raid_id);?>" class="rp_button edit"><?php echo JText::_('Edit');?></a></li>
				<?php } ?>
				</li>
			</ul>
		</td>
		<td rowspan="3">
			<div class="rp_event_roles">
				<ul>
					<li>
						<strong><?php echo JText::_('Attending Roles');?> (<?php echo (is_array($this->confirmed_roles[0]))?array_sum(@$this->confirmed_roles[0]):"0";?>):</strong><br />
						<?php if (@$this->confirmed_roles[0]) : ?>
							<?php foreach ($this->confirmed_roles[0] as $key => $role) : ?>
								<?php if ($this->roles[$key]->icon_name != '') : ?>
								<img src="<?php echo JURI::base()."images/raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
								<?php else: ?>
								<strong><?php echo $key;?></strong>
								<?php endif; ?>
								: <?php echo $role; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</li>
					<li>
						<hr />
					</li>
					<li>
						<strong><?php echo JText::_('Confirmed roles');?>:</strong><br />
						<?php if (@$this->confirmed_roles[1]) : ?>
							<?php foreach ($this->confirmed_roles[1] as $key => $role) : ?>
								<?php if ($this->roles[$key]->icon_name != '') : ?>
								<img src="<?php echo JURI::base()."images/raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
								<?php else: ?>
								<strong><?php echo $key;?></strong>
								<?php endif; ?>
								: <?php echo $role; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</li>
					<li>
						<strong><?php echo JText::_('Sitting roles');?>:</strong><br />
						<?php if (@$this->confirmed_roles[2]) : ?>
							<?php foreach ($this->confirmed_roles[2] as $key => $role) : ?>
								<?php if ($this->roles[$key]->icon_name != '') : ?>
								<img src="<?php echo JURI::base()."images/raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
								<?php else: ?>
								<strong><?php echo $key;?></strong>
								<?php endif; ?>
								: <?php echo $role; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</li>
					<li>
						<strong><?php echo JText::_('Waitlist roles');?>:</strong><br />
						<?php if (@$this->confirmed_roles[-1]) :?>
							<?php foreach ($this->confirmed_roles[-1] as $key => $role) : ?>
								<?php if ($this->roles[$key]->icon_name != '') : ?>
								<img src="<?php echo JURI::base()."images/raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
								<?php else: ?>
								<strong><?php echo $key;?></strong>
								<?php endif; ?>
								: <?php echo $role; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</li>
				</ul>
			</div>
		</td>
	</tr>
	<tr class="rp_event_details">
		<td>
			<div class="rp_event_description">
				<strong><?php echo JText::_('Description');?>:</strong><br />
				<p><?php echo $this->event->description;?></p>
			</div>
		</td>
	</tr>
</table>
<table class="rp_container">
	<tr class="rp_event_buttons">
		<td>
			<div>
				<a href="#" id="rp_switcher_attendants" class="active rp_switchers" onclick="javascript:rpSwitchTab('attendants');return false;"><?php echo JText::_('Attendants');?></a>
			<?php if (($hasChars) && ($this->canSignup)) { ?>
				<a href="#" id="rp_switcher_signup" class="rp_switchers" onclick="javascript:rpSwitchTab('signup');return false;"><?php echo JText::_('Signup');?></a>
			<?php } ?>
			<?php if ($this->event->raid_history!='') { ?>
				<a href="#" id="rp_switcher_history" class="rp_switchers" onclick="javascript:rpSwitchTab('history');return false;"><?php echo JText::_('History');?></a>
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
							<th><?php echo JText::_('Name');?></th>
							<th><?php echo JText::_('Status');?></th>
							<th><?php echo JText::_('Role');?></th>
							<th><?php echo JText::_('Confirmed');?></th>
							<th><?php echo JText::_('Signup time');?></th>
						</tr>
					</thead>
					<tbody>
			<?php foreach ($this->attendants as $attendant) { ?>
						<tr>
							<td>
								<input type="hidden" name="characters[]" value="<?php echo $attendant->character_id;?>" />
								<a href="#" onclick="javascript:rpShowTooltip('att_char_name_<?php echo $attendant->character_id;?>');return false;" onmouseenter="javascript:rpShowTooltip('att_char_name_<?php echo $attendant->character_id;?>');" id="att_char_name_<?php echo $attendant->character_id;?>" style="color:<?php echo $attendant->class_color;?>" class="rp_tooltips" title="<?php echo $attendant->char_level." lvl. ".$attendant->class_name;?>">
									<strong><?php echo $attendant->char_name;?></strong>
								</a>
							</td>
							<td><a href="#" onclick="javascript:rpShowTooltip('att_char_queue_<?php echo $attendant->character_id;?>');return false;" onmouseenter="javascript:rpShowTooltip('att_char_queue_<?php echo $attendant->character_id;?>');" id="att_char_queue_<?php echo $attendant->character_id;?>" class="attendance<?php if ($attendant->comments!='') { ?> rp_tooltips" title="<?php echo $attendant->comments;?><?php } ?>"><?php echo JText::_('RAIDPLANNER_STATUS_'.$attendant->queue); ?></a></td>
							<td style="color:<?php echo $this->roles[$attendant->role_name]->font_color;?>;background-color:<?php echo $this->roles[$attendant->role_name]->body_color;?>;"><?php
								if (!$this->isOfficer) {
									echo $attendant->role_name;
								} else { ?>
								<select name="role[<?php echo $attendant->character_id;?>]">
									<option value="0">-</option>
								<?php foreach ($this->roles as $role) { ?>
									<option value="<?php echo $role->role_id;?>" <?php if ($role->role_id==$attendant->role_id) {?>selected="selected"<?php } ?>><?php echo $role->role_name;?></option>
								<?php } ?>
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
			<?php if ($this->isOfficer) { ?>
						<tr>
							<td>
								<select name="new_character">
									<option value=""></option>
								<?php foreach($this->all_characters as $onechar) {?>
									<option value="<?php echo $onechar->character_id;?>"><?php echo $onechar->char_name;?></option>
								<?php } ?>
								</select>
							</td>
							<td>
								<select name="new_queue">
									<?php for($i=-1;$i<=2;$i++) { if ($i!=0) { ?>
									<option value="<?php echo $i;?>"><?php echo JText::_('RAIDPLANNER_STATUS_'.$i); ?></option>
									<?php } } ?>
								</select>
							</td>
							<td>
								<select name="new_role">
									<option value="0">-</option>
								<?php foreach ($this->roles as $role) { ?>
									<option value="<?php echo $role->role_id;?>" <?php if ($role->role_id==$attendant->role_id) {?>selected="selected"<?php } ?>><?php echo $role->role_name;?></option>
								<?php } ?>
								</select>
							</td>
							<td>
								<select name="new_confirm">
									<option value="0">-</option>
									<option value="-1"><?php echo JText::_('RAIDPLANNER_CONFIRMATION_-1');?></option>
									<option value="1"><?php echo JText::_('RAIDPLANNER_CONFIRMATION_1');?></option>
									<option value="2"><?php echo JText::_('RAIDPLANNER_CONFIRMATION_2');?></option>
								</select>
							</td>
							<td>
							</td>
						</tr>
			<?php } ?>
					</tbody>
				</table>
			<?php if ($this->isOfficer) { ?>
				<input type="submit" name="SubmitButton" value="<?php echo JText::_('Save');?>" />
				<div class="rp_history_editor">
					<label><?php echo JText::_('History');?>:
						<textarea style="float:right;" name="history" rows="1" cols="20"><?php echo $this->event->raid_history; ?></textarea>
					</label>
				</div>

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
				<table>
					<tr>
						<th><?php echo JText::_('Attendance');?></th>
						<th><?php echo JText::_('Role');?></th>
						<th><?php echo JText::_('Character');?></th>
						<th><?php echo JText::_('Comments');?></th>
					</tr>
					<tr>
						<td>
							<ul class="queue">
								<li><label><input type="radio" name="queue" value="1" <?php if (($this->selfstatus->queue==1) || (intval(@$this->selfstatus->queue)==0)) { ?>checked="checked"<?php } ?> /><?php echo JText::_('RAIDPLANNER_STATUS_1');?></label></li>
								<li><label><input type="radio" name="queue" value="-1" <?php if ($this->selfstatus->queue==-1) { ?>checked="checked"<?php } ?> /><?php echo JText::_('RAIDPLANNER_STATUS_-1');?></label></li>
								<li><label><input type="radio" name="queue" value="2" <?php if ($this->selfstatus->queue==2) { ?>checked="checked"<?php } ?> /><?php echo JText::_('RAIDPLANNER_STATUS_2');?></label></li>
							</ul>
						</td>
						<td>
							<ul class="role">
							<?php foreach ($this->roles as $role) {
								if (intval($this->selfstatus->role_id)==0) {
									$this->selfstatus->role_id=$role->role_id;
								}
							?>
								<li><label><input type="radio" name="role" value="<?php echo $role->role_id;?>" <?php if ($this->selfstatus->role_id==$role->role_id) { ?>checked="checked"<?php } ?> /><?php echo $role->role_name;?></label></li>
							<?php } ?>
							</ul>
						</td>
						<td>
							<?php if($hasChars) { ?>
								<select name="character_id">
								<?php foreach ($this->characters as $character) { ?>
									<option value="<?php echo $character->character_id;?>" <?php if ($this->selfstatus->character_id==$character->character_id) { ?>selected="selected"<?php } ?> var="<?php echo $character->role_id;?>"><?php echo $character->char_name;?></option>
								<?php } ?>
								</select>
							<?php } ?>
						</td>
						<td>
							<textarea name="comments" rows="5" cols="40"><?php echo $this->selfstatus->comments; ?></textarea></label>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<input type="submit" name="SubmitButton" value="<?php echo JText::_('Save');?>" />
							<input type="hidden" name="option" value="com_raidplanner" />
							<input type="hidden" name="controller" value="" />
							<input type="hidden" name="task" value="signup" />
							<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
							<input type="hidden" name="month" value="<?php echo JHTML::_('date', $this->event->start_time, '%Y-%m'); ?>" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
<?php } ?>
<?php if ($this->event->raid_history!='') { ?>
	<tr class="rp_event_history" id="rp_event_history" style="display:none;">
		<td>
			<div class="rp_history_viewer" id="rp_history_viewer">
				<?php echo $this->event->raid_history; ?>
			</div>
		</td>
	</tr>
<?php } ?>
</table>