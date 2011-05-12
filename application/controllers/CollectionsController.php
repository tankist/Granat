<?php

class CollectionsController extends Zend_Controller_Action {

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		$request = $this->getRequest();
		/**
		 * @var Skaya_Paginator $collections
		 */
		$collections = $this->_helper->service('Collection')->getNonEmptyCollectionsPaginator();
		$collections->setItemCountPerPage(6)->setCurrentPageNumber($page = $request->getParam('page', 1));
		$this->view->collections = $collections;
		$this->view->imagePathHelper = $this->_helper->imagePath;
		$this->view->page = $page;
	}

	public function getAction() {
		// action body
	}

	public function modelAction() {
		// action body
	}

}