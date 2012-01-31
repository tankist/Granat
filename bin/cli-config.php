<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

include "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance();

// Creating application
$application = new Zend_Application(
    APPLICATION_ENV,
    array(
        'config' => array(
            APPLICATION_PATH . '/configs/application.ini',
            APPLICATION_PATH . '/configs/doctrine.yaml'
        )
    )
);

// Bootstrapping resources
$application->bootstrap();

/**
 * Retrieve Doctrine Container resource
 * @var \Bisna\Application\Container\DoctrineContainer $container
 */
$container = $application->getBootstrap()->getResource('doctrine');

try {
    // Bootstrapping Console HelperSet
    $helperSet = array();

    if (($dbal = $container->getConnection(getenv('CONN') ? : $container->defaultConnection)) !== null) {
        $helperSet['db'] = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($dbal);
    }

    if (($em = $container->getEntityManager(getenv('EM') ? : $container->defaultEntityManager)) !== null) {
        $helperSet['em'] = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
    }

    $helperSet['dialog'] = new \Symfony\Component\Console\Helper\DialogHelper();

} catch (\Exception $e) {
    
}

$realHelperSet = new \Symfony\Component\Console\Helper\HelperSet($helperSet);