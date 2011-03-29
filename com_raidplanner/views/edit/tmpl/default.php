<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access');

$dateFormat = JText::_('%Y-%m-%d')." %H:%M";
?>
<div class="rp_container">
	<form action="<?php echo JRoute::_('index.php');?>" method="post">
	
		<label><?php echo JText::_('Event name');?>:<input type="text" name="location" value="<?php echo $this->event->location;?>"></label>
		<label><?php echo JText::_('Icon');?><select name="icon_name" id="icon_name">
			<option value=""></option>
			<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
			<option value="<?php echo $icon_file;?>"<?php if ($icon_file==$this->event->icon_name) {?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
			<?php endforeach; ?>
		</select></label>
		<br />
		<label><?php echo JText::_('Description');?>:<br />
			<textarea name="description" cols="40" rows="5"><?php echo $this->event->description;?></textarea>
		</label><br />
		<label><?php echo JText::_('Start time');?>: <?php echo JHTML::calendar(JHTML::_('date', $this->event->start_time, $dateFormat),'start_time','start_time',$dateFormat);?></label><br />
		<label><?php echo JText::_('Invite time');?>: <?php echo JHTML::calendar(JHTML::_('date', $this->event->invite_time, $dateFormat),'invite_time','invite_time',$dateFormat);?></label><br />
		<label><?php echo JText::_('Frozen');?> <input type="text" name="freeze_time" id="freeze_time" value="<?php echo $this->event->freeze_time;?>" size="3" /> <?php echo JText::_('mins. before start');?></label><br />
		<label><?php echo JText::_('Level Range');?> <input type="text" name="minimum_level" id="minimum_level" value="<?php echo $this->event->minimum_level;?>" size="3" /> - <input type="text" name="maximum_level" id="maximum_level" value="<?php echo $this->event->maximum_level;?>" size="3" /></label><br />
		<label><?php echo JText::_('Minimum Rank');?> <input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo $this->event->minimum_rank;?>" size="3" /></label><br />
	
		<input type="submit" name="SubmitButton" value="Save" />
		
		<input type="hidden" name="option" value="com_raidplanner" />
		<input type="hidden" name="controller" value="" />
		<input type="hidden" name="task" value="saveevent" />
		<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
		<input type="hidden" name="month" value="<?php echo JHTML::_('date', $this->event->start_time, '%Y-%m'); ?>" />
	</form>
</div>
