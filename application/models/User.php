<?php
/**
* @property int id
* @property string $email
* @property string $firstName
* @property string $lastName
* @property string $password
* @property string $role
* @property string $dateAdded
* @property string $status
*/

class Model_User extends Skaya_Model_Abstract implements Zend_Auth_Adapter_Interface {

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
		/**
		 * @var Model_User $user
		 */
		$user = $this->getMapper()->getUserByEmail($this->email);
		if (is_array($user) &&
			!empty($user) &&
			!empty($this->email) && $user['email'] == $this->email &&
			$user['password'] == md5($this->password) &&
			$user['status'] == self::USER_STATE_ACTIVE
		) {
			if (empty($user['role'])) {
				$user['role'] = self::USER_ROLE_USER;
			}
			$this->populate($user);
			unset($this->password);
			$result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this);
		}
		else {
			$code = Zend_Auth_Result::FAILURE;
			if (!is_array($user) ||
				empty($user) ||
				$user['username'] != $this->email
			) {
				$code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
			}
			elseif ($user['password'] != $this->password) {
				$code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
			}
			$result = new Zend_Auth_Result($code, null, array('loginFailed' => 'Incorrect Email & Password'));
		}
		return $result;
	}


}