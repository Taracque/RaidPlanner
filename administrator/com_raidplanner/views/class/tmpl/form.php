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

if (RaidPlannerHelper::getJVersion() < '3.0') {
	RaidPlannerHelper::fixBootstrap();
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
		<div class="control-group">
			<label class="control-label" for="class_name"><?php echo JText::_( 'COM_RAIDPLANNER_CLASS_NAME' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="class_name" id="class_name" size="32" maxlength="250" value="<?php echo $this->class->class_name;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="class_color"><?php echo JText::_( 'COM_RAIDPLANNER_CLASS_COLOR' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="class_color" id="class_color" size="10" maxlength="7" value="<?php echo $this->class->class_color;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="class_css"><?php echo JText::_( 'COM_RAIDPLANNER_CSS_NAME' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="class_css" id="class_css" size="20" maxlength="45" value="<?php echo $this->class->class_css;?>" />
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="class_id" value="<?php echo $this->class->class_id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="classes" />
	<input type="hidden" name="controller" value="classes" />
</form>