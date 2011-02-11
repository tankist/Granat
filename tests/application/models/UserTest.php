<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserTest
 *
 * @author victor
 */

namespace Model;

class UserTest extends \ControllerTestCase {
	
	protected $_email = 'email@email.com';
	
	protected $_password = '123';
	
	protected $_user = null;
	
	public function setUp() {
		parent::setUp();
		$this->_resetUserModel();
	}

	public function testCanCreateUserModel() {
		$user = new \Model\User();
		$this->assertType('\\Model\\User', $user);
	}
	
	public function testCanPopulate() {
		$newEmail = 'newemail@email.com';
		$newPassword = 'test123';
		
		//Populate from array
		$this->_user->populate(array(
			'email' => $newEmail,
			'password' => $newPassword
		));
		$this->assertEquals($newEmail, $this->_user->email);
		$this->assertEquals($newPassword, $this->_user->password);
		
		$this->_resetUserModel();
		
		//Populate from object
		$newUserDefinition = new \stdClass();
		$newUserDefinition->email = $newEmail;
		$newUserDefinition->password = $newPassword;
		$this->_user->populate($newUserDefinition);
		$this->assertEquals($newEmail, $this->_user->email);
		$this->assertEquals($newPassword, $this->_user->password);
		
		$this->_resetUserModel();
		
		//Popualte with Model
		$anotherUser = new \Model\User();
		$anotherUser->populate($this->_user);
		$this->assertEquals($this->_email, $anotherUser->email);
		$this->assertEquals($this->_password, $anotherUser->password);
		
		$this->_resetUserModel();
	}
	
	public function testCanSetGetValues() {
		$testValue = 'testValue';
		$this->_user->someTestValue = $testValue;
		$this->assertTrue(isset ($this->_user->someTestValue));
		$this->assertEquals($testValue, $this->_user->someTestValue);
	}
	
	public function testCanGetSetRole() {
		//Getting default role without getter
		$this->assertEquals(\Model\User::USER_ROLE_GUEST, $this->_user->role);

		//Setting new Role and getting it with getter
		$this->_user->role = \Model\User::USER_ROLE_ADMIN;
		$this->assertEquals(\Model\User::USER_ROLE_ADMIN, $this->_user->getRole());
	}

	public function testIssetField() {
		$this->assertTrue(isset($this->_user->email));
		$this->assertFalse(isset($this->_user->junkField));
	}
	
	public function testUnsetField() {
		unset($this->_user->email);
		$this->assertFalse(isset ($this->_user->email));
	}
	
	public function testToArray() {
		//Simple test
		$array = $this->_user->toArray();
		$this->assertType('array', $array);
		$this->assertArrayHasKey('email', $array);
		$this->assertArrayHasKey('password', $array);
		$this->assertEquals($this->_email, $array['email']);
		$this->assertEquals($this->_password, $array['password']);
		
		//Test with nested objects
		$testValue = 'testValue';
		
		$this->_user->nestedSimpleObject = new \stdClass();
		$this->_user->nestedSimpleObject->testValue = $testValue;
		$this->_user->nestedModel = new \Model\User(array(
			'testValue' => $testValue
		));
		$array = $this->_user->toArray();
		$this->assertArrayHasKey('nestedSimpleObject', $array);
		$this->assertArrayHasKey('nestedModel', $array);
		$this->assertEquals($testValue, $array['nestedSimpleObject']['testValue']);
		$this->assertEquals($testValue, $array['nestedModel']['testValue']);
	}
	
	public function testGetSetMapperType() {
		$testMapperType = 'db';
		//Resetting current user mapper type
		$this->_user->setMapperType(null);

		//Test static default types
		\Model\User::setDefaultMapperType($testMapperType);
		$this->assertEquals($testMapperType, \Model\User::getDefaultMapperType());

		//Testing getter without setting
		$this->assertEquals(\Model\User::getDefaultMapperType(), $this->_user->getMapperType());

		//Testing current model mapper types getter/setter
		$this->_user->setMapperType($testMapperType);
		$this->assertEquals($testMapperType, $this->_user->getMapperType());
	}

	/**
	 * @expectedException \Model\Exception
	 */
	public function testUserExceptions() {
		//Testing with wrong parameters sent to constructor
		$failedUser = new \Model\User(1);
	}
	
	/**
	 * @expectedException \Model\Exception
	 */
	public function testUserSetterExceptions() {
		//Testing empty set parameter
		$parameter = '';
		$this->_user->$parameter = 123;
	}

	protected function _resetUserModel() {
		if (!$this->_user) {
			$this->_user = new \Model\User();
		}
		$this->_user->populate(array(
			'email' => $this->_email,
			'password' => $this->_password
		));
	}
	
}
?>
