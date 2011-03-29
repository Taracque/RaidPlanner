<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

$dateFormat = JText::_('%Y-%m-%d');
$timeFormat = "%H:%M";

$invite_time = explode( " ", JHTML::_('date', $this->event->invite_time, $dateFormat . " " . $timeFormat ) );
$start_time = explode( " ", JHTML::_('date', $this->event->start_time, $dateFormat . " " . $timeFormat ) );
?>
<div class="rp_container">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" id="rp_edit_form">
		<label><?php echo JText::_('Event name');?>:<input type="text" name="location" value="<?php echo $this->event->location;?>"></label>
		<label><?php echo JText::_('Icon');?><select name="icon_name" id="icon_name">
			<option value=""></option>
			<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
			<option value="<?php echo $icon_file;?>"<?php if ($icon_file==$this->event->icon_name) {?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
			<?php endforeach; ?>
		</select></label>
		<label><?php echo JText::_('Template');?><select name="template_id" id="template_id" onchange="document.getElementById('rp_edit_form').submit();">
			<option value=""></option>
			<?php foreach ($this->templates as $template) : ?>
			<option value="<?php echo $template->raid_id;?>"><?php echo $template->location;?></option>
			<?php endforeach; ?>
		</select></label>
		<br />
		<label><?php echo JText::_('Description');?>:<br />
			<textarea name="description" cols="40" rows="5"><?php echo $this->event->description;?></textarea>
		</label><br />
		<label><?php echo JText::_('Start time');?>: <?php echo JHTML::calendar(JHTML::_('date', $start_time[0], $dateFormat),'start_time[1]','start_time_1',$dateFormat);?> <input type="text" name="start_time[2]" id="start_time_2" value="<?php echo $start_time[1];?>" size="6" /></label><br />
		<label><?php echo JText::_('Duration');?>: <input type="text" name="duration_mins" id="duration_mins" value="<?php echo $this->event->duration_mins;?>" size="3" /> <?php echo JText::_('minutes');?></label><br />
		<label><?php echo JText::_('Invite time');?>: <?php echo JHTML::calendar(JHTML::_('date', $invite_time[0], $dateFormat),'invite_time[1]','invite_time_1',$dateFormat);?> <input type="text" name="invite_time[2]" id="invite_time_2" value="<?php echo $invite_time[1];?>" size="6" /></label><br />
		<label><?php echo JText::_('Frozen');?> <input type="text" name="freeze_time" id="freeze_time" value="<?php echo $this->event->freeze_time;?>" size="3" /> <?php echo JText::_('mins. before start');?></label><br />
		<label><?php echo JText::_('Level Range');?> <input type="text" name="minimum_level" id="minimum_level" value="<?php echo $this->event->minimum_level;?>" size="3" /> - <input type="text" name="maximum_level" id="maximum_level" value="<?php echo $this->event->maximum_level;?>" size="3" /></label><br />
		<label><?php echo JText::_('Minimum Rank');?> <input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo $this->event->minimum_rank;?>" size="3" /></label><br />
	
		<input type="submit" name="SubmitButton" value="Save" />
		
		<input type="hidden" name="option" value="com_raidplanner" />
		<input type="hidden" name="controller" value="" />
		<input type="hidden" name="task" value="saveevent" />
		<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
	</form>
</div>
