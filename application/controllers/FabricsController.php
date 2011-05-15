<?php

class FabricsController extends Zend_Controller_Action {

	const FABRICS_PER_PAGE = 5;

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		/**
		 * @var Skaya_Paginator $fabrics
		 */
		$this->view->fabrics = $fabrics = $this->_helper->service('Fabric')->getFabricsPaginator();
		$fabrics
			->setCurrentPageNumber($this->getRequest()->getParam('page', 1))
			->setItemCountPerPage(self::FABRICS_PER_PAGE);
	}

	public function getAction() {
		// action body
	}

}
