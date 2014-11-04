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
							sortable: true,
							sortIndex: <?php echo $this->initial_sort;?>,
							zebra: true,
							selectable: true,
							allowMultiSelect: false,
							paginate:true,
							paginateRows:25,
							paginationControlPages:25,
							filterable:true,
							strings:{
								next:'<i class="icon-next" title="<?php echo JText::_('JNEXT');?>"></i>',
								previous:'<i class="icon-previous" title="<?php echo JText::_('JPREVIOUS');?>"></i>',
								rows:'<?php echo JText::_('COM_RAIDPLANNER_ROW_COUNT');?>',
								search : '<?php echo JText::_('JGLOBAL_LOOKING_FOR');?>'
							},
							classHeaderPaginationContorlTH:'',
							classHeaderPaginationContorlTR:'',
							classHeaderPaginationContorlDiv:'rp_header pagination',
							classHeaderPaginationContorlUL:'rp_right pagination-list',
							classHeaderPaginationContorlLI:'',
							classHeaderNumOfRowsContorlUL:'rp_left',
							classHeaderNumOfRowsContorlLI:'',
							classHeaderFilterContorlDiv:'rp_filter',
						}
					).updatePagination();
				}
			}
		});
	</script>
	<?php if ($this->guild_plugin) { echo implode(" ", $this->guild_plugin->trigger( 'onRPGetGuildHeader' ) ); } ?>
	</div>
	<div class="rp_roster_table">
		<table class="rp_container" id="roster_table">
			<thead>
				<tr class="rp_header">
					<th class="rp_header"><?php echo JText::_('COM_RAIDPLANNER_CHARACTER_NAME');?></th>
					<?php if ($this->show_account == 1) : ?>
					<th class="rp_header"><?php echo JText::_('JGLOBAL_USERNAME');?></th>
					<?php endif; ?>
					<th class="rp_header"><?php echo JText::_('COM_RAIDPLANNER_LEVEL');?></th>
					<th class="rp_header"><?php echo JText::_('COM_RAIDPLANNER_GENDER');?></th>
					<th class="rp_header"><?php echo JText::_('COM_RAIDPLANNER_RACE');?></th>
					<th class="rp_header"><?php echo JText::_('COM_RAIDPLANNER_CLASS');?></th>
					<th class="rp_header"><?php echo JText::_('COM_RAIDPLANNER_RANK');?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($this->characters as $character) : ?>
				<tr class="rp_roster">
					<td>
						<a href="<?php if ($this->guild_plugin) { echo implode(" ", $this->guild_plugin->trigger( 'onRPGetCharacterLink', array($character['char_name']) ) ); }?>"><?php echo $character['char_name']; ?></a>
					</td>
					<?php if ($this->show_account == 1) : ?>
					<td><a href="<?php echo "#";?>"><?php echo $character['username'];?></a>
					<?php endif; ?>
					<td><?php echo $character['char_level']; ?></td>
					<td><?php echo $character['gender_name']; ?></td>
					<td><?php echo $character['race_name']; ?></td>
					<td class="<?php echo $character['class_css'];?>"><?php echo $character['class_name']; ?></td>
					<td><span style="display:none;"><?php echo $character['rank'];?></span><?php echo $this->ranks[$character['rank']]; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>