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
		<canvas id="rp_guild_tabard" width="120" height="120">
		</canvas>
		<script type="text/javascript">
			window.addEvent('domready',function(){
				if ($('roster_table')) {
					if ((MooTools.version >= '1.2.4') && (typeof(HtmlTable)!='undefined')) {
						var rosterTable = new HtmlTable(
							$('roster_table'),
							{
								properties: {
									border: 0,
									cellspacing: 1,
									cellpadding: 5
								},
								sortable :true,
								zebra: true,
								selectable: true,
								allowMultiSelect: false,
								paginate:true,
								paginateRows:25,
								paginationControlPages:25,
								filterable:true,
								strings:{
									next:'<?php echo JTEXT::_('next');?>',
									previous:'<?php echo JTEXT::_('previous');?>',
									rows:'<?php echo JTEXT::_('rows');?>'
								},
								classHeaderPaginationContorlTH:'',
								classHeaderPaginationContorlTR:'',
								classHeaderPaginationContorlDiv:'rp_header',
								classHeaderPaginationContorlUL:'rp_left',
								classHeaderPaginationContorlLI:'rp_control',
								classHeaderNumOfRowsContorlUL:'rp_right',
								classHeaderNumOfRowsContorlLI:'rp_control',
								classHeaderFilterContorlDiv:'rp_filter'
							}
						).updatePagination();
					}
				}
				var tabard = new GuildTabard('rp_guild_tabard', {
					'ring': '<?php echo $this->guildinfo->params->side;?>',
					'bg': [ 0, '<?php echo $this->guildinfo->params->emblem->backgroundColor;?>' ],
					'border': [ <?php echo $this->guildinfo->params->emblem->border;?>, '<?php echo $this->guildinfo->params->emblem->borderColor;?>' ],
					'emblem': [ <?php echo $this->guildinfo->params->emblem->icon;?>, '<?php echo $this->guildinfo->params->emblem->iconColor;?>' ]
				}, '<?php echo JURI::base();?>images/raidplanner/tabards/');
			});
		</script>
		<h2><a href="<?php echo $this->guildinfo->params->link;?>" target="_blank"><?php echo $this->guildinfo->guild_name;?></a></h2>
		<strong>
			<?php echo JTEXT::_('LEVEL');?> <?php echo $this->guildinfo->guild_level;?> <?php echo $this->guildinfo->params->side;?> <?php echo JTEXT::_('GUILD');?><br />
			<?php echo $this->guildinfo->guild_realm;?> - <?php echo strtoupper($this->guildinfo->guild_region);?>
		</strong>
	</div>
	<div class="rp_roster_table">
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
					<td><a href="<?php echo sprintf($this->guildinfo->params->char_link, urlencode( $character['char_name'] ) );?>" target="_blank"><?php echo $character['char_name']; ?></a></td>
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
</div>