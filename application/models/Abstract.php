<?php
abstract class Model_Abstract implements Model_Interface {
	
	const MAPPER_DATABASE = 'db';
	const MAPPER_SESSION = 'session';
	const MAPPER_COUCHDB = 'couchdb'; 
	
	protected $_data = array();
	
	protected $_modelName = '';
	
	protected $_mapperType = '';
	
	protected $_mappers = array();
	
	protected static $_defaultMapperType = ''; 
	
	public function __construct($data = array()) {
		if (!empty($data)) $this->populate($data);
	}
	
	public function populate($data = array()) {
		if (is_object($data)) {
			if (method_exists($data, 'toArray')) {
				$data = $data->toArray();
			}
			else {
				$data = (array)$data;
			}
		}
		
		if (!is_array($data)) {
			throw new Model_Exception('Data must be array or object');
		}
		
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
		
		return $this;
	}
	
	public function __set($name, $value) {
		if (!is_string($name) || empty($name)) {
			throw new Model_Exception('Name cannot be empty');
		}
		
		$camelcaseFilter = new Zend_Filter_Word_UnderscoreToCamelCase();
		
		$setterName = 'set' . ucfirst($camelcaseFilter->filter($name));
		if (method_exists($this, $setterName)) {
			call_user_func(array($this, $setterName), $value);
		}
		else {
			$this->_data[$name] = $value;
		}
	}
	
	public function __get($name) {
		$camelcaseFilter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$getterName = 'get' . ucfirst($camelcaseFilter->filter($name));
		
		$data = null;
		if (array_key_exists($name, $this->_data)) {
			$data = $this->_data[$name];
		}
		elseif (method_exists($this, $getterName)) {
			$data = call_user_func(array($this, $getterName));
		}
		return $data;
	} 
	
	public function __isset($name) {
		return array_key_exists($name, $this->_data);
	}
	
	public function __unset($name) {
		if (array_key_exists($name, $this->_data)) {
			unset($this->_data[$name]);
		}
	}
	
	public function toArray() {
		$data = (array)$this->_data;
		foreach ($data as $key => &$value) {
			if (is_object($value)) {
				if (method_exists($value, 'toArray')) {
					$value = $value->toArray();
				}
				else {
					$value = (array)$value;
				}
			}
		}
		return $data;
	}
	
	public function save() {
		$data = $this->getMapper()->save($this->toArray());
		return $this->populate($data);
	}
	
	public function delete() {
		$this->getMapper()->delete($this->toArray());
		return $this;
	}
	
	/**
	* Create (if necessary) and return mapper instance for the given model and mapper name
	* 
	* @param string $mapperType
	* @param string $modelName
	* @param array $options
	* @return Model_Mapper_Abstract
	*/
	public function getMapper($mapperType = '', $modelName = '', $options = array()) {
		$modelName = (!empty($modelName))?$modelName:$this->_modelName;
		$mapperType = (!empty($mapperType))?$mapperType:$this->_mapperType;
		$mapperHash = strtolower($modelName) . '_' . strtolower($mapperType);
		if (!array_key_exists($mapperHash, $this->_mappers)) {
			$this->_mappers[$mapperHash] = Model_Mapper_Abstract::factory($mapperType, $modelName, $options);
		}
		return $this->_mappers[$mapperHash];
	}
	
	/**
	* @desc return mapper type
	*/
	public function getMapperType() {
		if (empty($this->_mapperType)) {
			$this->_mapperType = self::getDefaultMapperType();
		}
		return $this->_mapperType;
	}
	
	/**
	* @desc set mapper type
	*/
	public function setMapperType($mapperType) {
		$this->_mapperType = $mapperType;
		return $this;
	}
	
	public static function setDefaultMapperType($mapperType) {
		self::$_defaultMapperType = $mapperType;
	}
	
	public static function getDefaultMapperType() {
		return self::$_defaultMapperType;
	}
}
?>
