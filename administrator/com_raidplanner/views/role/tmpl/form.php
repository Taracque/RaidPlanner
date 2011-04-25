<?php
/*------------------------------------------------------------------------
# Role Form Template for RaidPlanner Component
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
				<label for="role_name">
					<?php echo JText::_( 'Role Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="role_name" id="role_name" size="32" maxlength="250" value="<?php echo $this->role->role_name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="body_color">
					<?php echo JText::_( 'Body Color' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="body_color" id="body_color" size="10" maxlength="7" value="<?php echo $this->role->body_color;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="header_color">
					<?php echo JText::_( 'Header Color' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="header_color" id="header_color" size="10" maxlength="7" value="<?php echo $this->role->header_color;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="font_color">
					<?php echo JText::_( 'Font Color' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="font_color" id="font_color" size="10" maxlength="7" value="<?php echo $this->role->font_color;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="icon_name">
					<?php echo JText::_( 'Icon' ); ?>:
				</label>
			</td>
			<td>
				<select name="icon_name" id="icon_name">
					<option value=""></option>
				<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
					<option value="<?php echo $icon_file;?>"<?php if($icon_file==$this->role->icon_name){?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="role_id" value="<?php echo $this->role->role_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="roles" />
<input type="hidden" name="controller" value="roles" />
</form>