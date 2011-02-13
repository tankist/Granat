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

class Model_UserTest extends ControllerTestCase {
	
	protected $_email = 'email@email.com';
	
	protected $_password = '123';
	
	protected $_user = null;
	
	public function setUp() {
		parent::setUp();
		$this->_resetUserModel();
	}

	public function testCanCreateUserModel() {
		$user = new Model_User();
		$this->assertType('Model_User', $user);
	}
	
	public function testCanGetSetRole() {
		//Getting default role without getter
		$this->assertEquals(Model_User::USER_ROLE_GUEST, $this->_user->role);

		//Setting new Role and getting it with getter
		$this->_user->role = Model_User::USER_ROLE_ADMIN;
		$this->assertEquals(Model_User::USER_ROLE_ADMIN, $this->_user->getRole());
	}

	protected function _resetUserModel() {
		if (!$this->_user) {
			$this->_user = new Model_User();
		}
		$this->_user->populate(array(
			'email' => $this->_email,
			'password' => $this->_password
		));
	}
	
}
?>
