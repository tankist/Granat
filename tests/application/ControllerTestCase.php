<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    /**
     * @var Zend_Application
     */
    protected $_application;

    protected function setUp()
    {
        // указываем функцию, которая будет выполнена до запуска тестов
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }

    protected function appBootstrap()
    {
        // инициализируем наше приложение
        $this->_application = new Zend_Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/configs/application.ini'
        );

        $this->_application->bootstrap();
    }
}
