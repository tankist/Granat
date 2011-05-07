<?php

class Model_Mapper_Db_Category extends Skaya_Model_Mapper_Db_Abstract {

	const TABLE_NAME = 'Categories';

	protected $_mapperTableName = self::TABLE_NAME;

	public function getCategoryById($category_id) {
		$categoryTable = self::_getTableByName(self::TABLE_NAME);
		$categoryBlob = $categoryTable->fetchRowById($category_id);
		return $this->getMappedArrayFromData($categoryBlob);
	}

	public function getCategories($order = null, $count = null, $offset = null) {
		$categoryTable = self::_getTableByName(self::TABLE_NAME);
		$categoryBlob = $categoryTable->fetchAll(null, $order, $count, $offset);
		return $this->getMappedArrayFromData($categoryBlob);
	}

	public function getCategoriesPaginator($order = null) {
		$categoryTable = self::_getTableByName(self::TABLE_NAME);
		$select = $categoryTable->select();
		if ($order) {
			$select->order($this->_mapOrderStatement($order));
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array(
				$this, 'getMappedArrayFromData'
			)
		)));
		return $paginator;
	}

}