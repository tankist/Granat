<?php

/**
 * @class Admin_Bootstrap
 */
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initResourceLoader()
    {
        $this->getResourceLoader()->addResourceType('helper', 'helpers', 'Helper');
    }

    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Admin_Helper_Navigator());
    }

    /**
     * @return Zend_Session_Namespace
     */
    protected function _initSessionNamespace()
    {
        $this->getApplication()->bootstrap('session');
        return new Zend_Session_Namespace($this->_moduleName);
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

    protected function _initRoutes()
    {
        $module = strtolower($this->getModuleName());

        $this->bootstrap(array('frontController'));
        /** @var $front Zend_Controller_Front */
        $front = $this->getResource('frontController');
        $ini = $front->getModuleDirectory($module) . '/configs/router.ini';
        $routes = new Zend_Config_Ini($ini, APPLICATION_ENV);
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();

        $hostnameRouter = new Zend_Controller_Router_Route_Hostname($module . '.granat.:tld', array(
            'module' => $module,
            'controller' => 'index',
            'action' => 'index',
            'tld' => $this->getOption('tld')
        ));

        /** @var $langRoute Zend_Controller_Router_Route */
        $langRoute = new Zend_Controller_Router_Route(':lang',
            array('lang' => 'ru'),
            array('lang' => '^(en|ru)$')
        );

        $langHostnameRoute = new Skaya_Controller_Router_Route_Chain();
        $langHostnameRoute->chain($hostnameRouter)->chain($langRoute);
        $router->addRoute('admin', $langHostnameRoute);

        $tmpRouter = new Zend_Controller_Router_Rewrite();
        $tmpRouter->removeDefaultRoutes();
        $tmpRouter->addConfig($routes->routes);
        foreach ($tmpRouter->getRoutes() as $name => $route) {
            $chain = new Skaya_Controller_Router_Route_Chain();
            $router->addRoute($module . '-' . $name, $chain->chain($hostnameRouter)->chain($langRoute)->chain($route));
        }

        if ($router->hasRoute($module . '-' . 'default')) {
            /** @var $default Zend_Controller_Router_Route */
            $default = $router->getRoute($module . '-' . 'default');
            $router->addRoute(
                $module . '-' . 'default-pages',
                $default->chain($router->getRoute('pagination'))
            );
        }

        unset($tmpRouter);
    }

}
