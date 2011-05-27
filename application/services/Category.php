<?php

class Service_Category extends Skaya_Model_Service_Abstract {

    /**
     * @var Model_Mapper_Db_Category
     */
    protected $_mapper;

    protected function __construct() {
        parent::__construct();
        $this->_mapper = new Model_Mapper_Decorator_Cache_Category($this->_mappers->category);
    }

	public static function create($data = array()) {
		if (array_key_exists('id', $data)) {
			unset($data['id']);
		}
		return new Model_Category($data);
	}

	public function getCategoryById($id) {
		$categoryData = $this->_mapper->getCategoryById($id);
		return new Model_Category($categoryData);
	}

	public function getCategories($order = null, $count = null, $offset = null) {
		$categoriesBlob = $this->_mapper->getCategories($order, $count, $offset);
		return new Model_Collection_Categories($categoriesBlob);
	}

	public function getCategoriesPaginator($order = null) {
		$paginator = $this->_mapper->getCategoriesPaginator($order);
		$paginator->addFilter(new Skaya_Filter_Array_Collection('Model_Collection_Categories'));
		return $paginator;
	}

}