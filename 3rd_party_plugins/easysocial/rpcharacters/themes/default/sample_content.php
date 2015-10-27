<?php
/**
* @package		RaidPlanner
* @copyright	Copyright (C) 2015 taracque. All rights reserved.
* @license		GNU General Public License version 2 or later
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div data-field-characters>
	<ul class="form-control" style="display:block;float:left;clear:left;width:100%;padding:0;margin:0;height:10em;" id="rp_characterEditorList_<?php echo $field->id;?>">
<?php for($idx = 1; $idx <= 5; $idx++) : ?>
		<li style="display:block;float:left;clear:left;width:100%;padding:0;border-bottom:1px solid gray;">
			<img src="<?php echo(JURI::root());?>media/com_raidplanner/images/delete.png" alt="<?php echo JText::_('JACTION_DELETE');?>" style="float:right;margin:0;" />
			<a href="#">Character<?php echo $idx;?></a>
			<span> &lsaquo;Guild name&rsaquo;</span>
		</li>
<?php endfor; ?>
	</ul>
</div>