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
var attendanceChart;
var raidLocationsChart;

function loadAttendance()
{
    attendanceChart.load({
		method: 'get',
		url: '<?php echo JRoute::_('index.php?option=com_raidplanner&task=getStats&type=attendance', false); ?>&from=' + document.id('start_time').get('value') + '&to=' + document.id('end_time').get('value')
    });
}

function loadRaidLocations()
{
    raidLocationsChart.load({
		method: 'get',
		url: '<?php echo JRoute::_('index.php?option=com_raidplanner&task=getStats&type=raidlocations', false); ?>&from=' + document.id('start_time').get('value') + '&to=' + document.id('end_time').get('value')
    });
}

function loadCharts()
{
	loadAttendance();
	loadRaidLocations();
}

window.addEvent('domready', function() {
    attendanceChart = new MilkChart.Bar("attendanceChart",{height:2000,width:600});
    raidLocationsChart = new MilkChart.Column("raidLocationsChart",{width:600,height:400});
    loadCharts();
})
</script>

	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?>
				<?php echo JText::_('COM_RAIDPLANNER_START_TIME'); ?>:
				<?php
					echo JHTML::_('calendar', RaidPlannerHelper::getDate(strtotime('-1 month')), 'start_time', 'start_time', '%Y-%m-%d' );
				?> - <?php
					echo JHTML::_('calendar', RaidPlannerHelper::getDate('now'), 'end_time', 'end_time', '%Y-%m-%d' );
				?>
			</td>
			<td nowrap="nowrap">
				<button onclick="loadCharts();"><?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?></button>
			</td>
		</tr>
	</table>
    <table id="attendanceChart">
		<thead>
			<tr>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr><td></td></tr>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
			</tr>
		</tfoot>
	</table>
    <table id="raidLocationsChart">
		<thead>
			<tr>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr><td></td></tr>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
			</tr>
		</tfoot>
	</table>
