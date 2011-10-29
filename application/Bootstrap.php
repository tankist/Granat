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

    protected function _initDoctrine()
    {
        $options = $this->getOptions();
        $doctrinePath = $options['includePaths']['library'];
        require_once $doctrinePath . '/Doctrine/Common/ClassLoader.php';
        $autoloader = Zend_Loader_Autoloader::getInstance();

        $doctrineAutoloader = array(new \Doctrine\Common\ClassLoader(), 'loadClass');
        $autoloader->pushAutoloader($doctrineAutoloader, 'Doctrine');

        $classLoader = new \Doctrine\Common\ClassLoader('Entities', realpath(__DIR__ . '/models/'));
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Entities');
        $classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(__DIR__ . '/../library/Doctrine/'));
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Symfony');
        $classLoader = new \Doctrine\Common\ClassLoader('Repository', realpath(__DIR__ . '/models/'));
        $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Repository');

        $config = new \Doctrine\ORM\Configuration();
        $driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '/models/Entities');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setProxyDir(APPLICATION_PATH . '/models/Proxies');
        $config->setProxyNamespace('Proxies');

        if (APPLICATION_ENV == "development") {
            $cache = new \Doctrine\Common\Cache\ArrayCache();
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);
        }/* else {
            $cacheOptions = $options['cache']['backendOptions'];
            $cache = new \Doctrine\Common\Cache\MemcacheCache();
            $memcache = new Memcache;
            $memcache->connect($cacheOptions['servers']['host'], $cacheOptions['servers']['port']);
            $cache->setMemcache($memcache);
        }*/

        if (APPLICATION_ENV == "development") {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        $em = \Doctrine\ORM\EntityManager::create($options['db'], $config);
        $em->getConnection()->setCharset('utf8');
        Zend_Registry::set('em', $em);

        return $em;
    }

    protected function _initDoctrineLogger()
    {
        $this->bootstrap('doctrine');
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getResource('doctrine');
        $logger = null;
        if (APPLICATION_ENV == 'development') {
            $logger = new \Sch\Doctrine\Logger\Firebug();
            $em->getConfiguration()->setSQLLogger($logger);
        }
        return $logger;
    }

}
