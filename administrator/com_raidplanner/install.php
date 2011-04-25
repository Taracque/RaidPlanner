<?php
/*------------------------------------------------------------------------
# Custom Installer for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install()
{

    $installer = & JInstaller::getInstance();
    $source = $installer->getPath('source');

	$msg = '';

	$moduleInstaller = new JInstaller();
	
	if ( $moduleInstaller->install($source . DS . 'mod_raidplanner_today') ) {
		// module installed
		$out = 'RaidPlanner Today module installed!';
	} else {
		$out = 'RaidPlanner Today module installation failed!';
	}
	$installer->set('message', $out);
}

function com_uninstall()
{
    $installer = & JInstaller::getInstance();
	$installer->set('message', 'RaidPlanner Today module needs to be removed manualy!');
}