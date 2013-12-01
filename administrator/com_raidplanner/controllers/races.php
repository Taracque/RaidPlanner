<?php
/*------------------------------------------------------------------------
# Races for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerControllerRaces extends RaidPlannerController
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
		JRequest::setVar( 'view', 'race' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);

		parent::display();
	}

	function apply()
	{
		$this->save();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('race');
		$task = $this->getTask();

        $post = JRequest::get('post');

		if ($race_id = $model->store($post)) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_SAVED', JText::_('COM_RAIDPLANNER_RACE') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ERROR_SAVING_X', JText::_('COM_RAIDPLANNER_RACE') );
		}
		
		// Check the table in so it can be edited.... we are done with it anyway
		if ($task == 'apply') {
			$link = 'index.php?option=com_raidplanner&view=race&task=edit&cid[]=' . $race_id;
		} else {
			$link = 'index.php?option=com_raidplanner&view=races';
		}
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('race');
		if(!$model->delete()) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ERROR_ONE_OR_MORE_X_COULD_NOT_BE_DELETED', JText::_('COM_RAIDPLANNER_RACES') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_DELETED', JText::_('COM_RAIDPLANNER_RACE') );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=races', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'COM_RAIDPLANNER_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=races', $msg );
	}
	
}