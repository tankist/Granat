<?php

class CollectionsController extends Zend_Controller_Action {

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		/**
		 * @var Model_Collection_Collections $collections
		 */
		$collections = $this->_helper->service('Collection')->getCollections();
		$this->view->collections = $collections;
	}

	public function getAction() {
		// action body
	}

	public function modelAction() {
		// action body
	}

}