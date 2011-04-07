<?php
/**
 * Group View for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class RaidPlannerViewGroup extends JView
{

	function display($tpl = null)
	{
		//get the group
		$group		=& $this->get('Data');
		$isNew		= ($group->group_id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Group' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$model =& $this->getModel();
		$group_users = $model->getGroupUsers();
		$users = $model->getUsers();

		$this->assignRef('group', $group);
		$this->assignRef('group_users', $group_users);
		$this->assignRef('users', $users);
		$this->assignRef('permissions', $model->getPermissions() );
		parent::display($tpl);
	}
}