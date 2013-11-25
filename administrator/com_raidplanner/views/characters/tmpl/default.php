<?php
/*------------------------------------------------------------------------
# Characters List Template for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (RaidPlannerHelper::getJVersion() >= '3.0') {
	JHtml::_('formbehavior.chosen', 'select');
} else {
	RaidPlannerHelper::fixBootstrap();
}
?>
<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label for="search" class="element-invisible"><?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?></label>
			<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" >
		</div>
		<div class="btn-group pull-left hidden-phone">
			<label for="level_min" class="element-invisible"><?php echo JText::_( 'COM_RAIDPLANNER_LEVEL' ); ?></label>
			<div class="input-append input-prepend">
				<input type="text" name="level_min" id="level_min" value="<?php echo htmlspecialchars($this->lists['level_min']);?>" class="input-mini hasTooltip" onchange="document.adminForm.submit();" data-original-title="<?php echo JText::_( 'COM_RAIDPLANNER_LEVEL_RANGE' ); ?>">
				<span class="add-on">-</span>
				<input type="text" name="level_max" id="level_max" value="<?php echo htmlspecialchars($this->lists['level_max']);?>" class="input-mini hasTooltip" onchange="document.adminForm.submit();" data-original-title="<?php echo JText::_( 'COM_RAIDPLANNER_LEVEL_RANGE' ); ?>" >
			</div>
			<label for="guild_filter" class="element-invisible"><?php echo JText::_( 'COM_RAIDPLANNER_GUILD' ); ?></label>
			<select name="guild_filter" onchange="document.adminForm.submit();">
				<option></option>
				<?php foreach ($this->guilds as $guild_id => $guild): ?>
				<option value="<?php echo $guild_id;?>" <?php if ($guild_id == $this->lists['guild_filter']) { echo "selected=\"selected\""; } ?>><?php echo $guild->guild_name;?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button onclick="this.form.submit();" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?>">
				<i class="icon-search"></i>
			</button>
			<button onclick="document.getElementById('search').value='';document.getElementById('level_min').value='';document.getElementById('level_max').value='';this.form.submit();" class="btn hasTooltip" data-original-title="<?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?>">
				<i class="icon-remove"></i>
			</button>
		</div>
<?php if (RaidPlannerHelper::getJVersion() >= '3.0') : ?>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
<?php endif; ?>
	</div>
	<div class="clearfix"> </div>
    <table class="adminlist table table-striped">
    <thead>
        <tr>
			<th width="20">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this);" />
			</th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_CHARACTER_NAME', 'c.char_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'JGLOBAL_USERNAME', 'u.name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_CLASS', 'cl.class_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_RANK', 'c.rank', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_GENDER', 'c.gender_id', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_RACE', 'rc.race_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_LEVEL', 'c.char_level', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th>
                <?php echo JHTML::_( 'grid.sort', 'COM_RAIDPLANNER_GUILD', 'g.guild_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="5%">
				<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'c.character_id', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
        </tr>            
    </thead>
    <tbody>
    <?php
    $k = 0;
    $i = 0;
    $ranks = RaidPlannerHelper::getRanks();
    foreach ($this->characters as &$row)
    {
    	$checked    = JHTML::_( 'grid.id', $i++, $row->character_id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $checked; ?>
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
                <?php echo @$ranks[$row->rank]; ?>
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
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_raidplanner&controller=guilds&view=guild&task=edit&cid[]='.$row->guild_id);?>"><?php echo $row->guild_name; ?></a>
            </td>
            <td>
                <?php echo $row->character_id; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </tbody>
	<tfoot>
		<tr>
			<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
    </table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="option" value="com_raidplanner" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="characters" />
	<input type="hidden" name="view" value="characters" />
</form>
