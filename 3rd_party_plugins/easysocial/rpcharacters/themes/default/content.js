<?php
/**
* @package		RaidPlanner
* @copyright	Copyright (C) 2015 taracque. All rights reserved.
* @license		GNU General Public License version 2 or later
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

?>
EasySocial
	.require()
	.app('fields/user/rpcharacters/content')
	.done(function($) {
		$('[data-field-<?php echo $field->id; ?>]').addController('EasySocial.Controller.Field.RPCharacters', {
			required: <?php echo $field->required ? 1 : 0; ?>
		});
	});

function jRecalCharacterValue_<?php echo $field->id; ?>() {
	var ul = document.id("rp_characterEditorList_<?php echo $field->id; ?>");
	var val = "";
	ul.getChildren("li").each(function(li){
		if (li.get("id") && (li.get("id") != "rp_characterEditorField_<?php echo $field->id; ?>_0") && (li.getChildren("a")[0].get("text") != "") ) {
			if (li.getChildren("input")[0].get("value")) {
				val = val + li.getChildren("input")[0].get("value") + ":";
			}
			val = val + li.getChildren("a")[0].get("text") + ";";
		}
	})
	document.id("<?php echo $inputName; ?>").set("value", val );
}

function jSelectCharacter_<?php echo $field->id; ?>(idx, char_id, char_name) {
	if (char_name) {
		var line = document.id( "rp_characterEditorField_<?php echo $field->id; ?>_" + idx );
		if (idx==0) {
			var ul = line.getParent("ul");
			ul.getChildren("li").each(function(li) {
				if ( (li) && (li.get("id")) ) {
					if(li.get("id").replace("rp_characterEditorField_<?php echo $field->id; ?>_","")*1>idx) idx=li.get("id").replace("rp_characterEditorField_<?php echo $field->id; ?>_","")*1;
					if(li.get("id") == "rp_characterEditorField_<?php echo $field->id; ?>_0") {
						line = li.clone().setStyle("display","block");
					}
				}
			})
			idx = Number(idx) + 1;
			line.set("id","rp_characterEditorField_<?php echo $field->id; ?>_" + (idx));
			ul.grab(line,"top");
			if (SqueezeBox) {
				SqueezeBox.assign(line.getChildren("a")[0],{parse:"rel"});
			}
		}
		line.getChildren("a")[0].set("text",char_name);
		line.getChildren("a")[0].set("href","<?php echo JURI::root();?>index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_<?php echo $field->id; ?>&amp;character=" + char_name + "&amp;char_id=" + char_id + "&amp;fieldidx=" + idx );
		line.getChildren("input")[0].set("value",char_id);
		jRecalCharacterValue_<?php echo $field->id; ?>();
	}
	SqueezeBox.close();
}
