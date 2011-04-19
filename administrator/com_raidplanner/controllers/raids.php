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
		$model = $this->getModel('raid');
        $post = JRequest::get('post');
		if ($model->store($post)) {
			$msg = JText::_( 'Raid Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Raid' );
		}

		$raid_id = JRequest::getVar('raid_id',  0, '', 'int');
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_raidplanner&view=raid&controller=raids&task=edit&cid[]='.$raid_id;
		$this->setRedirect($link, $msg);
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('raid');

        $post = JRequest::get('post');
		if ($model->store($post)) {
			$msg = JText::_( 'Raid Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Raid' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_raidplanner&view=raids';
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
			$msg = JText::_( 'Error: One or More Raids Could not be Deleted' );
		} else {
			$msg = JText::_( 'Raid(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=raids', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=raids', $msg );
	}
}