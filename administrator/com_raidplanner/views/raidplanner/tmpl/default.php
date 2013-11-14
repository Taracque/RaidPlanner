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
<div class="row-fluid">
	<div class="well well-small">
		<h2 class="module-title nav-header"><?php echo JText::_('COM_RAIDPLANNER_STATUS');?></h2>
		<ul>
			<li>RaidPlanner User plugin: <?php echo JText::_( JPluginHelper::isEnabled('user', 'raidplanner')?'JENABLED':'JDISABLED');?></li>
		</ul>
		<a href="index.php?option=com_raidplanner&view=raidplanner&task=service" class="btn">
			<?php echo JText::_('COM_RAIDPLANNER_DB_CHECKS');?>
		</a>
	</div>
</div>
<div class="row-fluid">
	<h2 class="module-title nav-header"><?php echo JText::_('COM_RAIDPLANNER_INSTALL_PLUGIN'); ?></h2>
	<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" class="form-horizontal" id="adminForm">
		<ul class="nav nav-tabs" id="myTabTabs">
			<li class="active">
				<a href="#upload" data-toggle="tab"><?php echo JText::_( 'COM_RAIDPLANNER_UPLOAD_FROM_FILE' );?></a>
			</li>
			<li>
				<a href="#download" data-toggle="tab"><?php echo JText::_( 'COM_RAIDPLANNER_DOWNLOAD_FROM_URL' );?></a>
			</li>
			<li>
				<a href="#directory" data-toggle="tab"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALL_FROM_DIRECTORY' );?></a>
			</li>
		</ul>
		<div class="tab-content" id="myTabContent">
			<div id="upload" class="tab-pane active">
				<fieldset class="uploadform">
					<legend><?php echo JText::_( 'COM_RAIDPLANNER_UPLOAD_FROM_FILE' );?></legend>
					<div class="control-group">
						<label for="install_theme" class="control-label"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_PACKAGE_FILE' );?></label>
						<div class="controls">
							<input class="input_box" name="install_theme" id="install_theme" type="file" size="57">
						</div>
					</div>
				</fieldset>
			</div>
			<div id="download" class="tab-pane">
				<fieldset class="uploadform">
					<legend><?php echo JText::_( 'COM_RAIDPLANNER_DOWNLOAD_FROM_URL' );?></legend>
					<div class="control-group">
						<label for="install_url" class="control-label"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_FROM_URL' );?></label>
						<div class="controls">
							<input type="text" id="install_url" name="install_url" class="input_box" size="70" value="">
						</div>
					</div>
				</fieldset>
			</div>
			<div id="directory" class="tab-pane">
				<fieldset class="uploadform">
					<legend><?php echo JText::_( 'COM_RAIDPLANNER_INSTALL_FROM_DIRECTORY' );?></legend>
					<div class="control-group">
						<label for="install_directory" class="control-label"><?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_DIRECTORY' );?></label>
						<div class="controls">
							<input type="text" id="install_directory" name="install_directory" class="input_box" size="70" value="<?php echo JFactory::getApplication()->getCfg('tmp_path');?>">
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<input type="hidden" name="task" value="doInstall" />
		<input type="hidden" name="option" value="com_raidplanner" />
		<div class="form-actions">
			<input class="button btn btn-primary" type="submit" name="submit" value="<?php echo JText::_( 'COM_RAIDPLANNER_INSTALLER_INSTALL' ); ?>" />
		</div>
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
<div class="row-fluid">
	<div class="well well-small">
		<h2 class="module-title nav-header"><?php echo JText::_('COM_RAIDPLANNER_INSTALLED_PLUGINS'); ?></h2>
		<table class="table table-striped">
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
	</div>
</div>
