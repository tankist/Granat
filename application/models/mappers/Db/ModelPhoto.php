<?php

class Model_Mapper_Db_ModelPhoto extends Skaya_Model_Mapper_Db_Abstract {

	const TABLE_NAME = 'Photos';

	protected $_mapperTableName = self::TABLE_NAME;

    /**
     * @cachable
     * @cache_id model_photo_{$id}
     * @cache_tags model_photo item
     * @param  $id
     * @return array
     */
	public function getPhotoById($id) {
		$photoTable = self::_getTableByName(self::TABLE_NAME);
		$photoBlob = $photoTable->fetchRowById($id);
		return $this->getMappedArrayFromData($photoBlob);
	}

    /**
     * @cachable
     * @cache_id model_photo_{$id}
     * @cache_tags model_photo item
     * @param  $model_id
     * @param  $id
     * @return array
     */
	public function getModelPhotoById($model_id, $id) {
		$photoTable = self::_getTableByName(self::TABLE_NAME);
		$photoBlob = $photoTable->fetchRowByIdAndModelId($id, $model_id);
		return $this->getMappedArrayFromData($photoBlob);
	}

	public function getPhotos($order = null, $count = null, $offset = null) {
		$photoTable = self::_getTableByName(self::TABLE_NAME);
		$photoBlob = $photoTable->fetchAll(null, $order, $count, $offset);
		return $this->getMappedArrayFromData($photoBlob);
	}

	public function getPhotosPaginator($order = null) {
		$photoTable = self::_getTableByName(self::TABLE_NAME);
		$select = $photoTable->select();
		if ($order) {
			$select->order($this->_mapOrderStatement($order));
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}

    /**
     * @cachable
     * @cache_tags model_photos list
     * @param  $model_id
     * @param null $order
     * @param null $count
     * @param null $offset
     * @return array
     */
	public function getModelPhotos($model_id, $order = null, $count = null, $offset = null) {
		$photoTable = self::_getTableByName(self::TABLE_NAME);
		$photoBlob = $photoTable->fetchAll($photoTable->select()->where('model_id = ?', (int)$model_id), $order, $count, $offset);
		return $this->getMappedArrayFromData($photoBlob);
	}

	public function getModelPhotosPaginator($model_id, $order = null) {
		$photoTable = self::_getTableByName(self::TABLE_NAME);
		$select = $photoTable->select()->where('model_id = ?', (int)$model_id);
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