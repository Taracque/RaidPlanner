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
			<div class="clr"></div>
			<fieldset>
				<legend>
					<label for="sync_plugin"><?php echo JText::_( 'COM_RAIDPLANNER_SYNC_PLUGIN' ); ?>:</label>
					<select name="sync_plugin" id="sync_plugin" style="float: none;" onchange="document.id('plugin_settings').setStyle('display','none');document.id('plugin_settings_warning').setStyle('display','');">
						<option value=""></option>
						<?php foreach ($this->sync_plugins as $plugin) :?>
							<option value="<?php echo $plugin;?>" <?php if ($plugin==$this->guild->sync_plugin) { echo "selected=\"selected\" ";}?>><?php echo $plugin;?></option>
						<?php endforeach; ?>
					</select>
				</legend>
				<strong id="plugin_settings_warning" style="display:none;"><?php echo JText::_( 'COM_RAIDPLANNER_SAVE_TOSET_PLUGIN' );?></strong>
				<ul id="plugin_settings" class="adminformlist">
					<?php foreach ($this->sync_params as $param) : ?>
					<li>
						<label for="params_<?php echo $param['name'];?>"><?php echo JText::_( $param['label'] ); ?>:</label>
						<?php if ($param['type'] == 'list') :?>
							<select name="params[<?php echo $param['name'];?>]" id="params_<?php echo $param['name'];?>">
								<option></option>
							<?php foreach ($param['data'] as $option) :?>
								<option value="<?php echo $option['value'];?>" <?php if ($option['value'] == $this->guild->params[$param['name']]) { echo "selected=\"selected\" ";}?>><?php echo JText::_( $option['label'] ); ?></option>
							<?php endforeach; ?>
							</select>
						<?php else: ?>
							<input type="text" name="params[<?php echo $param['name'];?>]" id="params_<?php echo $param['name'];?>" value="<?php echo $this->guild->params[$param['name']];?>" />
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
					<?php if ($this->do_sync) : ?>
					<li>
						<label for="sync_now"><?php echo JText::_( 'COM_RAIDPLANNER_SYNC_NOW' ); ?>:</label>
						<input type="checkbox" name="sync_now" id="sync_now" value="1" />
					</li>
					<li>
						<label for="last_sync"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD_LAST_SYNC' ); ?>:</label>
						<input type="text" name="last_sync" value="<?php echo $this->guild->lastSync; ?>" disabled="disabled" />
					</li>
					<?php endif; ?>
				</ul>
			</fieldset>
		</li>
	</ul>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="guild_id" value="<?php echo $this->guild->guild_id; ?>" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="guild" />
<input type="hidden" name="controller" value="guilds" />
</form>