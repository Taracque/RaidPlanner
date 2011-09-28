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

// register delete user code function
$_PLUGINS->registerFunction( 'onAfterUserUpdate', 'syncRaidPlannerFields','getraidplannerTab' );

/**
 * Basic tab extender. Any plugin that needs to display a tab in the user profile
 * needs to have such a class. Also, currently, even plugins that do not display tabs (e.g., auto-welcome plugin)
 * need to have such a class if they are to access plugin parameters (see $this->params statement).
 */
class getraidplannerTab extends cbTabHandler {
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
	
}