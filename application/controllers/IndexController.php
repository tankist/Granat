<?php

class IndexController extends Zend_Controller_Action {

	const RANDOM_ITEMS_COUNT = 5;

	const SLIDER_ITEMS_COUNT = 25;

	public function init() {
		$this->view->imagePathHelper = $this->_helper->imagePath;
	}

	public function indexAction() {
        /**
         * @var Service_Model
         */
        $service = $this->_helper->service('Model');
		/**
		 * @var Model_Collection_Models $models
		 */
		$this->view->models = $service->getRandomModels(self::SLIDER_ITEMS_COUNT);
		$this->view->randomModels = $service->getRandomModels(self::RANDOM_ITEMS_COUNT);
		$this->view->randomFabrics = $this->_helper->service('Fabric')->getFabrics('RAND()', self::RANDOM_ITEMS_COUNT);

		/**
		 * @var Zend_Controller_Action_Helper_ViewRenderer
		 */
		$viewRenderer = $this->_helper->viewRenderer;
		$viewRenderer->setScriptAction('index-' . $this->view->language);
	}

	public function contactsAction() {
		$yandexMapsOptions = $this->getInvokeArg('ymaps');
		$this->view->apiKey = (is_array($yandexMapsOptions) && array_key_exists('key', $yandexMapsOptions))?$yandexMapsOptions['key']:'';
	}

}