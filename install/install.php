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

	$out = '';

	$extInstaller = new JInstaller();

	// intsall RaidPlanner Today Module
	if ( $extInstaller->install($source . DS . 'mod_raidplanner_today') ) {
		// module installed
		$out .= 'RaidPlanner Today module installed!<br />';
	} else {
		$out .= 'RaidPlanner Today module installation failed!<br />';
	}
	
	// intsall RaidPlanner User Plugin (just for J 1.6!)
	$version = new JVersion();
	if ($version->RELEASE == '1.6') {
		if ( $extInstaller->install($source . DS . 'plg_raidplanner') ) {
			// module installed
			$out .= 'RaidPlanner User plugin installed!<br />';
		} else {
			$out .= 'RaidPlanner User plugin installation failed!<br />';
		}
	}	
	
	$installer->set('message', $out);
}

function com_uninstall()
{
    $installer = & JInstaller::getInstance();
	$installer->set('message', 'RaidPlanner Today module, and RaidPlanner user plugins needs to be removed manualy!');
}