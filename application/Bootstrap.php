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
    }

    protected function _initHelperBroker()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Sch_Controller_Action_Helper');
        Zend_Controller_Action_HelperBroker::addHelper(new Sch_Controller_Action_Helper_CurrentUser());
        Zend_Controller_Action_HelperBroker::addHelper(new Sch_Controller_Action_Helper_IndexNavigation());
    }

    public function _initGedmo()
    {
        $this->bootstrap(array('locale', 'doctrine'));
        /** @var $doctrineContainer \Bisna\Doctrine\Container */
        $doctrineContainer = $this->getResource('doctrine');
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrineContainer->getEntityManager();
        $driver = $em->getConfiguration()->getMetadataDriverImpl();
        $chain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
        if ($driver instanceof \Doctrine\ORM\Mapping\Driver\Driver) {
            $chain->addDriver($driver, 'Entities');
        }
        elseif ($driver instanceof \Doctrine\ORM\Mapping\Driver\DriverChain) {
            $chain = $driver;
        }
        $driver = $em->getConfiguration()->newDefaultAnnotationDriver(APPLICATION_PATH . '/../library/Gedmo/Translatable/Entity');
        $chain->addDriver($driver, 'Gedmo\Translatable');
        $em->getConfiguration()->setMetadataDriverImpl($chain);

        $locale = $this->getResource('locale');
        if (!($locale instanceof Zend_Locale)) {
            if (!Zend_Locale::isLocale($locale)) {
                $locale = new Zend_Locale();
            }
        }
        $treeListener = new \Gedmo\Tree\TreeListener();
        $translatableListener = new \Gedmo\Translatable\TranslationListener();
        $translatableListener->setTranslatableLocale($locale->toString());
        $em->getEventManager()->addEventSubscriber($treeListener);
        $em->getEventManager()->addEventSubscriber($translatableListener);
    }

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
        $routes = $router->getRoutes();

        /** @var $langRoute Zend_Controller_Router_Route */
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

    protected function _initCache()
    {
        $this->bootstrap('cachemanager')->bootstrap('translate')->bootstrap('locale');
        /** @var $cacheManager Zend_Cache_Manager */
        $cacheManager = $this->getResource('cachemanager');
        if ($database = $cacheManager->getCache('database')) {
            Zend_Db_Table_Abstract::setDefaultMetadataCache($database);
            Zend_Paginator::setCache($database);
        }
        if ($locale = $cacheManager->getCache('locale')) {
            Zend_Translate::setCache($locale);
            Zend_Locale::setCache($locale);
        }
    }

    protected function _initDoctrineLogger()
    {
        $this->bootstrap(array('locale', 'doctrine'));
        /** @var $doctrineContainer \Bisna\Doctrine\Container */
        $doctrineContainer = $this->getResource('doctrine');
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrineContainer->getEntityManager();
        $logger = null;
        if (APPLICATION_ENV == 'development') {
            $logger = new \Sch\Doctrine\Logger\Firebug();
            $em->getConfiguration()->setSQLLogger($logger);
        }
        return $logger;
    }

}
