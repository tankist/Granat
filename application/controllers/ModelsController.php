<?php

class ModelsController extends Zend_Controller_Action {

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		$request = $this->getRequest();
		$collection_id = $request->getParam('collection_id');
		/**
		 * @var Model_Collection $collection
		 */
		$collection = $this->_helper->service('Collection')->getCollectionById($collection_id);
		if (!$collection_id) {
			/**
			 * @var Skaya_Paginator $models
			 */
			$models = $this->_helper->service('Model')->getModelsPaginator();
		}
		else {
			$this->view->category = $category =
				$this->_helper->service('Category')->getCategoryById($request->getParam('category_id'));
			/**
			 * @var Skaya_Paginator $models
			 */
			$models = $collection->getCategoryModelsPaginator($category);
		}
		$models->setItemCountPerPage(6)->setCurrentPageNumber($page = $request->getParam('page', 1));
		$this->view->models = $models;
		$this->view->imagePathHelper = $this->_helper->imagePath;
		$this->view->page = $page;
		$this->view->collection = $collection;

		/**
		 * @var Skaya_Paginator $collections
		 */
		$collections = $this->_helper->service('Collection')->getNonEmptyCollectionsPaginator();
		$collections->setItemCountPerPage(100000)->setCurrentPageNumber(1);
		$this->view->collections = $collections;
	}

	public function viewAction() {
		// action body
	}

}