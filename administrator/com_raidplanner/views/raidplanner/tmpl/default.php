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
	<div class="well well-small">
		<h2 class="module-title nav-header"><?php echo JText::_('COM_RAIDPLANNER_INSTALLED_PLUGINS'); ?></h2>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_NAME' ); ?></th>
					<th><?php echo JText::_( 'COM_RAIDPLANNER_PLUGIN_TYPE' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->installed_plugins as $plugin) : ?>
				<tr>
					<td><?php echo $plugin->name; ?></td>
					<td><?php echo $plugin->element; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
