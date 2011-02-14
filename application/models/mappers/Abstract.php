<?php

abstract class Model_Mapper_Abstract implements Model_Mapper_Interface {
	
	protected $_fieldMapping = array();
	
	protected $_reverseFieldMapping = array();
	
	/**
	* Makes model mapper using provider type and model name as parameters
	* 
	* @param mixed $provider
	* @param mixed $modelName
	* @param mixed $options
	* @return Model_Mapper_Abstract
	* @throws Model_Mapper_Exception
	*/
	public static function factory($provider, $modelName, $options = array()) {
		$className = ucfirst($provider).'_'.ucfirst($modelName);
		if (!class_exists($className, true)) {
			throw new Exception('Mapper class not found');
		}
		$instance = new $className($options);
		return $instance;
	}
	
	public function __construct($options = array()) {
		$this->setOptions($options);
		if (!empty($this->_fieldMapping) && empty($this->_reverseFieldMapping)) {
			$this->_reverseFieldMapping = array_flip($this->_fieldMapping);
		}
	}
    
    public function init() {
        
    }

    /**
	 * Set mapper state from options array
	 * @param  array $options
	 * @return Model_Mapper_Abstract
	 */
	public function setOptions($options) {
		foreach ($options as $key => $value) {
			$method = 'set' . $key;
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function map($data = array()) {
		$mappedData = array();
		foreach ((array)$data as $key => $value) {
			$mappedName = (array_key_exists($key, $this->_reverseFieldMapping))?$this->_reverseFieldMapping[$key]:$key;
			$mappedData[$mappedName] = $value;
		}
		return $mappedData;
	}
	
	public function unmap($data = array()) {
		$mappedData = array();
		foreach ((array)$data as $key => $value) {
			$mappedName = (array_key_exists($key, $this->_fieldMapping))?$this->_fieldMapping[$key]:$key;
			$mappedData[$mappedName] = $value;
		}
		return $mappedData;
	}
	
	public function getRawArrayFromData($data) {
		if (is_array($data)) {
			return $data;
		}
		
		if (is_object($data)) {
			return (array)$data;
		}
		
		return array();
	}
	
	public function getMappedArrayFromData($data) {
		$data = $this->getRawArrayFromData($data);
		if (gettype(current($data)) != 'array' && gettype(current($data)) != 'object') {
			return $this->map($data);
		}
		$newData = array();
		foreach ((array)$data as $row) {
			if (is_object($row)) {
				$row = $this->getRawArrayFromData($row);
			}
			$newData[] = $this->map($row);
		}
		return $newData;
	}
    
    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        $fullClassName = get_class($this);
        if (strpos($fullClassName, '_') !== false) {
            $mapperName = strrchr($fullClassName, '_');
            return ltrim($mapperName, '_');
        } elseif (strpos($fullClassName, '\\') !== false) {
            $mapperName = strrchr($fullClassName, '\\');
            return ltrim($mapperName, '\\');
        } else {
            return $fullClassName;
        }
    }

}
?>
