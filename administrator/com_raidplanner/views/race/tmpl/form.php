<?php
/*------------------------------------------------------------------------
# Race Form Template for RaidPlanner Component
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
			<label class="control-label" for="race_name"><?php echo JText::_( 'COM_RAIDPLANNER_RACE_NAME' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="race_name" id="race_name" size="32" maxlength="250" value="<?php echo $this->race->race_name;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="race_css"><?php echo JText::_( 'COM_RAIDPLANNER_CSS_NAME' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="race_css" id="race_css" size="32" maxlength="250" value="<?php echo $this->race->race_css;?>" />
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="race_id" value="<?php echo $this->race->race_id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="races" />
	<input type="hidden" name="controller" value="races" />
</form>