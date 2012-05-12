<?php
/*------------------------------------------------------------------------
# Races List Template for RaidPlanner Component
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
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->races ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'JGRID_HEADING_ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'COM_RAIDPLANNER_RACE_NAME' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'COM_RAIDPLANNER_CSS_NAME' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->races as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->race_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->race_id; ?>
            </td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=races&view=race&task=edit&cid[]='.$row->race_id);?>"><?php echo $row->race_name; ?></a>
            </td>
            <td>
                <?php echo $row->race_css; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
</div>
 
<input type="hidden" name="option" value="com_raidplanner" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="races" />
<input type="hidden" name="view" value="races" />
</form>
