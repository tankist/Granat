<?php

/**
 * @class CollectionsController
 */
class CollectionsController extends Zend_Controller_Action
{

    /**
     * @var Service_Collection
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_Collection($this->_helper->Em());
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $page = $request->getParam('page', 1);
        /**
         * @var Skaya_Paginator $collections
         */
        $collections = $this->_service->getPaginator(array('nonEmpty' => true));
        $collections
            ->setItemCountPerPage(6)
            ->setCurrentPageNumber($page);
        $this->view->collections = $collections;
        $this->view->imagePathHelper = $this->_helper->attachmentPath;
        $this->view->page = $page;
    }

}
