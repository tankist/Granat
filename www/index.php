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
require_once 'Zend/Cache/Core.php';
require_once 'Zend/Cache/Backend/File.php';
require_once 'Skaya/Application.php';
$configCache = new Zend_Cache_Core(array(
	'automatic_serialization'=>true
));
$backend = new Zend_Cache_Backend_File(array(
	'cache_dir' => realpath(APPLICATION_PATH . '/../cache')
));
$configCache->setBackend($backend);

// Create application, bootstrap, and run
$application = new Skaya_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini',
	$configCache
);
$application->bootstrap()
            ->run();