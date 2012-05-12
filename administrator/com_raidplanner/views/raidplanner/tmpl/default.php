<?php
/*------------------------------------------------------------------------
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

<h3>RaidPlanner</h3>
<p>Raid organizer component.</p>
<p>If RaidPlanner plugin is installed and enabled, and users has a <code>characters</code> attribute added (and characters are spearated by newline), characters are automatically assigned to Joomla User.</p>
<fieldset>
	<legend><?php echo JText::_('PLUGINS');?></legend>
	<ul>
<?php
	if ( RaidPlannerHelper::getJVersion() == '1.5') {
?>
		<li>UserMeta plugin (<?php echo JText::_('OPTIONAL'); ?>): <?php echo JText::_( JPluginHelper::isEnabled('system', 'usermeta')?'ENABLED':'DISABLED');?></li>
		<li>Mootools Upgrade plugin (<?php echo JText::_('REQUIRED'); ?>): <?php echo JText::_( JPluginHelper::isEnabled('system', 'mtupgrade')?'ENABLED':'DISABLED');?></li>
<?php
	} elseif ( RaidPlannerHelper::getJVersion() >= '1.6') {
?>
		<li>RaidPlanner User plugin: <?php echo JText::_( JPluginHelper::isEnabled('user', 'raidplanner')?'JENABLED':'JDISABLED');?></li>
<?php
	}
?>
	</ul>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('COM_RAIDPLANNER_THEME_INSTALL'); ?></legend>
	<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">
		<input type="hidden" name="task" value="doInstall" />
		<input type="hidden" name="option" value="com_raidplanner" />
		<input class="input_box" name="install_theme" type="file" size="57" />
		<input class="button" type="submit" name="submit" value="<?php echo JText::_( 'COM_RAIDPLANNER_UPLOAD_THEME' ); ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
    <table class="adminlist">
    <thead>
        <tr>
            <th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_NAME' ); ?></th>
            <th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_TYPE' ); ?></th>
            <th><?php echo JText::_( 'JGLOBAL_CREATED_DATE_ON' ); ?></th>
            <th><?php echo JText::_( 'JAUTHOR' ); ?></th>
            <th><?php echo JText::_( 'JACTION_DELETE' ); ?></th>
        </tr>
	</thead>
	<tbody>
		<?php foreach ($this->installed_plugins as $plugin) : ?>
		<tr>
			<td><span class="hasTip" title="<?php echo htmlspecialchars( $plugin['description'] );?>"><?php echo $plugin['name']; ?></span></td>
			<td><?php echo $plugin['type']; ?></td>
			<td><?php echo $plugin['creationDate'] . " (" . $plugin['version'] . ")"; ?></td>
			<td><span class="hasTip" title="<?php echo htmlspecialchars( $plugin['authorEmail'] );?>"><?php echo $plugin['author']; ?></span></td>
			<td><a href="#" onclick="return false;"><?php echo JText::_( 'JACTION_DELETE' ); ?></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	</table>
</fieldset>
<fieldset>
	<legend><?php echo JText::_('JOPTION_MENUS');?>:</legend>
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=raids">
			<span class="rp_raids"></span>
			<?php echo JText::_('COM_RAIDPLANNER_RAIDS');?>
		</a>
	</div>

	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=guilds">
			<span class="rp_guilds"></span>
			<?php echo JText::_('COM_RAIDPLANNER_GUILDS');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=characters">
			<span class="rp_chars"></span>
			<?php echo JText::_('COM_RAIDPLANNER_CHARACTERS');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=groups">
			<span class="rp_groups"></span>
			<?php echo JText::_('COM_RAIDPLANNER_GROUPS');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=roles">
			<span class="rp_roles"></span>
			<?php echo JText::_('COM_RAIDPLANNER_ROLES');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=classes">
			<span class="rp_classes"></span>
			<?php echo JText::_('COM_RAIDPLANNER_CLASSES');?>
		</a>
	</div>

	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=races">
			<span class="rp_races"></span>
			<?php echo JText::_('COM_RAIDPLANNER_RACES');?>
		</a>
	</div>

</fieldset>

<fieldset>
	<legend><?php echo JText::_('COM_RAIDPLANNER_UTILITIES');?>:</legend>
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=raidplanner&task=service">
			<span class="rp_service"></span>
			<?php echo JText::_('COM_RAIDPLANNER_DB_CHECKS');?>
		</a>
	</div>
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=stats">
			<span class="rp_stats"></span>
			<?php echo JText::_('COM_RAIDPLANNER_STATS');?>
		</a>
	</div>
</fieldset>