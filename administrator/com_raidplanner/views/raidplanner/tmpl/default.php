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
		<li>UserMeta plugin (<?php echo JText::_('JOPTIONAL_OPTIONAL'); ?>): <?php echo JText::_( JPluginHelper::isEnabled('system', 'usermeta')?'ENABLED':'DISABLED');?></li>
		<li>Mootools Upgrade plugin (<?php echo JText::_('JREQUIRED'); ?>): <?php echo JText::_( JPluginHelper::isEnabled('system', 'mtupgrade')?'ENABLED':'DISABLED');?></li>
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
	<legend><?php echo JText::_('COM_RAIDPLANNER_PLUGINS'); ?></legend>
	<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" class="adminform" id="adminForm">
		<input type="hidden" name="task" value="doInstall" />
		<input type="hidden" name="option" value="com_raidplanner" />
		<label for="install_theme"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_PACKAGE_FILE' );?></label> <input class="input_box" name="install_theme" id="install_theme" type="file" size="57" /><br />
		<label for="install_url"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_FROM_URL' );?></label> <input type="text" id="install_url" name="install_url" class="input_box" size="70" value=""><br />
		<label for="install_directory"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_DIRECTORY' );?></label> <input type="text" id="install_directory" name="install_directory" class="input_box" size="70" value="<?php echo JFactory::getApplication()->getCfg('tmp_path');?>"><br />
		<input class="button" type="submit" name="submit" value="<?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_INSTALL' ); ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
    <table class="adminlist">
    <thead>
        <tr>
            <th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_NAME' ); ?></th>
            <th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_TYPE' ); ?></th>
            <th><?php echo JText::_( 'JGLOBAL_CREATED_DATE' ); ?></th>
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
			<td><a href="index.php?option=com_raidplanner&task=doUninstall&plugin=<?php echo $plugin['filename'];?>" onclick="return confirm('<?php echo JText::_('COM_RAIDPLANNER_INSTALLER_CONFIRM_DELETE_PROMPT');?>');"><?php echo JText::_( 'JACTION_DELETE' ); ?></a></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	</table>
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