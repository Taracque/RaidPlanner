<?php
/**
* Joomla Community Builder User Plugin: plug_raidplanner
* @version $Id$
* @package plug_raidplanner
* @subpackage raidplanner.php
* @author Taracque
* @copyright (C) Taracque, http://taracque.hu
* @license Limited  http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @beta 0.1
*/

/** ensure this file is being included by a parent file */
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

global $_PLUGINS;

$_PLUGINS->registerFunction( 'onAfterUserUpdate', 'syncRaidPlannerFields','getraidplannerTab' );

/**
 * Basic tab extender. Any plugin that needs to display a tab in the user profile
 * needs to have such a class. Also, currently, even plugins that do not display tabs (e.g., auto-welcome plugin)
 * need to have such a class if they are to access plugin parameters (see $this->params statement).
 */
class getRaidPlannerTab extends cbTabHandler {
	/**
	 * Construnctor
	 */
	function getraidplannerTab() {
		$this->cbTabHandler();
	}
	
	/**
	 * syncRaidPlannerFields
	 */
	function syncRaidPlannerFields( $row, $rowExtras, $success) {
		if ($success)
		{
			$params = $this->params;
			$is_plug_enabled = $params->get('rpPlugEnabled', "1");
			if ($is_plug_enabled != "0")
			{
				$chars_field = $params->get('rpPlugCharactersField', '');
				$onvac_field = $params->get('rpPlugVacationsField', '');
				$calsec_field = $params->get('rpPlugCalSecretField', '');
				
				$data = array();
				
				if ($chars_field != '')
				{
					$data['characters'] = $row->$chars_field;
				}
				if ($onvac_field != '')
				{
					$data['vacation'] = $row->$onvac_field;
				}
				if ($calsec_field != '')
				{
					$data['calendar_secret'] = $row->$calsec_field;
				}

				$juser =& JFactory::getUser($row->user_id);
				$params = json_decode($juser->params);
				foreach ($data as $k => $v) {
					$juser->setParam($k, $v);
					$params->$k = $v;
				}
				
				$juser->params = json_encode($params);
				$table = $juser->getTable();
				$table->bind($juser->getProperties());
				$table->store();
			}
		}
	}

	function loadFieldList($name,$value,$control_name) {
		global $_CB_database;

		$_CB_database->setQuery( "SELECT `name`, `title` FROM #__comprofiler_fields WHERE `published`=1 ORDER BY `title` ASC" );
		$fieldList = $_CB_database->loadObjectList();
		$fieldRegList = array();
			$fieldRegList[] = moscomprofilerHTML::makeOption( '', '');
		if ($fieldList !== false) {
			foreach ($fieldList AS $fld) {
				$fieldRegList[] = moscomprofilerHTML::makeOption( $fld->name, $fld->title);
			}
		}
		$valAsObj = (isset($value)) ?
		array_map(create_function('$v', '$o=new stdClass(); $o->value=$v; return $o;'), explode("|*|", $value ))
		: null;

		return moscomprofilerHTML::selectList( $fieldRegList, $control_name .'['. $name .'][]', '', 'value', 'text', $valAsObj, true );
	}
}


$_PLUGINS->loadPluginGroup( 'user', array( (int) 1 ) );
$_PLUGINS->registerUserFieldTypes( array( 'rpcharactersfield' => 'CBfield_rpcharacters' ) );
$_PLUGINS->registerUserFieldParams();

class CBfield_rpcharacters extends CBfield_textarea {
	/**
	 * Returns a field in specified format
	 *
	 * @param  moscomprofilerFields  $field
	 * @param  moscomprofilerUser    $user
	 * @param  string                $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string                $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int                   $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed                
	 */
	function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$oReturn							=	'';

		if ( is_object( $user ) ) {
			switch ( $output ) {
				case 'htmledit':
					$oReturn =	null;
					if ( $reason == 'edit' ) {
						$value = $user->get( $field->name );
						// Load the javascript
						JHtml::_('behavior.framework');
						JHtml::_('behavior.modal', 'a.modal');
				
						// Build the script.
						$script = array();
						$script[] = '';
						$script[] = '	function jRecalCharacterValue_'.$field->fieldid.'() {';
						$script[] = '		var ul = document.id("rp_characterEditorList_' . $field->fieldid . '");';
						$script[] = '		var val = "";';
						$script[] = '		ul.getChildren("li").each(function(li){';
						$script[] = '			if (li.get("id") && (li.get("id") != "rp_characterEditorField_' . $field->fieldid . '_0") && (li.getChildren("a").get("text") != "") ) {';
						$script[] = '				val = val + li.getChildren("a").get("text") + "\n";';
						$script[] = '			}';
						$script[] = '		})';
						$script[] = '		document.id(rp_characterEditorValue_' . $field->fieldid . ').set("value", val);';
						$script[] = '	}';
						$script[] = '';
						$script[] = '	function jSelectCharacter_'.$field->fieldid.'(idx, name) {';
						$script[] = '		var line = document.id( "rp_characterEditorField_' . $field->fieldid . '_" + idx );';
						$script[] = '		if (idx==0) {';
						$script[] = '			var ul = line.getParent("ul");';
						$script[] = '			ul.getChildren("li").each(function(li){';
						$script[] = '				if ( (li) && (li.get("id")) ) {';
						$script[] = '					if(li.get("id").replace("rp_characterEditorField_' . $field->fieldid . '_","")*1>idx) idx=li.get("id").replace("rp_characterEditorField_' . $field->fieldid . '_","")*1;';
						$script[] = '					if(li.get("id") == "rp_characterEditorField_' . $field->fieldid . '_0") {';
						$script[] = '						line = li.clone().setStyle("display","block");';
						$script[] = '					}';
						$script[] = '				}';
						$script[] = '			})';
						$script[] = '			idx = Number(idx) + 1;';
						$script[] = '			line.set("id","rp_characterEditorField_' . $field->fieldid . '_" + (idx));';
						$script[] = '			ul.grab(line,"top");';
						$script[] = '			if (SqueezeBox) {';
						$script[] = '				if (SqueezeBox.assign) {';
						$script[] = '					SqueezeBox.assign(line.getChildren("a"),{parse:"rel"});';
						$script[] = '				} else {';
						$script[] = '					line.getChildren("a").each(function(el){';
						$script[] = '						el.addEvent("click",function(e){';
						$script[] = '							SqueezeBox.fromElement(el,{parse:"rel"});';
						$script[] = '						});';
						$script[] = '					});';
						$script[] = '				}';
						$script[] = '			}';
						$script[] = '		}';
						$script[] = '		line.getChildren("a").set("text",name);';
						$script[] = '		line.getChildren("a").set("href","' . JURI::root() . 'index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_'.$field->fieldid.'&amp;character=" + name + "&amp;fieldidx=" + idx );';
						$script[] = '		line.getChildren("input").set("value",name);';
						$script[] = '		SqueezeBox.close();';
						$script[] = '		jRecalCharacterValue_'.$field->fieldid.'();';
						$script[] = '	}';
				
						// Add the script to the document head.
						JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
				
						$chars = str_replace( array("\n", ",", ";", "\r", "\t"), " ", $value );
						$chars = preg_replace('!\s+!', ' ', $chars);
						$chars = explode( " ", $chars);
				
						$html = '<input type="hidden" name="' . $field->name. '" value="' . implode("\n",$chars). '" id="rp_characterEditorValue_' . $field->fieldid . '" />';
						$html .= '<div style="width:' . $field->params->get('cols', 40) . 'em;height:' . $field->params->get('rows',5) . 'em;overflow-y:auto;overflow-x:hidden;">';
						$html .= '<ul style="display:block;float:left;clear:left;width:100%;padding:0;" id="rp_characterEditorList_' . $field->fieldid . '">';
						$idx = 0;
				
						$html .= '<li style="display:none;float:left;clear:left;width:100%;" id="rp_characterEditorField_' . $field->fieldid . '_0">';
						$html .= '<img src="' . JURI::root() . 'components/com_raidplanner/assets/delete.png" alt="' . JText::_('JACTION_DELETE') . '" onclick="this.getParent(\'li\').dispose();" style="float:right;margin:0;" />';
						$html .= '<a class="modal" href="" rel="{handler: \'iframe\', size: {x: 450, y: 300}}"></a>';
						$html .= '</li>';
						
						foreach ($chars as $char)
						{
							if ( trim($char) )
							{
								$idx ++;
								$link = JURI::root() . 'index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_'.$field->fieldid.'&amp;character=' . htmlspecialchars(trim($char), ENT_COMPAT, 'UTF-8') . '&amp;fieldidx=' . $idx;
					
								$html .= '<li style="display:block;float:left;clear:left;width:100%;" id="rp_characterEditorField_' . $field->fieldid . '_' . $idx . '">';
								$html .= '<img src="' . JURI::root() . 'components/com_raidplanner/assets/delete.png" alt="' . JText::_('JACTION_DELETE') . '" onclick="this.getParent(\'li\').dispose();jRecalCharacterValue_'.$field->fieldid.'();" style="float:right;margin:0;" />';
								$html .= '<a class="modal" href="' . $link . '" rel="{handler: \'iframe\', size: {x: 450, y: 300}}">' . $char . '</a>';
								$html .= '</li>';
							}
						}
						$link = JURI::root() . 'index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_'.$field->fieldid.'&amp;character=&amp;fieldidx=';
						$html .= '<li style="display:block;float:left;clear:left;width:100%;"><a class="modal" rel="{handler: \'iframe\', size: {x: 450, y: 300}}" href="' . $link . '"><img src="' . JURI::root() . 'components/com_raidplanner/assets/new.png" alt="' . JText::_('JNEW') . '" style="margin:0;" /> '. JText::_('JNEW') . '</a></li>';
				
						$html .= '</ul>';
						$html .= '</div>';
						$oReturn = $html;
					} else {
						$oReturn = parent::getField( $field, $user, $output, $reason, $list_compare_types );
					}
					break;
				case 'html':
				case 'rss':
				case 'json':
				case 'php':
				case 'xml':
				case 'csvheader':
				case 'fieldslist':
				case 'csv':
				default:
					$oReturn = parent::getField( $field, $user, $output, $reason, $list_compare_types );
					break;
			}
		}
		return $oReturn;
	}
}//end of rank field