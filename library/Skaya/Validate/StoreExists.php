<?php
class Skaya_Validate_StoreExists extends Zend_Validate_Abstract {
	
	const NON_EXISTENT = 'nonExistent';
	const STRING_EMPTY = 'storeStringEmpty';
	const INVALID    = 'notString';
	
	protected $_messageTemplates = array(
		self::INVALID      => "Invalid type given, value should be string",
		self::NON_EXISTENT      => "Store '%value%' does not exists or suspended",
		self::STRING_EMPTY => "'%value%' is an empty string",
	);
	
	public function isValid($value) {
		if (!is_string($value)) {
			$this->_error(self::INVALID);
			return false;
		}
		
		if (empty($value)) {
			$this->_error(self::STRING_EMPTY);
			return false;
		}
		
		$store = Model_Sellcast::getStoreByDomain($value);
		if (empty($store) || !$store->getId()) {
			$this->_error(self::NON_EXISTENT);
			return false;
		}
		
		return true;
	}
}
?>
