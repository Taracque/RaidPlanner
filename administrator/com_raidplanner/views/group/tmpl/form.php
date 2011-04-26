<?php
/*------------------------------------------------------------------------
# Group Form Template for RaidPlanner Component
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
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="group_name">
					<?php echo JText::_( 'Group Name' ); ?>:
				</label>
			</td>
			<td>
				<?php if ($this->group->group_name != 'Guest') : ?>
				<input class="text_area" type="text" name="group_name" id="group_name" size="32" maxlength="250" value="<?php echo $this->group->group_name;?>" />
				<?php else: ?>
				<input type="hidden" name="group_name" id="group_name" value="<?php echo $this->group->group_name;?>" /><?php echo $this->group->group_name;?>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="default">
					<?php echo JText::_( 'Default' ); ?>:
				</label>
			</td>
			<td>
				<input type="checkbox" type="checkbox" name="default" id="default" value="1" <?php if ($this->group->default==1) {?>checked="checked"<?php }?> />
			</td>
		</tr>
		<?php if ($this->group->group_name != 'Guest') : ?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="group_members">
					<?php echo JText::_( 'Membership' ); ?>:
				</label>
			</td>
			<td>
				<table class="adminlist">
					<thead style="display:block;width:300px;">
						<tr>
							<th width="20">&nbsp;</th>
							<th width="280"><?php echo JText::_( 'User' ); ?></th>
						</tr>
					</thead>
					<tbody style="height:200px;overflow:scroll;display:block;width:300px;">
			<?php
				$i=0;
				foreach ($this->users as $user ) {
			?>
					<tr>
						<td width="20">
							<input type="checkbox" name="members[<?php echo $user->id;?>]" value="<?php echo $user->id;?>" onclick="isChecked(this.checked);"<?php if(isset($this->group_users[$user->id])) {?> checked="checked"<?php }?>>
						</td>
						<td width="260">
							<?php echo $user->name;?> (<?php echo $user->group_name;?>)
						</td>
					</tr>
			<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td width="100" align="right" class="key">
				<label for="group_members">
					<?php echo JText::_( 'Permissions' ); ?>:
				</label>
			</td>
			<td>
				<table class="adminlist">
					<thead style="display:block;width:300px;">
						<tr>
							<th width="20">&nbsp;</th>
							<th width="280"><?php echo JText::_( 'Permissions' ); ?></th>
						</tr>
					</thead>
					<tbody style="height:200px;overflow:scroll;display:block;width:300px;">
			<?php
				$i=0;
				foreach ($this->permissions as $permission_name => $permission_value ) {
			?>
					<tr>
						<td width="20">
							<input type="checkbox" name="permissions[<?php echo $permission_name;?>]" value="1" onclick="isChecked(this.checked);"<?php if($permission_value==1) {?> checked="checked"<?php }?>>
						</td>
						<td width="260">
							<?php echo $permission_name;?>
						</td>
					</tr>
			<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="group_id" value="<?php echo $this->group->group_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="groups" />
<input type="hidden" name="controller" value="groups" />
</form>