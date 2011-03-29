<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->classes ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Class Name' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->classes as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->class_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->class_id; ?>
            </td>
            <td>
                <a style="color:<?php echo $row->class_color;?>;" href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=classes&view=class&task=edit&cid[]='.$row->class_id);?>"><?php echo $row->class_name; ?></a>
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
<input type="hidden" name="controller" value="classes" />
<input type="hidden" name="view" value="classes" />
</form>
