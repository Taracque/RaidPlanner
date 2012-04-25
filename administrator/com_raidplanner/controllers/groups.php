<?php
/*------------------------------------------------------------------------
# Groups Controller for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerControllerGroups extends RaidPlannerController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add' , 'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'group' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('group');

        $post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_SAVED', JText::_('COM_RAIDPLANNER_GROUP') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ERROR_SAVING_X', JText::_('COM_RAIDPLANNER_GROUP') );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect('index.php?option=com_raidplanner&view=groups', $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('group');
		if(!$model->delete()) {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_ONE_OR_MORE_X_COULD_NOT_BE_DELETED', JText::_('COM_RAIDPLANNER_GROUPS') );
		} else {
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_DELETED', JText::_( 'COM_RAIDPLANNER_GROUP' ) );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'COM_RAIDPLANNER_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}
	
	function setDefault()
	{
		$group_id = JRequest::getVar('cid', null, 'INT');
		$model = $this->getModel('group');
		if (!$model->setDefault()) {
			$msg = JText::_( 'COM_RAIDPLANNER_DEFAULT_GROUP_CANT_CHANGED' );
		} else {
			$msg = JText::_( 'COM_RAIDPLANNER_DEFAULT_GROUP_CHANGED' );
		}
		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}
	
	function saveRules()
	{
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		if (isset($data['rules'])) {
			$rules	= new JAccessRules($data['rules']);
			$asset	= JTable::getInstance('asset');

			if (!$asset->loadByName( 'com_raidplanner.frontend' )) {
				$root	= JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = 'com_raidplanner.frontend';
				$asset->title = 'com_raidplanner.frontend';
				$asset->setLocation($root->id, 'last-child');
			}
			$asset->rules = (string) $rules;

			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}
			$msg = JText::sprintf( 'COM_RAIDPLANNER_X_SAVED', JText::_('COM_RAIDPLANNER_GROUP') );
		}
		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}
}