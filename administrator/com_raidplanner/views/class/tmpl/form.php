<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>
		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="class_name">
					<?php echo JText::_( 'Class Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="class_name" id="class_name" size="32" maxlength="250" value="<?php echo $this->class->class_name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="class_color">
					<?php echo JText::_( 'Class Color' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="class_color" id="class_color" size="10" maxlength="7" value="<?php echo $this->class->class_color;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="armory_id">
					<?php echo JText::_( 'Armory ID' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="armory_id" id="armory_id" size="10" maxlength="7" value="<?php echo $this->class->armory_id;?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="class_id" value="<?php echo $this->class->class_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="classes" />
<input type="hidden" name="controller" value="classes" />
</form>