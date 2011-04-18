<?php
/**
 * RaidPlanner View for RaidPlanner Component
 * 
 * @package    RaidPlanner
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

JHTML::stylesheet('raidplanner.css', 'administrator/components/com_raidplanner/assets/');

/**
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class RaidPlannerViewRaidPlanner extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the data

		JToolBarHelper::title( JText::_( 'RaidPlanner' ) );
		JToolBarHelper::preferences( 'com_raidplanner' );

		$view = JRequest::getVar('view');

		JSubMenuHelper::addEntry(JText::_('Raids'), 'index.php?option=com_raidplanner&view=raids', ($view == 'raid'));
		JSubMenuHelper::addEntry(JText::_('Characters'), 'index.php?option=com_raidplanner&view=characters', ($view == 'characters'));
		JSubMenuHelper::addEntry(JText::_('Groups'), 'index.php?option=com_raidplanner&view=groups', ($view == 'groups'));
		JSubMenuHelper::addEntry(JText::_('Roles'), 'index.php?option=com_raidplanner&view=roles', ($view == 'roles'));
		JSubMenuHelper::addEntry(JText::_('Classes'), 'index.php?option=com_raidplanner&view=classes', ($view == 'classes'));

		parent::display($tpl);
	}
}