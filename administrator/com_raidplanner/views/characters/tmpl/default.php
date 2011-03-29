<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->characters ); ?>);" />
			</th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Character Name' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'User' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Class' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Rank' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Gender' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Race' ); ?>
            </th>
            <th>
                <?php echo JText::_( 'Level' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k = 0;
    $i = 0;
    foreach ($this->characters as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->character_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
			</td>
            <td>
                <?php echo $row->character_id; ?>
            </td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=characters&view=character&task=edit&cid[]='.$row->character_id);?>"><?php echo $row->char_name; ?></a>
            </td>
            <td>
                <?php echo $row->user_name; ?>
            </td>
            <td>
                <span style="color:<?php echo $row->class_color; ?>"><?php echo $row->class_name; ?></span>
            </td>
            <td>
                <?php echo $row->rank; ?>
            </td>
            <td>
                <?php echo $row->gender_name; ?>
            </td>
            <td>
                <?php echo $row->race_name; ?>
            </td>
            <td>
                <?php echo $row->char_level; ?>
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
<input type="hidden" name="controller" value="characters" />
<input type="hidden" name="view" value="characters" />
</form>
