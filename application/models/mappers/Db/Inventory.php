<?php
class Model_Mapper_Db_Inventory extends Model_Mapper_Db_Abstract {
	const TABLE_NAME = 'products';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	protected $_fieldMapping = array(
		'addDate' => 'add_dt'
	);
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		if (array_key_exists('add_dt', $data) && is_int($data['add_dt'])) {
			$data['add_dt'] = new Zend_Db_Expr('FROM_UNIXTIME(' . $data['add_dt'] . ')');
		}
		else {
			$data['add_dt'] = new Zend_Db_Expr('NOW()');
		}
		return $data;
	}
	
	public function map($data = array()) {
		if (array_key_exists('add_dt', $data) && is_string($data['add_dt'])) {
			$data['add_dt'] = strtotime($data['add_dt']);
		}
		return parent::map($data);
	}
	
	public function getProductInventory($product_id) {
		$thisTable = self::_getTableByName(self::TABLE_NAME);
		$select = $this->_getInventorySelect()->where('p.id = ?', (int)$product_id);
		$inventoryBlob = $thisTable->fetchAll($select);
		return $this->getMappedArrayFromData($inventoryBlob);
	}
	
	public function getProductInventoryPaginator($product_id) {
		$thisTable = self::_getTableByName(self::TABLE_NAME);
		$select = $this->_getInventorySelect()->where('p.id = ?', (int)$product_id);
		$inventoryPaginator = Skaya_Paginator::factory($select, 'DbSelect');
		$inventoryPaginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $inventoryPaginator;
	}
	
	public function getStoreInventory($store_id) {
		$thisTable = self::_getTableByName(self::TABLE_NAME);
		$select = $this->_getInventorySelect()->where('p.store_id = ?', (int)$store_id);
		$inventoryBlob = $thisTable->fetchAll($select);
		return $this->getMappedArrayFromData($inventoryBlob);
	}
	
	public function getStoreInventoryPaginator($store_id) {
		$thisTable = self::_getTableByName(self::TABLE_NAME);
		$select = $this->_getInventorySelect()->where('p.store_id = ?', (int)$store_id);
		$inventoryPaginator = Skaya_Paginator::factory($select, 'DbSelect');
		$inventoryPaginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $inventoryPaginator;
	}
	
	protected function _getInventorySelect() {
		$thisTable = self::_getTableByName(self::TABLE_NAME);
		$select = $thisTable->select()->setIntegrityCheck(false)->from(array(
			'p' => $thisTable->info(Model_DbTable_Abstract::NAME)
		), array(
			'product_id' => 'p.id',
			'add_dt' => 'p.add_dt',
			'name' => new Zend_Db_Expr("CONCAT(p.name, ' / ', o.name)")
		))
		->joinInner(array(
			'o' => self::_getTableByName(Model_Mapper_Db_Option::TABLE_NAME)->info(Model_DbTable_Abstract::NAME)
		), 'o.product_id = p.id', array(
			'option_id' => 'o.id'
		));
		return $select;
	}
}
?>
