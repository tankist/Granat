<?php
class Skaya_Validate_UsernameNotExists extends Zend_Validate_Abstract {
	
	const EXISTENT = 'Existent';
	const STRING_EMPTY = 'storeStringEmpty';
	const INVALID    = 'notString';
	
	protected $_messageTemplates = array(
		self::INVALID      => "Invalid type given, value should be string",
		self::EXISTENT      => "Username '%value%' exists or suspended",
		self::STRING_EMPTY => "'%value%' is an empty string",
	);
	
	protected $_id = null;
	
	public function __construct($id = null) {
		$this->setId($id);
	}
	
	/**
	* Returns the id.
	*
	* @return integer
	*/
	public function getId() {
		return (int)$this->_id;
	}

	/**
	* Sets the id.
	*
	* @param  string $id
	* @return Skaya_Validate_StoreNotExists Provides a fluent interface
	*/
	public function setId($id) {
		$this->_id = (int)$id;
		return $this;
	}
	
	public function isValid($value) {
		if (!is_string($value)) {
			$this->_error(self::INVALID);
			return false;
		}
		
		if (empty($value)) {
			$this->_error(self::STRING_EMPTY);
			return false;
		}
		
		$id = $this->getId();
		
		$user = Skaya_Model_Service_Abstract::factory('User')->getUserByUsername($value);
		if ( $user && ( $user->id != $id ) ) {
			$this->_error(self::EXISTENT,$value);
			return false;
		}
		
		return true;
	}
}
?>
