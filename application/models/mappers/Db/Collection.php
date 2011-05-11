<?php

class Model_Mapper_Db_Collection extends Skaya_Model_Mapper_Db_Abstract {

	const TABLE_NAME = 'Collections';

	protected $_mapperTableName = self::TABLE_NAME;

	protected $_fieldMapping = array(
		'mainModelId' => 'main_model_id'
	);

	public function unmap($data) {
		$data = parent::unmap($data);
		if (empty($data['main_model_id'])) {
			unset($data['main_model_id']);
		}
		return $data;
	}

	public function getCollectionById($id) {
		$collectionTable = self::_getTableByName(self::TABLE_NAME);
		$collectionBlob = $collectionTable->fetchRowById($id);
		return $this->getMappedArrayFromData($collectionBlob);
	}

	public function getCollections($order = null, $count = null, $offset = null) {
		$collectionTable = self::_getTableByName(self::TABLE_NAME);
		$collectionBlob = $collectionTable->fetchAll(null, $order, $count, $offset);
		return $this->getMappedArrayFromData($collectionBlob);
	}

	public function getCollectionsPaginator($order = null) {
		$collectionTable = self::_getTableByName(self::TABLE_NAME);
		$select = $collectionTable->select();
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