<?php
class Model_Mapper_Db_OrderProduct extends Model_Mapper_Db_Product {
	
	const TABLE_NAME = 'orderProducts';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	protected $_fieldMapping = array(
		'id' => 'product_id',
		'description' => 'product_desc',
		'name' => 'product_name',
		'price' => 'product_price'
	);
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		foreach (array('product_info', 'shipping_info') as $key) {
			if (array_key_exists($key, $data) && !is_string($data[$key])) {
				$data[$key] = serialize($data[$key]);
			}
		}
		return $data;
	}
	
	public function map($data = array()) {
		foreach (array('product_info', 'shipping_info') as $key) {
			if (array_key_exists($key, $data) && is_string($data[$key])) {
				$data[$key] = unserialize($data[$key]);
			}
		}
		return parent::map($data);
	}
	
	public function getOrderProducts($order_id) {
		$productTable = self::_getTableByName(self::TABLE_NAME);
		$productsBlob = $productTable->fetchAllByOrderId((int)$order_id);
		return $this->getMappedArrayFromData($productsBlob);
	}
	
	public function calculateOrderTotalPrice($order_id) {
		$orderProductsTable = self::_getTableByName(self::TABLE_NAME);
		$select = $orderProductsTable->select()
			->from(array('op' => $orderProductsTable->info(Model_DbTable_Abstract::NAME)), array(
				'totalPrice' => new Zend_Db_Expr('SUM(op.product_price * op.quantity)')
			))
			->where('op.order_id = ?', (int)$order_id);
		$priceBlob = $orderProductsTable->fetchRow($select);
		$price = ($priceBlob)?(float)$priceBlob->totalPrice:0;
		return $price;
	}
	
}
?>
