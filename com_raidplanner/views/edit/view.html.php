<?php
/*------------------------------------------------------------------------
# Edit View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewEdit extends JViewLegacy
{
    function display($tpl = null)
    {
		$model = $this->getModel();

		if (! $model->userIsOfficer( JRequest::getVar('id') ) ) {
			$app = JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_raidplanner&task=default&month='.JRequest::getVar('month').'&modalevent='.JRequest::getVar('id') ) );

		} else {
			$template_id = JRequest::getVar('template_id');
			if (intval($template_id) > 0) {
				$event = $model->getEvent( $template_id , true );
			} else {
				$event = $model->getEvent(JRequest::getVar('id') );
			}
			$this->assign( 'icons', $this->getIcons() );
			$this->assign( 'guilds', RaidPlannerHelper::getGuilds() );
			$this->assign( 'groups', RaidPlannerHelper::getGroups( false ) );
			$this->assignRef( 'event', $event );
			$this->assign( 'templates', $model->getTemplates() );
			$this->assign( 'candelete', $model->canDelete( $event->raid_id ) );

			parent::display($tpl);
			
			/* display the event in detail */
			$vName = 'event';
			$mName = 'event';
			$document = JFactory::getDocument();
			$vType = $document->getType();
			$vLayout = 'preview';
			
			$controller = new RaidPlannerController();
			$view = $controller->getView( $vName, $vType);
			$view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . '/views/' . strtolower($vName) . '/tmpl');
	
			// Get/Create the model
			if ($model = $this->getModel($mName)) {
				// Push the model into the view (as default)
				$view->setModel($model, true);
			}
			
			// add event model to calendar view
			if ($vName == 'calendar') {
				$eventmodel = $this->getModel('event');
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
		$path = JPATH_ROOT . '/media/com_raidplanner/raid_icons';
		$files = array();

		if (JFolder::exists( $path ) )
		{
			$allFiles = JFolder::files( $path );
			if ( !empty( $allFiles ) )
			{
				foreach ($allFiles as $fname)
				{
					if (
						($fname != '.') && 
						($fname != '..') &&
						($fname != 'index.html') &&
						($fname != basename($_SERVER['PHP_SELF']))
					) {
						$files[$fname] = ucwords(str_replace("_"," ",JFile::stripExt($fname)));
					}
				}
			}
		}
		return $files;
	}
}