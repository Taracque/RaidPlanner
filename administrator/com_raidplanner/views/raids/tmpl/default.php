<?php
/*------------------------------------------------------------------------
# Raids List Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label for="search" class="element-invisible"><?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?></label>
			<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" >
		</div>
		<div class="btn-group pull-left hidden-phone">
			<label for="start_time_min" class="element-invisible"><?php echo JText::_('COM_RAIDPLANNER_START_TIME'); ?></label>
			<div class="input-append input-prepend">
				<?php echo JHTML::_('calendar', $this->lists['start_time_min'], 'start_time_min', 'start_time_min', '%Y-%m-%d' , array('class' => 'input-small' ) ); ?>
				<span class="add-on">-</span>
				<?php echo JHTML::_('calendar', $this->lists['start_time_max'], 'start_time_max', 'start_time_max', '%Y-%m-%d' , array('class' => 'input-small' ) ); ?>
			</div>
			<label for="guild_filter" class="element-invisible"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD' ); ?></label>
			<select name="guild_filter" onchange="document.adminForm.submit();" class="input-medium">
				<option></option>
				<?php foreach ($this->guilds as $guild_id => $guild): ?>
				<option value="<?php echo $guild_id;?>" <?php if ($guild_id == $this->lists['guild_filter']) { echo "selected=\"selected\""; } ?>><?php echo $guild->guild_name;?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button onclick="this.form.submit();" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?>">
				<i class="icon-search"></i>
			</button>
			<button onclick="document.getElementById('search').value='';document.getElementById('start_time_min').value='';document.getElementById('start_time_max').value='';this.form.submit();" class="btn hasTooltip" data-original-title="<?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?>">
				<i class="icon-remove"></i>
			</button>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	</div>
	<div class="clearfix"> </div>
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this);" />
				</th>
				<th width="100">
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_START_TIME', 'r.start_time', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_LOCATION', 'r.location', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_MINIMUM_LEVEL', 'r.minimum_level', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_MAXMIUM_LEVEL', 'r.maximum_level', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_MINIMUM_RANK', 'r.minimum_rank', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_INVITED_GROUP', 'g.group_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_GUILD', 'gu.guild_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="100">
					<?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_TEMPLATE', 'r.is_template', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'r.id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		$i = 0;
		$ranks = RaidPlannerHelper::getRanks();
		foreach ($this->raids as &$row)
		{
			$checked    = JHTML::_( 'grid.id', $i++, $row->raid_id );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<?php echo JHTML::_('date', $row->start_time, JText::_('DATE_FORMAT_LC4') . " H:i"  ); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=raids&view=raid&task=edit&cid[]='.$row->raid_id);?>"><?php echo $row->location; ?></a>
				</td>
				<td>
					<?php echo $row->minimum_level; ?>
				</td>
				<td>
					<?php echo $row->maximum_level; ?>
				</td>
				<td>
					<?php echo @$ranks[$row->minimum_rank]; ?>
				</td>
				<td>
					<?php echo $row->group_name; ?>
				</td>
				<td>
					<?php echo $row->guild_name; ?>
				</td>
				<td>
					<?php echo ($row->is_template == 0) ? '-' : ( ($row->is_template == 1) ? JText::_( 'JYES' ) : JText::sprintf( 'COM_RAIDPLANNER_DAYS_BEFORE', abs($row->is_template) ) ) ; ?>
				</td>
				<td>
					<?php echo $row->raid_id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="raids" />
	<input type="hidden" name="view" value="raids" />
</form>
