<?php

defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT .'/components/com_community/libraries/core.php';
 
class plgCommunityRaidplanner extends CApplications
{
	var $name = "Raidplanner";
	var $_name = 'raidplanner';
 
	function plgCommunityRaidplanner(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}
 
 	function onSystemStart() {
		JLoader::register('CFieldsRp_characters', JPATH_SITE . '/plugins/community/raidplanner/fields/rp_characters.php', true);
	}

	function onProfileDisplay()
	{
	}
}