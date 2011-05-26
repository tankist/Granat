<?php

class Service_Model extends Skaya_Model_Service_Abstract {

    /**
     * @var Model_Mapper_Db_Model
     */
    protected $_mapper;

    protected function __construct() {
        parent::__construct();
        $this->_mapper = new Model_Mapper_Decorator_Cache_Model($this->_mappers->model);
    }

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Model($data);
	}

	public function getModelById($id) {
		$modelData = $this->_mapper->getModelById($id);
		return new Model_Model($modelData);
	}

	public function getModels($order = null, $count = null, $offset = null) {
		$modelsBlob = $this->_mapper->getModels($order, $count, $offset);
		return new Model_Collection_Models($modelsBlob);
	}

	public function getModelsPaginator($order = null) {
		$paginator = $this->_mapper->getModelsPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Models'));
		return $paginator;
	}

	public function getRandomModels($count = null) {
		$modelsBlob = $this->_mapper->getRandomModels($count);
		return new Model_Collection_Models($modelsBlob);
	}

}