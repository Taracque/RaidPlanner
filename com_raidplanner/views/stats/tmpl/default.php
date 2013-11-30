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

if (RaidPlannerHelper::getJVersion() >= '3.0') {
	JHtml::_('formbehavior.chosen', 'select');
} else {
	RaidPlannerHelper::fixBootstrap();
}
?>
<script type="text/javascript">
var colors = [
	'black','green','orange','red',''
];
var months = [];
months[ 1] = '<?php echo JText::_( 'JANUARY' );?>';
months[ 2] = '<?php echo JText::_( 'FEBRUARY' );?>';
months[ 3] = '<?php echo JText::_( 'MARCH' );?>';
months[ 4] = '<?php echo JText::_( 'APRIL' );?>';
months[ 5] = '<?php echo JText::_( 'MAY' );?>';
months[ 6] = '<?php echo JText::_( 'JUNE' );?>';
months[ 7] = '<?php echo JText::_( 'JULY' );?>';
months[ 8] = '<?php echo JText::_( 'AUGUST' );?>';
months[ 9] = '<?php echo JText::_( 'SEPTEMBER' );?>';
months[10] = '<?php echo JText::_( 'OCTOBER' );?>';
months[11] = '<?php echo JText::_( 'NOVEMBER' );?>';
months[12] = '<?php echo JText::_( 'DECEMBER' );?>';

function getStats()
{
	statReq.get({
		'start_time' : document.id('start_time').get('value'),
		'end_time' : document.id('end_time').get('value'),
		'character_id' : document.id('character_id').get('value'),
		'group_id' : document.id('group_id').get('value'),
		'guild_id' : document.id('guild_id').get('value')
	});
}

function drawBars()
{
	/* get the biggest number in the table */
	var biggest = 0;
	var cur;
	var total = 0;
	document.id('chart').getChildren('tbody')[0].getElements('td').each(function(el){
		if ((cur = el.get('text').toInt()) && (cur>biggest)) {
			biggest = cur;
		}
	});
	
	/* go thru each rows (except the first one, and draw the bars */
	var bars = [];
	var first;
	var color;
	document.id('chart').getChildren('tbody')[0].getChildren('tr').each(function(row){
		first = true;
		bars = [];
		row.getChildren('td').each(function(cell){
			var cell_val = cell;
			if (first) {
				first=false;
			} else {
				bars.push( cell.get('text').toInt() );
			}
		});
		
		/* draw the bars itself */
		total = bars[bars.length-3];
		delete bars[bars.length-1];
		delete bars[bars.length-2];
		delete bars[bars.length-3];
		total_div = new Element('div',{ style : 'width:' + Math.round(100*total/biggest) + '%;height:16px;overflow:hidden;position:relative;'});
		bars.each(function(bar,idx){
			if (colors[idx+1]!='') {
				color = colors[idx+1];
			} else {
				color = 'transparent;position:absolute;top:0;left:0;border-right:2px solid black;';
			}
			total_div.grab( new Element('div',{style : 'height:16px;width:' + Math.floor(100*bar/total) + '%;float:left;background-color:' + color}));
		},this);
		/* put the whole thing into last td element of the row */
		if (row.getChildren('td.bars').length>0) {
			row.getChildren('td.bars')[0].grab(total_div);
		}
	});
}

var statReq;

window.addEvent('domready', function() {
	if (!Object.each) {
		Object.each = $each;
	}

	statReq = new Request.JSON({
		url : '<?php echo JRoute::_( 'index.php?option=com_raidplanner&view=stats&task=getjson' ,false ); ?>',
		method : 'get',
		onSuccess : function (responseJSON, responseText)
		{
			document.id('chart').empty();
			var tr;
			var thead = new Element('thead');
			tr = new Element('tr');
			Object.each(responseJSON.titles,(function(value,key){
				tr.grab(new Element('td',{'text':value}));
			}));
			tr.grab(new Element('td',{style:'width:100%',text:'' }));
			thead.grab(tr);
			document.id('chart').grab(thead);
			
			var tbody = new Element('tbody');
			Object.each(responseJSON.data,(function(row) {
				tr = new Element('tr');
				var i = 0;
				Object.each(row,(function(value){
					if ( (i==0) && (responseJSON.type=='bymonth') ) {
						value = months[value.toInt()];
					}
					tr.grab(new Element('td',{'text':value,styles:{color:colors[i]}}));
					i++;
				}));
				tr.grab(new Element('td',{style:'width:100%',class:'bars'}));
				tbody.grab(tr);
			}));
			document.id('chart').grab(tbody);
			drawBars();
		}
	});


	getStats();
});
</script>
<div id="filter-bar" class="btn-toolbar">
	<div class="filter-search btn-group pull-left">
		<label for="start_time_min" class="element-invisible"><?php echo JText::_('COM_RAIDPLANNER_START_TIME'); ?></label>
		<div class="input-append input-prepend">
			<?php echo JHTML::_('calendar', RaidPlannerHelper::getDate(strtotime('-3 month'), null, RaidPlannerHelper::sqlDateFormat() ), 'start_time', 'start_time', '%Y-%m-%d',array('class' => 'input-small' ) ); ?>
			<span class="add-on">-</span>
			<?php echo JHTML::_('calendar', RaidPlannerHelper::getDate('now', null, RaidPlannerHelper::sqlDateFormat() ), 'end_time', 'end_time', '%Y-%m-%d', array('class' => 'input-small' ) ); ?>
		</div>
	</div>
	<div class="filter-search btn-group pull-left">
		<?php if ($this->by_chars == 1) : ?>
		<label for="character_id" class="element-invisible"><?php echo JText::_( 'COM_RAIDPLANNER_CHARACTER' ); ?></label>
		<select name="character_id" id="character_id" class="input-normal">
			<option></option>
			<?php foreach ($this->characters as $character_id => $character) : ?>
			<option value="<?php echo $character_id;?>"><?php echo $character->char_name;?></option>
			<?php endforeach; ?>
		</select>
		<?php else: ?>
		<input type="hidden" name="character_id" id="character_id" value="" >
		<?php endif; ?>
		<label for="group_id" class="element-invisible"><?php echo JText::_( 'COM_RAIDPLANNER_GROUP' ); ?></label>
		<select name="group_id" id="group_id" class="input-small">
			<option></option>
			<?php foreach ($this->groups as $group_id => $group) : ?>
			<option value="<?php echo $group_id;?>"><?php echo $group->group_name;?></option>
			<?php endforeach; ?>
		</select>
		<?php if ($this->guild_id == 0) : ?>
		<label for="guild_id" class="element-invisible"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD' ); ?></label>
		<select name="guild_id" id="guild_id" class="input-small">
			<option></option>
			<?php foreach ($this->guilds as $guild_id => $guild) : ?>
			<option value="<?php echo $guild_id;?>"><?php echo $guild->guild_name;?></option>
			<?php endforeach; ?>
		</select>
		<?php else: ?>
		<label for="guild_id" class="btn disabled"><?php echo $this->guilds[$this->guild_id]->guild_name; ?></label>
		<input type="hidden" name="guild_id" id="guild_id" value="<?php echo $this->guild_id;?>" >
		<?php endif; ?>
	</div>
	<div class="btn-group pull-left hidden-phone">
		<button class="btn" onclick="getStats();" data-original-title="<?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?>">
			<i class="icon-search"></i>
		</button>
		<button class="btn" onclick="document.id('character_id').value='';document.id('group_id').value='';document.id('start_time').value='';document.id('end_time').value='';this.form.submit();" data-original-title="<?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?>">
			<i class="icon-remove"></i>
		</button>
	</div>
</div>
<div class="clearfix clr clear"></div>
<table style="width:100%" id="chart">
</table>