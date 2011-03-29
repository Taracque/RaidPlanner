<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->groups ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Group Name' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Default' ); ?>
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
            	<img src="templates/khepri/images/menu/icon-16-default.png" alt="<?php echo JText::_( 'Default' );?>">
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
