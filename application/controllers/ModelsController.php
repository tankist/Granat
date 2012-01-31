<?php

/**
 * @class ModelsController
 */
class ModelsController extends Zend_Controller_Action
{

    /**
     * @var Service_Model
     */
    protected $_service;

    /**
     * @var Service_Collection
     */
    protected $_collectionsService;

    public function init()
    {
        $em = $this->_helper->Em();
        $this->_service = new Service_Model($em);
        $this->_collectionsService = new Service_Collection($em);
        $this->view->imagePathHelper = $this->_helper->attachmentPath;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $this->view->page = $page = $request->getParam('page', 1);
        $collection_id = $request->getParam('collection_id');
        /** @var $collection \Entities\Collection */
        $this->view->collection = $collection = $this->_collectionsService->getById($collection_id);
        $params = array();
        if ($collection) {
            $params['collection_id'] = $collection->getId();
        }
        else {
            $categoryService = new Service_Category($this->_helper->Em());
            /** @var $category \Entities\Category */
            $this->view->category = $category = $categoryService->getById($request->getParam('category_id'));
            if ($category) {
                $params['category_id'] = $category->getId();
            }
        }
        $this->view->models = $this->_service->getPaginator($params)
                                            ->setItemCountPerPage(6)
                                            ->setCurrentPageNumber($page);
        $this->view->collections = $this->_collectionsService->getNonEmptyCollections();
    }

    public function viewAction()
    {
        $request = $this->getRequest();
        $model_id = $request->getParam('model_id');
        if (!($model = $this->_service->getById($model_id))) {
            throw new Zend_Controller_Action_Exception('Model not found', 404);
        }
        $this->view->model = $model;
        $this->view->collections = $this->_collectionsService->getNonEmptyCollections();
    }

}
