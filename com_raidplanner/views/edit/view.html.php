<?php
/**
 * @package    RaidPlanner
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_1
 * @license    GNU/GPL
*/
 
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/**
 * HTML View class for the RaidPlanner Component
 *
 * @package    RaidPlanner
 */
 
class RaidPlannerViewEdit extends JView
{
    function display($tpl = null)
    {
		$model = &$this->getModel();

		if (! $model->userIsOfficer( JRequest::getVar('id') ) ) {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::_('index.php?option=com_raidplanner&task=default&month='.JRequest::getVar('month').'&modalevent='.JRequest::getVar('id') ) );

		} else {
	
			$this->assignRef( 'icons', $this->getIcons() );
			$this->assignRef( 'event', $model->getEvent(JRequest::getVar('id') ) );
			
			parent::display($tpl);
			
			/* display the event in detail */
			$vName = 'event';
			$mName = 'event';
			$document = &JFactory::getDocument();
			$vType		= $document->getType();
			$vLayout = 'preview';
			
			$controller = new RaidPlannerController();
			$view = &$controller->getView( $vName, $vType);
			$view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.strtolower($vName).DS.'tmpl');
	
			// Get/Create the model
			if ($model = &$this->getModel($mName)) {
				// Push the model into the view (as default)
				$view->setModel($model, true);
			}
			
			// add event model to calendar view
			if ($vName == 'calendar') {
				$eventmodel = &$this->getModel('event');
				$view->setModel($eventmodel, false);
			}
	
			// Set the layout
			$view->setLayout($vLayout);
	
			// Display the view
			$view->display();
		}
	}

	function getIcons()
	{
		$path = JPATH_BASE . DS . 'media' . DS . 'com_raidplanner' . DS . 'icons';
		
		$dhandle = opendir($path);
		$files = array();
		
		if ($dhandle) {
			while (false !== ($fname = readdir($dhandle))) {
				// if the file is not this file, and does not start with a '.' or '..',
				// then store it for later display
				if (($fname != '.') && ($fname != '..') &&
				($fname != basename($_SERVER['PHP_SELF']))) {
					// store the filename
					if (!is_dir( $path . DS . $fname )) {
						$info = pathinfo( $path . DS . $fname );
						$files[$fname] = ucwords(str_replace("_"," ",basename($fname,'.'.$info['extension'])));
					}
				}
			}
		   // close the directory
		   closedir($dhandle);
		}
		return $files;
	}
}