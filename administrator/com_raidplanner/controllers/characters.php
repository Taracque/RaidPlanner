<?php
/**
 * Characters Controller for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class RaidPlannerControllerCharacters extends RaidPlannerController
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
		JRequest::setVar( 'view', 'character' );
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
		$model = $this->getModel('character');

        $post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::_( 'Character Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Character' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect('index.php?option=com_raidplanner&view=characters', $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('character');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Characters Could not be Deleted' );
		} else {
			$msg = JText::_( 'Character(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_raidplanner&view=characters', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_raidplanner&view=characters', $msg );
	}
	
}