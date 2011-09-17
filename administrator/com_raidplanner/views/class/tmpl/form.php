<?php
/*------------------------------------------------------------------------
# Class Form Template for RaidPlanner Component
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
		<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="class_name">
					<?php echo JText::_( 'COM_RAIDPLANNER_CLASS_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="class_name" id="class_name" size="32" maxlength="250" value="<?php echo $this->class->class_name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="class_color">
					<?php echo JText::_( 'COM_RAIDPLANNER_CLASS_COLOR' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="class_color" id="class_color" size="10" maxlength="7" value="<?php echo $this->class->class_color;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="armory_id">
					<?php echo JText::_( 'COM_RAIDPLANNER_CLASS_ARMORY_ID' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="armory_id" id="armory_id" size="10" maxlength="7" value="<?php echo $this->class->armory_id;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="class_id" value="<?php echo $this->class->class_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="classes" />
<input type="hidden" name="controller" value="classes" />
</form>