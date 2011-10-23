<?php

class Admin_IndexController extends Zend_Controller_Action
{

    /**
     * @var Model_User
     */
    protected $_user;

    public function init()
    {
        $this->_helper->getHelper('AjaxContext')->initContext('json');
        $this->_user = $this->_helper->user();
    }

    public function indexAction()
    {
        // action body
    }

}





