<?php
 /**
  * @version		
  * @copyright	Copyright (C) 2011 Taracque. All rights reserved.
  * @license	GNU General Public License version 2 or later; see LICENSE.txt
  */
 
 defined('JPATH_BASE') or die;
 
  /**
   * Custom profile plugin for RaidPlanner Component.
   *
   * @package		RaidPlanner.Plugins
   * @subpackage	user.profile
   * @version		1.6
   */
  class plgUserRaidPlanner extends JPlugin
  {
	/**
	 * @param	string	The context for the data
	 * @param	int		The user id
	 * @param	object
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile','com_users.registration','com_users.user','com_admin.profile' ))){
			return true;
		}
 
		$userId = isset($data->id) ? $data->id : 0;
 		if ($userId) {
			$juser =& JFactory::getUser($userId);

			$data->raidplanner = array();

			$data->raidplanner['characters'] = $juser->getParam('characters', '');
			$data->raidplanner['calendar_secret'] = $juser->getParam('calendar_secret', '');
			$data->raidplanner['vacation'] = $juser->getParam('vacation', '');
		} 
		return true;
	}
 
	/**
	 * @param	JForm	The form to be altered.
	 * @param	array	The associated data for the form.
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareForm($form, $data)
	{
		// Load user_profile plugin language
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_raidplanner', JPATH_ADMINISTRATOR);
 
		if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_users.profile', 'com_users.registration','com_users.user','com_admin.profile'))) {
			return true;
		}
		if ($form->getName()=='com_users.profile')
		{
			// Add the profile fields to the form.
			JForm::addFormPath(dirname(__FILE__).'/profiles');
			$form->loadFile('profile', false);
 
			// Toggle whether the something field is required.
			if ($this->params->get('profile-require_raidplanner', 1) > 0) {
				$form->setFieldAttribute('characters', 'required', $this->params->get('profile-require_raidplanner') == 2, 'profile');
				$form->setFieldAttribute('calendar_secret', 'required', $this->params->get('profile-require_raidplanner') == 2, 'profile');
				$form->setFieldAttribute('vacation', 'required', $this->params->get('profile-require_raidplanner') == 2, 'profile');
			} else {
				$form->removeField('characters', 'profile');
				$form->removeField('calendar_secret', 'profile');
				$form->removeField('vacation', 'profile');
			}
		}
 
		//In this example, we treat the frontend registration and the back end user create or edit as the same. 
		elseif ($form->getName()=='com_users.registration' || $form->getName()=='com_users.user' )
		{		
			// Add the registration fields to the form.
			JForm::addFormPath(dirname(__FILE__).'/profiles');
			$form->loadFile('profile', false);
 
			// Toggle whether the characters field is required.
			if ($this->params->get('register-require_raidplanner', 1) > 0) {
				$form->setFieldAttribute('characters', 'required', $this->params->get('register-require_raidplanner') == 2, 'profile');
				$form->setFieldAttribute('calendar_secret', 'required', $this->params->get('register-require_raidplanner') == 2, 'profile');
				$form->setFieldAttribute('vacation', 'required', $this->params->get('register-require_raidplanner') == 2, 'profile');
			} else {
				$form->removeField('characters', 'profile');
				$form->removeField('calendar_secret', 'profile');
				$form->removeField('vacation', 'profile');
			}
		} else {
				JForm::addFormPath(dirname(__FILE__).'/profiles');
				$form->loadFile('profile', false);
		}
	}

	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');
		if ($userId && $result && isset($data['raidplanner']) && (count($data['raidplanner'])))
		{
			try
			{
				$juser =& JFactory::getUser($userId);
				$params = json_decode($juser->params);
				foreach ($data['raidplanner'] as $k => $v) {
					$juser->setParam($k, $v);
					$params->$k = $v;
				}
				$juser->params = json_encode($params);
				$table = $juser->getTable();
				$table->bind($juser->getProperties());
				$table->store();
			}
			catch (JException $e) {
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}
		return true;
	}
 
 }
