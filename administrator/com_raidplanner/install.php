<?php

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