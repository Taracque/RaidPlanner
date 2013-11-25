<?php
/**
 * @copyright (C) 2013 by Taracque - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (COMMUNITY_COM_PATH.'/libraries/fields/profilefield.php');

JLoader::import('plugins.user.raidplanner.raidplanner',JPATH_SITE);


class CFieldRPCharacterEditor extends JFormFieldRPCharacterEditor {
	
	public function setData( $data ) {
		$keys = array('id', 'value' );
		
		foreach ($keys as $key) {
			$this->$key = $data->$key;
		}
		
		$this->name = 'field'.$this->id;
	}
	
	public function setParams( $params ) {
		$params_arr = json_decode( $params, true );
		foreach ($params_arr as $key => $value)
		{
			$this->element[$key] = $value;
		}
	}
	
}

class CFieldsRP_Characters extends CProfileField
{
	
	/**
	 * Method to format the specified value for text type
	 **/	 	
	public function getFieldData( $field )
	{
		$value = $field['value'];
		
		if( empty( $value ) )
			return $value;
		
		return '<a rel="nofollow" href="' . $value . '" target="_blank">' . $value . '</a>';
	}
	
	public function getFieldHTML( $field , $required )
	{
		$html = '';
		if ($field->type == 'rp_characters') {

			$myField = new CFieldRPCharacterEditor();
			$myField->setData( $field );
			$myField->setParams( $field->params );

			$html = $myField->getInput();
		}
		
		return $html;
	}
	
	public function isValid( $value , $required )
	{
		return true;
	}
	
	public function formatdata( $value )
	{
		return $value;
	}
}
