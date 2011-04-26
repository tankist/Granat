<?php

class Model_User extends Skaya_Model_Abstract {

	const USER_STATE_ACTIVE = 'active';
	const USER_STATE_DEACTIVE = 'deactive';

	const USER_ROLE_GUEST = 'guest';
	const USER_ROLE_USER = 'user';
	const USER_ROLE_ADMIN = 'admin';

	protected $_modelName = 'User';

	public function getRole() {
		if (empty($this->_data['role'])) {
			$this->_data['role'] = self::USER_ROLE_GUEST;
		}
		return $this->_data['role'];
	}

	public function authenticate() {
		$user = $this->getMapper()->getUserByemail($this->email);
		if (is_array($user) && !empty($user) && $user['email'] == $this->email && $user['password'] == $this->password && $user['state'] == self::USER_STATE_ACTIVE) {
			$user['role'] = self::USER_ROLE_USER;
			$this->populate($user);
			unset($this->password);
			$result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this);
		}
		else {
			$code = Zend_Auth_Result::FAILURE;
			if (!is_array($user) || empty($user) || $user['email'] != $this->email) $code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
			if ($user['password'] != $this->password) $code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
			$result = new Zend_Auth_Result($code, null, array('loginFailed' => 'Incorrect User & Password'));
		}
		return $result;
	}

}