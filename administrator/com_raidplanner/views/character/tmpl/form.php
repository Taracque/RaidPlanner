<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="char_name">
					<?php echo JText::_( 'Character Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="char_name" id="char_name" size="32" maxlength="250" value="<?php echo $this->character->char_name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="profile_id">
					<?php echo JText::_( 'Username' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_('list.users', 'profile_id', $this->character->profile_id, 0, null, 'name', 0); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="class_id">
					<?php echo JText::_( 'Class' ); ?>:
				</label>
			</td>
			<td>
				<select name="class_id" id="class_id">
				<?php foreach ($this->classes as $class) : ?>
					<option value="<?php echo $class->class_id;?>" style="color:<?php echo $class->class_color;?>"><?php echo $class->class_name;?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="gender_id">
					<?php echo JText::_( 'Gender' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_('select.radiolist', $this->genders, 'gender_id', '', 'value', 'text', $this->character->gender_id); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="race_id">
					<?php echo JText::_( 'Race' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_('select.genericlist', $this->races, 'race_id', '', 'value', 'text', $this->character->race_id); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="char_level">
					<?php echo JText::_( 'Level' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="char_level" id="char_level" size="10" maxlength="4" value="<?php echo $this->character->char_level;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="rank">
					<?php echo JText::_( 'Rank' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="rank" id="rank" size="10" maxlength="4" value="<?php echo $this->character->rank;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="character_id" value="<?php echo $this->character->character_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="characters" />
<input type="hidden" name="controller" value="characters" />
</form>