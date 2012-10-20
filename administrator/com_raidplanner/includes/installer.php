<?php
/*------------------------------------------------------------------------
# RaidPlanner Installer class
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2012 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

class RaidPlannerInstaller
{
	private $_app;
	private $_tmp;

	function __construct()
	{
		$this->_app = JFactory::getApplication();
		$this->_tmp = $this->_app->getCfg('tmp_path');
	}

	/**
	 * Install the theme package from an uploaded archive package
	 * $ul_variable_name : the variable which used in form upload
	 */
	public function installUploaded( $ul_variable_name )
	{
		$file = JRequest::getVar ( $ul_variable_name, NULL, 'FILES', 'array' );
		if (!$file || !is_uploaded_file ( $file ['tmp_name'])) {
			return false;
		} else {
			$success = JFile::upload($file ['tmp_name'], $this->_tmp . '/' . $file ['name']);
			if ( !$success ) {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_UPLOAD_FAILED', $file ['name']), 'warning' );
				return false;
			} 
			
			return $this->installArchive( $this->_tmp . '/' . $file ['name'] );
		}
	}

	/**
	 * Install the theme package from an archive file
	 * $archive : path to the (uploaded) archive
	 */
	public function installArchive($archive)
	{
		$folder_name = uniqid('RPinstall_');
		
		$tmp = $this->_tmp . '/' . $folder_name;
		$success = JArchive::extract ( $archive, $tmp );
		if (! $success) {
			$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_EXTRACT_FAILED', $archive), 'warning' );
			return false;
		}
		JFile::delete( $archive );
		return $this->installPackage( $tmp );
	}

	/**
	 * Remove files, folders as defined in the XML
	 * $filesets : array of JSimpleXMLElement
	 * $dest : base destination folder
	 */
	private function doRemove( $filesets, $dest )
	{
		if ( !$filesets ) {
			return false;
		}
		foreach ($filesets as $files) {
			$attributes = $files->attributes();
			if (isset($attributes->folder)) {
				$source_folder = (string)$attributes->folder . '/';
			} else {
				$source_folder = "";
			}
			if (isset($attributes->destination)) {
				$destination = (string)$attributes->destination . '/';
			} else {
				$destination = "";
			}
			if ($filelist = @$files->file) {
				foreach ($filelist as $file) {
					if (! JFile::delete( $dest . '/' . $destination . (string)$file, null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_DELETE_FAILED', (string)$file ), 'warning' );
					}
				}
			}
			if ($folderlist = @$files->folder) {
				foreach ($folderlist as $folder) {
					if (! JFolder::delete( $dest . '/' . $destination . (string)$folder, null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_DELETE_FAILED', (string)$folder ), 'warning' );
					}
				}
			}
		}
	}

	/**
	 * Copies files, folders as defined in the XML
	 * $filesets : array of JSimpleXMLElement
	 * $source : source folder
	 * $dest : base destination folder
	 */
	private function doCopy( $filesets, $basepath, $dest )
	{
		if ( !$filesets ) {
			return false;
		}
		foreach ($filesets as $files) {
			$attributes = $files->attributes();
			if (isset($attributes['folder'])) {
				$source_folder = $attributes['folder'] . '/';
			} else {
				$source_folder = "";
			}
			if (isset($attributes['destination'])) {
				$destination = $attributes['destination'] . '/';
			} else {
				$destination = "";
			}
			if ($filelist = @$files->file) {
				foreach ($filelist as $file) {
					if (! JFile::copy( $basepath . '/' . $source_folder . (string)$file, $dest . '/' . $destination . (string)$file, null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_COPY_FAILED', (string)$file ), 'warning' );
					}
				}
			}
			if ($folderlist = @$files->folder) {
				foreach ($folderlist as $folder) {
					if (! JFolder::copy( $basepath . '/' . $source_folder . (string)$folder, $dest . '/' . $destination . (string)$folder, null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_COPY_FAILED', (string)$folder ), 'warning' );
					}
				}
			}
		}
	}
	
	/**
	 * Installs an archive from the given URL
	 */
	public function installFromURL( $url )
	{
		$data = RaidPlannerHelper::downloadData( $url );
		if ( file_put_contents( $this->_tmp . '/' . basename($url), $data ) ) {
			return $this->installArchive( $this->_tmp . '/' . basename($url) );
		} else {
			$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_DOWNLOAD_FAILED', $url ), 'warning' );
			return false;
		}
	}
	
	/**
	 * Executes the SQL commands defined in the xmlnode
	 */
	private function doSQL( $xmlnode )
	{
		$db = & JFactory::getDBO();
		foreach ($xmlnode->sql as $sql) {
			$attributes = $sql->attributes();
			$condition_met = true;
			if ($attributes->condition) {
				$db->setQuery( (string)$attributes->condition );
				$condition_met = (boolean) $db->loadResult();
			}
			if ($condition_met) {
				$db->setQuery( (string)$sql );
				$db->query();
			}
		}
	}

	/**
	 * Install the theme package from $folder path
	 */
	public function installPackage($folder)
	{
		// find the manifest file
		$xmlfiles = JFolder::files($folder, '.xml$', 1, true);
		foreach ($xmlfiles as $xmlfile)
		{
			// get parent folder of $xmlfile for reference
			$basepath = pathinfo( $xmlfile, PATHINFO_DIRNAME );
			$xml_name = basename( $xmlfile );
			// install using the xml
			$xml = simplexml_load_file( $xmlfile );

			$attributes = $xml->attributes();
			if ( (string)$attributes->type == "raidplanner_theme") {
				// copy the manifest file
				JFile::copy( $xmlfile, JPATH_ADMINISTRATOR . '/components/com_raidplanner/themes/' . $xml_name, null, true );
				$this->doCopy( $xml->fileset, $basepath, JPATH_SITE . '/images/raidplanner' );
				$this->doCopy( $xml->administrator[0]->fileset, $basepath, JPATH_ADMINISTRATOR . '/components/com_raidplanner' );
				// do SQL commands
				$this->doSQL( $xml->install[0] );
			} else {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_UNKNOWN_TYPE', (string)$xml->attributes()->type), 'warning' );
			}
		}
		JFolder::delete( $folder );
		return true;
	}
	
	/**
	 * Uninstalls the given plugin
	 */
	public function uninstall( $xmlfile )
	{
		$xml = simplexml_load_file( JPATH_ADMINISTRATOR . '/components/com_raidplanner/themes/' . $xmlfile );
		$attributes = $xml->attributes();
		if ( (string)$attributes->type == "raidplanner_theme") {
			// do SQL commands
			$this->doSQL( $xml->uninstall[0] );
			// remove files
			$this->doRemove( $xml->fileset, JPATH_SITE . '/images/raidplanner' );
			$this->doRemove( $xml->administrator[0]->fileset, JPATH_ADMINISTRATOR . '/components/com_raidplanner' );
			// remove the manifest file
			if ( !JFile::delete( JPATH_ADMINISTRATOR . '/components/com_raidplanner/themes/' . $xmlfile ) ) {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALLER_DELETE_FAILED', (string)$xml->attributes()->type), 'warning' );
				return false;
			}
		}
	}
	
	/**
	 * Get the list of installed packages
	 * $type : filter for type (unused)
	 */
	public static function getInstalledList( $type = '' )
	{
		$installed = array();
		$xmlfiles = JFolder::files( JPATH_ADMINISTRATOR . '/components/com_raidplanner/themes/', '.xml$', 1, true);
		foreach ($xmlfiles as $xmlfile)
		{
			$xml = simplexml_load_file( $xmlfile );

			if ( ( $type == '') || ( str_replace( 'raidplanner_' , '' , $xml->attributes ( "type" ) ) == $type ) )
			$installed[] = array(
				'name'			=>	(string)$xml->name[0],
				'type'			=>	str_replace( 'raidplanner_' , '' , $xml->attributes ( "type" ) ),
				'filename'		=>	basename($xmlfile),
				'creationDate'	=>	(string)$xml->creationDate[0],
				'author'		=>	(string)$xml->author[0],
				'authorEmail'	=>	(string)$xml->authorEmail[0],
				'version'		=>	(string)$xml->version[0],
				'description'	=>	(string)$xml->description[0]
			);
		}
		
		return $installed;
	}
}