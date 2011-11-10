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
<script type="text/javascript">
window.addEvent('domready', function() {
    var chart = new MilkChart.Line("chart");
})
</script>
<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?>
				<?php echo JText::_('COM_RAIDPLANNER_START_TIME'); ?>:
				<?php
					echo JHTML::_('calendar', $this->lists['start_time_min'], 'start_time_min', 'start_time_min', '%Y-%m-%d' );
				?> - <?php
					echo JHTML::_('calendar', $this->lists['start_time_max'], 'start_time_max', 'start_time_max', '%Y-%m-%d' );
				?>
			</td>
			<td nowrap="nowrap">
				<button onclick="this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?></button>
				<button onclick="document.getElementById('search').value='';document.getElementById('start_time_min').value='';document.getElementById('start_time_max').value='';this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?></button>
			</td>
		</tr>
	</table>
    <table class="adminlist" id="chart">
		<thead>
			<tr>
				<th><?php echo JText::_('COM_RAIDPLANNER_STATUSES_-1');?></th>
				<th><?php echo JText::_('COM_RAIDPLANNER_STATUSES_1');?></th>
				<th><?php echo JText::_('COM_RAIDPLANNER_STATUSES_2');?></th>
				<th><?php echo JText::_('COM_RAIDPLANNER_TOTAL_SIGNED');?></th>
				<th><?php echo JText::_('COM_RAIDPLANNER_CONFIRMATIONS_1');?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>10</td>
				<td>98</td>
				<td>8</td>
				<td>206</td>
				<td>133</td>
			</tr>
			<tr>
				<td>3</td>
				<td>44</td>
				<td>2</td>
				<td>106</td>
				<td>99</td>
			</tr>
		</tbody>
		<tfoot>
			<tr><td>Dögrovás</td><td>Minsa</td></tr>
		</tfoot>
	</table>

	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="stats" />
</form>