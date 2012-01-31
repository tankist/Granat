<?php

/**
 * @class FabricsController
 */
class FabricsController extends Zend_Controller_Action
{

    const FABRICS_PER_PAGE = 5;

    /**
     * @var Service_Fabric
     */
    protected $_service;

    public function init()
    {
        $this->_service = new Service_Fabric($this->_helper->Em());
    }

    public function indexAction()
    {
        /**
         * @var Skaya_Paginator $fabrics
         */
        $this->view->fabrics = $fabrics = $this->_service->getPaginator();
        $fabrics
            ->setCurrentPageNumber($this->getRequest()->getParam('page', 1))
            ->setItemCountPerPage(self::FABRICS_PER_PAGE);
    }

    public function getAction()
    {
        // action body
    }

}
