<?php

class Service_Model extends Skaya_Model_Service_Abstract {

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Model($data);
	}

	public function getModelById($id) {
		$modelData = $this->_mappers->model->getModelById($id);
		return new Model_Model($modelData);
	}

	public function getModels($order = null, $count = null, $offset = null) {
		$modelsBlob = $this->_mappers->model->getModels($order, $count, $offset);
		return new Model_Collection_Models($modelsBlob);
	}

	public function getModelsPaginator($order = null) {
		$paginator = $this->_mappers->model->getModelsPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Models'));
		return $paginator;
	}

}