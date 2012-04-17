<?php
/*------------------------------------------------------------------------
# Raids Controller for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerControllerRaids extends RaidPlannerController
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
		JRequest::setVar( 'view', 'raid' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * apply a record (and redirect to main page)
	 * @return void
	 */
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
		$model = $this->getModel('raid');
		$task = $this->getTask();
        $post = JRequest::get('post');

		if ($raid_id = $model->store($post)) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_SAVED', JText::_('COM_RAIDPLANNER_RAID')  );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ERROR_SAVING_X', JText::_('COM_RAIDPLANNER_RAID')  );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		if ($task == 'apply') {
			$link = 'index.php?option=com_raidplanner&view=raid&controller=raids&task=edit&cid[]='.$raid_id;
		} else {
			$link = 'index.php?option=com_raidplanner&view=raids';
		}
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('raid');
		if(!$model->delete()) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ERROR_ONE_OR_MORE_X_COULD_NOT_BE_DELETED', JText::_('COM_RAIDPLANNER_RAIDS') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_DELETED', JText::_('COM_RAIDPLANNER_RAID') );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=raids', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'COM_RAIDPLANNER_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=raids', $msg );
	}
}