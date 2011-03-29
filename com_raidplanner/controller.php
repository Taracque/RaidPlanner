<?php
/**
 * @package    RaidPlanner
 * @subpackage Components
 * @license    GNU/GPL
 */
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
/**
 * Raid Planner Component Controller
 *
 * @package    RaidPlanner
 * @subpackage Components
 */
class RaidPlannerController extends JController
{
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {

		$document = &JFactory::getDocument();
		$vType		= $document->getType();
		$mName	= '';
		switch ($this->getTask())
		{
			case 'edit' :
				$vName = 'edit';
				$mName = 'event';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
			break;
			case 'signup':
				$vName = 'calendar';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
				$model = &$this->getModel('event');
				$model->signupEvent();
				$this->setRedirect(JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&month='.JRequest::getVar('month').'&modalevent='.JRequest::getVar('raid_id') ) );
			break;
			case 'saveevent':
				$vName = 'calendar';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
				$model = &$this->getModel('event');
				$raid_id = $model->saveEvent();
				$this->setRedirect(JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&month='.JRequest::getVar('month').'&modalevent='.$raid_id ) );
			break;
			case 'confirm':
				$vName = 'calendar';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
				$model = &$this->getModel('event');
				$model->confirmEvent();
				$this->setRedirect(JRoute::_('index.php?option=com_raidplanner&view=calendar&task=default&month='.JRequest::getVar('month').'&modalevent='.JRequest::getVar('raid_id') ) );
			break;
			case 'viewevent':
				$vName = 'event';
				$mName = 'event';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
			break;
			case 'feed':
				$vName = 'calendar';
				$vType = 'feed';
				$vLayout = JRequest::getCmd( 'layout', 'feed' );
			break;
			default:
			case 'default':
				$vName = 'calendar';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
			break;
		}
		
		// Get/Create the view
		$view = &$this->getView( $vName, $vType);
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
