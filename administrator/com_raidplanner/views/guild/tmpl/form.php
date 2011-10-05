<?php
/*------------------------------------------------------------------------
# Guild Form Template for RaidPlanner Component
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
<div class="width-100 fltlft col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
		<ul class="adminformlist">
		<li>
			<label for="guild_name"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD_NAME' ); ?>:</label>
			<input class="text_area" type="text" name="guild_name" id="guild_name" size="32" maxlength="250" value="<?php echo $this->guild->guild_name;?>" />
		</li>
		<li>
			<label for="guild_region"><?php echo JText::_( 'COM_RAIDPLANNER_ARMORY_REGION' ); ?>:</label>
			<select name="guild_region" id="guild_region">
				<option value="eu">EU</option>
				<option value="us">US</option>
				<option value="kr">KR</option>
				<option value="tw">TW</option>
				<option value="cn">CN</option>
			</select>
		</li>
		<li>
			<label for="guild_realm"><?php echo JText::_( 'COM_RAIDPLANNER_ARMORY_REALM' ); ?>:</label>
			<input class="text_area" type="text" name="guild_realm" id="guild_realm" size="32" maxlength="250" value="<?php echo $this->guild->guild_realm;?>" />
		</li>
		<li>
			<label for="guild_level"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD_LEVEL' ); ?>:</label>
			<input class="text_area" type="text" name="guild_level" id="guild_level" size="5" maxlength="3" value="<?php echo $this->guild->guild_level;?>" />
		</li>
		<li>
			<label for="sync_now"><?php echo JText::_( 'COM_RAIDPLANNER_SYNC_NOW' ); ?>:</label>
			<input type="checkbox" name="sync_now" id="sync_now" value="1" />
		</li>
		<li>
			<label for="last_sync"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD_LAST_SYNC' ); ?>:</label>
			<input type="text" name="last_sync" value="<?php echo $this->guild->lastSync; ?>" disabled="disabled" />
		</li>
	</ul>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="guild_id" value="<?php echo $this->guild->guild_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="guild" />
<input type="hidden" name="controller" value="guilds" />
</form>