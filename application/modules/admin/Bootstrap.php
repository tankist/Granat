<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initSessionNamespace()
    {
        $this->getApplication()->bootstrap('session');
        $session = new Zend_Session_Namespace($this->_moduleName);
        return $session;
    }

    protected function _initErrorHandler()
    {
        $this->bootstrap('frontcontroller');
        $front = Zend_Controller_Front::getInstance();
        $errorOptions = array(
            'module' => strtolower($this->_moduleName),
            'controller' => 'error',
            'action' => 'error'
        );
        $errorPlugin = new Skaya_Controller_Plugin_ErrorHandler($errorOptions);
        $front->registerPlugin($errorPlugin, 101);
    }

    protected function _initPaginator()
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator.phtml');
    }

}
