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

		if ($order) {
			$order = $this->_mapOrderStatement($order);
		}
		
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

	public function getCollectionCategories($collection_id, $order = null, $count = null, $offset = null) {
		$categoriesTable = self::_getTableByName(self::TABLE_NAME);
		$modelsTable = self::_getTableByName(Model_Mapper_Db_Model::TABLE_NAME);

		$select = $categoriesTable->select()->setIntegrityCheck(false)
			->from(array('c' => $categoriesTable->info(Zend_Db_Table_Abstract::NAME)))
			->joinInner(array('m' => $modelsTable->info(Zend_Db_Table_Abstract::NAME)), 'm.category_id = c.id', array())
			->where('m.collection_id = ?', (int)$collection_id)
			->group('c.id');

		if ($order) {
			$order = $this->_mapOrderStatement($order);
		}

		$categoryBlob = $categoriesTable->fetchAll($select, $order, $count, $offset);
		return $this->getMappedArrayFromData($categoryBlob);
	}

}