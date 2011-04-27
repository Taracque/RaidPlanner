<?php
/*------------------------------------------------------------------------
# Preview Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$dateFormat = JText::_('DATE_FORMAT_LC2');
?>
<fieldset class="rp_preview">
	<legend><?php echo JText::_('Preview');?></legend>
	<table class="rp_container">
		<tr class="rp_event_details">
			<td>
			<?php if ($this->event->icon_name) : ?>
				<img src="<?php echo JURI::base()."images/raidplanner/raid_icons/".$this->event->icon_name;?>" float="left" style="float:left; margin: 0 5px 5px 0;" />
			<?php endif; ?>
				<div class="rp_event_description">
					<strong><?php echo JText::_('Edit');?>:</strong><br />
					<p><?php echo $this->event->description;?></p>
				</div>
				<div class="rp_event_roles">
					<ul>
						<li>
							<strong><?php echo JText::_('Confirmed Roles');?>:</strong><br />
							<?php if (@$this->confirmed_roles[1]) foreach ($this->confirmed_roles[1] as $key => $role) { ?>
								<strong><?php echo $key;?>:</strong> <?php echo $role; ?>
							<?php } ?>
						</li>
						<li>
							<strong><?php echo JText::_('Sitting Roles');?>:</strong><br />
							<?php if (@$this->confirmed_roles[2]) foreach ($this->confirmed_roles[2] as $key => $role) { ?>
								<strong><?php echo $key;?>:</strong> <?php echo $role; ?>
							<?php } ?>
						</li>
						<li>
							<strong><?php echo JText::_('Waiting Roles');?>:</strong><br />
							<?php if (@$this->confirmed_roles[-1]) foreach ($this->confirmed_roles[-1] as $key => $role) { ?>
								<strong><?php echo $key;?>:</strong> <?php echo $role; ?>
							<?php } ?>
						</li>
					</ul>
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
								<th><?php echo JText::_('Signeup Time');?></th>
							</tr>
						</thead>
						<tbody>
				<?php foreach ($this->attendants as $attendant) { 
				?>
							<tr>
								<td><input type="hidden" name="characters[]" value="<?php echo $attendant->character_id;?>" /><span style="color:<?php echo $attendant->class_color;?>" class="hasTip" title="<?php echo $attendant->char_level." lvl. ".$attendant->class_name;?>"><?php echo $attendant->char_name;?></span></td>
								<td><?php echo JText::_('RAIDPLANNER_STATUS_'.$attendant->queue); ?></td>
								<td><span <?php if ($attendant->comments!='') { ?>class="hasTip" title="<?php echo htmlspecialchars( $attendant->comments, ENT_QUOTES, 'UTF-8' );?>"<?php } ?>><?php echo $attendant->role_name; ?></span></td>
								<td><?php echo JText::_('RAIDPLANNER_CONFIRMATION_'.$attendant->confirmed); ?></td>
								<td><?php echo JHTML::_('date', $attendant->timestamp, $dateFormat);?></td>
							</tr>
				<?php } ?>
						</tbody>
					</table>
				</form>
			</td>
		</tr>
	</table>
</fieldset>