<?php
/*------------------------------------------------------------------------
# Character Form Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (RaidPlannerHelper::getJVersion() < '3.0') {
	RaidPlannerHelper::fixBootstrap();
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
		<div class="control-group">
			<label class="control-label" for="char_name"><?php echo JText::_( 'COM_RAIDPLANNER_CHARACTER_NAME' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="char_name" id="char_name" size="32" maxlength="250" value="<?php echo $this->character->char_name;?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="profile_id"><?php echo JText::_( 'JGLOBAL_USERNAME' ); ?></label>
			<div class="controls">
				<select name="profile_id" id="profile_id">
					<option value=""></option>
					<?php foreach ($this->users as $user_id => $user) : ?>
						<option value="<?php echo $user_id;?>"<?php if($user_id==@$this->character->profile_id){?> selected="selected"<?php } ?>><?php echo @$user->username;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="class_id"><?php echo JText::_( 'COM_RAIDPLANNER_CLASS' ); ?></label>
			<div class="controls">
				<select name="class_id" id="class_id">
					<?php foreach ($this->classes as $class_id => $class) : ?>
						<option value="<?php echo $class_id;?>" style="color:<?php echo $class->class_color;?>"<?php if ($this->character->class_id == $class_id){ echo " selected=\"selected\"";}?>><?php echo $class->class_name;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="gender_id"><?php echo JText::_( 'COM_RAIDPLANNER_GENDER' ); ?></label>
			<div class="controls">
				<?php echo JHTML::_('select.genericlist', $this->genders, 'gender_id', '', 'gender_id', 'gender_name', $this->character->gender_id); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="race_id"><?php echo JText::_( 'COM_RAIDPLANNER_RACE' ); ?></label>
			<div class="controls">
				<?php echo JHTML::_('select.genericlist', $this->races, 'race_id', '', 'race_id', 'race_name', $this->character->race_id); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="char_level"><?php echo JText::_( 'COM_RAIDPLANNER_LEVEL' ); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="char_level" id="char_level" size="10" maxlength="4" value="<?php echo $this->character->char_level;?>" class="input-mini" >
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="rank"><?php echo JText::_( 'COM_RAIDPLANNER_RANK' ); ?></label>
			<div class="controls">
				<select name="rank" id="rank">
					<?php foreach (RaidPlannerHelper::getRanks() as $rank_id => $rank) : ?>
						<option value="<?php echo $rank_id;?>"<?php if ($this->character->rank == $rank_id){ echo " selected=\"selected\"";}?>><?php echo $rank;?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="guild_id"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD' ); ?></label>
			<div class="controls">
				<?php echo JHTML::_('select.genericlist', $this->guilds, 'guild_id', '', 'guild_id', 'guild_name', $this->character->guild_id); ?>
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="character_id" value="<?php echo $this->character->character_id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="characters" />
	<input type="hidden" name="controller" value="characters" />
</form>