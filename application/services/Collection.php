<?php

class Service_Collection extends Skaya_Model_Service_Abstract {

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Collection($data);
	}

	public function getCollectionById($id) {
		$collectionData = $this->_mappers->collection->getCollectionById($id);
		return new Model_Collection($collectionData);
	}

	public function getCollections($order = null, $count = null, $offset = null) {
		$collectionsBlob = $this->_mappers->collection->getCollections($order, $count, $offset);
		return new Model_Collection_Collections($collectionsBlob);
	}

	public function getCollectionsPaginator($order = null) {
		$paginator = $this->_mappers->collection->getCollectionsPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Collections'));
		return $paginator;
	}

	public function getNonEmptyCollectionsPaginator($order = null) {
		$paginator = $this->_mappers->collection->getNonEmptyCollectionsPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Collections'));
		return $paginator;
	}

}