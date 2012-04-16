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
function getStats()
{
	statReq.get({
		'start_time' : document.id('start_time').get('value'),
		'end_time' : document.id('end_time').get('value'),
		'char_id' : 0, //document.id('character_id').get('value'),
		'group_id' : document.id('group_id').get('value')
	});
}

var statReq;

window.addEvent('domready', function() {
	statReq = new Request.JSON({
		url : '<?php echo JRoute::_( 'index.php?option=com_raidplanner&controller=stats' ,false ); ?>',
		method : 'get',
		onSuccess : function (responseJSON, responseText)
		{
			document.id('chart').empty();
			var tr;
			var thead = null;
			
			responseJSON.each(function(row) {
				if (!thead) {
					thead = new Element('thead');
					tr = new Element('tr');
					tr.grab(new Element('td',{'text':'char_name'}));
					tr.grab(new Element('td',{'text':'attending'}));
					tr.grab(new Element('td',{'text':'not_attending'}));
					tr.grab(new Element('td',{'text':'confirmed'}));
					tr.grab(new Element('td',{'text':'late'}));
					tr.grab(new Element('td',{'text':'raids'}));
					thead.grab(tr);
					document.id('chart').grab(thead);
				}
				tr = new Element('tr');
				tr.grab(new Element('td',{'text':row.char_name}));
				tr.grab(new Element('td',{'text':row.attending}));
				tr.grab(new Element('td',{'text':row.not_attending}));
				tr.grab(new Element('td',{'text':row.confirmed}));
				tr.grab(new Element('td',{'text':row.late}));
				tr.grab(new Element('td',{'text':row.raids}));
				document.id('chart').grab(tr);
			});
		}
	});

	getStats();
})
</script>
<table>
	<tr>
		<td width="100%">
			<?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?>
			<?php echo JText::_('COM_RAIDPLANNER_START_TIME'); ?>:
			<?php
				echo JHTML::_('calendar', $this->lists['start_time'], 'start_time', 'start_time', '%Y-%m-%d' );
			?> - <?php
				echo JHTML::_('calendar', $this->lists['end_time'], 'end_time', 'end_time', '%Y-%m-%d' );
			?>
			<?php if (1==0): ?>
			<select name="character_id" id="character_id">
				<option></option>
				<?php foreach ($this->characters as $character_id => $character) : ?>
				<option value="<?php echo $character_id;?>"><?php echo $character['char_name'];?></option>
				<?php endforeach; ?>
			</select>
			<?php endif; ?>
			<select name="group_id" id="group_id">
				<option></option>
				<?php foreach ($this->groups as $group_id => $group) : ?>
				<option value="<?php echo $group_id;?>"><?php echo $group->group_name;?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td nowrap="nowrap">
			<button onclick="getStats();"><?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?></button>
			<button onclick="document.getElementById('search').value='';document.getElementById('start_time_min').value='';document.getElementById('start_time_max').value='';this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?></button>
		</td>
	</tr>
</table>
<table class="adminlist" id="chart">
</table>
