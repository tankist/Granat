<?php

abstract class Service_Abstract {
	
	protected static $_instances = array();
	
	protected static $_defaultMapperType = '';
	
	protected $_mapperType = '';
	
	protected function __construct() {}
	
	protected function __clone() {}
	
	public static function factory($serviceName) {
		if (!array_key_exists($serviceName, self::$_instances)) {
			$className = "Service_$serviceName";
			self::$_instances[$serviceName] = new $className();
		}
		return self::$_instances[$serviceName];
	}
	
	public static function setDefaultMapperType($mapperType) {
		self::$_defaultMapperType = $mapperType;
	}
	
	public static function getDefaultMapperType() {
		return self::$_defaultMapperType;
	}
	
	public function setMapperType($mapperType) {
		$this->_mapperType = $mapperType;
		return $this;
	}
	
	public function getMapperType() {
		if (empty($this->_mapperType)) {
			$this->_mapperType = self::getDefaultMapperType();
		}
		return $this->_mapperType;
	}
	
	public static function getStaticMapper($mapperType = null, $mapperName = null) {
		if (empty($mapperName)) {
			$mapperName = self::_getClassName();
		}
		if (empty($mapperType)) {
			$mapperType = self::getDefaultMapperType();
		}
		$mapper = Model_Mapper_Abstract::factory($mapperType, $mapperName);
		return $mapper;
	}
	
	public function getMapper($mapperType = null, $mapperName = null) {
		if (empty($mapperType)) {
			$mapperType = $this->getMapperType();
		}
		return self::getStaticMapper($mapperType, $mapperName);
	}
	
	public static function create($data = array()) {
		$modelName = 'Model_' . self::_getClassName();
		$modelInstance = new $modelName($data);
		return $modelInstance;
	}
	
	protected static function _getClassName() {
		$className = '';
		$_name = get_called_class();
		$lastNsSeparatorPos = strrpos($_name, Skaya_Loader_Autoloader_Resource::NS_SEPARATOR);
		if ($lastNsSeparatorPos !== false) {
			$className = substr($_name, $lastNsSeparatorPos + 1);
		}
		return $className;
	}
	
}
?>
