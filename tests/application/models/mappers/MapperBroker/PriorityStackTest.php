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

// Call Model_Mapper_HelperBrokerTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Model_Mapper_MapperBrokerTest::main");
}

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Controller
 * @group      Model_Mapper
 * @group      Model_Mapper_Helper
 */
class Model_Mapper_MapperBroker_PriorityStackTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Model_Mapper_HelperBroker_PriorityStack
     */
    public $stack = null;

    public function setUp()
    {
        $this->stack = new Model_Mapper_MapperBroker_PriorityStack();
    }

    public function testStackMaintainsLifo()
    {
        $this->stack->push(new Model_Mapper_Db_User());
        $this->stack->push(new Model_Mapper_Db_Photo());
        $this->assertEquals(2, count($this->stack));
        $iterator = $this->stack->getIterator();
        $this->assertEquals('Model_Mapper_Db_Photo', get_class(current($iterator)));
        next($iterator);
        $this->assertEquals('Model_Mapper_Db_User', get_class(current($iterator)));
    }

    public function testStackPrioritiesWithDefaults()
    {
        $this->stack->push(new Model_Mapper_Db_User());
        $this->stack->push(new Model_Mapper_Db_Photo());
        $this->assertEquals(3, $this->stack->getNextFreeHigherPriority());
        $this->assertEquals(0, $this->stack->getNextFreeLowerPriority());
        $this->assertEquals(2, $this->stack->getHighestPriority());
        $this->assertEquals(1, $this->stack->getLowestPriority());
    }


    public function testStackMaintainsReturnsCorrectNextPriorityWithSetPriorities()
    {
        $this->stack->offsetSet(10, new Model_Mapper_Db_User());
        $this->stack->offsetSet(11, new Model_Mapper_Db_Photo());
        $this->assertEquals(12, $this->stack->getNextFreeHigherPriority(10));
        $this->assertEquals(9, $this->stack->getNextFreeLowerPriority(10));
        $this->assertEquals(11, $this->stack->getHighestPriority());
        $this->assertEquals(10, $this->stack->getLowestPriority());
    }

    public function testStackMaintainsReturnsCorrectNextPriorityWithSetPrioritiesSplit()
    {
        $this->stack->offsetSet(10, new Model_Mapper_Db_User());
        $this->stack->offsetSet(20, new Model_Mapper_Db_Photo());
        $this->assertEquals(11, $this->stack->getNextFreeHigherPriority(10));
        $this->assertEquals(9, $this->stack->getNextFreeLowerPriority(10));

        $this->assertEquals(11, $this->stack->getNextFreeHigherPriority(11));
        $this->assertEquals(11, $this->stack->getNextFreeLowerPriority(11));

        $this->assertEquals(21, $this->stack->getNextFreeHigherPriority(20));
        $this->assertEquals(19, $this->stack->getNextFreeLowerPriority(20));

        $this->assertEquals(20, $this->stack->getHighestPriority());
        $this->assertEquals(10, $this->stack->getLowestPriority());
    }

    public function testStackAccessors()
    {
        $this->stack->push(new Model_Mapper_Db_User());
        $this->stack->push(new Model_Mapper_Db_Photo());
        unset($this->stack->Db_User);
        $this->assertEquals(1, count($this->stack));
        $this->assertEquals('Model_Mapper_Db_Photo', get_class(current($this->stack->getIterator())));
        $this->assertEquals('Model_Mapper_Db_Photo', get_class($this->stack->Db_Photo));
        $this->assertEquals('Model_Mapper_Db_Photo', get_class($this->stack->offsetGet('Db_Photo')));
        $this->assertEquals('Model_Mapper_Db_Photo', get_class($this->stack->offsetGet(2)));
    }

}