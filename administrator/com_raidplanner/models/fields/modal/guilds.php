<?php
/**
 * @version		$Id: guilds.php 21020 2011-10-04 16:52:01Z Taracque $
 * @copyright	Copyright (C) 2011 Taracque. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports a modal guild picker.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_raidplanner
 * @since		1.6
 */
class JFormFieldModal_Guilds extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Modal_Guilds';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();
		$script[] = '	function jSelectChart_'.$this->id.'(id, name, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = id;';
		$script[] = '		document.id("'.$this->id.'_name").value = name;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Get the title of the linked chart
		$db = JFactory::getDBO();
		$db->setQuery(
			'SELECT guild_name' .
			' FROM #__raidplanner_guild' .
			' WHERE guild_id = '.(int) $this->value
		);
		$title = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
		}

		if (empty($title)) {
			$title = JText::_('COM_RAIDPLANNER_SELECT_A_GUILD');
		}

		$link = 'index.php?option=com_raidplanner&amp;view=guilds&amp;layout=modal&amp;tmpl=component&amp;function=jSelectChart_'.$this->id;

		$html = "\n".'<div class="fltlft"><input type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('COM_RAIDPLANNER_CHANGE_GUILD_BUTTON').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">'.JText::_('COM_RAIDPLANNER_CHANGE_GUILD_BUTTON').'</a></div></div>'."\n";
		// The active contact id field.
		if (0 == (int)$this->value) {
			$value = '';
		} else {
			$value = (int)$this->value;
		}

		// class='required' for client side validation
		$class = '';
		if ($this->required) {
			$class = ' class="required modal-value"';
		}

		$html .= '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';

		return $html;
	}
}
