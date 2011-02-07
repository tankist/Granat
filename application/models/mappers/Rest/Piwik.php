<?php
class Model_Mapper_Rest_Piwik extends Model_Mapper_Abstract {
	
	protected static $auth_token = '';
	
	protected $_auth_token = '';
	
	public static function setDefaultAuthToken($auth_token) {
		self::$auth_token = $auth_token;
	}
	
	public static function getDefaultAuthToken() {
		return self::$auth_token;
	}
	
	public static function setAuthToken($auth_token) {
		$this->_auth_token = $auth_token;
	}
	
	public static function getAuthToken() {
		$auth_token = $this->getAuthToken();
		if (empty($auth_token)) {
			$auth_token = self::getDefaultAuthToken();
			$this->setAuthToken($auth_token);
		}
		return $auth_token;
	}
	
}
?>
