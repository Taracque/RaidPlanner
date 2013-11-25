<?php
/*------------------------------------------------------------------------
# Roles List Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (RaidPlannerHelper::getJVersion() < '3.0') {
	RaidPlannerHelper::fixBootstrap();
}
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">
    <table class="adminlist table table-striped">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->roles ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'JGRID_HEADING_ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'COM_RAIDPLANNER_ROLE_NAME' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->roles as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->role_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->role_id; ?>
            </td>
            <td>
                <a style="margin:5px;color:<?php echo $row->font_color;?>;background:<?php echo $row->body_color;?>;border-top:4px solid <?php echo $row->header_color;?>;" href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=roles&view=role&task=edit&cid[]='.$row->role_id);?>"><?php echo $row->role_name; ?></a>
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
<input type="hidden" name="controller" value="roles" />
<input type="hidden" name="view" value="roles" />
</form>
