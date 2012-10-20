<?php
/*------------------------------------------------------------------------
# Guild List Template for RaidPlanner Component
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
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="function" value="<?php echo $function; ?>" />
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?>
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
			</td>
			<td nowrap="nowrap">
				<button onclick="this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?></button>
			</td>
		</tr>
	</table>
    <table class="adminlist">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->guilds ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'JGRID_HEADING_ID' ); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_GUILD_NAME', 'guild_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_GUILD_MEMBERS', 'members', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_SYNC_PLUGIN', 'sync_plugin', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_GUILD_LAST_SYNC', 'lastSync', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
        </tr>            
    </thead>
    <tbody>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->guilds as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->guild_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->guild_id; ?>
            </td>
            <td>
            	<?php if ($function=="") : ?>
                <a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=guilds&view=guild&task=edit&cid[]='.$row->guild_id);?>"><?php echo $row->guild_name; ?></a>
                <?php else: ?>
                <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $row->guild_id; ?>', '<?php echo $this->escape(addslashes($row->guild_name)); ?>');"><?php echo $row->guild_name; ?></a>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $row->members; ?>
            </td>
            <td>
                <?php echo $row->sync_plugin; ?>
            </td>
            <td>
                <?php echo $row->lastSync; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </tbody>
	<tfoot>
		<tr>
			<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
    </table>
</div>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="guilds" />
<input type="hidden" name="view" value="guilds" />
</form>
