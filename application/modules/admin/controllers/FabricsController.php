<?php

class Admin_FabricsController extends Zend_Controller_Action {

	/**
	 * @var Model_User
	 */
	protected $_user;

	public function init() {
		$this->_helper->getHelper('AjaxContext')->initContext('json');
		$this->_user = $this->_helper->user();
	}

	public function indexAction() {
		// action body
	}

	public function addAction() {
		// action body
	}

	public function editAction() {
		// action body
	}

	public function deleteAction() {
		// action body
	}

	public function saveAction() {
		// action body
	}


}









