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
			$success = JFile::upload($file ['tmp_name'], $this->_tmp . DS . $file ['name']);
			if ( !$success ) {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_UPLOAD_FAILED', $file ['name']), 'notice' );
				return false;
			} 
			
			return $this->installArchive( $this->_tmp . DS . $file ['name'] );
		}
	}

	/**
	 * Install the theme package from an archive file
	 * $archive : path to the (uploaded) archive
	 */
	public function installArchive($archive)
	{
		$folder_name = uniqid('RPinstall_');
		
		if ( JFolder::exists( $this->_tmp . DS . $folder_name ) ) {
			if ( !JFolder::delete( $this->_tmp . DS . $folder_name ) ) {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_FOLDER_EXISTS_CANTDELETE', $folder_name), 'warning' );
				return false;
			}
		}
		$tmp = $this->_tmp . DS . $folder_name;
		$success = JArchive::extract ( $archive, $tmp );
		if (! $success) {
			$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_EXTRACT_FAILED', $archive), 'warning' );
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
			if (isset($attributes['folder'])) {
				$source_folder = $attributes['folder'] . DS;
			} else {
				$source_folder = "";
			}
			if (isset($attributes['destination'])) {
				$destination = $attributes['destination'] . DS;
			} else {
				$destination = "";
			}
			if ($filelist = @$files->file) {
				foreach ($filelist as $file) {
					if (! JFile::delete( $dest . DS . $destination . $file->data(), null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_UNINSTALL_DELETE_FAILED', $file->data() ), 'warning' );
					}
				}
			}
			if ($folderlist = @$files->folder) {
				foreach ($folderlist as $folder) {
					if (! JFolder::delete( $dest . DS . $destination . $folder->data(), null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_UNINSTALL_DELETE_FAILED', $folder->data() ), 'warning' );
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
				$source_folder = $attributes['folder'] . DS;
			} else {
				$source_folder = "";
			}
			if (isset($attributes['destination'])) {
				$destination = $attributes['destination'] . DS;
			} else {
				$destination = "";
			}
			if ($filelist = @$files->file) {
				foreach ($filelist as $file) {
					if (! JFile::copy( $basepath . DS . $source_folder . $file->data(), $dest . DS . $destination . $file->data(), null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_COPY_FAILED', $file->data() ), 'warning' );
					}
				}
			}
			if ($folderlist = @$files->folder) {
				foreach ($folderlist as $folder) {
					if (! JFolder::copy( $basepath . DS . $source_folder . $folder->data(), $dest . DS . $destination . $folder->data(), null, true ) ) {
						$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_COPY_FAILED', $folder->data() ), 'warning' );
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
		if ( file_put_contents( $this->_tmp . DS . basename($url), $data ) ) {
			return $this->installArchive( $this->_tmp . DS . basename($url) );
		} else {
			$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_DOWNLOAD_FAILED', $url ), 'warning' );
			return false;
		}
	}
	
	/**
	 * Install the theme package from $folder path
	 */
	public function installPackage($folder)
	{
		// find the manifest file
		$xmlfiles = JFolder::files($folder, '.xml$', 1, true);
		$xml =& JFactory::getXMLParser( 'simple' );
		foreach ($xmlfiles as $xmlfile)
		{
			// get parent folder of $xmlfile for reference
			$basepath = pathinfo( $xmlfile, PATHINFO_DIRNAME );
			$xml_name = basename( $xmlfile );
			// install using the xml
			$xml->loadFile( $xmlfile );
			$attributes = $xml->document->attributes();
			if ($attributes['type'] == "raidplanner_theme") {
				// move the manifest file
				if ( !JFile::copy( $xmlfile, JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'themes' . DS . $xml_name, null, true ) ) {
					$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_INSTALL_COPY_FAILED', $xml_name ), 'warning' );
				}
				$this->doCopy( $xml->document->fileset, $basepath, JPATH_SITE . DS . 'images' . DS . 'raidplanner' );
				$this->doCopy( $xml->document->administrator[0]->fileset, $basepath, JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' );
				
			} else {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_UNKNOWN_INSTALL_TYPE', $xml->document->attributes()->type), 'warning' );
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
		$xml =& JFactory::getXMLParser( 'simple' );
		$xml->loadFile( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'themes' . DS . $xmlfile );
		$attributes = $xml->document->attributes();
		if ($attributes['type'] == "raidplanner_theme") {
			$this->doRemove( $xml->document->fileset, JPATH_SITE . DS . 'images' . DS . 'raidplanner' );
			$this->doRemove( $xml->document->administrator[0]->fileset, JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' );
			// remove the manifest file
			if ( !JFile::delete( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'themes' . DS . $xmlfile ) ) {
				$this->_app->enqueueMessage ( JText::sprintf('COM_RAIDPLANNER_UNINSTALL_DELETE_FAILED', $xml->document->attributes()->type), 'warning' );
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
		$xmlfiles = JFolder::files( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_raidplanner' . DS . 'themes' . DS, '.xml$', 1, true);
		$xml =& JFactory::getXMLParser( 'simple' );
		foreach ($xmlfiles as $xmlfile)
		{
			$xml->loadFile( $xmlfile );
			if ( ( $type == '') || ( str_replace( 'raidplanner_' , '' , $xml->document->attributes ( "type" ) ) == $type ) )
			$installed[] = array(
				'name'			=>	$xml->document->name[0]->data(),
				'type'			=>	str_replace( 'raidplanner_' , '' , $xml->document->attributes ( "type" ) ),
				'filename'		=>	basename($xmlfile),
				'creationDate'	=>	$xml->document->creationDate[0]->data(),
				'author'		=>	$xml->document->author[0]->data(),
				'authorEmail'	=>	$xml->document->authorEmail[0]->data(),
				'version'		=>	$xml->document->version[0]->data(),
				'description'	=>	$xml->document->description[0]->data()
			);
		}
		
		return $installed;
	}
}