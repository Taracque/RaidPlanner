<?php
/*------------------------------------------------------------------------
# Event Edit Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$dateFormat = JText::_('%Y-%m-%d');
$version = new JVersion();
switch ($version->RELEASE) {
	case '1.5':
		$timeformat = '%H:%M';
	break;
	default:
	case '1.6':
		$timeformat = 'H:i';
	break;
}

$invite_time = explode( " ", JHTML::_('date', $this->event->invite_time, JText::_('DATE_FORMAT_LC4') . " " . $timeformat ) );
$start_time = explode( " ", JHTML::_('date', $this->event->start_time, JText::_('DATE_FORMAT_LC4') . " " . $timeformat ) );
?>
<div class="rp_container">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" id="rp_edit_form">
		<label><?php echo JText::_('COM_RAIDPLANNER_RAID_NAME');?>:<input type="text" name="location" value="<?php echo $this->event->location;?>"></label>
		<label><?php echo JText::_('COM_RAIDPLANNER_ICON');?><select name="icon_name" id="icon_name">
			<option value=""></option>
			<?php foreach ($this->icons as $icon_file => $icon_name) : ?>
			<option value="<?php echo $icon_file;?>"<?php if ($icon_file==$this->event->icon_name) {?> selected="selected"<?php } ?>><?php echo $icon_name;?></option>
			<?php endforeach; ?>
		</select></label>
		<label><?php echo JText::_('COM_RAIDPLANNER_TEMPLATE');?><select name="template_id" id="template_id" onchange="document.getElementById('rp_edit_form').submit();">
			<option value=""></option>
			<?php foreach ($this->templates as $template) : ?>
			<option value="<?php echo $template->raid_id;?>"><?php echo $template->location;?></option>
			<?php endforeach; ?>
		</select></label>
		<br />
		<label><?php echo JText::_('JGLOBAL_DESCRIPTION');?>:<br />
			<textarea name="description" cols="40" rows="5"><?php echo $this->event->description;?></textarea>
		</label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_START_TIME');?>: <?php echo JHTML::calendar(JHTML::_('date', $start_time[0], JText::_('DATE_FORMAT_LC4')),'start_time[1]','start_time_1',$dateFormat);?> <input type="text" name="start_time[2]" id="start_time_2" value="<?php echo $start_time[1];?>" size="6" /></label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_DURATION');?>: <input type="text" name="duration_mins" id="duration_mins" value="<?php echo $this->event->duration_mins;?>" size="3" /> <?php echo JText::_('COM_RAIDPLANNER_MINUTES');?></label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_INVITE_TIME');?>: <?php echo JHTML::calendar(JHTML::_('date', $invite_time[0], JText::_('DATE_FORMAT_LC4')),'invite_time[1]','invite_time_1',$dateFormat);?> <input type="text" name="invite_time[2]" id="invite_time_2" value="<?php echo $invite_time[1];?>" size="6" /></label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_FREEZE_TIME');?> <input type="text" name="freeze_time" id="freeze_time" value="<?php echo $this->event->freeze_time;?>" size="3" /> <?php echo JText::_('COM_RAIDPLANNER_MINUTES_BEFORE_START');?></label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_LEVEL_RANGE');?> <input type="text" name="minimum_level" id="minimum_level" value="<?php echo $this->event->minimum_level;?>" size="3" /> - <input type="text" name="maximum_level" id="maximum_level" value="<?php echo $this->event->maximum_level;?>" size="3" /></label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_MINIMUM_RANK');?> <input type="text" name="minimum_rank" id="minimum_rank" value="<?php echo $this->event->minimum_rank;?>" size="3" /></label><br />
		<label><?php echo JText::_('COM_RAIDPLANNER_GUILD');?>
			<select name="guild_id" id="guild_id">
				<option value=""></option>
				<?php foreach ($this->guild as $guild_id => $guild_name) : ?>
				<option value="<?php echo $guild_id;?>"<?php if ($guild_id==$this->event->guild_id) {?> selected="selected"<?php } ?>><?php echo $guild_name;?></option>
				<?php endforeach; ?>
			</select>
		</label><br />
	
		<input type="submit" name="SubmitButton" value="<?php echo JText::_('JSAVE');?>" />
<?php if($this->candelete) : ?>
		<script type="text/javascript">
			function confirmDelete() {
				if (confirm("<?php echo JText::_('COM_RAIDPLANNER_SURE_TO_DELETE');?>")) {
					$('task').set('value','deleteevent');
					$('rp_edit_form').submit();
				}
			}
		</script>
		<input type="button" name="DeleteButton" value="<?php echo JText::_('JACTION_DELETE');?>" onclick="confirmDelete();"/>
<?php endif; ?>
		
		<input type="hidden" name="option" value="com_raidplanner" />
		<input type="hidden" name="controller" value="" />
		<input type="hidden" name="task" id="task" value="saveevent" />
		<input type="hidden" name="raid_id" value="<?php echo $this->event->raid_id; ?>" />
	</form>
</div>
