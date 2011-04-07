<?php
class Skaya_Filter_Array_Map implements Zend_Filter_Interface {

	protected $_callback;

	protected $_keyKey;

	protected $_isDeleteKeyFromResult = true;

	protected $_valueKey;

	public function __construct($valueKey, $keyKey = null, $isDeleteKeyFromResult = true) {
		$this->setIsDeleteKeyFromResult($isDeleteKeyFromResult);
		if (is_callable($valueKey)) {
			$this->setCallback($valueKey);
		}
		else {
			$this->setValueKey($valueKey);
			$this->setKeyKey($keyKey);
			$this->setCallback(array($this, '_filterMapArray'));
		}
	}

	public function filter($value) {
		if (!is_callable($this->getCallback())) {
			throw new Zend_Filter_Exception('Cannot find callback to map array with');
		}
		if (!is_array($value)) {
			throw new Zend_Filter_Exception('Value should be an array');
		}
		$data = array();
		array_walk($value, array($this, '_walker'), &$data);
		return $data;
	}

	public function setCallback($callback) {
		$this->_callback = $callback;
	}

	public function getCallback() {
		return $this->_callback;
	}

	public function setIsDeleteKeyFromResult($isDeleteKeyFromResult) {
		$this->_isDeleteKeyFromResult = $isDeleteKeyFromResult;
	}

	public function getIsDeleteKeyFromResult() {
		return $this->_isDeleteKeyFromResult;
	}

	public function setKeyKey($keyKey) {
		$this->_keyKey = $keyKey;
	}

	public function getKeyKey() {
		return $this->_keyKey;
	}

	public function setValueKey($valueKey) {
		$this->_valueKey = $valueKey;
	}

	public function getValueKey() {
		return $this->_valueKey;
	}

	protected function _filterMapArray($element, $index) {
		$key = $index;
		$keyKey = $this->getKeyKey();
		if (array_key_exists($keyKey, $element)) {
			$key = $element[$keyKey];
			if ($this->getIsDeleteKeyFromResult()) {
				unset($element[$keyKey]);
			}
		}
		$valueKey = $this->getValueKey();
		if (array_key_exists($valueKey, $element)) {
			$element = $element[$valueKey];
		}
		return array($key, $element);
	}

	protected function _walker($element, $index, &$out) {
		list($key, $value) = call_user_func($this->getCallback(), $element, $index);
		$out[$key] = $value;
		return true;
	}

}
