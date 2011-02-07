<?php
class Model_Mapper_Db_OrderBilling extends Model_Mapper_Db_Abstract {
	const TABLE_NAME = 'billing';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	protected $_fieldMapping = array(
		'firstName' => 'first_name',
		'lastName' => 'last_name'
	);
	
	public function getOrderBillingInfo($order_id) {
		$shippingInfo = $this->_getTableByName($this->_mapperTableName)->fetchRowByOrderId((int)$order_id);
		return $this->getMappedArrayFromData($shippingInfo);
	}
}
?>
