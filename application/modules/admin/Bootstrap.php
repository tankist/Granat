<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initSessionNamespace()
    {
        $this->getApplication()->bootstrap('session');
        $session = new Zend_Session_Namespace($this->_moduleName);
        return $session;
    }

    protected function _initAuthAcl()
    {
        $this->bootstrap('acl');

        $moduleName = strtolower($this->_moduleName);

        $front = Zend_Controller_Front::getInstance();
        $authPlugin = $front->getPlugin('Skaya_Controller_Plugin_Auth');
        if (!$authPlugin) {
            $authPlugin = new Skaya_Controller_Plugin_Auth(
                Zend_Auth::getInstance(),
                $this->getResource('acl'),
                $moduleName
            );
            $front->registerPlugin($authPlugin);
        }
        else {
            $authPlugin
                ->setAcl($this->getResource('acl'), $moduleName)
                ->setAuth(Zend_Auth::getInstance(), $moduleName);
        }
        $authPlugin->setIsSeparateAuthNamespace(true);
        $options = $this->getOption('authacl');
        foreach ((array)$options as $key => $value) {
            if (strtolower($key) == 'noauth') {
                $authPlugin->setNoAuthRules($value, $moduleName);
            }
            if (strtolower($key) == 'noacl') {
                $authPlugin->setNoAclRules($value, $moduleName);
            }
        }
        return $authPlugin;
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
