<?php
/**
 * Hello Controller for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
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
		$this->registerTask( 'add'  , 	'edit' );
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
			$msg = JText::_( 'Group Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Group' );
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
			$msg = JText::_( 'Error: One or More Groups Could not be Deleted' );
		} else {
			$msg = JText::_( 'Group(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}
	
	function setDefault()
	{
		$group_id = JRequest::getVar('cid', null, 'INT');
		$model = $this->getModel('group');
		if (!$model->setDefault()) {
			$msg = JText::_( 'Error: Default group can\'t changed' );
		} else {
			$msg = JText::_( 'Default group changed' );
		}
		$this->setRedirect( 'index.php?option=com_raidplanner&view=groups', $msg );
	}
}