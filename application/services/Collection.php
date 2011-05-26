<?php

class Service_Collection extends Skaya_Model_Service_Abstract {

    /**
     * @var Model_Mapper_Db_Collection
     */
    protected $_mapper;

    protected function __construct() {
        parent::__construct();
        $this->_mapper = new Model_Mapper_Decorator_Cache_Collection($this->_mappers->collection);
    }

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Collection($data);
	}

	public function getCollectionById($id) {
		$collectionData = $this->_mapper->getCollectionById($id);
		return new Model_Collection($collectionData);
	}

	public function getCollections($order = null, $count = null, $offset = null) {
		$collectionsBlob = $this->_mapper->getCollections($order, $count, $offset);
		return new Model_Collection_Collections($collectionsBlob);
	}

	public function getCollectionsPaginator($order = null) {
		$paginator = $this->_mapper->getCollectionsPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Collections'));
		return $paginator;
	}

	public function getNonEmptyCollectionsPaginator($order = null) {
		$paginator = $this->_mapper->getNonEmptyCollectionsPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Collections'));
		return $paginator;
	}

}