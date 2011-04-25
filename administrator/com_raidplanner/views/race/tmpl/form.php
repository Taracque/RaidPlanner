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
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="race_name">
					<?php echo JText::_( 'Race Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="race_name" id="race_name" size="32" maxlength="250" value="<?php echo $this->race->race_name;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="race_id" value="<?php echo $this->race->race_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="races" />
<input type="hidden" name="controller" value="races" />
</form>