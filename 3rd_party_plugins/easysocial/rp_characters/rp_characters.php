<?php

defined('_JEXEC') or die('Restricted access');

class SocialFieldsUserRP_Characters extends SocialFieldItem {

	public function onRegister (&$post, &$registration) {
		return $this->display();
	}

	public function onEdit (&$post, &$user) {
		return $this->display();
	}

	public function onDisplay (&$post, &$user) {
		return $this->display();
	}

	public function onSample () {
		return $this->display();
	}

}
