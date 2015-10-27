<?php
/**
* @package		RaidPlanner
* @copyright	Copyright (C) 2015 taracque. All rights reserved.
* @license		GNU General Public License version 2 or later
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

JHtml::_('behavior.framework');
JHtml::_('behavior.modal', 'a.open-modal');

JFactory::getLanguage()->load('com_raidplanner', JPATH_SITE );

JLoader::register('RaidPlannerHelper', JPATH_ADMINISTRATOR . '/components/com_raidplanner/helper.php' );
?>
<div data-field-characters>
	<input type="hidden" name="<?php echo $inputName;?>" value="<?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8');?>" id="<?php echo $inputName;?>">
	<ul class="form-control" style="display:block;float:left;clear:left;width:100%;padding:0;margin:0;height:10em;" id="rp_characterEditorList_<?php echo $field->id;?>">
		<li style="display:none;float:left;clear:left;width:100%;padding:0;border-bottom:1px solid gray;" id="rp_characterEditorField_<?php echo $field->id;?>_0">
			<img src="<?php echo JURI::root(); ?>media/com_raidplanner/images/delete.png" alt="<?php echo JText::_('JACTION_DELETE');?>" onclick="this.getParent('li').dispose();" style="float:right;margin:0;" />
			<a class="open-modal" href="" rel="{handler: 'iframe', size: {x: 450, y: 300}}"></a>
			<input type="hidden" value="" />
		</li>
<?php
	$chars = RaidPlannerHelper::getProfileChars( $value, true, true );
	foreach ($chars as $char)
	{
		$idx++;
?>
		<li style="display:block;float:left;clear:left;width:100%;padding:0;border-bottom:1px solid gray;" id="rp_characterEditorField_<?php echo $field->id;?>_<?php echo $idx;?>">
			<img src="<?php echo(JURI::root());?>media/com_raidplanner/images/delete.png" alt="<?php echo JText::_('JACTION_DELETE');?>" onclick="this.getParent('li').dispose();jRecalCharacterValue_<?php echo $field->id;?>" style="float:right;margin:0;" />
			<a class="open-modal" href="<?php echo JURI::root();?>index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_<?php echo $field->id;?>&amp;character=<?php echo htmlspecialchars( $char['char_name'], ENT_COMPAT, 'UTF-8');?>&amp;char_id=<?php echo $char['char_id'];?>&amp;fieldidx=<?php echo $idx; ?>" rel="{handler: 'iframe', size: {x: 450, y: 300}}"><?php echo $char['char_name'];?></a>
		<?php if ($char['guild_name']!='') : ?>
				<span> &lsaquo;<?php echo $char['guild_name']; ?>&rsaquo;</span>
		<?php endif; ?>
			<input type="hidden" value="<?php echo $char['char_id'];?>" />
		</li>
<?php
	}
?>
		<li style="display:block;float:left;clear:left;width:100%;"><a class="open-modal" rel="{handler: 'iframe', size: {x: 450, y: 300}}" href="<?php echo JURI::root();?>index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_<?php echo $field->id;?>&amp;character=&amp;fieldidx="><img src="<?php echo JURI::root();?>media/com_raidplanner/images/new.png" alt="<?php echo JText::_('JACTION_NEW'); ?>" style="margin:0;" /><?php echo JText::_('PLG_USER_RAIDPLANNER_ADD_NEW_CHARACTER'); ?></a></li>
	</ul>
</div>
