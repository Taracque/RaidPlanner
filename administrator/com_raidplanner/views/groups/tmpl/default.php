<?php
/*------------------------------------------------------------------------
# Groups List Template for RaidPlanner Component
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
<?php if (!$this->groups) : ?>
	<h2><?php echo JText::_( 'COM_RAIDPLANNER_USE_JOOMLAACL' );?></h2>
	<p><?php echo JText::_( 'COM_RAIDPLANNER_USE_JOOMLAACL_DESC' );?></p>
<?php else: ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->groups ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'JGRID_HEADING_ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'COM_RAIDPLANNER_GROUP_NAME' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'JDEFAULT' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->groups as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->group_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->group_id; ?>
            </td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=groups&view=group&task=edit&cid[]='.$row->group_id);?>"><?php echo $row->group_name; ?></a>
            </td>
            <td>
                <?php if ($row->default == 1): ?>
            	<img src="templates/<?php echo JFactory::getApplication()->getTemplate();?>/images/menu/icon-16-default.png" alt="<?php echo JText::_( 'Default' );?>">
            	<?php endif; ?>
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
<input type="hidden" name="controller" value="groups" />
<input type="hidden" name="view" value="groups" />
</form>
<?php endif; ?>
