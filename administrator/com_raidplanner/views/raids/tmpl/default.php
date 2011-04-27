<?php
/*------------------------------------------------------------------------
# Raids List Template for RaidPlanner Component
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
<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
				<?php echo JText::_('Start time'); ?>:
				<?php
					echo JHTML::_('calendar', $this->lists['start_time_min'], 'start_time_min', 'start_time_min', JText::_('DATE_FORMAT_LC4') );
				?> - <?php
					echo JHTML::_('calendar', $this->lists['start_time_max'], 'start_time_max', 'start_time_max', JText::_('DATE_FORMAT_LC4') );
				?>
			</td>
			<td nowrap="nowrap">
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';document.getElementById('start_time_min').value='';document.getElementById('start_time_max').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
	</table>
    <table class="adminlist">
    <thead>
		<tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->raids ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="100">
                <?php echo JHTML::_( 'grid.sort', 'Start Time', 'r.start_time', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
            	<?php echo JHTML::_( 'grid.sort', 'Location', 'r.location', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'Minimum Level', 'r.minimum_level', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'Maximum Level', 'r.maximum_level', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'Minimum Rank', 'r.minimum_rank', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'Invited Group', 'g.group_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="100">
                <?php echo JHTML::_( 'grid.sort', 'Template', 'r.is_template', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
        </tr>            
    </thead>
    <tbody>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->raids as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->raid_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->raid_id; ?>
            </td>
            <td>
                <?php echo JHTML::_('date', $row->start_time, JText::_('DATE_FORMAT_LC2') ); ?>
            </td>
            <td>
				<a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=raids&view=raid&task=edit&cid[]='.$row->raid_id);?>"><?php echo $row->location; ?></a>
            </td>
            <td>
				<?php echo $row->minimum_level; ?>
			</td>
            <td>
				<?php echo $row->maximum_level; ?>
            </td>
            <td>
				<?php echo $row->minimum_rank; ?>
            </td>
            <td>
				<?php echo $row->group_name; ?>
            </td>
            <td>
				<?php echo ($row->is_template) ? JText::_('Yes') : '-' ; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    	</tbody>
		<tfoot>
			<tr>
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
    </table>
</div>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="raids" />
<input type="hidden" name="view" value="raids" />
</form>
