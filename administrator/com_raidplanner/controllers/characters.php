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