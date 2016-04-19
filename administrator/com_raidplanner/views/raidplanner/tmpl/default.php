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

if (RaidPlannerHelper::getJVersion() < '3.0') {
	RaidPlannerHelper::fixBootstrap();
}
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
	<div class="well well-small">
		<h2 class="module-title nav-header"><?php echo JText::_('COM_RAIDPLANNER_INSTALLED_PLUGINS'); ?></h2>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_NAME' ); ?></th>
					<th><?php echo JText::_( 'JVERSION' ); ?></th>
					<th><?php echo JText::_( 'JAUTHOR' ); ?></th>
					<th><?php echo JText::_( 'JDATE' ); ?></th>
					<th><?php echo JText::_( 'JSTATUS' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->installed_plugins as $plugin) : ?>
				<tr>
					<td><a href="<?php echo JRoute::_( 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $plugin->extension_id );?>"><?php echo $plugin->name; ?></a></td>
					<td><?php echo $plugin->version; ?></td>
					<td><?php echo $plugin->author; ?></td>
					<td><?php echo $plugin->releaseDate; ?></td>
					<td><?php if ($plugin->status == 1) { echo '<i class="icon-publish"></i>'; } else { echo '<i class="icon-unpublish"></i>'; } ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
