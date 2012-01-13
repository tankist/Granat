<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

// Call Skaya_Model_Mapper_MapperBrokerTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Skaya_Model_Mapper_MapperBrokerTest::main");
}

require_once 'Skaya/Model/Mapper/Interface.php';
require_once 'Skaya/Model/Mapper/Abstract.php';
require_once 'Skaya/Model/Interface.php';
require_once 'Skaya/Model/Abstract.php';
require_once 'Skaya/Model/Mapper/MapperBroker/PriorityStack.php';

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Controller
 * @group      Model_Mapper
 * @group      Model_Mapper_Mapper
 */
class Skaya_Model_Mapper_MapperBrokerTest extends ControllerTestCase
{
    /**
     * @var Zend_Controller_Front
     */
    protected $front;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {

        $suite = new PHPUnit_Framework_TestSuite("Skaya_Model_Mapper_MapperBrokerTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
        $this->front = Zend_Controller_Front::getInstance();
        $this->front->resetInstance();
        $this->front->setParam('noViewRenderer', true)
            ->setParam('noErrorHandler', true)
            ->throwExceptions(true);
        Skaya_Model_Mapper_MapperBroker::resetMappers();
    }

    public function testGetExistingMapperThrowsExceptionWithUnregisteredMapper()
    {
        try {
            $received = Skaya_Model_Mapper_MapperBroker::getExistingMapper('testMapper');
            $this->fail('Retrieving unregistered Mappers should throw an exception');
        } catch (Exception $e) {
            // success
        }
    }

    public function testLoadingMapperOnlyInitializesOnce()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http();
        $request->setModuleName('default')
            ->setControllerName('Model_Mapper_Mapper-broker')
            ->setActionName('index');
        $response = new Zend_Controller_Response_Cli();
        $this->front->setResponse($response);

        $mapper = new Skaya_Model_Mapper_MapperBroker_TestMapper();
        Skaya_Model_Mapper_MapperBroker::addMapper($mapper);

        $controller = new Skaya_Model_Mapper_MapperBrokerModel($request, $response, array());
        $controller->test();
        $received = $controller->getMapper('testMapper');
        $this->assertSame($mapper, $received);
        $this->assertEquals(1, $mapper->count);
    }

    public function testLoadingAndCheckingMappersStatically()
    {
        $mapper = new Model_Mapper_Mapper_Redirector();
        Skaya_Model_Mapper_MapperBroker::addMapper($mapper);

        $this->assertTrue(Skaya_Model_Mapper_MapperBroker::hasMapper('redirector'));
    }

    public function testLoadingAndRemovingMappersStatically()
    {
        $mapper = new Model_Mapper_Mapper_Redirector();
        Skaya_Model_Mapper_MapperBroker::addMapper($mapper);

        $this->assertTrue(Skaya_Model_Mapper_MapperBroker::hasMapper('redirector'));
        Skaya_Model_Mapper_MapperBroker::removeMapper('redirector');
        $this->assertFalse(Skaya_Model_Mapper_MapperBroker::hasMapper('redirector'));
    }

    public function testReturningMapper()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-get-redirector/');
        $this->front->setResponse(new Zend_Controller_Response_Cli());

        $this->front->returnResponse(true);
        $response = $this->front->dispatch($request);
        $this->assertEquals('Model_Mapper_Mapper_Redirector', $response->getBody());
    }

    public function testReturningMapperViaMagicGet()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-Mapper-via-magic-get/');
        $this->front->setResponse(new Zend_Controller_Response_Cli());

        $this->front->returnResponse(true);
        $response = $this->front->dispatch($request);
        $this->assertEquals('Model_Mapper_Mapper_Redirector', $response->getBody());
    }

    public function testReturningMapperViaMagicCall()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-Mapper-via-magic-call/');
        $this->front->setResponse(new Zend_Controller_Response_Cli());

        $this->front->returnResponse(true);

        require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files/Mappers/TestMapper.php';
        Skaya_Model_Mapper_MapperBroker::addMapper(new MyApp_TestMapper());

        $response = $this->front->dispatch($request);
        $this->assertEquals('running direct call', $response->getBody());
    }

    public function testNonExistentMapper()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-bad-Mapper/');
        $this->front->setResponse(new Zend_Controller_Response_Cli());

        $this->front->returnResponse(true);
        $response = $this->front->dispatch($request);
        $this->assertContains('not found', $response->getBody());
    }

    public function testCustomMapperRegistered()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-custom-Mapper/');
        $this->front->setResponse(new Zend_Controller_Response_Cli());

        $this->front->returnResponse(true);

        require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files/Mappers/TestMapper.php';
        Skaya_Model_Mapper_MapperBroker::addMapper(new MyApp_TestMapper());

        $response = $this->front->dispatch($request);
        $this->assertEquals('MyApp_TestMapper', $response->getBody());
    }

    public function testCustomMapperFromPath()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files');
        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-custom-Mapper/');
        $this->front->setResponse(new Zend_Controller_Response_Cli());

        $this->front->returnResponse(true);

        Skaya_Model_Mapper_MapperBroker::addPath(
            dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'Mappers',
            'MyApp'
        );

        $response = $this->front->dispatch($request);
        $this->assertEquals('MyApp_TestMapper', $response->getBody());
    }

    public function testGetExistingMappers()
    {
        Skaya_Model_Mapper_MapperBroker::addMapper(new Model_Mapper_Mapper_Redirector());
        Skaya_Model_Mapper_MapperBroker::addMapper(new Model_Mapper_Mapper_ViewRenderer()); // @todo in future this should throw an exception

        $mappers = Skaya_Model_Mapper_MapperBroker::getExistingMappers();
        $this->assertTrue(is_array($mappers));
        $this->assertEquals(2, count($mappers));
        $this->assertContains('ViewRenderer', array_keys($mappers));
        $this->assertContains('Redirector', array_keys($mappers));
    }

    public function testGetMapperStatically()
    {
        $mapper = Skaya_Model_Mapper_MapperBroker::getStaticMapper('viewRenderer');
        $this->assertTrue($mapper instanceof Model_Mapper_Mapper_ViewRenderer);

        $mappers = Skaya_Model_Mapper_MapperBroker::getExistingMappers();
        $this->assertTrue(is_array($mappers));
        $this->assertEquals(1, count($mappers));
    }

    public function testMapperPullsResponseFromRegisteredActionController()
    {
        $mapper = Skaya_Model_Mapper_MapperBroker::getStaticMapper('viewRenderer');

        $aRequest = new Zend_Controller_Request_Http();
        $aRequest->setModuleName('default')
            ->setControllerName('Model_Mapper_Mapper-broker')
            ->setActionName('index');
        $aResponse = new Zend_Controller_Response_Cli();
        $controller = new Skaya_Model_Mapper_MapperBrokerModel($aRequest, $aResponse, array());

        $fRequest = new Zend_Controller_Request_Http();
        $fRequest->setModuleName('foo')
            ->setControllerName('foo-bar')
            ->setActionName('baz');
        $fResponse = new Zend_Controller_Response_Cli();
        $this->front->setRequest($fRequest)
            ->setResponse($fResponse);

        $mapper->setActionController($controller);

        $hRequest = $mapper->getRequest();
        $this->assertSame($hRequest, $aRequest);
        $this->assertNotSame($hRequest, $fRequest);
        $hResponse = $mapper->getResponse();
        $this->assertSame($hResponse, $aResponse);
        $this->assertNotSame($hResponse, $fResponse);
    }

    public function testMapperPullsResponseFromFrontControllerWithNoRegisteredActionController()
    {
        $mapper = Skaya_Model_Mapper_MapperBroker::getStaticMapper('viewRenderer');
        $this->assertNull($mapper->getActionController());

        $aRequest = new Zend_Controller_Request_Http();
        $aRequest->setModuleName('default')
            ->setControllerName('Model_Mapper_Mapper-broker')
            ->setActionName('index');
        $aResponse = new Zend_Controller_Response_Cli();

        $fRequest = new Zend_Controller_Request_Http();
        $fRequest->setModuleName('foo')
            ->setControllerName('foo-bar')
            ->setActionName('baz');
        $fResponse = new Zend_Controller_Response_Cli();
        $this->front->setRequest($fRequest)
            ->setResponse($fResponse);

        $hRequest = $mapper->getRequest();
        $this->assertNotSame($hRequest, $aRequest);
        $this->assertSame($hRequest, $fRequest);
        $hResponse = $mapper->getResponse();
        $this->assertNotSame($hResponse, $aResponse);
        $this->assertSame($hResponse, $fResponse);
    }

    public function testMapperPathStackIsLifo()
    {
        Skaya_Model_Mapper_MapperBroker::addPath(
            dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'Mappers',
            'MyApp'
        );

        $urlMapper = Skaya_Model_Mapper_MapperBroker::getStaticMapper('url');
        $this->assertTrue($urlMapper instanceof MyApp_Url);
    }

    /**
     * @group ZF-4704
     */
    public function testPluginLoaderShouldHaveDefaultPrefixPath()
    {
        $loader = Skaya_Model_Mapper_MapperBroker::getPluginLoader();
        $paths = $loader->getPaths('Model_Mapper_Mapper');
        $this->assertFalse(empty($paths));
    }

    public function testCanLoadNamespacedMapper()
    {
        $this->front->setControllerDirectory(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files')
            ->setResponse(new Zend_Controller_Response_Cli())
            ->returnResponse(true);

        $path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '_files/Mappers';
        Skaya_Model_Mapper_MapperBroker::addPath($path, 'MyApp\Controller\Action\Mapper\\');

        $request = new Zend_Controller_Request_Http('http://framework.zend.com/Mapper-broker/test-can-load-namespaced-Mapper/');
        $response = $this->front->dispatch($request);
        $this->assertEquals('MyApp\Controller\Action\Mapper\NamespacedMapper', $response->getBody());
    }

    /**
     * @group ZF-4704
     */
    public function testBrokerShouldAcceptCustomPluginLoaderInstance()
    {
        $loader = Skaya_Model_Mapper_MapperBroker::getPluginLoader();
        $custom = new Zend_Loader_PluginLoader();
        Skaya_Model_Mapper_MapperBroker::setPluginLoader($custom);
        $test = Skaya_Model_Mapper_MapperBroker::getPluginLoader();
        $this->assertNotSame($loader, $test);
        $this->assertSame($custom, $test);
    }
}

class Skaya_Model_Mapper_MapperBroker_TestMapper extends Skaya_Model_Mapper_Abstract
{
    public $count = 0;

    public function init()
    {
        ++$this->count;
    }

    public function save($data)
    {

    }

    public function delete($data)
    {

    }

    public function search($conditions, $order = null, $count = null, $offset = null)
    {

    }
}

class Skaya_Model_Mapper_MapperBrokerModel extends Skaya_Model_Abstract
{
    public $mapper;

    public function init()
    {
        $this->mapper = $this->_mapper->getMapper('testMapper');
    }

    public function test()
    {
        $this->_mapper->getMapper('testMapper');
    }
}

// Call Skaya_Model_Mapper_MapperBrokerTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Skaya_Model_Mapper_MapperBrokerTest::main") {
    Skaya_Model_Mapper_MapperBrokerTest::main();
}
