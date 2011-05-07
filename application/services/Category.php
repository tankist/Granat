<?php

class Service_Category extends Skaya_Model_Service_Abstract {

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Category($data);
	}

	public function getCategoryById($id) {
		$categoryData = $this->_mappers->category->getCategoryById($id);
		return new Model_Category($categoryData);
	}

	public function getCategories($order = null, $count = null, $offset = null) {
		$categoriesBlob = $this->_mappers->category->getCategories($order, $count, $offset);
		return new Model_Collection_Categories($categoriesBlob);
	}

	public function getCategoriesPaginator($order = null) {
		$paginator = $this->_mappers->category->getCategoriesPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Categories'));
		return $paginator;
	}

}