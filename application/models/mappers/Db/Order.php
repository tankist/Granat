<?php
class Model_Mapper_Db_Order extends Model_Mapper_Db_Abstract {
	
	const TABLE_NAME = 'orders';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	public function map($data = array()) {
		foreach (array('last_modified', 'date_purchased') as $key) {
			if (array_key_exists($key, $data) && is_string($data[$key])) {
				$data[$key] = strtotime($data[$key]);
			}
		}
		return parent::map($data);
	}
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		foreach (array('last_modified', 'date_purchased') as $key) {
			if (array_key_exists($key, $data) && is_integer($data[$key])) {
				$data[$key] = new Zend_Db_Expr('FROM_UNIXTIME(' . $data[$key] . ')');
			}
		}
		return $data;
	}
	
	public function getOrderById($order_id) {
		$orderTable = self::_getTableByName(self::TABLE_NAME);
		$orderBlob = $orderTable->fetchRowById($order_id);
		return $this->getMappedArrayFromData($orderBlob);
	}
	
	public function getStoreOrdersPaginator($store_id) {
		$select = self::_getTableByName(self::TABLE_NAME)->select(true)
			->where('store_id = ?', (int)$store_id)
			->where('status <> ?', Model_Order::ORDER_STATUS_HIDDEN);
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}
	
	public function getStoreOrdersStatusPaginator($store_id, $status = null) {
		$select = self::_getTableByName(self::TABLE_NAME)->select(true)->where('store_id = ?', (int)$store_id);
		if ($status !== null) {
			$select->where('status = ?', $status);
		}
		$paginator = Skaya_Paginator::factory($select, 'DbSelect');
		$paginator->addFilter(new Zend_Filter_Callback(array(
			'callback' => array($this, 'getMappedArrayFromData')
		)));
		return $paginator;
	}
	
	public function getStoreOrders($store_id) {
		$select = self::_getTableByName(self::TABLE_NAME)->select(true)
			->where('store_id = ?', (int)$store_id)
			->where('status <> ?', Model_Order::ORDER_STATUS_HIDDEN);
		$ordersBlob = self::_getTableByName(self::TABLE_NAME)->fetchAll($select);
		return $this->getMappedArrayFromData($ordersBlob);
	}
	
	public function getStoreOrdersCount($store_id) {
		$select = self::_getTableByName(self::TABLE_NAME)->select(true)
			->where('store_id = ?', (int)$store_id)
			->where('status <> ?', Model_Order::ORDER_STATUS_HIDDEN);
		return self::_getTableByName(self::TABLE_NAME)->count($select);
	}
	
	public function getStoreOrdersCountByStatus($store_id, $status) {
		$table = self::_getTableByName(self::TABLE_NAME);
		$select = $table->select(true)->where('store_id = ?', $store_id);
		$select->where('status IN ("' . join("', '", (array)$status) . '")');
		return $table->count($select);
	}
	
	public function getStoreOrdersByDateRange($store_id, $startDate, $finishDate = null) {
		$select = self::_getTableByName(self::TABLE_NAME)->select(true)
			->where('store_id = ?', (int)$store_id)
			->where('status <> ?', Model_Order::ORDER_STATUS_HIDDEN);
		$dateExpr = 'date_purchased >= ?';
		if (is_int($startDate)) {
			$dateExpr = new Zend_Db_Expr('date_purchased >= FROM_UNIXTIME(?)');
		}
		$select->where($dateExpr, $startDate);
		if ($finishDate !== null) {
			$dateExpr = 'date_purchased <= ?';
			if (is_int($finishDate)) {
				$dateExpr = new Zend_Db_Expr('date_purchased >= FROM_UNIXTIME(?)');
			}
			$select->where($dateExpr, $finishDate);
		}
		$ordersBlob = self::_getTableByName(self::TABLE_NAME)->fetchAll($select);
		return $this->getMappedArrayFromData($ordersBlob);
	}
}
?>
