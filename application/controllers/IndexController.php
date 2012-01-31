<?php

/**
 * @class IndexController
 */
class IndexController extends Zend_Controller_Action
{

    const RANDOM_ITEMS_COUNT = 5;

    const SLIDER_ITEMS_COUNT = 25;

    /**
     * @var Service_Model
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_Model($this->_helper->Em());
        $this->view->imagePathHelper = $this->_helper->attachmentPath;
    }

    public function indexAction()
    {
        $this->view->models = $this->_service->getRandomModels(self::SLIDER_ITEMS_COUNT);
        $this->view->randomModels = $this->_service->getRandomModels(self::RANDOM_ITEMS_COUNT);
//        $this->view->randomFabrics = $this->_helper->service('Fabric')->getFabrics('RAND()', self::RANDOM_ITEMS_COUNT);

        /** @var $viewRenderer Zend_Controller_Action_Helper_ViewRenderer */
        $viewRenderer = $this->_helper->viewRenderer;
        $viewRenderer->setScriptAction('index-' . $this->view->language);
    }

    public function contactsAction()
    {
        $yandexMapsOptions = $this->getInvokeArg('ymaps');
        $this->view->apiKey = (is_array($yandexMapsOptions) && array_key_exists('key', $yandexMapsOptions)) ? $yandexMapsOptions['key'] : '';
    }

}
