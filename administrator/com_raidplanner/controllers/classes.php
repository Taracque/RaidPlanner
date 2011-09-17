<?php
/*------------------------------------------------------------------------
# Classes for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerControllerClasses extends RaidPlannerController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'class' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('class');

        $post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_CLASS_SAVED', JText::_('COM_RAIDPLANNER_CLASS') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ERROR_SAVING_X', JText::_('COM_RAIDPLANNER_CLASS') );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect('index.php?option=com_raidplanner&view=classes', $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('class');
		if(!$model->delete()) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ONE_OR_MORE_X_COULD_NOT_BE_DELETED', JText::_('COM_RAIDPLANNER_CLASSES') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_DELETED', JText::_('COM_RAIDPLANNER_CLASS') );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=classes', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'COM_RAIDPLANNER_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=classes', $msg );
	}
	
}