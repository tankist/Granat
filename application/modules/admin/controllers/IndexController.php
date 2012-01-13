<?php

class Admin_IndexController extends Zend_Controller_Action
{

    /**
     * @var \Entities\User
     */
    protected $_user;

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
        $this->_helper->getHelper('AjaxContext')->initContext('json');
        $this->_user = $this->_helper->currentUser();
    }

    public function indexAction()
    {
        // action body
    }

}





