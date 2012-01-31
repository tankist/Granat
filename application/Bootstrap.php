<?php

/**
 * @class Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoloadNamespace()
    {
        require_once '/Doctrine/Common/ClassLoader.php';

        require_once APPLICATION_PATH .
            '/../library/Symfony/Component/Di/sfServiceContainerAutoloader.php';

        sfServiceContainerAutoloader::register();
        $autoloader = \Zend_Loader_Autoloader::getInstance();

        $doctrineAutoloader = new \Doctrine\Common\ClassLoader('Doctrine');
        $autoloader->pushAutoloader(array($doctrineAutoloader, 'loadClass'), 'Doctrine');

        $symfonyAutoloader = new \Doctrine\Common\ClassLoader('Symfony');
        $autoloader->pushAutoloader(array($symfonyAutoloader, 'loadClass'), 'Symfony');

        $doctrineExtensionsAutoloader = new \Doctrine\Common\ClassLoader('DoctrineExtensions');
        $autoloader->pushAutoloader(array($doctrineExtensionsAutoloader, 'loadClass'), 'DoctrineExtensions');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Bisna');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Gedmo');
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Gedmo');

        $modelsPath = realpath(APPLICATION_PATH . '/models');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Entities', $modelsPath);
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Entities');

        $fmmAutoloader = new \Doctrine\Common\ClassLoader('Repository', $modelsPath);
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'), 'Repository');
    }

    protected function _initHelperBroker()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Sch_Controller_Action_Helper');
        Zend_Controller_Action_HelperBroker::addHelper(new Sch_Controller_Action_Helper_CurrentUser());
        Zend_Controller_Action_HelperBroker::addHelper(new Sch_Controller_Action_Helper_IndexNavigation());
    }

    /**
     * @return Zend_Session_Namespace
     */
    protected function _initSessionNamespace()
    {
        $session = new Zend_Session_Namespace('Skaya');
        return $session;
    }

    protected function _initRoutes()
    {
        $this->bootstrap('frontcontroller')->bootstrap('locale');
        /** @var $front Zend_Controller_Front */
        $front = $this->getResource('frontcontroller');
        $routesConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/router.ini', APPLICATION_ENV);
        /** @var $router Zend_Controller_Router_Rewrite */
        $router = $front->getRouter();
        $router->addConfig($routesConfig, 'routes');
        /** @var $routes Zend_Controller_Router_Route[] */
        $routes = $router->getRoutes();

        /** @var $langRoute Zend_Controller_Router_Route */
        $langRoute = new Zend_Controller_Router_Route(':lang',
            array('lang' => 'ru'),
            array('lang' => '^(en|ru)$')
        );

        $defaultRoute = new Skaya_Controller_Router_Route_Chain();
        $defaultRoute->chain($langRoute)->chain($router->getRoute('defaultmodule'));
        $router->addRoute('default', $defaultRoute);

        foreach ($routes as $name => $route) {
            if (!in_array($name, array('default', 'defaultmodule')) && !$route->isAbstract(true)) {
                $chain = new Skaya_Controller_Router_Route_Chain();
                $router->addRoute($name, $chain->chain($langRoute)->chain($route));
            }
        }

        $front->registerPlugin(new Skaya_Controller_Plugin_MultilingualRouter($langRoute->getDefault('lang')));
    }

    protected function _initYandexMaps()
    {
        $this->bootstrap('frontcontroller');
        /** @var $front Zend_Controller_Front */
        $front = $this->getResource('frontcontroller');
        $front->setParam('ymaps', $this->getOption('ymaps'));
    }

    protected function _initTranslation()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new Skaya_Controller_Action_Helper_Translator());
    }

    protected function _initCache()
    {
        $this->bootstrap('cachemanager')->bootstrap('translate')->bootstrap('locale');
        /** @var $cacheManager Zend_Cache_Manager */
        $cacheManager = $this->getResource('cachemanager');
        if (($database = $cacheManager->getCache('database'))) {
            Zend_Db_Table_Abstract::setDefaultMetadataCache($database);
            Zend_Paginator::setCache($database);
        }
        if (($locale = $cacheManager->getCache('locale'))) {
            Zend_Translate::setCache($locale);
            Zend_Locale::setCache($locale);
        }
    }

    protected function _initTwitterBootstrap()
    {
        Sch_Twitter_View_Helper_FlashMessages::setVersion(Sch_Twitter_View_Helper_FlashMessages::TWITTER_VERSION_2);
    }

}
