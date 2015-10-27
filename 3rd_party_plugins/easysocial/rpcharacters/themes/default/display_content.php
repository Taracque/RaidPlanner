<?php
/**
* @package		RaidPlanner
* @copyright	Copyright (C) 2015 taracque. All rights reserved.
* @license		GNU General Public License version 2 or later
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

JFactory::getLanguage()->load('com_raidplanner', JPATH_SITE );

JHtml::_('behavior.framework');
JHtml::_('behavior.modal', 'a.open-modal');
?>
<ul class="form-control" style="display:block;float:left;clear:left;width:100%;padding:0;margin:0;height:10em;" id="rp_characterEditorList_<?php echo $field->id;?>">
<?php
	$chars = RaidPlannerHelper::getProfileChars( $value, true, true );
	foreach ($chars as $char)
	{
		$idx++;
?>
	<li style="display:block;float:left;clear:left;width:100%;padding:0;border-bottom:1px solid gray;">
		<?php echo $char['char_name'];?>
	<?php if ($char['guild_name']!='') : ?>
		<span> &lsaquo;<?php echo $char['guild_name']; ?>&rsaquo;</span>
	<?php endif; ?>
	</li>
<?php
	}
?>
</ul>
