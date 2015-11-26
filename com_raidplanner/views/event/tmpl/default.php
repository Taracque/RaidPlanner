<?php
/*------------------------------------------------------------------------
# Event Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$dateFormat = RaidPlannerHelper::shortDateFormat();
RaidPlannerHelper::fixBootstrap( true );
$hasChars = !empty($this->characters);
?>
<table class="rp_header_container">
	<tr>
		<td class="rp_header">
		<?php if ($this->event->icon_name) : ?>
			<img src="<?php echo JURI::base()."media/com_raidplanner/raid_icons/".$this->event->icon_name;?>" style="float:left; margin: 0 5px 5px 0;" alt="<?php echo $this->event->icon_name; ?>" />
		<?php endif; ?>
		<?php if ($this->isOfficer) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&task=edit&view=edit&id='.$this->event->raid_id);?>" class="rp_button edit btn"><?php echo JText::_('JGLOBAL_EDIT');?></a>
		<?php endif; // isOfficer ?>
			<ul>
				<li><h2><?php echo $this->event->location; ?><?php if ($this->event->guild_name) { echo " - " . $this->event->guild_name;}?></h2></li>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_INVITE_TIME');?>:</strong> <?php echo JHTML::_('date', $this->event->invite_time, $dateFormat); ?>, 
					<strong><?php echo JText::_('COM_RAIDPLANNER_START_TIME');?>:</strong> <?php echo JHTML::_('date', $this->event->start_time, $dateFormat); ?>,
					<strong><?php echo JText::_('COM_RAIDPLANNER_END_TIME');?>:</strong> <?php echo JHTML::_('date', $this->event->end_time, $dateFormat); ?>
				</li>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_RAID_LEADER');?>:</strong> <?php echo $this->event->raid_leader;?>
				</li>
			</ul>
			<div class="rp_event_description">
				<strong><?php echo JText::_('JGLOBAL_DESCRIPTION');?>:</strong><br />
				<p><?php echo $this->event->description;?></p>
			</div>
		</td>
		<td class="rp_event_roles">
			<ul>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_ATTENDING_ROLES');?> (<?php echo (isset($this->confirmed_roles[0]) && is_array($this->confirmed_roles[0]))?array_sum(@$this->confirmed_roles[0]):"0";?>):</strong><br />
					<?php if ( (isset($this->confirmed_roles[0])) && (@$this->confirmed_roles[0]) ) : ?>
						<?php foreach ($this->confirmed_roles[0] as $key => $role) : ?>
							<?php if ($this->roles[$key]->icon_name != '') : ?>
							<img src="<?php echo JURI::base()."media/com_raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
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
					<strong><?php echo JText::_('COM_RAIDPLANNER_CONFIRMED_ROLES');?>:</strong><br />
					<?php if (@$this->confirmed_roles[1]) : ?>
						<?php foreach ($this->confirmed_roles[1] as $key => $role) : ?>
							<?php if ($this->roles[$key]->icon_name != '') : ?>
							<img src="<?php echo JURI::base()."media/com_raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
							<?php else: ?>
							<strong><?php echo $key;?></strong>
							<?php endif; ?>
							: <?php echo $role; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</li>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_SITTING_ROLES');?>:</strong><br />
					<?php if (@$this->confirmed_roles[2]) : ?>
						<?php foreach ($this->confirmed_roles[2] as $key => $role) : ?>
							<?php if ($this->roles[$key]->icon_name != '') : ?>
							<img src="<?php echo JURI::base()."media/com_raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
							<?php else: ?>
							<strong><?php echo $key;?></strong>
							<?php endif; ?>
							: <?php echo $role; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</li>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_WAITLIST_ROLES');?>:</strong><br />
					<?php if (@$this->confirmed_roles[-1]) :?>
						<?php foreach ($this->confirmed_roles[-1] as $key => $role) : ?>
							<?php if ($this->roles[$key]->icon_name != '') : ?>
							<img src="<?php echo JURI::base()."media/com_raidplanner/role_icons/".$this->roles[$key]->icon_name;?>" alt="<?php echo $key;?>" />
							<?php else: ?>
							<strong><?php echo $key;?></strong>
							<?php endif; ?>
							: <?php echo $role; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</li>
				<?php if (@$this->onvacation) :?>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_USERS_ON_VACATION');?>:</strong><br />
					<?php foreach ($this->onvacation as $vacationusers) : ?>
					<?php echo $vacationusers; ?> 
					<?php endforeach; ?>
				</li>
				<?php endif; ?>
				<?php if (@$this->missingSignups) :?>
				<li>
					<strong><?php echo JText::_('COM_RAIDPLANNER_NOT_SIGNED');?>:</strong><br />
					<?php foreach ($this->missingSignups as $missingUser) : ?>
					<?php echo $missingUser; ?> 
					<?php endforeach; ?>
				</li>
				<?php endif;?>
			</ul>
		</td>
	</tr>
</table>
<div class="rp_container">
	<div class="rp_event_buttons">
		<div>
			<a href="#" id="rp_switcher_attendants" class="active rp_switchers" onclick="javascript:rpSwitchTab('attendants');return false;"><?php echo JText::_('COM_RAIDPLANNER_ATTENDANTS');?></a>
		<?php if (($hasChars) && ($this->canSignup)) : ?>
			<a href="#" id="rp_switcher_signup" class="rp_switchers" onclick="javascript:rpSwitchTab('signup');return false;"><?php echo JText::_('COM_RAIDPLANNER_SIGNUP');?></a>
		<?php endif; ?>
		<?php if ( ($this->params['show_history']==1) && ($this->event->raid_history!='') ) : ?>
			<a href="#" id="rp_switcher_history" class="rp_switchers" onclick="javascript:rpSwitchTab('history');return false;"><?php echo JText::_('COM_RAIDPLANNER_HISTORY');?></a>
		<?php endif; ?>
		<?php if (($this->finished) && ($hasChars) && ($this->params['allow_rating']==1)) : ?>
			<a href="#" id="rp_switcher_rating" class="rp_switchers" onclick="javascript:rpSwitchTab('rating');return false;"><?php echo JText::_('COM_RAIDPLANNER_RATING');?></a>
		<?php endif; ?>
		</div>
	</div>
	<div class="rp_event_attendants" id="rp_event_attendants">
		<form action="index.php" method="post">
			<table onclick="javascript:rpMakeSortable(this);">
				<thead>
					<tr>
						<th><?php echo JText::_('COM_RAIDPLANNER_CHARACTER_NAME');?></th>
						<th><?php echo JText::_('COM_RAIDPLANNER_STATUS');?></th>
						<th><?php echo JText::_('COM_RAIDPLANNER_ROLE');?></th>
						<th><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATION');?></th>
						<th><?php echo JText::_('COM_RAIDPLANNER_SIGNUP_TIME');?></th>
					</tr>
				</thead>
				<tbody>
		<?php foreach ($this->attendants as $attendant) : ?>
					<tr>
						<td class="<?php echo $attendant->class_css;?>">
							<input type="hidden" name="characters[]" value="<?php echo $attendant->character_id;?>" />
							<a href="<?php if ($guild_plugin = RaidPlannerHelper::getGuildPlugin( $attendant->guild_id) ) { echo implode(" ", $guild_plugin->trigger( 'onRPGetCharacterLink', array($attendant->char_name) ) ); } else { echo '#" onclick="javascript:rpShowTooltip(\'att_char_name_' . $attendant->character_id . '\');return false;" '; }?>" onmouseenter="javascript:rpShowTooltip('att_char_name_<?php echo $attendant->character_id;?>');" id="att_char_name_<?php echo $attendant->character_id;?>" style="color:<?php echo $attendant->class_color;?>" class="rp_tooltips" title="<?php echo $attendant->char_level." lvl. ".$attendant->class_name;?>">
								<strong><?php echo $attendant->char_name;?></strong>
							</a>
						</td>
						<td>
							<a href="#" onclick="javascript:rpShowTooltip('att_char_queue_<?php echo $attendant->character_id;?>');return false;" onmouseenter="javascript:rpShowTooltip('att_char_queue_<?php echo $attendant->character_id;?>');" id="att_char_queue_<?php echo $attendant->character_id;?>" class="attendance<?php if ($attendant->comments!='') { ?> rp_tooltips" title="<?php echo htmlspecialchars($attendant->comments, ENT_QUOTES, "UTF-8");?><?php } ?>"><?php echo JText::_('COM_RAIDPLANNER_STATUSES_'.$attendant->queue); ?></a>
							<?php if ($this->isOfficer) : ?>
								<a href="#" onclick="javascript:rpEditQueue('<?php echo $attendant->character_id;?>');return false;" id="att_char_edit_button_<?php echo $attendant->character_id;?>" class="edit_button">&nbsp;</a>
								<select name="queue[<?php echo $attendant->character_id;?>]" id="att_char_edit_queue_<?php echo $attendant->character_id;?>" style="display:none" class="input-small">
									<option value="1" <?php if ($attendant->queue==1) { ?>selected="selected"<?php } ?>><?php echo JText::_('COM_RAIDPLANNER_STATUSES_1');?></option>
									<option value="-1" <?php if ($attendant->queue==-1) { ?>selected="selected"<?php } ?>><?php echo JText::_('COM_RAIDPLANNER_STATUSES_-1');?></option>
									<option value="2" <?php if ($attendant->queue==2) { ?>selected="selected"<?php } ?>><?php echo JText::_('COM_RAIDPLANNER_STATUSES_2');?></option>
								</select>
								<input type="hidden" name="comments[<?php echo $attendant->character_id;?>]" value="<?php echo $attendant->comments;?>" />
							<?php endif; ?>
						</td>
						<td style="<?php
							if ( (isset($attendant->role_name)) && (isset($this->roles[$attendant->role_name])) ) {
								if ($this->roles[$attendant->role_name]->font_color!='') {
									echo "color:" . $this->roles[$attendant->role_name]->font_color . ";";
								}
								if ($this->roles[$attendant->role_name]->body_color!='') {
									echo "background-color:" . $this->roles[$attendant->role_name]->body_color . ";";
								}
							}
						?>"><?php
							if (!$this->isOfficer) {
								echo $attendant->role_name;
							} else { ?>
							<select name="role[<?php echo $attendant->character_id;?>]" class="input-small">
								<option value="0">-</option>
							<?php foreach ($this->roles as $role) { ?>
								<option value="<?php echo $role->role_id;?>" <?php if ($role->role_id==$attendant->role_id) {?>selected="selected"<?php } ?>><?php echo $role->role_name;?></option>
							<?php } ?>
							</select>
							<?php }							
						?></td>
						<td><?php
							if (!$this->isOfficer) {
								echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_'.$attendant->confirmed);
							} else {
						?><select name="confirm[<?php echo $attendant->character_id;?>]" class="input-small">
							<option value="0">-</option>
							<option value="-1" <?php if ($attendant->confirmed==-1) {?>selected="selected"<?php } ?>><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_-1');?></option>
							<option value="1" <?php if ($attendant->confirmed==1) {?>selected="selected"<?php } ?>><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_1');?></option>
							<option value="2" <?php if ($attendant->confirmed==2) {?>selected="selected"<?php } ?>><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_2');?></option>
							</select>
						<?php
							}
						?></td>
						<td><?php echo JHTML::_('date', $attendant->timestamp, $dateFormat);?></td>
					</tr>
		<?php endforeach; ?>
		<?php if ($this->isOfficer) : ?>
					<tr>
						<td>
							<select name="new_character" class="input-normal">
								<option value=""></option>
							<?php foreach($this->all_characters as $onechar) {?>
								<option value="<?php echo $onechar->character_id;?>"><?php echo $onechar->char_name;?></option>
							<?php } ?>
							</select>
						</td>
						<td>
							<select name="new_queue" class="input-small">
								<?php for($i=-1;$i<=2;$i++) { if ($i!=0) { ?>
								<option value="<?php echo $i;?>"><?php echo JText::_('COM_RAIDPLANNER_STATUSES_'.$i); ?></option>
								<?php } } ?>
							</select>
						</td>
						<td>
							<select name="new_role" class="input-small">
								<option value="0">-</option>
							<?php foreach ($this->roles as $role) { ?>
								<option value="<?php echo $role->role_id;?>"><?php echo $role->role_name;?></option>
							<?php } ?>
							</select>
						</td>
						<td>
							<select name="new_confirm" class="input-small">
								<option value="0">-</option>
								<option value="-1"><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_-1');?></option>
								<option value="1"><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_1');?></option>
								<option value="2"><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_2');?></option>
							</select>
						</td>
						<td>
						</td>
					</tr>
		<?php endif; ?>
				</tbody>
			</table>
		<?php if ($this->isOfficer) : ?>
			<div class="form-actions">
				<input type="submit" name="SubmitButton" value="<?php echo JText::_('JSAVE');?>" class="btn btn-primary">
				<div class="rp_history_editor">
				<?php if ($this->params['show_history']==1) : ?>
					<label style="float:right;"><?php echo JText::_('COM_RAIDPLANNER_HISTORY');?><br />
						<textarea name="history" style="float:left;" rows="3" cols="20"><?php echo $this->xml_history; ?></textarea>
					</label>
				<?php endif; ?>
				<?php if ($this->macro) : ?>
					<label style="float:right;"><?php echo JText::_('COM_RAIDPLANNER_INVITE_MACRO');?><br />
						<textarea style="float:left;" cols="20" rows="3"><?php echo $this->macro;?></textarea>
					</label>
				<?php endif; ?>
				</div>

				<input type="hidden" name="option" value="com_raidplanner" />
				<input type="hidden" name="Itemid" value="<?php if ( isset( JFactory::getApplication()->getMenu()->getActive()->id ) ) { echo JFactory::getApplication()->getMenu()->getActive()->id; } ?>" />
				<input type="hidden" name="task" value="confirm" />
				<input type="hidden" name="layout" value="default" />
				<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
			</div>
		<?php endif; ?>
		</form>
	</div>
<?php if (($hasChars) && ($this->canSignup)) : ?>
	<div class="rp_event_signup" id="rp_event_signup" style="display:none;">
		<form action="<?php echo JRoute::_('index.php');?>" method="post">
			<table>
				<tr>
					<th><?php echo JText::_('COM_RAIDPLANNER_ATTENDANCE');?></th>
					<th><?php echo JText::_('COM_RAIDPLANNER_ROLE');?></th>
					<th><?php echo JText::_('COM_RAIDPLANNER_CHARACTER');?></th>
					<th><?php echo JText::_('COM_RAIDPLANNER_COMMENTS');?></th>
				</tr>
				<tr>
					<td>
						<ul class="queue">
							<li>
								<label for="rp_radio_queue1" class="radio">
									<input type="radio" name="queue" id="rp_radio_queue1" value="1" <?php if (($this->selfstatus->queue==1) || (intval(@$this->selfstatus->queue)==0)) { ?>checked="checked"<?php } ?> />
									<?php echo JText::_('COM_RAIDPLANNER_STATUSES_1');?>
								</label>
							</li>
							<li>
								<label for="rp_radio_queue2" class="radio">
									<input type="radio" name="queue" id="rp_radio_queue2" value="-1" <?php if ($this->selfstatus->queue==-1) { ?>checked="checked"<?php } ?> />
									<?php echo JText::_('COM_RAIDPLANNER_STATUSES_-1');?>
								</label>
							</li>
							<li>
								<label for="rp_radio_queue3" class="radio">
									<input type="radio" name="queue" id="rp_radio_queue3" value="2" <?php if ($this->selfstatus->queue==2) { ?>checked="checked"<?php } ?> />
									<?php echo JText::_('COM_RAIDPLANNER_STATUSES_2');?>
								</label>
							</li>
						</ul>
					</td>
					<td>
						<ul class="role">
						<?php foreach ($this->roles as $role) {
							if (intval($this->selfstatus->role_id)==0) {
								$this->selfstatus->role_id=$role->role_id;
							}
						?>
							<li>
								<label for="rp_radio_role<?php echo $role->role_id;?>" class="radio">
									<input type="radio" name="role" id="rp_radio_role<?php echo $role->role_id;?>" value="<?php echo $role->role_id;?>" <?php if ($this->selfstatus->role_id==$role->role_id) { ?>checked="checked"<?php } ?> />
									<?php echo $role->role_name;?>
								</label>
							</li>
						<?php } ?>
						</ul>
					</td>
					<td>
						<?php if($hasChars) { ?>
							<select name="character_id">
							<?php foreach ($this->characters as $character) { ?>
								<option value="<?php echo $character->character_id;?>" <?php if ($this->selfstatus->character_id==$character->character_id) { ?>selected="selected"<?php } ?>><?php echo $character->char_name;?></option>
							<?php } ?>
							</select>
						<?php } ?>
					</td>
					<td>
						<textarea name="comments" rows="5" style="width:95%;padding:0;"><?php echo $this->selfstatus->comments; ?></textarea>
					</td>
				</tr>
<?php if ($this->params['multi_raid_signup']>0) : ?>
				<tr>
					<th colspan="4"><?php echo JText::_( 'COM_RAIDPLANNER_MULTIRAIDSIGNUP_EXPLANATION' );?></th>
				</tr>
				<tr>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_START_TIME' );?></th>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_LOCATION' );?></th>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_STATUS' );?></th>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_SIGNUP' );?></th>
				<tr>
<?php foreach ($this->upcoming as $upcoming) : ?>
	<?php if ($upcoming->raid_id != $this->event->raid_id) : ?>
				<tr class="rp_hover">
					<td><label for="signup_raid_<?php echo $upcoming->raid_id; ?>" class="checkbox"><?php echo JHTML::_('date', $upcoming->start_time, $dateFormat); ?></label></td>
					<td><a href="#" title="<?php echo $upcoming->description; ?>"><?php echo $upcoming->location; ?><?php if (@$upcoming->guild_name) { echo " - " . $upcoming->guild_name;}?></a></td>
					<td><?php echo JText::_('COM_RAIDPLANNER_STATUSES_' . intval($upcoming->queue) );?></td>
					<td><label for="signup_raid_<?php echo $upcoming->raid_id; ?>" class="checkbox"><input type="checkbox" value="1" name="signup_raid[<?php echo $upcoming->raid_id;?>]" id="signup_raid_<?php echo $upcoming->raid_id; ?>"><?php echo JText::_( 'COM_RAIDPLANNER_SIGNUP' );?></label>
				</tr>
	<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
				<tr>
					<td colspan="4">
						<div class="form-actions">
							<input type="submit" name="SubmitButton" value="<?php echo JText::_('Save');?>" class="btn btn-primary" />
							<input type="hidden" name="option" value="com_raidplanner" />
							<input type="hidden" name="task" value="signup" />
							<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
<?php endif; ?>
<?php if ( ($this->params['show_history']==1) && ($this->event->raid_history!='') ) : ?>
	<div class="rp_event_history" id="rp_event_history" style="display:none;">
		<div class="rp_history_viewer" id="rp_history_viewer">
			<?php echo $this->event->raid_history; ?>
		</div>
	</div>
<?php endif; ?>
<?php if (($this->finished) && ($hasChars) && ($this->params['allow_rating']==1)) : ?>
	<div class="rp_event_rating" id="rp_event_rating" style="display:none;">
		<form action="index.php" method="post">
			<table class="table-striped">
				<thead>
		<?php if ($this->canRate) : ?>
					<tr>
						<th colspan="2">
							<?php echo JText::_( 'COM_RADIPLANNER_RATING_EXPLANATION' ); ?>
						</th>
					</tr>
		<?php endif; ?>
					<tr>
						<th><?php echo JText::_('COM_RAIDPLANNER_CHARACTER_NAME');?></th>
						<th><?php echo JText::_('COM_RAIDPLANNER_RATING');?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong><?php echo JText::_( 'COM_RAIDPLANNER_RAID_RATING' ); ?></strong></td>
						<td>
		<?php
			$rating = @$this->ratings[0]->rating;
			if ($rating > 0) {
				$green = (int) 255; $red = ((1-$rating) * 255); $blue = $red;
			} else {
				$red = (int) 255; $green = ( (1-abs($rating)) * 255); $blue = $green;
			}
		?>
							<div class="rp_rating" style="background-color:rgb(<?php echo $red;?>,<?php echo $green; ?>,<?php echo $blue; ?>);">
								<span><?php echo (100*$rating)."%";?></span>
							</div>
		<?php if ($this->canRate) : ?>
							<div class="controls">
								<fieldset class="radio btn-group rp-vote">
									<input type="radio" id="raid_vote_neg" name="character_vote[0]" value="-1">
									<label for="raid_vote_neg" class="btn btn-mini"><i class="icon-minus"></i></label>
									<input type="radio" id="raid_vote_pass" name="character_vote[0]" value="0" checked="checked">
									<label for="raid_vote_pass" class="btn btn-mini"><i class="icon-question"></i></label>
									<input type="radio" id="raid_vote_pos" name="character_vote[0]" value="1">
									<label for="raid_vote_pos" class="btn btn-mini"><i class="icon-plus"></i></label>
								</fieldset>
							</div>
		<?php endif; ?>
						</td>
					</tr>
		<?php foreach ($this->attendants as $attendant) : ?>
		<?php	if ($attendant->queue > 0) : ?>
					<tr>
						<td class="<?php echo $attendant->class_css;?>">
							<a href="#" onclick="javascript:rpShowTooltip('rate_char_name_<?php echo $attendant->character_id;?>');return false;" onmouseenter="javascript:rpShowTooltip('rate_char_name_<?php echo $attendant->character_id;?>');" id="rate_char_name_<?php echo $attendant->character_id;?>" style="color:<?php echo $attendant->class_color;?>" class="rp_tooltips" title="<?php echo $attendant->char_level." lvl. ".$attendant->class_name;?>">
								<strong><?php echo $attendant->char_name;?></strong>
							</a>
						</td>
						<td>
		<?php
			$rating = @$this->ratings[$attendant->character_id]->rating;
			if ($rating > 0) {
				$green = (int) 255; $red = ((1-$rating) * 255); $blue = $red;
			} else {
				$red = (int) 255; $green = ( (1-abs($rating)) * 255); $blue = $green;
			}
		?>
							<div class="rp_rating" style="background-color:rgb(<?php echo $red;?>,<?php echo $green; ?>,<?php echo $blue; ?>);">
								<span><?php echo (100*$rating)."%";?></span>
							</div>
		<?php if ($this->canRate) : ?>
							<div class="controls">
								<fieldset class="radio btn-group rp-vote">
									<input type="radio" id="char_vote_neg_<?php echo $attendant->character_id;?>" name="character_vote[<?php echo $attendant->character_id;?>]" value="-1">
									<label for="char_vote_neg_<?php echo $attendant->character_id;?>" class="btn btn-mini"><i class="icon-minus"></i></label>
									<input type="radio" id="char_vote_pass_<?php echo $attendant->character_id;?>" name="character_vote[<?php echo $attendant->character_id;?>]" value="0" checked="checked">
									<label for="char_vote_pass_<?php echo $attendant->character_id;?>" class="btn btn-mini"><i class="icon-question"></i></label>
									<input type="radio" id="char_vote_pos_<?php echo $attendant->character_id;?>" name="character_vote[<?php echo $attendant->character_id;?>]" value="1">
									<label for="char_vote_pos_<?php echo $attendant->character_id;?>" class="btn btn-mini"><i class="icon-plus"></i></label>
								</fieldset>
							</div>
		<?php endif; ?>
						</td>
					</tr>
		<?php	endif; ?>
		<?php endforeach; ?>
		<?php if ($this->canRate) : ?>
					<tr>
						<td colspan="2">
							<div class="form-actions">
								<input type="submit" name="SubmitButton" value="<?php echo JText::_('JSAVE');?>" class="btn btn-primary">
								<input type="hidden" name="option" value="com_raidplanner" />
								<input type="hidden" name="Itemid" value="<?php if ( isset( JFactory::getApplication()->getMenu()->getActive()->id ) ) { echo JFactory::getApplication()->getMenu()->getActive()->id; } ?>" />
								<input type="hidden" name="task" value="rate" />
								<input type="hidden" name="layout" value="default" />
								<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
							</div>
						</td>
					</tr>
		<?php endif; ?>
				</tbody>
			</table>
		</form>
	</div>
<?php endif; ?>
</div>