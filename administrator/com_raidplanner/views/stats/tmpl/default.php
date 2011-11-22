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
    chart.load({
		method: 'get',
		url: '<?php echo JRoute::_('index.php?option=com_raidplanner&task=getStats', false); ?>'
    });
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
				<th>Column A</th><th>Column B</th><th>Column C</th><th>Column D</th>
			</tr>
		</thead>
		<tbody>
			<tr><td>8.3</td><td>70</td><td>10.3</td><td>100</td></tr>
			<tr><td>8.6</td><td>65</td><td>10.3</td><td>125</td></tr>
			<tr><td>8.8</td><td>63</td><td>10.2</td><td>106</td></tr>
			<tr><td>10.5</td><td>72</td><td>16.4</td><td>162</td></tr>
			<tr><td>11.1</td><td>80</td><td>22.6</td><td>89</td></tr>
	
		</tbody>
		<tfoot>
			<tr>
				<td>Row 1</td><td>Row 2</td><td>Row 3</td><td>Row 4</td><td>Row 5</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="stats" />
</form>