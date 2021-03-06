<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initModule()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'basePath' => APPLICATION_PATH,
            ));
        return $loader;
    }

    protected function _initAutoloadNamespace()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Sch_Controller_Action_Helper');
    }

    protected function _initSessionNamespace()
    {
        $session = new Zend_Session_Namespace('Skaya');
        return $session;
    }

    protected function _initAuthAcl()
    {
        $this->bootstrap('acl');

        $front = Zend_Controller_Front::getInstance();
        $authPlugin = $front->getPlugin('Skaya_Controller_Plugin_Auth');
        if (!$authPlugin) {
            $authPlugin = new Skaya_Controller_Plugin_Auth(
                Zend_Auth::getInstance(),
                $this->getResource('acl')
            );
            $front->registerPlugin($authPlugin);
        }
        else {
            $authPlugin
                ->setAcl($this->getResource('acl'))
                ->setAuth(Zend_Auth::getInstance());
        }
        $authPlugin->setIsSeparateAuthNamespace(true);
        $options = $this->getOption('authacl');
        foreach ((array)$options as $key => $value) {
            if (strtolower($key) == 'noauth') {
                $authPlugin->setNoAuthRules($value);
            }
            if (strtolower($key) == 'noacl') {
                $authPlugin->setNoAclRules($value);
            }
        }
        return $authPlugin;
    }

    protected function _initRoutes()
    {
        $this->bootstrap('frontcontroller')->bootstrap('locale');
        /**
         * @var Zend_Controller_Front $front
         */
        $front = $this->getResource('frontcontroller');
        $routesConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini', APPLICATION_ENV);
        /**
         * @var Zend_Controller_Router_Rewrite $router
         */
        $router = $front->getRouter();
        $router->addConfig($routesConfig, 'routes');
        $routes = $router->getRoutes();

        /**
         * @var Zend_Controller_Router_Route $langRoute
         */
        $langRoute = $router->getRoute('language');

        foreach ($routes as $name => $route) {
            if (!in_array($name, array('language', 'default', 'defaultmodule'))) {
                $chain = new Skaya_Controller_Router_Route_Chain();
                $router->addRoute($name, $chain->chain($langRoute)->chain($route));
            }
        }

        $front->registerPlugin(new Skaya_Controller_Plugin_MultilingualRouter($langRoute->getDefault('lang')));
    }

    protected function _initYandexMaps()
    {
        $this->bootstrap('frontcontroller');
        $options = $this->getOption('ymaps');
        $this->getResource('frontcontroller')->setParam('ymaps', $options);
    }

    protected function _initTranslation()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Skaya_Controller_Action_Helper_Translator());
    }

    protected function _initMapperBrocker()
    {
        Skaya_Model_Mapper_MapperBroker::getPluginLoader()->addPrefixPath('Model_Mapper', APPLICATION_PATH . '/models/mappers');
    }

    protected function _initCache()
    {
        $this->bootstrap('cachemanager')->bootstrap('translate')->bootstrap('locale')->bootstrap('db');
        /**
         * @var Zend_Cache_Manager $cacheManager
         */
        $cacheManager = $this->getResource('cachemanager');
        if ($database = $cacheManager->getCache('database')) {
            Zend_Db_Table_Abstract::setDefaultMetadataCache($database);
            Zend_Paginator::setCache($database);
            Skaya_Model_Mapper_Decorator_Cache::setCache($database);
        }
        if ($locale = $cacheManager->getCache('locale')) {
            Zend_Translate::setCache($locale);
            Zend_Locale::setCache($locale);
        }
    }

}
