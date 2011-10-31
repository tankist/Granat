<?php

class Model_Mapper_Db_Model extends Skaya_Model_Mapper_Db_Abstract {

	const TABLE_NAME = 'Models';

	protected $_mapperTableName = self::TABLE_NAME;

	protected $_fieldMapping = array(
		'mainPhotoId' => 'main_photo_id'
	);

    /**
     * @cachable
     * @cache_id model_{$id}
     * @cache_tags model item
     * @param  $id
     * @return array
     */
	public function getModelById($id) {
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$modelBlob = $modelTable->fetchRowById($id);
		return $this->getMappedArrayFromData($modelBlob);
	}

    /**
     * @cachable
     * @cache_tags models list
     * @param null $order
     * @param null $count
     * @param null $offset
     * @return array
     */
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

    /**
     * @cachable
     * @cache_tags models list
     * @param  $collection_id
     * @param null $order
     * @param null $count
     * @param null $offset
     * @return array
     */
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

	public function getCollectionModelsByCategory($collection_id, $category_id, $order = null, $count = null, $offset = null) {
		if (empty($category_id)) {
			return $this->getCollectionModels($collection_id, $order, $count, $offset);
		}
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$modelBlob = $modelTable->fetchAllByCollectionIdAndCategoryId($collection_id, $category_id, $order, $count, $offset);
		return $this->getMappedArrayFromData($modelBlob);
	}

	public function getCollectionModelsPaginatorByCategory($collection_id, $category_id, $order = null) {
		if (empty($category_id)) {
			return $this->getCollectionModelsPaginator($collection_id, $order);
		}
		$modelTable = self::_getTableByName(self::TABLE_NAME);
		$select = $modelTable->select()->where('collection_id = ?', (int)$collection_id);
		if ($category_id) {
			$select->where('category_id = ?', (int)$category_id);
		}
		if ($order) {
			$select->order($this->_mapOrderStatement($order));
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}

	public function getPreviousModel($model_id) {
        $modelTable = self::_getTableByName(self::TABLE_NAME);
        $select = $modelTable->select()->setIntegrityCheck(false);
        $select
            ->from(array('m' => $modelTable->info(Skaya_Model_DbTable_Abstract::NAME)), array('id' => 'm.id'))
            ->joinLeft(array('gm' => $modelTable->info(Skaya_Model_DbTable_Abstract::NAME)), 'gm.collection_id = m.collection_id AND gm.id > m.id', array())
            ->where('gm.id = ?', $model_id)
            ->order('m.id DESC')
            ->limit(1);
        $prevModel = $modelTable->fetchRow($select);
        return $this->getModelById(($prevModel)?$prevModel->id:0);
//		return $this->getModelById(new Zend_Db_Expr('getPrevModelId(' . $model_id . ')'));
	}

	public function getNextModel($model_id) {
        $modelTable = self::_getTableByName(self::TABLE_NAME);
        $select = $modelTable->select()->setIntegrityCheck(false);
        $select
            ->from(array('m' => $modelTable->info(Skaya_Model_DbTable_Abstract::NAME)), array('id' => 'm.id'))
            ->joinLeft(array('gm' => $modelTable->info(Skaya_Model_DbTable_Abstract::NAME)), 'gm.collection_id = m.collection_id AND gm.id < m.id', array())
            ->where('gm.id = ?', $model_id)
            ->order('m.id ASC')
            ->limit(1);
        $nextModel = $modelTable->fetchRow($select);
        return $this->getModelById(($nextModel)?$nextModel->id:0);
//		return $this->getModelById(new Zend_Db_Expr('getNextModelId(' . $model_id . ')'));
	}

    public function getRandomModels($count = null) {
        $modelTable = self::_getTableByName(self::TABLE_NAME);
        $select = $modelTable->select()->order('RAND()')->limit($count);
        $modelBlob = $modelTable->fetchAll($select);
		return $this->getMappedArrayFromData($modelBlob);
    }

}