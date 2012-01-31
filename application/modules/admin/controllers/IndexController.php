<?php

/**
 * @class Admin_IndexController
 */
class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        Zend_Layout::getMvcInstance()
            ->setLayoutPath(APPLICATION_PATH . '/modules/admin/layouts/scripts')
            ->setLayout('admin');
    }

    public function preDispatch()
    {
        $this->_helper->navigator();
    }

    public function indexAction()
    {
        $this->_redirect($this->_helper->url->url(array('controller' => 'collections'), 'admin-default'));
    }

}





