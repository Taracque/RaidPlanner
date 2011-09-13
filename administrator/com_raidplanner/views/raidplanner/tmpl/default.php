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

<h3>RaidPlanner</h3>
<p>Raid organizer component.</p>
<p>If RaidPlanner plugin is installed and enabled, and users has a <code>characters</code> attribute added (and characters are spearated by newline), characters are automatically assigned to Joomla User.</p>

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

	<div class="rp_icon_button">
		<a href="index.php?option=com_raidplanner&view=races">
			<span class="rp_races"></span>
			<?php echo JText::_('Races');?>
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