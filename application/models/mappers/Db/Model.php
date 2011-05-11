<?php

class Model_Mapper_Db_Model extends Skaya_Model_Mapper_Db_Abstract {

	const TABLE_NAME = 'Models';

	protected $_mapperTableName = self::TABLE_NAME;

	protected $_fieldMapping = array(
		'mainPhotoId' => 'main_photo_id'
	);

	public function getModelById($id) {
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$modelBlob = $modelTable->fetchRowById($id);
		return $this->getMappedArrayFromData($modelBlob);
	}

	public function getModels($order = null, $count = null, $offset = null) {
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$modelBlob = $modelTable->fetchAll(null, $order, $count, $offset);
		return $this->getMappedArrayFromData($modelBlob);
	}

	public function getModelsPaginator($order = null) {
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$select = $modelTable->select();
		if ($order) {
			$select->order($this->_mapOrderStatement($order));
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}

	public function getCollectionModels($collection_id, $order = null, $count = null, $offset = null) {
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$modelBlob = $modelTable->fetchAllByCollectionId($collection_id, $order, $count, $offset);
		return $this->getMappedArrayFromData($modelBlob);
	}

	public function getCollectionModelsPaginator($collection_id, $order = null) {
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$select = $modelTable->select()->where('collection_id = ?', $collection_id);
		if ($order) {
			$select->order($this->_mapOrderStatement($order));
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}

}