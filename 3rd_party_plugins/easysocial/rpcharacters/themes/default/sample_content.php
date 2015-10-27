<?php
/**
* @package		RaidPlanner
* @copyright	Copyright (C) 2015 taracque. All rights reserved.
* @license		GNU General Public License version 2 or later
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div data-field-textbox>
	<input type="text" id="<?php echo $inputName;?>"
		name="<?php echo $inputName;?>"
		class="form-control input-sm"
		value="<?php echo JText::_( $params->get( 'default' ), true ); ?>"
		placeholder="<?php echo JText::_( $params->get( 'placeholder' ), true ); ?>"
		data-input
		<?php if( $params->get( 'readonly' ) ) { ?>disabled="disabled"<?php } ?>
	/>
</div>
