<?php
class Model_Mapper_Db_OrderShipping extends Model_Mapper_Db_Abstract {
	const TABLE_NAME = 'shipping';
	
	protected $_mapperTableName = self::TABLE_NAME;
	
	protected $_fieldMapping = array(
		'firstName' => 'first_name',
		'lastName' => 'last_name',
		'shippingCompany' => 'shipping_company',
		'trackingNumber' => 'tracking_number',
		'trackingUrl' => 'tracking_url',
		'shippingInfo' => 'shipping_info'
	);
	
	public function getOrderShippingInfo($order_id) {
		$shippingInfo = $this->_getTableByName($this->_mapperTableName)->fetchRowByOrderId((int)$order_id);
		return $this->getMappedArrayFromData($shippingInfo);
	}
	
	public function unmap($data = array()) {
		$data = parent::unmap($data);
		if (isset($data['shipping_info']) && !is_string($data['shipping_info'])) {
			$data['shipping_info'] = serialize($data['shipping_info']);
		}
		return $data;
	}
	
	public function map($data = array()) {
		if (isset($data['shipping_info']) && is_string($data['shipping_info'])) {
			$data['shipping_info'] = unserialize($data['shipping_info']);
		}
		return parent::map($data);
	}
}
?>
