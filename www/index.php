<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
                                           realpath(APPLICATION_PATH . '/../library'),
                                           get_include_path()
                                       )));

/** Zend_Application */
require_once 'Zend/Cache.php';

if (APPLICATION_ENV == 'production') {
    $frontendOptions = array(
        'lifetime' => 7200,
        'regexps' => array(
            // кэширование всего IndexController
            '^/.*$' => array(
                'cache' => true,
                'cache_with_get_variables' => true,
                'cache_with_cookie_variables' => true,
                'make_id_with_cookie_variables' => false
            ),

            // не кэшируем админку
            '^/admin/*' => array('cache' => false)
        )
    );

    $backendOptions = array(
        'cache_dir' => APPLICATION_PATH . '/../cache/page/'
    );

    // получение объекта Zend_Cache_Frontend_Page
    /** @var $cache Zend_Cache_Frontend_Page */
    $cache = Zend_Cache::factory('Page',
                                 'File',
                                 $frontendOptions,
                                 $backendOptions);

    $cache->start();
}

require_once 'Skaya/Application.php';

$configCache = Zend_Cache::factory(
    'Core', 'File',
    array('automatic_serialization' => true),
    array('cache_dir' => realpath(APPLICATION_PATH . '/../cache'))
);

// Create application, bootstrap, and run
$application = new Skaya_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini',
    $configCache
);
$application->bootstrap()
    ->run();
