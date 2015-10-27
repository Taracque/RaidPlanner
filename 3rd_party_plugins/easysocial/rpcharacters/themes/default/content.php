<?php
/**
* @package		RaidPlanner
* @copyright	Copyright (C) 2015 taracque. All rights reserved.
* @license		GNU General Public License version 2 or later
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div data-field-textbox data-min="<?php echo $params->get( 'min' ); ?>" data-max="<?php echo $params->get( 'max' ); ?>">
	<input type="text" id="<?php echo $inputName;?>"
		value="<?php echo $value; ?>"
		name="<?php echo $inputName;?>"
		class="form-control input-sm"
		placeholder="<?php echo JText::_( $params->get( 'placeholder' ), true ); ?>"
		data-field-textbox-input
		<?php if( $params->get( 'readonly' ) ) { ?>disabled="disabled"<?php } ?>
		<?php if( $params->get( 'required' ) ) { ?>data-check-required<?php } ?>
		<?php if( $params->get( 'regex_validate' ) ) { ?>
		data-check-validate
		data-check-format="<?php echo $params->get( 'regex_format' ); ?>"
		data-check-modifier="<?php echo $params->get( 'regex_modifier' ); ?>"
		<?php } ?>
	/>
</div>
