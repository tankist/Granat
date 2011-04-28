<?php

class Admin_ModelImageController extends Zend_Controller_Action {

	/**
	 * @var Model_User
	 */
	protected $_user = null;

	public $contexts = array(
		'upload' => true,
		'delete' => true
	);

	public function init() {
		$this->_helper->getHelper('AjaxContext')->initContext('json');
		$this->_helper->getHelper('ContextSwitch')->clearHeaders('json')->initContext('json');
		$this->_user = $this->_helper->user();
	}

	public function indexAction() {
		// action body
	}

	public function uploadAction() {
		$filesCount = count($_FILES);
		$this->view->assign(array(
			'name' => 'image.jpg',
			'type' => 'image/jpeg',
			'size' => 34324233
		));
	}

	public function deleteAction() {
		// action body
	}

}