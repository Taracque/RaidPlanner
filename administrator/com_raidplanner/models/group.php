<?php
/**
 * Raids Model for RaidPlanner Component
 * 
 * @package    Raids
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_5
 * @license        GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class RaidPlannerModelGroup extends JModel
{

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__raidplanner_groups '.
					'  WHERE group_id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->group_id = 0;
			$this->_data->group_name = null;
			$this->_data->default = 0;
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{	
		$row =& $this->getTable();
		if ($this->_id==0) {
			$this->_id = $row->group_id;
		}

		$data = JRequest::get( 'post' );

		// store membership and permission
		$permissions = $data['permissions'];
		unset($data['permissions']);
		$members = $data['members'];
		unset($data['members']);
	
		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		// if default is set, remove default from all other records
		if ($data['default']==1) {
			$this->setDefault();
		}
		
		// Store permissions
		$query = "DELETE FROM #__raidplanner_permissions WHERE group_id = ".$row->group_id;
		$this->_db->Execute($query);
		foreach ($permissions as $permission_name => $permission_value) {
			$query = "INSERT INTO #__raidplanner_permissions (permission_name, permission_value, group_id) VALUES (".$this->_db->Quote($permission_name).",".intval($permission_value).",".$row->group_id.")";
			$this->_db->Execute($query);
		}
		
		// Store members
		foreach ($members as $member) {
			$query = "REPLACE INTO #__raidplanner_profile SET group_id = ".$row->group_id.", profile_id = ".intval($member);
			$this->_db->Execute($query);
		}
		return true;
	}

	function setDefault()
	{
		$query = "UPDATE #__raidplanner_groups SET `default`=(group_id = ".$this->_id.")";
		$this->_db->Execute($query);

		return true;
	}
	
	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	 * Method to get users for this group
	 *
	 * @access public
	 * @return array of user ids in group
	 */
	function getGroupUsers()
	{
		$query = "SELECT profile_id,group_id FROM #__raidplanner_profile WHERE group_id = ".$this->_id;
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList('profile_id');
	}

	/**
	 * Method to get users for this group
	 *
	 * @access public
	 * @return array of user ids in group
	 */
	function getUsers()
	{
		$query = "SELECT u.id,u.name,g.group_name FROM #__users AS u LEFT JOIN #__raidplanner_profile AS p ON p.profile_id = u.id LEFT JOIN #__raidplanner_groups AS g ON g.group_id = p.group_id ORDER BY u.name ASC";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function getPermissions()
	{
		$permissions = array(
			'allow_signup'	=>	0,
			'view_raids'	=>	0,
			'edit_raids_own'	=>	0,
			'edit_subscriptions_own'	=>	0,
			'edit_raids_any'	=>	0,
			'edit_subscriptions_any'	=>	0
		);
		
		$query = "SELECT permission_name,permission_value FROM #__raidplanner_permissions WHERE group_id = ".$this->_id;
		$this->_db->setQuery( $query );
		$result = $this->_db->loadObjectList('permission_name');
		foreach ($result as $key => $perm) {
			if (isset($permissions[$key])) {
				$permissions[$key] = $perm->permission_value;
			}
		}
		
		return $permissions;
	}

}