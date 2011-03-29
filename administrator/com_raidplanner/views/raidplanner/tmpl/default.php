<h3>RaidPlanner</h3>
<p>Raid organizer component, can cooperate with <a href="http://pixelbyte.dk/index.php/pbroster-download" target="_blank">PB-Roster</a> component to automatically update characters.</p>
<p>If <a href="http://pixelbyte.dk/index.php/pbroster-download" target="_blank">UserMeta</a> plugin is installed, and users has a <code>characters</code> attribute added (and characters are spearated by newline), characters are automatically assigned to Joomla Users, add this to user.xml file:<br/>
<code>
&lt;param name="characters" type="textarea" default="" label="Characters" description="Characters, separated by newline!" rows="5" cols="60" /&gt;
</code>
</p>

<fieldset>
	<legend><?php echo JText::_('Settings');?>:</legend>
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=raids">
			<span class="rp_raids"></span>
			<?php echo JText::_('Raids');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=characters">
			<span class="rp_chars"></span>
			<?php echo JText::_('Characters');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=groups">
			<span class="rp_groups"></span>
			<?php echo JText::_('Groups');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=roles">
			<span class="rp_roles"></span>
			<?php echo JText::_('Roles');?>
		</a>
	</div>
	
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=classes">
			<span class="rp_classes"></span>
			<?php echo JText::_('Classes');?>
		</a>
	</div>
</fieldset>

<fieldset>
	<legend><?php echo JText::_('Tools');?>:</legend>
	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=raidplanner&task=service">
			<span class="rp_service"></span>
			<?php echo JText::_('Service');?>
		</a>
	</div>
</fieldset>