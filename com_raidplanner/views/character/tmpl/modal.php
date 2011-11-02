<?php
/*------------------------------------------------------------------------
# Character Editor Modal Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$function	= JRequest::getCmd('function', '');
?>
<table class="rp_container">
	<tr>
		<td class="rp_event_buttons">
			<div>
				<?php if ($this->canEdit) : ?>
				<a href="#" id="rp_switcher_selectchar" class="rp_switchers active" style="width:45%" onclick="javascript:rpSwitchTab('selectchar');return false;"><?php echo JText::_('COM_RAIDPLANNER_SELECT_CHARACTER');?></a>
					<?php if (@$this->character->character_id >0) : ?>
				<a href="#" id="rp_switcher_newchar" class="rp_switchers" style="width:45%" onclick="javascript:rpSwitchTab('newchar');return false;"><?php echo JText::_('COM_RAIDPLANNER_EDIT_CHARACTER');?></a>
					<?php else: ?>
				<a href="#" id="rp_switcher_newchar" class="rp_switchers" style="width:45%" onclick="javascript:rpSwitchTab('newchar');return false;"><?php echo JText::_('COM_RAIDPLANNER_ADD_NEW_CHARACTER');?></a>
					<?php endif; ?>
				<?php else: ?>
				<a href="#" id="rp_switcher_selectchar" class="rp_switchers active" style="width:100%" onclick="javascript:rpSwitchTab('selectchar');return false;"><?php echo JText::_('COM_RAIDPLANNER_SELECT_CHARACTER');?></a>
				<?php endif; ?>
			</div>
		</td>
	</tr>
	<tr id="rp_event_selectchar">
		<td>
			<label for="select_char"><?php echo JText::_('COM_RAIDPLANNER_SELECT_CHARACTER');?>:</label>
			<select name="select_char" onchange="javascript:if (window.parent) window.parent.<?php echo $this->escape($function);?>(<?php echo JRequest::getInt('fieldidx');?>, this.get('value'));">
				<option></option>
				<?php foreach ($this->characters as $character_id => $character) : ?>
				<option value="<?php echo $character['char_name'];?>" <?php if ($character_id==@$this->character->character_id) {?>selected="selected"<?php } ?>><?php echo $character['char_name'];?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<?php if ($this->canEdit) : ?>
	<tr id="rp_event_newchar" style="display:none;">
		<td>
			<form action="<?php echo JRoute::_('index.php');?>" method="post" id="rp_edit_form">
				<label><?php echo JText::_('COM_RAIDPLANNER_CHARACTER_NAME');?>:<input type="text" id="char_name" name="char_name" value="<?php echo @$this->character->char_name;?>"></label><br />
				<label><?php echo JText::_('COM_RAIDPLANNER_CLASS');?><select name="class_id" id="class_id">
					<?php foreach ($this->classes as $class_id => $class) : ?>
					<option value="<?php echo $class_id;?>"<?php if ($class_id==@$this->character->class_id) {?> selected="selected"<?php } ?>><?php echo $class->class_name;?></option>
					<?php endforeach; ?>
				</select></label><br />
				<label><?php echo JText::_('COM_RAIDPLANNER_GENDER');?><select name="gender_id" id="gender_id">
					<?php foreach ($this->genders as $gender_id => $gender) : ?>
					<option value="<?php echo $gender_id;?>"<?php if ($gender_id==@$this->character->gender_id) {?> selected="selected"<?php } ?>><?php echo $gender->gender_name;?></option>
					<?php endforeach; ?>
				</select></label><br />
				<label><?php echo JText::_('COM_RAIDPLANNER_RACE');?><select name="race_id" id="race_id">
					<?php foreach ($this->races as $race_id => $race) : ?>
					<option value="<?php echo $race_id;?>"<?php if ($race_id==@$this->character->race_id) {?> selected="selected"<?php } ?>><?php echo $race->race_name;?></option>
					<?php endforeach; ?>
				</select></label><br />
				<label><?php echo JText::_('COM_RAIDPLANNER_LEVEL');?>: <input type="text" name="char_level" id="char_level" value="<?php echo @$this->character->char_level;?>" size="10" /></label><br />
				<label><?php echo JText::_('COM_RAIDPLANNER_RANK');?>: 
					<select name="rank" id="rank">
						<?php foreach (RaidPlannerHelper::getRanks() as $rank_id => $rank) : ?>
						<option value="<?php echo $rank_id;?>"<?php if ($rank_id == @$this->character->rank) {?> selected="selected"<?php } ?>><?php echo $rank;?></option>
						<?php endforeach; ?>
					</select>
				</label><br />
				<label><?php echo JText::_('COM_RAIDPLANNER_GUILD');?>
					<select name="guild_id" id="guild_id">
						<?php foreach ($this->guilds as $guild_id => $guild) : ?>
						<option value="<?php echo $guild_id;?>"<?php if ($guild_id==@$this->character->guild_id) {?> selected="selected"<?php } ?>><?php echo $guild->guild_name;?></option>
						<?php endforeach; ?>
					</select>
				</label><br />
			
				<input type="submit" name="SubmitButton" value="<?php echo JText::_('JSAVE');?>" />
				<?php if (@$this->character->character_id>0) : ?>
				<input type="button" name="SelectButton" value="<?php echo JTExt::_('COM_RAIDPLANNER_SELECT_CHARACTER');?>" onclick="javascript:if (window.parent) window.parent.<?php echo $this->escape($function);?>(<?php echo JRequest::getInt('fieldidx');?>, document.id('char_name').get('value'));" />
				<?php endif; ?>

				<input type="hidden" name="function" value="<?php echo $function; ?>" />
				<input type="hidden" name="option" value="com_raidplanner" />
				<input type="hidden" name="view" id="view" value="character" />
				<input type="hidden" name="task" id="task" value="savecharacter" />
				<input type="hidden" name="tmpl" id="tmpl" value="<?php echo JRequest::getVar('tmpl');?>" />
				<input type="hidden" name="layout" id="layout" value="<?php echo JRequest::getVar('layout');?>" />
				<input type="hidden" name="fieldidx" id="fieldidx" value="<?php echo JRequest::getVar('fieldidx');?>" />
				<input type="hidden" name="character_id" value="<?php echo @$this->character->character_id; ?>" />
			</form>
		</td>
	</tr>
	<?php endif; ?>
</table>