<?php
/*------------------------------------------------------------------------
# Roster Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.utilities.date');
?>
<div class="rp_roster">
	<div class="rp_roster_header">
		<h3><?php echo $this->guildinfo->guild_name;?></h3>
		<strong>Level: <?php echo $this->guildinfo->guild_level;?>, <?php echo $this->guildinfo->params->side;?> <?php echo $this->guildinfo->guild_realm;?>-<?php echo $this->guildinfo->guild_region;?></strong>
		<canvas id="guild-tabard" width="240" height="240" style="display: inline; ">
			<div class="guild-tabard-default"></div>
		</canvas>
		<script type="text/javascript">
			      var tabard = new GuildTabard('guild-tabard', {
					'ring': '<?php echo $this->guildinfo->params->side;?>',
					'bg': [ 0, '<?php echo $this->guildinfo->params->emblem->backgroundColor;?>' ],
					'border': [ <?php echo $this->guildinfo->params->emblem->border;?>, '<?php echo $this->guildinfo->params->emblem->borderColor;?>' ],
					'emblem': [ <?php echo $this->guildinfo->params->emblem->icon;?>, '<?php echo $this->guildinfo->params->emblem->iconColor;?>' ]
				  }, '<?php echo JURI::base();?>images/raidplanner/tabards/');
		</script>
	</div>
	<input type="text" value="" id="roster_filter" size="20" />
	<table class="rp_container" id="roster_table">
		<thead>
			<tr class="rp_header">
				<th class="rp_header"><?php echo JTEXT::_('NAME');?></th>
				<th class="rp_header"><?php echo JTEXT::_('LEVEL');?></th>
				<th class="rp_header"><?php echo JTEXT::_('GENDER');?></th>
				<th class="rp_header"><?php echo JTEXT::_('RACE');?></th>
				<th class="rp_header"><?php echo JTEXT::_('CLASS');?></th>
				<th class="rp_header"><?php echo JTEXT::_('RANK');?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($this->characters as $character) : ?>
			<tr class="rp_roster">
				<td><?php echo $character['char_name']; ?></td>
				<td><?php echo $character['char_level']; ?></td>
				<td><?php echo $character['gender_name']; ?></td>
				<td><?php echo $character['race_name']; ?></td>
				<td><?php echo $character['class_name']; ?></td>
				<td><?php echo $character['rank']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>