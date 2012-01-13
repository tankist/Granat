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

class \Entities\UserTest extends ControllerTestCase
{

    protected $_email = 'email@email.com';

    protected $_password = '123';

    protected $_user = null;

    public function setUp()
    {
        parent::setUp();
        $this->_resetUserModel();
    }

    public function testCanCreateUserModel()
    {
        $user = new \Entities\User();
        $this->assertType('\Entities\User', $user);
    }

    public function testCanGetSetRole()
    {
        //Getting default role without getter
        $this->assertEquals(\Entities\User::USER_ROLE_GUEST, $this->_user->role);

        //Setting new Role and getting it with getter
        $this->_user->role = \Entities\User::USER_ROLE_ADMIN;
        $this->assertEquals(\Entities\User::USER_ROLE_ADMIN, $this->_user->getRole());
    }

    protected function _resetUserModel()
    {
        if (!$this->_user) {
            $this->_user = new \Entities\User();
        }
        $this->_user->populate(array(
            'email' => $this->_email,
            'password' => $this->_password
        ));
    }

}

?>
