<?php

class CollectionsController extends Zend_Controller_Action {

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		$params = $this->getRequest()->getParams();
		/**
		 * @var Model_Collection_Collections $collections
		 */
		$collections = $this->_helper->service('Collection')->getCollections();
		$this->view->collections = $collections;
		$this->view->imagePathHelper = $this->_helper->imagePath;
	}

	public function getAction() {
		// action body
	}

	public function modelAction() {
		// action body
	}

}