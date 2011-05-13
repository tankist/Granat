<?php

class IndexController extends Zend_Controller_Action {

	const RANDOM_ITEMS_COUNT = 5;

	public function init() {
		/* Initialize action controller here */
	}

	public function indexAction() {
		/**
		 * @var Model_Collection_Models $models
		 */
		$this->view->models = $models = $this->_helper->service('Model')->getModels('RAND()');

		$randomModels = new Model_Collection_Models();
		$modelsCount = $randomCount = count($models);
		if ($randomCount > self::RANDOM_ITEMS_COUNT) {
			$randomCount = self::RANDOM_ITEMS_COUNT;
		}
		$keys = array_rand(range(0, $modelsCount - 1), $randomCount);
		for ($i=0;$i<count($keys);$i++) {
			$randomModels[$i] = $models[$keys[$i]];
		}

		$this->view->randomModels = $randomModels;
		$this->view->randomFabrics = $this->_helper->service('Fabric')->getFabrics('RAND()', self::RANDOM_ITEMS_COUNT);
	}

	public function contactsAction() {
		// action body
	}

	public function loginAction() {
		// action body
	}

	public function logoutAction() {
		// action body
	}


}







